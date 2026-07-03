<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwoFactorAuthenticator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TwoFactorChallengeController extends Controller
{
    public function create(Request $request)
    {
        if (! $request->session()->has('2fa.user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    public function store(Request $request, TwoFactorAuthenticator $authenticator): RedirectResponse
    {
        $userId = $request->session()->get('2fa.user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = User::findOrFail($userId);
        $code = trim($validated['code']);

        $verified = $authenticator->verify($user->two_factor_secret, $code)
            || $user->consumeTwoFactorRecoveryCode($code);

        if (! $verified) {
            throw ValidationException::withMessages([
                'code' => 'That code is invalid or has expired.',
            ]);
        }

        $remember = $request->session()->pull('2fa.remember', false);
        $request->session()->forget('2fa.user_id');

        Auth::login($user, $remember);

        return AuthenticatedSessionController::finishLogin($request);
    }
}
