<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProjectController extends Controller
{
    public function show(Project $project)
    {
        $project->load('user', 'milestones', 'uploads.user', 'uploads.replies', 'payments', 'subscription');

        return view('admin.projects.show', [
            'project' => $project,
            'uploadsByCategory' => $project->uploads->groupBy('category'),
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'status' => ['sometimes', 'required', 'in:onboarding,in_progress,review,launched,maintenance'],
            'preview_url' => ['sometimes', 'nullable', 'url', 'max:255'],
            'progress_override' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $project->update($validated);

        return back()->with('status', 'Project updated.');
    }

    public function resetClientPassword(Project $project)
    {
        $project->user->update([
            'password' => Hash::make('admin123'),
        ]);

        return back()->with('status', "Password reset to \"admin123\" for {$project->user->name}.");
    }
}
