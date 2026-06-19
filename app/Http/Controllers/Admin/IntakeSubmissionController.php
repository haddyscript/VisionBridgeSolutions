<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeClientMail;
use App\Models\IntakeSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

    public function convert(Request $request, IntakeSubmission $intakeSubmission)
    {
        abort_if($intakeSubmission->project_id, 422, 'This submission has already been converted.');

        $validated = $request->validate([
            'project_name' => ['required', 'string', 'max:255'],
            'project_description' => ['nullable', 'string'],
        ]);

        $project = DB::transaction(function () use ($validated, $intakeSubmission) {
            $user = User::create([
                'name' => $intakeSubmission->contact_name,
                'email' => $intakeSubmission->contact_email,
                'password' => Str::random(40),
                'role' => 'client',
            ]);

            $project = $user->projects()->create([
                'name' => $validated['project_name'],
                'description' => $validated['project_description'],
            ]);

            $intakeSubmission->update([
                'status' => 'converted',
                'project_id' => $project->id,
            ]);

            $resetToken = Password::createToken($user);
            $resetUrl = route('password.reset', ['token' => $resetToken, 'email' => $user->email]);

            Mail::to($user->email)->send(new WelcomeClientMail($user, $resetUrl));

            return $project;
        });

        return redirect()->route('admin.projects.show', $project)
            ->with('status', 'Client account and project created. A welcome email has been sent.');
    }
}
