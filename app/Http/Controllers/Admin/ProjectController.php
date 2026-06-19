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

    public function resetClientPassword(Project $project)
    {
        $project->user->update([
            'password' => Hash::make('admin123'),
        ]);

        return back()->with('status', "Password reset to \"admin123\" for {$project->user->name}.");
    }
}
