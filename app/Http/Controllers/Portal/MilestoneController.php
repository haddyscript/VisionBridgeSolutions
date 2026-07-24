<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use App\Support\IcsCalendar;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function index(Request $request)
    {
        $project = $request->user()->projects()->first();

        $milestones = null;
        $total = 0;
        $completed = 0;

        if ($project) {
            // Newest first on this dedicated timeline page — the reverse of
            // the relationship's default ascending order, which other views
            // (admin edit list, the Overview "last 3" teaser, the Next
            // Milestone bar) still rely on staying oldest-first. Ordered by
            // created_at rather than position, since position is a manual
            // display-order counter that doesn't always match the actual
            // order milestones were entered in. reorder() is required here —
            // the milestones() relation itself defines ->orderBy('position'),
            // and a plain ->orderByDesc() would only stack on top of that
            // (position ASC, created_at DESC) instead of replacing it.
            $milestones = $project->milestones()->reorder('created_at', 'desc')->paginate(15);
            $total = $project->milestones()->count();
            $completed = $project->milestones()->where('status', 'completed')->count();
        }

        return view('portal.milestones', [
            'project' => $project,
            'milestones' => $milestones,
            'total' => $total,
            'completed' => $completed,
        ]);
    }

    public function ics(Request $request, Milestone $milestone)
    {
        $project = $request->user()->projects()->first();
        abort_unless($project && $milestone->project_id === $project->id, 403);
        abort_unless($milestone->due_date, 404);

        $ics = IcsCalendar::event(
            uid: "milestone-{$milestone->id}@visionbridgesolutions.com",
            title: $milestone->title.' — '.$project->name,
            description: $milestone->description,
            start: $milestone->due_date,
            allDay: true,
        );

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.\Illuminate\Support\Str::slug($milestone->title).'.ics"',
        ]);
    }
}
