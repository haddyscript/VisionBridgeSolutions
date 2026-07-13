<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        return view('admin.announcements.index', [
            'announcements' => Announcement::with('createdBy')->latest()->paginate(5),
        ]);
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

        $announcements = Announcement::with('createdBy')
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
        ]);

        // "Publish Live" activates immediately; "Save as Draft" leaves it inactive.
        $publish = $request->boolean('publish');

        $announcement = Announcement::create($validated + [
            'created_by' => $request->user()->id,
            'is_active' => $publish,
        ]);

        if ($publish) {
            $this->deactivateOverlapping($announcement);
        }

        return back()->with('status', $publish ? 'Announcement published.' : 'Announcement saved as draft.');
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
        ]);

        $announcement->update($validated);

        // Audiences may have changed — if this one is active, make sure no other
        // active announcement now overlaps its (new) audiences.
        if ($announcement->is_active) {
            $this->deactivateOverlapping($announcement);
        }

        return back()->with('status', 'Announcement updated.');
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

    public function destroy(Announcement $announcement)
    {
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
