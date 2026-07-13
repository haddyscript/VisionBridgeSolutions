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
            'announcements' => Announcement::with('createdBy')->latest()->paginate(15),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
            'audiences' => ['required', 'array', 'min:1'],
            'audiences.*' => ['in:' . implode(',', array_keys(Announcement::AUDIENCES))],
        ]);

        Announcement::create($validated + ['created_by' => $request->user()->id]);

        return back()->with('status', 'Announcement created.');
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        if ($validated['is_active']) {
            // Deactivate only other active announcements that share an audience,
            // so each audience shows one banner at a time — but a client banner
            // and a developer banner can still be active simultaneously.
            Announcement::where('is_active', true)
                ->where('id', '!=', $announcement->id)
                ->get()
                ->filter(fn (Announcement $other) => array_intersect(
                    $other->audiences ?? [],
                    $announcement->audiences ?? []
                ))
                ->each->update(['is_active' => false]);
        }

        $announcement->update($validated);

        return back()->with('status', $validated['is_active'] ? 'Announcement activated.' : 'Announcement deactivated.');
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
