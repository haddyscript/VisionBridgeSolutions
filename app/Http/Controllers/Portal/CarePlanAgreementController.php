<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\MaintenancePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarePlanAgreementController extends Controller
{
    public function show(Request $request)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project, 404);

        if ($project->hasAgreedToCarePlan()) {
            return redirect()->route('portal.agreement.show');
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

        if ($project->hasAgreedToCarePlan()) {
            return redirect()->route('portal.agreement.show');
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

        return redirect()->route('portal.agreement.show')
            ->with('status', 'Care Plan selected — next, please review and sign your Service Agreement.');
    }
}
