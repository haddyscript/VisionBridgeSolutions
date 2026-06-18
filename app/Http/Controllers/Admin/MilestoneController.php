<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use App\Models\Project;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $project->milestones()->create([
            'title' => $validated['title'],
            'status' => 'pending',
            'position' => $project->milestones()->max('position') + 1,
        ]);

        return back()->with('status', 'Milestone added.');
    }

    public function update(Request $request, Milestone $milestone)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed'],
        ]);

        $milestone->update($validated);

        return back()->with('status', 'Milestone updated.');
    }

    public function destroy(Milestone $milestone)
    {
        $milestone->delete();

        return back()->with('status', 'Milestone removed.');
    }
}
