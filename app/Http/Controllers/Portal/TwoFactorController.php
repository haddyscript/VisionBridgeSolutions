<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorAuthenticator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    public function show(Request $request, TwoFactorAuthenticator $authenticator)
    {
        $user = $request->user();

        if (! $user->hasTwoFactorEnabled() && ! $user->two_factor_secret) {
            $user->update(['two_factor_secret' => $authenticator->generateSecretKey()]);
            $user->refresh();
        }

        return view('portal.two-factor', [
            'enabled' => $user->hasTwoFactorEnabled(),
            'secretForDisplay' => $user->hasTwoFactorEnabled() ? null : $authenticator->formatSecretForDisplay($user->two_factor_secret),
            'recoveryCodes' => session('two_factor.fresh_recovery_codes'),
        ]);
    }

    public function confirm(Request $request, TwoFactorAuthenticator $authenticator)
    {
        $user = $request->user();

        abort_if($user->hasTwoFactorEnabled(), 422, 'Two-factor authentication is already enabled.');

        $validated = $request->validate([
            'code' => ['required', 'string'],
        ]);

        if (! $authenticator->verify($user->two_factor_secret, $validated['code'])) {
            throw ValidationException::withMessages([
                'code' => 'That code is invalid or has expired.',
            ]);
        }

        $recoveryCodes = $authenticator->generateRecoveryCodes();

        $user->update([
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => $recoveryCodes,
        ]);

        return redirect()->route('portal.two-factor.show')
            ->with('two_factor.fresh_recovery_codes', $recoveryCodes)
            ->with('status', 'Two-factor authentication is now enabled.');
    }

    public function disable(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $request->user()->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        return redirect()->route('portal.two-factor.show')->with('status', 'Two-factor authentication has been disabled.');
    }

    public function regenerateRecoveryCodes(Request $request, TwoFactorAuthenticator $authenticator)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        abort_unless($user->hasTwoFactorEnabled(), 422, 'Two-factor authentication is not enabled.');

        $recoveryCodes = $authenticator->generateRecoveryCodes();
        $user->update(['two_factor_recovery_codes' => $recoveryCodes]);

        return redirect()->route('portal.two-factor.show')
            ->with('two_factor.fresh_recovery_codes', $recoveryCodes)
            ->with('status', 'New recovery codes generated — your old codes no longer work.');
    }
}
