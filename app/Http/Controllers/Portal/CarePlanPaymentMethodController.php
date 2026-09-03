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
            'devBypassToken' => $this->matchingDevBypassToken($request),
        ]);
    }

    /**
     * Testing-only bypass — skips saving a real card and advances onboarding
     * as if the step were completed, leaving stripe_payment_method_id null.
     * Admin\ProjectController::update() only activates Care Plan billing
     * when that column is set, so a bypassed project simply never bills;
     * nothing downstream needs to know this step was skipped.
     *
     * Gated by DEV_BYPASS_TOKEN (see config/services.php) rather than any
     * environment check, per explicit request to allow this in production
     * too — unset in .env (the default everywhere) disables it entirely.
     * The token isn't shown anywhere in the UI unless already present on
     * the query string, and is re-checked here independently of the view.
     */
    public function skip(Request $request)
    {
        abort_unless($this->matchingDevBypassToken($request, $request->input('token')), 404);

        $project = $request->user()->projects()->with('subscription')->first();

        abort_unless($project, 404);

        $subscription = $project->subscription;

        abort_unless($subscription && $subscription->isPending(), 422, 'No pending Care Plan found for this project.');

        $request->user()->update(['onboarding_step' => 10]);

        return redirect()->route('portal.agreement.summary')
            ->with('status', 'Payment method step skipped (testing bypass) — no card was saved.');
    }

    /**
     * Returns the configured bypass token if $candidate matches it, else
     * null. Never true when DEV_BYPASS_TOKEN is unset, so an empty/missing
     * candidate can't accidentally match an empty configured value.
     */
    private function matchingDevBypassToken(Request $request, ?string $candidate = null): ?string
    {
        $configured = (string) config('services.dev_bypass.token');
        $candidate ??= (string) $request->query('dev_bypass', '');

        if ($configured === '' || $candidate === '' || ! hash_equals($configured, $candidate)) {
            return null;
        }

        return $configured;
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
