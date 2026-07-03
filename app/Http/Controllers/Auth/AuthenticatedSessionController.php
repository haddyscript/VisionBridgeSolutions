<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        if (Auth::user()->hasTwoFactorEnabled()) {
            $userId = Auth::user()->id;
            Auth::logout();

            $request->session()->put('2fa.user_id', $userId);
            $request->session()->put('2fa.remember', $request->boolean('remember'));

            return redirect()->route('two-factor.challenge');
        }

        return static::finishLogin($request);
    }

    /**
     * Shared by the normal (no 2FA) path here and TwoFactorChallengeController
     * once a pending login's code has been verified — both need the same
     * session regen, activity log, and redirect.
     */
    public static function finishLogin(Request $request): RedirectResponse
    {
        $request->session()->regenerate();

        LoginActivity::create([
            'user_id'      => $request->user()->id,
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
            'logged_in_at' => now(),
        ]);

        if (! $request->user()->isAdmin()) {
            $request->session()->put('show_payment_reminder', true);
        }

        $destination = $request->user()->isAdmin() ? route('admin.dashboard') : route('portal.dashboard');

        return redirect()->intended($destination);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
