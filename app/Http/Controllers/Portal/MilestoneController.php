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
        $project = $request->user()->projects()->with('milestones')->first();

        return view('portal.milestones', [
            'project' => $project,
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
