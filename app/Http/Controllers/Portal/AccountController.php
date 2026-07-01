<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\AccountEmailChangedMail;
use App\Mail\AccountPasswordChangedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        return view('portal.account', [
            'recentLogins' => $request->user()->loginActivities()->latest('logged_in_at')->take(5)->get(),
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
            Mail::to($oldEmail)->send(new AccountEmailChangedMail($user, $oldEmail, $validated['email']));
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

        Mail::to($user->email)->send(new AccountPasswordChangedMail($user));

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

    public function requestClosure(Request $request)
    {
        $user = $request->user();

        Mail::raw(
            "Account closure requested.\n\nName: {$user->name}\nEmail: {$user->email}\nTime: " . now()->toDateTimeString(),
            fn ($m) => $m->to(config('mail.admin_address'))->subject('Account Closure Request — ' . $user->name)
        );

        return back()->with('status', 'Your closure request has been received. Our team will follow up within 1–2 business days.');
    }
}
