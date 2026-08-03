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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IntakeSubmissionController extends Controller
{
    // Kept identical to the public form's own lists (IntakeController /
    // intake.create.blade.php) so an admin-logged intake is indistinguishable
    // from one the client submitted themselves — same options, same order.
    public const ORGANIZATION_TYPES = ['Church', 'Ministry', 'Nonprofit', 'Small Business', 'Entrepreneur', 'Other'];

    public const SERVICES = [
        'Custom Website Development', 'Landing Page Development', 'Church Website Development',
        'Ministry Website Development', 'Nonprofit Website Development', 'Small Business Website Development',
        'Website Redesign Services', 'Website Care Services', 'Hosting Management', 'Website Consulting',
    ];

    public const SOCIAL_LINKS = [
        'website' => 'Current Website', 'facebook' => 'Facebook', 'instagram' => 'Instagram',
        'twitter' => 'Twitter / X', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube', 'tiktok' => 'TikTok',
    ];

    private const FILE_FIELDS = [
        'photos' => 'photo',
        'videos' => 'video',
        'logos' => 'logo',
    ];

    public function index()
    {
        $submissions = IntakeSubmission::withCount('files')->latest()->paginate(15)->withQueryString();

        return view('admin.intake-submissions.index', [
            'submissions' => $submissions,
        ]);
    }

    /**
     * Admin "log a lead" — for a prospect that came in some other way (a
     * phone call, an in-person meeting, a consultation that hasn't gone
     * through the public intake form) rather than requiring the client to
     * fill out /get-started themselves.
     */
    public function create()
    {
        return view('admin.intake-submissions.create', [
            'organizationTypes' => self::ORGANIZATION_TYPES,
            'services' => self::SERVICES,
            'socialLinks' => self::SOCIAL_LINKS,
        ]);
    }

    /**
     * Same fields, same validation, and the same file-upload handling as the
     * public IntakeController::store() — deliberately kept in lockstep so an
     * admin-logged intake is a full substitute for the client filling out
     * /get-started themselves, not a stripped-down version. Same underlying
     * record and review flow too (shows up in this same inbox, converts to a
     * client the same way via convert()). The one difference: no
     * confirmation/notification emails fire here — the admin creating it
     * already knows about it, and nothing has actually been "submitted" by
     * the client to confirm.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'organization_type' => ['nullable', 'string', 'max:100'],
            'mission_statement' => ['nullable', 'string', 'max:3000'],
            'vision_statement' => ['nullable', 'string', 'max:3000'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'services' => ['nullable', 'array'],
            'services.*' => ['string'],
            'website_requirements' => ['nullable', 'string', 'max:5000'],
            'social_links' => ['nullable', 'array'],
            'social_links.*' => ['nullable', 'string', 'max:255'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['file', 'image', 'max:10240'],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['file', 'mimetypes:video/mp4,video/quicktime,video/webm,video/x-msvideo', 'max:51200'],
            'logos' => ['nullable', 'array'],
            'logos.*' => ['file', 'image', 'max:10240'],
        ]);

        $submission = IntakeSubmission::create([
            'organization_name' => $validated['organization_name'],
            'organization_type' => $validated['organization_type'] ?? null,
            'mission_statement' => $validated['mission_statement'] ?? null,
            'vision_statement' => $validated['vision_statement'] ?? null,
            'contact_name' => $validated['contact_name'],
            'contact_email' => $validated['contact_email'],
            'contact_phone' => $validated['contact_phone'] ?? null,
            'services' => array_values($validated['services'] ?? []),
            'website_requirements' => $validated['website_requirements'] ?? null,
            'social_links' => array_filter($validated['social_links'] ?? []),
        ]);

        foreach (self::FILE_FIELDS as $field => $category) {
            foreach ($request->file($field, []) as $file) {
                $path = $file->store("intake/{$submission->id}/{$category}", 'client_uploads');

                $submission->files()->create([
                    'category' => $category,
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('admin.intake-submissions.show', $submission)
            ->with('status', 'Intake logged for '.$submission->contact_name.'.');
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

        $linkedExistingAccount = false;

        try {
            $project = DB::transaction(function () use ($validated, $intakeSubmission, &$linkedExistingAccount) {
                // Re-fetch with a row lock so two concurrent "Approve & Create Client"
                // clicks on the same submission can't both pass the project_id check.
                $locked = IntakeSubmission::lockForUpdate()->find($intakeSubmission->id);

                abort_if($locked->project_id, 422, 'This submission has already been converted.');

                // The contact email may already belong to a client account —
                // e.g. one created via "+ Add Client" (no project attached
                // yet) before this intake was ever logged, same scenario
                // this admin-created-intake flow made newly common. Attach
                // the new project to that existing account instead of trying
                // (and failing, on the email-unique constraint) to create a
                // duplicate one.
                $existingUser = User::where('email', $locked->contact_email)->first();

                if ($existingUser) {
                    abort_if($existingUser->isAdmin(), 422, 'That email belongs to an admin/team account, not a client — check the contact email on this submission.');
                    // Every portal page resolves "the" project via
                    // ->projects()->first() — a second project here would
                    // just be silently invisible, not a real multi-project
                    // client, so this needs a deliberate manual decision
                    // rather than happening automatically.
                    abort_if(
                        $existingUser->projects()->exists(),
                        422,
                        "{$existingUser->name} already has a project on file — this submission can't be auto-converted onto a second one. Handle it manually from the Clients page if that's really what's needed."
                    );

                    $project = $existingUser->projects()->create([
                        'name' => $validated['project_name'],
                        'description' => $validated['project_description'],
                    ]);

                    $locked->update([
                        'status' => 'converted',
                        'project_id' => $project->id,
                    ]);

                    $linkedExistingAccount = true;

                    return $project;
                }

                $user = User::create([
                    'name' => $locked->contact_name,
                    'email' => $locked->contact_email,
                    'password' => Str::random(40),
                    'role' => 'client',
                    'email_verified_at' => now(),
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

        return redirect()->route('admin.projects.show', $project)->with(
            'status',
            $linkedExistingAccount
                ? 'Project created and linked to the existing account for this email — no new welcome email was sent, since the account already exists.'
                : 'Client account and project created. A welcome email has been sent.'
        );
    }

    public function resendWelcomeEmail(IntakeSubmission $intakeSubmission)
    {
        abort_unless($intakeSubmission->project_id, 422, 'This submission has not been converted to a client yet.');

        $this->sendWelcomeEmail($intakeSubmission->project->user);

        return back()->with('status', 'Welcome email resent with a fresh password-setup link.');
    }

    /**
     * Re-confirms the acting admin's own account password (not a shared or
     * hardcoded one) before permanently deleting a lead — same
     * `current_password` rule TeamController::updatePassword() uses. Files on
     * disk aren't touched by the intake_files cascade delete, so they're
     * removed here explicitly.
     */
    public function destroy(Request $request, IntakeSubmission $intakeSubmission)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        Storage::disk('client_uploads')->deleteDirectory("intake/{$intakeSubmission->id}");

        $name = $intakeSubmission->contact_name;
        $intakeSubmission->delete();

        return redirect()->route('admin.intake-submissions.index')
            ->with('status', "Submission from {$name} has been permanently deleted.");
    }

    private function sendWelcomeEmail(User $user): void
    {
        $resetToken = Password::createToken($user);
        $resetUrl = route('password.reset', ['token' => $resetToken, 'email' => $user->email]);

        Mail::to($user->email)->send(new WelcomeClientMail($user, $resetUrl));
    }
}
