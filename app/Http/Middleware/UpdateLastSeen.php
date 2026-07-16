<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // An admin viewing-as-client (or an owner logged in as another admin)
        // is browsing on the impersonated account's session, but it's the
        // admin driving, not the client — so this shouldn't make the client
        // show up as "Online" in the admin Client List. Mirrors the same
        // carve-out already applied to last_login_at (see
        // AuthenticatedSessionController::finishLogin's docblock).
        if ($user && ! $request->session()->has('impersonator_id')
            && (! $user->last_seen_at || $user->last_seen_at->lt(now()->subMinute()))) {
            $user->forceFill(['last_seen_at' => now()])->saveQuietly();
        }

        return $next($request);
    }
}
