<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function show(Project $project)
    {
        $project->load('user', 'milestones', 'uploads.user', 'payments', 'subscription');

        return view('admin.projects.show', [
            'project' => $project,
            'uploadsByCategory' => $project->uploads->groupBy('category'),
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:onboarding,in_progress,review,launched,maintenance'],
        ]);

        $project->update($validated);

        return back()->with('status', 'Project status updated.');
    }
}
