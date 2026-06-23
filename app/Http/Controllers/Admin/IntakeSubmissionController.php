<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeClientMail;
use App\Models\IntakeSubmission;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class IntakeSubmissionController extends Controller
{
    public function index()
    {
        $submissions = IntakeSubmission::withCount('files')->latest()->paginate(15)->withQueryString();

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

        try {
            $project = DB::transaction(function () use ($validated, $intakeSubmission) {
                // Re-fetch with a row lock so two concurrent "Approve & Create Client"
                // clicks on the same submission can't both pass the project_id check.
                $locked = IntakeSubmission::lockForUpdate()->find($intakeSubmission->id);

                abort_if($locked->project_id, 422, 'This submission has already been converted.');

                $user = User::create([
                    'name' => $locked->contact_name,
                    'email' => $locked->contact_email,
                    'password' => Str::random(40),
                    'role' => 'client',
                ]);

                $project = $user->projects()->create([
                    'name' => $validated['project_name'],
                    'description' => $validated['project_description'],
                ]);

                $locked->update([
                    'status' => 'converted',
                    'project_id' => $project->id,
                ]);

                $this->sendWelcomeEmail($user);

                return $project;
            });
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()->withErrors(['email' => 'A user with this email already exists. The submission may have just been converted by someone else.']);
            }

            throw $e;
        }

        return redirect()->route('admin.projects.show', $project)
            ->with('status', 'Client account and project created. A welcome email has been sent.');
    }

    public function resendWelcomeEmail(IntakeSubmission $intakeSubmission)
    {
        abort_unless($intakeSubmission->project_id, 422, 'This submission has not been converted to a client yet.');

        $this->sendWelcomeEmail($intakeSubmission->project->user);

        return back()->with('status', 'Welcome email resent with a fresh password-setup link.');
    }

    private function sendWelcomeEmail(User $user): void
    {
        $resetToken = Password::createToken($user);
        $resetUrl = route('password.reset', ['token' => $resetToken, 'email' => $user->email]);

        Mail::to($user->email)->send(new WelcomeClientMail($user, $resetUrl));
    }
}
