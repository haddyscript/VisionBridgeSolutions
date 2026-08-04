<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AnnouncementNotificationMail;
use App\Models\Announcement;
use App\Models\AnnouncementAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /** Broad but not unrestricted — blocks executable/script types (php, exe, html, svg, etc.) that would be dangerous served back from client_uploads. */
    private const ATTACHMENT_MIMES = 'pdf,doc,docx,xls,xlsx,ppt,pptx,txt,rtf,odt,csv,jpg,jpeg,png,gif,webp,mp4,mov,zip';

    /** Extra outside-the-app recipients who should be emailed whenever a Team or Developer announcement goes live — not tied to any User account. */
    private const TEAM_NOTIFICATION_EMAILS = [
        'sarbidajohncarl@gmail.com',
        'julsestorco031602@gmail.com',
        'johnnydavis45@yahoo.com',
    ];

    public function index(Request $request)
    {
        $announcements = Announcement::with('createdBy', 'attachments')->latest()->paginate(5);

        // Pagination links fetch just this list fragment so clicking "Next"
        // never triggers a full page reload — see index.blade.php's
        // loadAnnouncements(). $request->ajax() checks for X-Requested-With,
        // which that fetch() call sets explicitly.
        return $request->ajax()
            ? view('admin.announcements._list', compact('announcements'))
            : view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Read-only announcement history for admins who don't hold the
     * "Announcements" management permission (Developer, Project Manager,
     * Sales Rep, CSR, Administrative Staff, etc.) — every announcement ever
     * targeted at their audience, past or present, with whether they've
     * acknowledged each one. Admins who do hold the management permission
     * use the full /admin/announcements page instead, which already shows
     * everyone's history.
     */
    public function history(Request $request)
    {
        $user = $request->user();

        $announcements = Announcement::with('createdBy', 'attachments')
            ->withCount(['dismissals as acknowledged_count' => fn ($q) => $q->where('user_id', $user->id)])
            ->latest()
            ->get()
            ->filter(fn (Announcement $a) => $a->isVisibleTo($user))
            ->values();

        return view('admin.announcements.history', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:2000'],
            'event_date' => ['nullable', 'date'],
            'event_time' => ['nullable', 'string', 'max:100'],
            'audiences' => ['required', 'array', 'min:1'],
            'audiences.*' => ['in:' . implode(',', array_keys(Announcement::AUDIENCES))],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:'.self::ATTACHMENT_MIMES, 'max:25600'],
        ]);

        unset($validated['attachments']);

        // "Publish Live" activates immediately; "Save as Draft" leaves it inactive.
        $publish = $request->boolean('publish');

        $announcement = Announcement::create($validated + [
            'created_by' => $request->user()->id,
            'is_active' => $publish,
        ]);

        $this->storeAttachments($request, $announcement);

        if ($publish) {
            $this->deactivateOverlapping($announcement);
            $this->notifyTeamEmails($announcement);
        }

        return back()->with('status', $publish ? 'Announcement published.' : 'Announcement saved as draft.');
    }

    /** Emails a fixed outside-the-app recipient list whenever a live announcement targets Team or Developers — separate from the in-app banner, which those recipients may not otherwise see. */
    private function notifyTeamEmails(Announcement $announcement): void
    {
        if (! array_intersect($announcement->audiences ?? [], ['team', 'developer'])) {
            return;
        }

        $announcement->load('attachments', 'createdBy');

        dispatch(function () use ($announcement) {
            Mail::to(self::TEAM_NOTIFICATION_EMAILS)->send(new AnnouncementNotificationMail($announcement));
        })->afterResponse();
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:2000'],
            'event_date' => ['nullable', 'date'],
            'event_time' => ['nullable', 'string', 'max:100'],
            'audiences' => ['required', 'array', 'min:1'],
            'audiences.*' => ['in:' . implode(',', array_keys(Announcement::AUDIENCES))],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:'.self::ATTACHMENT_MIMES, 'max:25600'],
        ]);

        unset($validated['attachments']);

        $announcement->update($validated);

        $this->storeAttachments($request, $announcement);

        // Audiences may have changed — if this one is active, make sure no other
        // active announcement now overlaps its (new) audiences.
        if ($announcement->is_active) {
            $this->deactivateOverlapping($announcement);
        }

        return back()->with('status', 'Announcement updated.');
    }

    /** Adds newly uploaded files — existing attachments are untouched (removed individually via destroyAttachment). */
    private function storeAttachments(Request $request, Announcement $announcement): void
    {
        foreach ($request->file('attachments', []) as $file) {
            $announcement->attachments()->create([
                'path' => $file->store("announcements/{$announcement->id}/attachments", 'client_uploads'),
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ]);
        }
    }

    public function destroyAttachment(Announcement $announcement, AnnouncementAttachment $attachment)
    {
        abort_unless($attachment->announcement_id === $announcement->id, 404);

        Storage::disk('client_uploads')->delete($attachment->path);
        $attachment->delete();

        return back()->with('status', 'Attachment removed.');
    }

    public function toggle(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $deactivatedIds = [];

        if ($validated['is_active']) {
            $deactivatedIds = $this->deactivateOverlapping($announcement)->pluck('id')->all();
        }

        $announcement->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'id' => $announcement->id,
                'is_active' => $announcement->is_active,
                'deactivated' => $deactivatedIds,
            ]);
        }

        return back()->with('status', $validated['is_active'] ? 'Announcement activated.' : 'Announcement deactivated.');
    }

    /**
     * Deactivate other active announcements that share an audience with the
     * given one, so each audience only shows a single banner at a time — while
     * still allowing, say, a Client banner and a Developer banner to coexist.
     * Returns the announcements that were deactivated.
     */
    protected function deactivateOverlapping(Announcement $announcement): \Illuminate\Support\Collection
    {
        $overlapping = Announcement::where('is_active', true)
            ->where('id', '!=', $announcement->id)
            ->get()
            ->filter(fn (Announcement $other) => array_intersect(
                $other->audiences ?? [],
                $announcement->audiences ?? []
            ));

        $overlapping->each->update(['is_active' => false]);

        return $overlapping;
    }

    /**
     * Attachment rows cascade-delete automatically
     * (announcement_attachments.announcement_id has cascadeOnDelete()), but
     * that only removes the DB rows — the actual files have to be cleaned up
     * here first, before their path values are gone.
     */
    public function destroy(Announcement $announcement)
    {
        foreach ($announcement->attachments as $attachment) {
            Storage::disk('client_uploads')->delete($attachment->path);
        }

        $announcement->delete();

        return back()->with('status', 'Announcement deleted.');
    }

    /**
     * Dismiss the banner for the current admin (team/developer side) — the
     * client portal has its own dismiss route behind portal-only middleware.
     */
    public function dismiss(Request $request, Announcement $announcement)
    {
        $announcement->dismissals()->firstOrCreate(
            ['user_id' => $request->user()->id],
            ['dismissed_at' => now()],
        );

        return response()->noContent();
    }
}
