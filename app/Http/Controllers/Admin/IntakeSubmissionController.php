<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IntakeSubmission;
use Illuminate\Http\Request;

class IntakeSubmissionController extends Controller
{
    public function index()
    {
        $submissions = IntakeSubmission::withCount('files')->latest()->get();

        return view('admin.intake-submissions.index', [
            'submissions' => $submissions,
        ]);
    }

    public function show(IntakeSubmission $intakeSubmission)
    {
        $intakeSubmission->load('files');

        return view('admin.intake-submissions.show', [
            'submission' => $intakeSubmission,
            'filesByCategory' => $intakeSubmission->files->groupBy('category'),
        ]);
    }

    public function update(Request $request, IntakeSubmission $intakeSubmission)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,contacted,converted'],
        ]);

        $intakeSubmission->update($validated);

        return back()->with('status', 'Submission status updated.');
    }
}
