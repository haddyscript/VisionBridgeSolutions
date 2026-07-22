<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ClientNotification;
use App\Models\LoginActivity;
use App\Support\AdminGreetings;
use App\Support\AdminPermissions;
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

        if (Auth::user()->isAdmin() && ! Auth::user()->is_active) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'This account has been deactivated. Contact a super admin for access.',
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

        $user = $request->user();

        // Flag an unrecognized sign-in (a browser or device/IP the client has
        // never used) BEFORE recording this login, so we compare against their
        // own genuine history — not impersonation logins, and not this one.
        $unrecognizedLogin = false;
        if (! $user->isAdmin()) {
            $browser = LoginActivity::browserFromAgent($request->userAgent());
            $ip = $request->ip();

            $priorLogins = LoginActivity::where('user_id', $user->id)
                ->whereNull('impersonator_id')
                ->get(['user_agent', 'ip_address']);

            if ($priorLogins->isNotEmpty()) {
                $unrecognizedLogin = ! $priorLogins->contains(
                    fn ($l) => LoginActivity::browserFromAgent($l->user_agent) === $browser
                        && $l->ip_address === $ip
                );
            }
        }

        LoginActivity::create([
            'user_id'      => $user->id,
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
            'logged_in_at' => now(),
        ]);

        // Genuine login only — impersonation goes through Auth::login()
        // directly in Admin\ClientController::impersonate() and never calls
        // finishLogin(), so it never touches this column.
        $user->update(['last_login_at' => now()]);

        if ($unrecognizedLogin) {
            ClientNotification::send(
                $user,
                'security',
                'New sign-in to your account',
                'We noticed a sign-in from '.LoginActivity::browserFromAgent($request->userAgent())
                    .' ('.($request->ip() ?? 'unknown IP').'). If this wasn\'t you, change your password right away.',
                route('portal.account.index'),
            );
        }

        if (! $user->isAdmin()) {
            $request->session()->put('show_payment_reminder', true);
            $request->session()->put('show_survey_prompt', true);
        } else {
            // Read once via session()->pull() in layouts.admin — shows exactly
            // once per genuine login, never on impersonation (Admin\TeamController
            // ::impersonate() and Admin\ClientController::impersonate() call
            // Auth::login() directly and never go through finishLogin()).
            $request->session()->put('admin_greeting', AdminGreetings::random());
        }

        $destination = $request->user()->isAdmin()
            ? AdminPermissions::adminLandingRoute($request->user())
            : route('portal.dashboard');

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
