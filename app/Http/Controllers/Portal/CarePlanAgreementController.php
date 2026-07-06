<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\MaintenancePlan;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarePlanAgreementController extends Controller
{
    public function show(Request $request)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project, 404);

        if ($this->autoAgreeIfSubscriptionAlreadyExists($request, $project)) {
            return redirect()->route('portal.agreement.summary');
        }

        return view('portal.care-plan-agreement', [
            'plans' => MaintenancePlan::where('is_available', true)
                ->whereNotNull('price')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project, 404);

        if ($this->autoAgreeIfSubscriptionAlreadyExists($request, $project)) {
            return redirect()->route('portal.agreement.summary')
                ->with('status', 'Care Plan selected — please review your agreement summary before signing.');
        }

        $validated = $request->validate([
            'maintenance_plan_id' => ['required', 'exists:maintenance_plans,id'],
            'agree' => ['accepted'],
        ]);

        $plan = MaintenancePlan::findOrFail($validated['maintenance_plan_id']);

        abort_unless($plan->is_available && $plan->price !== null, 422, 'This plan is not available.');

        DB::transaction(function () use ($project, $plan, $request) {
            $project->carePlanAgreement()->create([
                'maintenance_plan_id' => $plan->id,
                'agreed_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
            ]);

            // Billing doesn't start yet — stays 'pending' until the project
            // launches (see StripeWebhookController::maybeAutoLaunchProject),
            // which is when the client gets emailed a link to complete checkout.
            $project->subscriptions()->create([
                'maintenance_plan_id' => $plan->id,
                'description' => $plan->name,
                'amount' => $plan->price,
                'currency' => 'usd',
                'interval' => $plan->interval,
                'status' => 'pending',
            ]);
        });

        $request->user()->update(['onboarding_step' => 8]);

        return redirect()->route('portal.agreement.summary')
            ->with('status', 'Care Plan selected — please review your agreement summary before signing.');
    }

    /**
     * A client who signed up through the public pre-account Care Plan form
     * (CarePlanSignupController) already selected a plan and paid — they
     * still have to pass through this onboarding step since it gates every
     * account regardless of how it was created, but presenting the form
     * again and letting store() run would create a second, duplicate
     * Subscription for the same project (see specs/CARE_PLAN_SUBSCRIPTION_FLOW.md).
     * If a non-canceled subscription already exists, auto-record the
     * agreement against that plan instead and skip creating a new one.
     */
    private function autoAgreeIfSubscriptionAlreadyExists(Request $request, Project $project): bool
    {
        if ($project->hasAgreedToCarePlan()) {
            return true;
        }

        $existingSubscription = $project->subscriptions()
            ->whereNotNull('maintenance_plan_id')
            ->where('status', '!=', 'canceled')
            ->first();

        if (! $existingSubscription) {
            return false;
        }

        DB::transaction(function () use ($project, $existingSubscription, $request) {
            $project->carePlanAgreement()->create([
                'maintenance_plan_id' => $existingSubscription->maintenance_plan_id,
                'agreed_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
            ]);
        });

        $request->user()->update(['onboarding_step' => max($request->user()->onboarding_step ?? 1, 8)]);

        return true;
    }
}
