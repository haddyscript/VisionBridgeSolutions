<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\SubscriptionCreatedMail;
use App\Models\MaintenancePlan;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Services\SubscriptionReconciler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\ApiErrorException;
use Stripe\HttpClient\CurlClient;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    /**
     * Branded in-page checkout (Stripe Elements) instead of redirecting to
     * Stripe's hosted Checkout page. Uses the SetupIntent-first pattern —
     * the card is collected/confirmed here, and the actual Subscription
     * is only created in confirm() once we know the card works. This
     * avoids depending on a Subscription's first-invoice PaymentIntent,
     * whose field name/shape varies across Stripe API versions.
     */
    public function checkout(Request $request, Subscription $subscription)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $subscription->project_id === $project->id, 403);
        abort_unless($subscription->isPending(), 422, 'This care plan has already been processed.');

        $this->configureStripe();

        try {
            $setupIntent = \Stripe\SetupIntent::create([
                'customer' => $request->user()->getOrCreateStripeCustomerId(),
                // Plain card only — Stripe's automatic_payment_methods default
                // also offers Link/Bancontact/etc., and Link's own "fast
                // checkout" auto-confirm raced with our form's submit handler,
                // confirming the same SetupIntent twice.
                'payment_method_types' => ['card'],
                'usage' => 'off_session',
                'metadata' => ['subscription_id' => $subscription->id],
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe error starting maintenance plan checkout.', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);

            abort(500, 'Could not reach Stripe to start this plan. Please try again shortly.');
        }

        return view('portal.subscription-checkout', [
            'subscription' => $subscription,
            'clientSecret' => $setupIntent->client_secret,
            'stripeKey' => config('services.stripe.key'),
        ]);
    }

    /**
     * Called by the checkout page's JS once Stripe Elements has confirmed the
     * SetupIntent (card saved). Creates the actual Subscription now, using
     * that confirmed card as the default payment method, and attempts the
     * first charge immediately. The webhook remains the source of truth for
     * flipping local status to 'active' once Stripe confirms payment.
     */
    public function confirm(Request $request, Subscription $subscription)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $subscription->project_id === $project->id, 403);
        abort_unless($subscription->isPending(), 422, 'This care plan has already been processed.');

        $validated = $request->validate([
            'setup_intent' => ['required', 'string'],
            'timezone' => ['nullable', 'string', 'max:100'],
        ]);

        if (! empty($validated['timezone'])) {
            $subscription->update(['timezone' => $validated['timezone']]);
        }

        $this->configureStripe();

        try {
            $setupIntent = \Stripe\SetupIntent::retrieve($validated['setup_intent']);

            if ($setupIntent->status !== 'succeeded' || ! $setupIntent->payment_method) {
                return response()->json(['error' => 'Card setup was not completed. Please try again.'], 422);
            }

            // Re-check against the database (not the route-bound instance,
            // which reflects the state at the *start* of this request) right
            // before doing anything destructive. A double-submit can have two
            // requests both pass the isPending() check above before either
            // finishes — without this, the second request would treat the
            // first request's brand-new, already-paid subscription as
            // "stale" and cancel it.
            if ($subscription->refresh()->isActive()) {
                return response()->json(['redirect' => route('portal.payments.index').'?checkout=success']);
            }

            // Any existing stripe_subscription_id here is genuinely stale —
            // left over from an earlier attempt under the old
            // Subscription-first flow, never paid, with no payment method
            // attached. Cancel it rather than reuse it, since reusing it
            // skipped attaching the card the client just confirmed and never
            // attempted a fresh charge.
            if ($subscription->stripe_subscription_id) {
                try {
                    \Stripe\Subscription::retrieve($subscription->stripe_subscription_id)->cancel();
                } catch (ApiErrorException) {
                    // Already canceled/gone — fine, we're replacing it anyway.
                }
            }

            // Use the real Stripe product/price the boss set up in the
            // dashboard whenever this subscription is tied to one of the
            // fixed Care Plan tiers — fall back to building an ad-hoc
            // product/price for admin-created custom amounts that aren't
            // tied to a maintenance_plan_id at all.
            if ($subscription->maintenancePlan?->stripe_price_id) {
                $item = ['price' => $subscription->maintenancePlan->stripe_price_id];
            } else {
                $product = \Stripe\Product::create(['name' => $subscription->description]);

                $item = [
                    'price_data' => [
                        'currency' => $subscription->currency,
                        'unit_amount' => $subscription->amount,
                        'recurring' => ['interval' => $subscription->interval],
                        'product' => $product->id,
                    ],
                ];
            }

            $stripeSubscription = \Stripe\Subscription::create([
                'customer' => $setupIntent->customer,
                'default_payment_method' => $setupIntent->payment_method,
                'items' => [$item],
                'metadata' => ['subscription_id' => $subscription->id],
            ]);

            // Set status here too (not just stripe_subscription_id) — canceling
            // the stale old subscription above can trigger its webhook to land
            // *during* this request (matching by the old id, which is still
            // what's saved locally at that point) and overwrite status to
            // 'canceled' before we get here. Writing the real status now wins
            // the race regardless of timing.
            $subscription->update([
                'stripe_subscription_id' => $stripeSubscription->id,
                'status' => in_array($stripeSubscription->status, ['active', 'trialing'], true) ? 'active' : 'pending',
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe error confirming maintenance plan subscription.', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Could not reach Stripe to finish setting up this plan. Please try again shortly.'], 500);
        }

        if (! in_array($stripeSubscription->status, ['active', 'trialing'], true)) {
            // Charge objects keep a clean, human-readable failure_message
            // regardless of Stripe API version — more reliable than digging
            // through the invoice/payment-intent shape for the same reason.
            $charges = \Stripe\Charge::all(['customer' => $setupIntent->customer, 'limit' => 1]);
            $declineReason = $charges->data[0]->failure_message ?? null;

            Log::warning('Maintenance plan subscription created but not active after first charge attempt.', [
                'subscription_id' => $subscription->id,
                'stripe_subscription_id' => $stripeSubscription->id,
                'stripe_status' => $stripeSubscription->status,
                'decline_reason' => $declineReason,
            ]);

            return response()->json([
                'error' => $declineReason ?? 'Your card could not be charged. Please try a different card.',
            ], 422);
        }

        Mail::to($request->user()->email)->send(new SubscriptionCreatedMail($subscription->load('project.user')));

        return response()->json(['redirect' => route('portal.payments.index').'?checkout=success']);
    }

    /**
     * Fail fast and loudly instead of hanging until PHP's hard execution
     * timeout silently kills the request (which produces a generic host
     * error page with nothing logged).
     */
    private function configureStripe(): void
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $curl = new CurlClient();
        $curl->setTimeout(20);
        $curl->setConnectTimeout(10);
        \Stripe\ApiRequestor::setHttpClient($curl);
    }

    public function refresh(Request $request, Subscription $subscription, SubscriptionReconciler $reconciler)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $subscription->project_id === $project->id, 403);

        return back()->with('status', $reconciler->reconcile($subscription));
    }

    public function receipt(Request $request, SubscriptionPayment $subscriptionPayment)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $subscriptionPayment->subscription->project_id === $project->id, 403);

        return view('portal.subscription-receipt', [
            'subscriptionPayment' => $subscriptionPayment,
            'project' => $project,
        ]);
    }

    /**
     * Branded billing-management page (update card / cancel) instead of
     * redirecting to Stripe's hosted Billing Portal. Card updates reuse the
     * same SetupIntent + Stripe Elements pattern as checkout().
     */
    public function manageBilling(Request $request)
    {
        $project = $request->user()->projects()->first();
        $subscription = $project?->subscription;

        abort_unless($subscription && ! $subscription->isPending(), 404, 'No active care plan found.');

        // Only a subscription tied to a real MaintenancePlan tier (not an
        // admin-created ad-hoc amount) has a well-defined "higher tier" to
        // upgrade to.
        $upgradeOptions = $subscription->maintenance_plan_id && $subscription->isActive()
            ? MaintenancePlan::where('is_available', true)
                ->whereNotNull('stripe_price_id')
                ->where('price', '>', $subscription->maintenancePlan->price)
                ->orderBy('price')
                ->get()
            : collect();

        $this->configureStripe();

        $card = null;

        try {
            if ($subscription->stripe_subscription_id) {
                $stripeSubscription = \Stripe\Subscription::retrieve([
                    'id' => $subscription->stripe_subscription_id,
                    'expand' => ['default_payment_method'],
                ]);

                $paymentMethod = $stripeSubscription->default_payment_method;
                $card = $paymentMethod?->card;
            }

            $setupIntent = \Stripe\SetupIntent::create([
                'customer' => $request->user()->getOrCreateStripeCustomerId(),
                'payment_method_types' => ['card'],
                'usage' => 'off_session',
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe error loading billing management page.', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);

            abort(500, 'Could not reach Stripe to load your billing details. Please try again shortly.');
        }

        return view('portal.subscription-billing', [
            'subscription' => $subscription,
            'card' => $card,
            'clientSecret' => $setupIntent->client_secret,
            'stripeKey' => config('services.stripe.key'),
            'upgradeOptions' => $upgradeOptions,
        ]);
    }

    public function updatePaymentMethod(Request $request, Subscription $subscription)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $subscription->project_id === $project->id, 403);
        abort_unless($subscription->stripe_subscription_id, 422, 'No active care plan found.');

        $validated = $request->validate([
            'setup_intent' => ['required', 'string'],
        ]);

        $this->configureStripe();

        try {
            $setupIntent = \Stripe\SetupIntent::retrieve($validated['setup_intent']);

            if ($setupIntent->status !== 'succeeded' || ! $setupIntent->payment_method) {
                return response()->json(['error' => 'Card setup was not completed. Please try again.'], 422);
            }

            $stripeSubscription = \Stripe\Subscription::update($subscription->stripe_subscription_id, [
                'default_payment_method' => $setupIntent->payment_method,
            ]);

            // Past due means there's an unpaid open invoice — Stripe will
            // retry it on its own schedule, but a client updating their card
            // because access was suspended expects "Pay Now" to actually pay
            // immediately rather than wait for that retry.
            if ($subscription->isPastDue() && $stripeSubscription->latest_invoice) {
                \Stripe\Invoice::pay($stripeSubscription->latest_invoice, [
                    'payment_method' => $setupIntent->payment_method,
                ]);
            }
        } catch (ApiErrorException $e) {
            Log::error('Stripe error updating maintenance plan payment method.', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Could not reach Stripe to update your card. Please try again shortly.'], 500);
        }

        return response()->json(['redirect' => route('portal.billing.show')]);
    }

    public function cancelPlan(Request $request, Subscription $subscription)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $subscription->project_id === $project->id, 403);

        if ($subscription->stripe_subscription_id) {
            $this->configureStripe();

            try {
                \Stripe\Subscription::retrieve($subscription->stripe_subscription_id)->cancel();
            } catch (ApiErrorException $e) {
                Log::error('Stripe error canceling maintenance plan.', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);

                return back()->withErrors(['subscription' => 'Could not reach Stripe to cancel this plan. Please try again or contact support.']);
            }
        }

        $subscription->update(['status' => 'canceled', 'canceled_at' => now()]);

        return redirect()->route('portal.payments.index')->with('status', 'Care plan canceled.');
    }

    /**
     * Self-service restart for a canceled plan — recreates the same plan
     * (same description/amount/maintenance_plan_id) as a fresh pending
     * Subscription and sends the client straight into the existing embedded
     * checkout flow, rather than requiring an admin to manually set it up.
     */
    public function restartPlan(Request $request, Subscription $subscription)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $subscription->project_id === $project->id, 403);
        abort_unless($subscription->isCanceled(), 422, 'Only a canceled plan can be restarted.');

        $newSubscription = $project->subscriptions()->create([
            'maintenance_plan_id' => $subscription->maintenance_plan_id,
            'description' => $subscription->description,
            'amount' => $subscription->amount,
            'currency' => $subscription->currency,
            'interval' => $subscription->interval,
        ]);

        return redirect()->route('portal.subscriptions.checkout', $newSubscription);
    }

    /**
     * Self-service upgrade to a higher Care Plan tier. Swaps the Stripe
     * subscription item's price via Subscription::update() rather than
     * canceling and recreating the subscription — that's what keeps the
     * existing billing cycle anchor (and therefore the renewal date)
     * untouched; only proration_behavior controls how the price difference
     * gets billed. Downgrades aren't offered here — same mechanism would
     * work, but self-service is scoped to upgrades only for now.
     */
    public function changePlan(Request $request, Subscription $subscription)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $subscription->project_id === $project->id, 403);
        abort_unless($subscription->isActive(), 422, 'Only an active care plan can be upgraded.');
        abort_unless(
            $subscription->stripe_subscription_id && $subscription->maintenance_plan_id,
            422,
            'This care plan isn\'t eligible for a self-service upgrade — contact support.'
        );

        $validated = $request->validate([
            'maintenance_plan_id' => ['required', 'exists:maintenance_plans,id'],
        ]);

        $targetPlan = MaintenancePlan::findOrFail($validated['maintenance_plan_id']);

        abort_unless(
            $targetPlan->is_available && $targetPlan->stripe_price_id && $targetPlan->price > $subscription->maintenancePlan->price,
            422,
            'That plan isn\'t available as an upgrade.'
        );

        $this->configureStripe();

        try {
            $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_subscription_id);
            $item = $stripeSubscription->items->data[0];

            // Omitting billing_cycle_anchor keeps Stripe's default behavior —
            // the existing renewal date stays exactly where it is.
            // proration_behavior 'create_prorations' bills the prorated
            // difference on the next regular invoice instead of charging
            // anything today.
            \Stripe\Subscription::update($subscription->stripe_subscription_id, [
                'items' => [[
                    'id' => $item->id,
                    'price' => $targetPlan->stripe_price_id,
                ]],
                'proration_behavior' => 'create_prorations',
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe error upgrading care plan.', [
                'subscription_id' => $subscription->id,
                'target_plan_id' => $targetPlan->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['subscription' => 'Could not reach Stripe to upgrade this plan. Please try again or contact support.']);
        }

        $subscription->update([
            'maintenance_plan_id' => $targetPlan->id,
            'description' => $targetPlan->name,
            'amount' => $targetPlan->price,
            'interval' => $targetPlan->interval,
        ]);

        return redirect()->route('portal.billing.show')->with(
            'status',
            "Upgraded to the {$targetPlan->name} plan. Your renewal date hasn't changed — you'll see the prorated difference on your next invoice."
        );
    }
}
