<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class CarePlanPaymentMethodController extends Controller
{
    /**
     * Onboarding step between Care Plan selection and the Agreement — saves
     * a card on the client's Stripe customer (SetupIntent, usage:
     * off_session) WITHOUT creating a Stripe Subscription or charging
     * anything. Same SetupIntent-first mechanic as
     * Portal\SubscriptionController::checkout(), deliberately without that
     * controller's launched/maintenance gate, since this has to work
     * mid-onboarding. Billing only actually starts once an admin marks the
     * project "Completed" (Admin\ProjectController::update, via
     * App\Services\CarePlanActivator).
     */
    public function show(Request $request)
    {
        $project = $request->user()->projects()->with('subscription')->first();

        abort_unless($project, 404);

        $subscription = $project->subscription;

        abort_unless($subscription && $subscription->isPending(), 422, 'No pending Care Plan found for this project.');

        // Card already saved (e.g. the client came back to this step) — no
        // need to collect it again.
        if ($subscription->stripe_payment_method_id) {
            $request->user()->update(['onboarding_step' => max($request->user()->onboarding_step ?? 1, 10)]);

            return redirect()->route('portal.agreement.summary');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $setupIntent = \Stripe\SetupIntent::create([
                'customer' => $request->user()->getOrCreateStripeCustomerId(),
                // Plain card only — see Portal\SubscriptionController::checkout()
                // for why (Link's own auto-confirm races the form's submit handler).
                'payment_method_types' => ['card'],
                'usage' => 'off_session',
                'metadata' => ['subscription_id' => $subscription->id],
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe error starting Care Plan payment-method setup.', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);

            abort(500, 'Could not reach Stripe to save your card. Please try again shortly.');
        }

        return view('portal.care-plan-payment-method', [
            'subscription' => $subscription,
            'clientSecret' => $setupIntent->client_secret,
            'stripeKey' => config('services.stripe.key'),
        ]);
    }

    /**
     * Called once Stripe Elements confirms the SetupIntent. Only saves the
     * resulting payment method id — never calls Subscription::create(), so
     * nothing is charged here.
     */
    public function confirm(Request $request)
    {
        $project = $request->user()->projects()->with('subscription')->first();

        abort_unless($project, 404);

        $subscription = $project->subscription;

        abort_unless($subscription && $subscription->isPending(), 422, 'No pending Care Plan found for this project.');

        $validated = $request->validate([
            'setup_intent' => ['required', 'string'],
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $setupIntent = \Stripe\SetupIntent::retrieve($validated['setup_intent']);

            if ($setupIntent->status !== 'succeeded' || ! $setupIntent->payment_method) {
                return response()->json(['error' => 'Card setup was not completed. Please try again.'], 422);
            }

            $subscription->update(['stripe_payment_method_id' => $setupIntent->payment_method]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe error confirming Care Plan payment-method setup.', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);

            return response()->json(['error' => 'Could not reach Stripe to save your card. Please try again shortly.'], 500);
        }

        $request->user()->update(['onboarding_step' => 10]);

        return response()->json(['redirect' => route('portal.agreement.summary')]);
    }
}
