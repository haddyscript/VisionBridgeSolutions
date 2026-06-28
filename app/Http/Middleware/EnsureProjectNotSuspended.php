<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProjectNotSuspended
{
    /**
     * Blocks portal access entirely while a project is suspended for an
     * overdue Care Plan payment — except the suspended-notice page itself and
     * the routes a client needs to actually pay their way out (Stripe's
     * billing portal, and the subscription checkout/refresh actions). Admins
     * are exempt. Runs before onboarding.complete so a suspended client is
     * never bounced into an onboarding step instead.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $project = $user?->projects()->first();

        $exemptRoutes = [
            'portal.suspended',
            'portal.billing-portal',
            'portal.subscriptions.checkout',
            'portal.subscriptions.refresh',
        ];

        if ($user && ! $user->isAdmin() && $project?->isSuspended() && ! $request->routeIs($exemptRoutes)) {
            return redirect()->route('portal.suspended');
        }

        return $next($request);
    }
}
