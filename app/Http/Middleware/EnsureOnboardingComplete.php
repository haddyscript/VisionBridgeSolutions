<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    /**
     * Onboarding gate, driven entirely by users.onboarding_step. Steps and
     * their minimum value to pass each gate:
     *
     *   4  Business information (questionnaire) ← GATE (step < 6 → questionnaire)
     *   5  Select Website Type      ← GATE (step < 7 → website-type)
     *   -  Deposit paid             ← GATE (step < 8 → deposit) — step only
     *      ever reaches 8 via StripeWebhookController::maybeAdvanceOnboardingAfterDeposit,
     *      once VisionBridge has set a price and the client has paid the
     *      initial 50% deposit, so no separate Payment lookup is needed here.
     *   6  Care Plan selection      ← GATE (step < 9 → care plan)
     *   -  Care Plan payment method ← GATE (step < 10 → care-plan-payment-method)
     *      saved via SetupIntent (no charge yet — billing starts once the
     *      project is later marked Completed, see Admin\ProjectController).
     *   7  Agreement Summary        ← GATE (step < 11 → summary)
     *   8  Read Master Agreement
     *   9  Acknowledgment checkboxes
     *  10  Electronic Signature     ← GATE (step < 13 → agreement)
     *  11  Billing Authorization    [baked into signature — no separate gate]
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
                return redirect()->route('portal.deposit.show');
            }

            if ($step < 9) {
                return redirect()->route('portal.care-plan-agreement.show');
            }

            if ($step < 10) {
                return redirect()->route('portal.care-plan-payment-method.show');
            }

            if ($step < 11) {
                return redirect()->route('portal.agreement.summary');
            }

            if ($step < 13) {
                return redirect()->route('portal.agreement.show');
            }
        }

        return $next($request);
    }
}
