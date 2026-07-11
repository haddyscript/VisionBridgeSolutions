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

        return back()->with('status', 'Password updated.');
    }

    public function updateNotifications(Request $request)
    {
        $request->user()->update([
            'notify_on_replies' => $request->boolean('notify_on_replies'),
            'notify_on_consultations' => $request->boolean('notify_on_consultations'),
        ]);

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

        return back()->with('status', 'Your closure request has been received. Our team will follow up within 1–2 business days.');
    }
}
