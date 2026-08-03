<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OnboardingCompleteController extends Controller
{
    /**
     * One-time summary shown right after the Agreement is signed — reads
     * data that's already true by this point (nothing new to compute), just
     * gives the client a clear "you're all set" moment before continuing
     * into the portal.
     */
    public function show(Request $request)
    {
        $project = $request->user()->projects()->with('payments', 'carePlanAgreement.maintenancePlan')->first();

        abort_unless($project, 404);

        // Same self-guard convention as the other onboarding pages (e.g.
        // ServiceAgreementController::show()) — this route sits outside the
        // onboarding.complete gate so it isn't itself a redirect loop, but
        // that means it's directly reachable by URL at any point, not just
        // right after signing. Send anyone who isn't actually finished back
        // to wherever the real gate would put them.
        if (! $project->hasSignedCurrentAgreement()) {
            return redirect()->route('portal.dashboard');
        }

        return view('portal.onboarding-complete', [
            'project' => $project,
        ]);
    }
}
