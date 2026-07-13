<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Full announcement history for the logged-in client — every announcement
     * ever targeted at their audience, past or present, with whether they've
     * acknowledged each one.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $announcements = Announcement::with('createdBy')
            ->withCount(['dismissals as acknowledged_count' => fn ($q) => $q->where('user_id', $user->id)])
            ->latest()
            ->get()
            ->filter(fn (Announcement $a) => $a->isVisibleTo($user))
            ->values();

        return view('portal.announcements.index', compact('announcements'));
    }

    public function dismiss(Request $request, Announcement $announcement)
    {
        $announcement->dismissals()->firstOrCreate(
            ['user_id' => $request->user()->id],
            ['dismissed_at' => now()],
        );

        return response()->noContent();
    }
}
