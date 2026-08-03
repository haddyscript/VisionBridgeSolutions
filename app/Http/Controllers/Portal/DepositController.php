<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    /**
     * Onboarding gate between Website Type and Care Plan selection —
     * VisionBridge reviews the client's website type + proposal and sets a
     * price manually (Admin\ProjectController::update), which auto-creates
     * this deposit Payment. Nothing to do here until that's happened; once
     * the deposit is paid, StripeWebhookController::maybeAdvanceOnboardingAfterDeposit
     * advances onboarding_step past this gate, so this page is never shown
     * again for that client.
     */
    public function show(Request $request)
    {
        $project = $request->user()->projects()->with('payments')->first();

        abort_unless($project, 404);

        return view('portal.deposit', [
            'project' => $project,
            'deposit' => $project->depositPayment(),
        ]);
    }
}
