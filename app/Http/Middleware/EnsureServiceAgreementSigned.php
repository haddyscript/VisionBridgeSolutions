<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureServiceAgreementSigned
{
    /**
     * No project work may begin until the client has digitally signed the
     * current Service Agreement. Admins are exempt — this only gates clients.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $project = $user?->projects()->first();

        if ($user && ! $user->isAdmin() && $project && ! $project->hasSignedCurrentAgreement()) {
            return redirect()->route('portal.agreement.show');
        }

        return $next($request);
    }
}
