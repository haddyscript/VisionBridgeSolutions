<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientNotification;
use App\Models\Milestone;
use App\Models\Project;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
        ]);

        $project->milestones()->create([
            'title' => $validated['title'],
            'status' => 'pending',
            'due_date' => $validated['due_date'] ?? null,
            'position' => $project->milestones()->max('position') + 1,
        ]);

        return back()->with('status', 'Milestone added.');
    }

    public function update(Request $request, Milestone $milestone)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed'],
        ]);

        $newlyCompleted = $validated['status'] === 'completed' && $milestone->status !== 'completed';

        $validated['completed_at'] = $validated['status'] === 'completed'
            ? ($milestone->completed_at ?? now())
            : null;

        $milestone->update($validated);

        if ($newlyCompleted) {
            ClientNotification::send(
                $milestone->project->user,
                'milestone_completed',
                'Milestone completed',
                $milestone->title,
                route('portal.dashboard'),
            );
        }

        return back()->with('status', 'Milestone updated.');
    }

    public function destroy(Milestone $milestone)
    {
        $milestone->delete();

        return back()->with('status', 'Milestone removed.');
    }
}
