<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\AccountEmailChangedMail;
use App\Mail\AccountPasswordChangedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $project = $request->user()->projects()->first();

        return view('portal.account', [
            // Excludes admin "log in as client" impersonation sessions
            // (LoginActivity rows with impersonator_id set) — those are a
            // real admin action, not the client's own sign-in, and showing
            // them here would read as unrecognized/suspicious activity to
            // the client. The row itself is still written (it's the audit
            // trail admins rely on for impersonation), just not surfaced here.
            'recentLogins' => $request->user()->loginActivities()
                ->whereNull('impersonator_id')
                ->latest('logged_in_at')
                ->take(5)
                ->get(),
            'project' => $project,
            'questionnaire' => $project?->questionnaire,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone'            => ['nullable', 'string', 'max:30'],
            'current_password' => ['required', 'current_password'],
        ]);

        $oldEmail = $user->email;
        $emailChanged = $validated['email'] !== $oldEmail;

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        if ($emailChanged) {
            $newEmail = $validated['email'];

            dispatch(function () use ($user, $oldEmail, $newEmail) {
                Mail::to($oldEmail)->send(new AccountEmailChangedMail($user, $oldEmail, $newEmail));
            })->afterResponse();
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Profile updated.']);
        }

        return back()->with('status', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        dispatch(function () use ($user) {
            Mail::to($user->email)->send(new AccountPasswordChangedMail($user));
        })->afterResponse();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Password updated.']);
        }

        return back()->with('status', 'Password updated.');
    }

    /**
     * The onboarding questionnaire only ever runs once, before the rest of
     * the portal unlocks — this is the only place a client can go back and
     * fix/add an answer afterward (e.g. a social link they skipped).
     */
    public function updateBusinessInfo(Request $request)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project, 404);

        $validated = $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'organization_type' => ['nullable', 'string', 'max:100'],
            'mission_statement' => ['nullable', 'string', 'max:3000'],
            'vision_statement' => ['nullable', 'string', 'max:3000'],
            'services' => ['nullable', 'array'],
            'services.*' => ['string'],
            'requested_pages' => ['nullable', 'string', 'max:5000'],
            'brand_colors' => ['nullable', 'string', 'max:255'],
            'social_links' => ['nullable', 'array'],
            'social_links.*' => ['nullable', 'string', 'max:255'],
            'additional_notes' => ['nullable', 'string', 'max:3000'],
        ]);

        $project->update(['name' => $validated['organization_name']]);

        $project->questionnaire()->updateOrCreate(
            ['project_id' => $project->id],
            [
                'organization_type' => $validated['organization_type'] ?? null,
                'mission_statement' => $validated['mission_statement'] ?? null,
                'vision_statement' => $validated['vision_statement'] ?? null,
                'services' => array_values($validated['services'] ?? []),
                'requested_pages' => $validated['requested_pages'] ?? null,
                'brand_colors' => $validated['brand_colors'] ?? null,
                'social_links' => array_filter($validated['social_links'] ?? []),
                'additional_notes' => $validated['additional_notes'] ?? null,
            ]
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Business information updated.']);
        }

        return back()->with('status', 'Business information updated.');
    }

    public function updateNotifications(Request $request)
    {
        $request->user()->update([
            'notify_on_replies' => $request->boolean('notify_on_replies'),
            'notify_on_consultations' => $request->boolean('notify_on_consultations'),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Notification preferences updated.']);
        }

        return back()->with('status', 'Notification preferences updated.');
    }

    /**
     * Invalidates every other session for this account (any device/browser
     * other than the one submitting this form) — relies on the
     * AuthenticateSession middleware being applied to the 'web' group so it
     * takes effect regardless of session driver.
     */
    public function logoutOtherDevices(Request $request)
    {
        // Named error bag — this page also has Profile/Password forms with
        // their own "current_password" field; without a bag, a failed
        // validation here would incorrectly also appear to fail those.
        $validated = $request->validateWithBag('logoutOtherDevices', [
            'current_password' => ['required', 'current_password'],
        ]);

        Auth::guard('web')->logoutOtherDevices($validated['current_password']);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'You\'ve been logged out of all other devices.']);
        }

        return back()->with('status', 'You\'ve been logged out of all other devices.');
    }

    public function requestClosure(Request $request)
    {
        $user = $request->user();

        dispatch(function () use ($user) {
            Mail::raw(
                "Account closure requested.\n\nName: {$user->name}\nEmail: {$user->email}\nTime: " . now()->toDateTimeString(),
                fn ($m) => $m->to(config('mail.johnny_address'))->subject('Account Closure Request — ' . $user->name)
            );
        })->afterResponse();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Your closure request has been received. Our team will follow up within 1–2 business days.']);
        }

        return back()->with('status', 'Your closure request has been received. Our team will follow up within 1–2 business days.');
    }
}
