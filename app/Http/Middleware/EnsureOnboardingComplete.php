<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    /**
     * No project work may begin until the client has selected and agreed to a
     * Website Care Plan, then digitally signed the current Service Agreement,
     * then completed the onboarding questionnaire. Admins are exempt — this
     * only gates clients. Suspended projects are blocked entirely, regardless
     * of onboarding progress — see EnsureProjectNotSuspended, which runs first.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $project = $user?->projects()->first();

        if ($user && ! $user->isAdmin() && $project) {
            if (! $project->hasAgreedToCarePlan()) {
                return redirect()->route('portal.care-plan-agreement.show');
            }

            if (! $project->hasSignedCurrentAgreement()) {
                return redirect()->route('portal.agreement.show');
            }

            if (! $project->hasCompletedQuestionnaire()) {
                return redirect()->route('portal.questionnaire.show');
            }
        }

        return $next($request);
    }
}
