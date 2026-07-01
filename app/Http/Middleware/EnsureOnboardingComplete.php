<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    /**
     * 13-step onboarding gate. Steps and their minimum value to pass each gate:
     *
     *   1  Welcome landing (public, no auth)
     *   2  Create account
     *   3  Verify email
     *   4  Business information (questionnaire) ← GATE 1 (step < 6 → questionnaire)
     *   5  Select Website Type      ← GATE 2 (step < 7 → website-type)
     *   6  Care Plan selection      ← GATE 3 (step < 8 → care plan)
     *   7  Agreement Summary        ← GATE 3 (step < 10 → summary)
     *   8  Read Master Agreement
     *   9  Acknowledgment checkboxes
     *  10  Electronic Signature     ← GATE 4 (step < 13 → agreement)
     *  11  Billing Authorization    [baked into signature — no separate gate]
     *  12  Payment                  [pending — requires pre-set pricing]
     *  13  Portal access granted
     *
     * Admins are exempt. Suspended projects are blocked by EnsureProjectNotSuspended
     * which runs before this middleware.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $project = $user?->projects()->first();

        if ($user && ! $user->isAdmin() && $project) {
            $step = $user->onboarding_step ?? 1;

            if ($step < 6) {
                return redirect()->route('portal.questionnaire.show');
            }

            if ($step < 7) {
                return redirect()->route('portal.website-type.show');
            }

            if ($step < 8) {
                return redirect()->route('portal.care-plan-agreement.show');
            }

            if ($step < 10) {
                return redirect()->route('portal.agreement.summary');
            }

            if ($step < 13) {
                return redirect()->route('portal.agreement.show');
            }
        }

        return $next($request);
    }
}
