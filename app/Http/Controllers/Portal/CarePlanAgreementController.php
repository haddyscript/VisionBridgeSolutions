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
            return redirect()->route('portal.care-plan-payment-method.show');
        }

        // Deposit gate — only applies here, since the check above already
        // exempted anyone who came through the public Care Plan signup flow
        // (CarePlanSignupController), which has no deposit/proposal step at
        // all. This page sits outside EnsureOnboardingComplete's middleware
        // group by design (see that middleware's own docblock), so without
        // this a client could reach Care Plan selection by typing this URL
        // directly, before ever paying the deposit.
        if (! $project->hasDepositPaid()) {
            return redirect()->route('portal.deposit.show');
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
            return redirect()->route('portal.care-plan-payment-method.show')
                ->with('status', 'Care Plan selected — next, save a payment method for it.');
        }

        // Same deposit gate as show() — re-checked here too since store()
        // is a separate request a client could hit directly (e.g. replaying
        // a form POST) without ever loading show() first.
        if (! $project->hasDepositPaid()) {
            return redirect()->route('portal.deposit.show');
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

            // Billing doesn't start yet — stays 'pending' until an admin
            // marks the project Completed (see Admin\ProjectController::update
            // and App\Services\CarePlanActivator), which is what actually
            // creates the real Stripe Subscription using the payment method
            // saved in the next onboarding step.
            $project->subscriptions()->create([
                'maintenance_plan_id' => $plan->id,
                'description' => $plan->name,
                'amount' => $plan->price,
                'currency' => 'usd',
                'interval' => $plan->interval,
                'status' => 'pending',
            ]);
        });

        $request->user()->update(['onboarding_step' => 9]);

        return redirect()->route('portal.care-plan-payment-method.show')
            ->with('status', 'Care Plan selected — next, save a payment method for it.');
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

        $request->user()->update(['onboarding_step' => max($request->user()->onboarding_step ?? 1, 9)]);

        return true;
    }
}
