<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Services\SubscriptionReconciler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\BillingPortal\Session as BillingPortalSession;
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
        abort_unless($subscription->isPending(), 422, 'This maintenance plan has already been processed.');

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
        abort_unless($subscription->isPending(), 422, 'This maintenance plan has already been processed.');

        $validated = $request->validate([
            'setup_intent' => ['required', 'string'],
        ]);

        $this->configureStripe();

        try {
            $setupIntent = \Stripe\SetupIntent::retrieve($validated['setup_intent']);

            if ($setupIntent->status !== 'succeeded' || ! $setupIntent->payment_method) {
                return response()->json(['error' => 'Card setup was not completed. Please try again.'], 422);
            }

            // Any existing stripe_subscription_id here is stale — left over
            // from an earlier attempt under the old Subscription-first flow,
            // never paid, with no payment method attached. Cancel it rather
            // than reuse it, since reusing it skipped attaching the card the
            // client just confirmed and never attempted a fresh charge.
            if ($subscription->stripe_subscription_id) {
                try {
                    \Stripe\Subscription::retrieve($subscription->stripe_subscription_id)->cancel();
                } catch (ApiErrorException) {
                    // Already canceled/gone — fine, we're replacing it anyway.
                }
            }

            $product = \Stripe\Product::create(['name' => $subscription->description]);

            $stripeSubscription = \Stripe\Subscription::create([
                'customer' => $setupIntent->customer,
                'default_payment_method' => $setupIntent->payment_method,
                'items' => [[
                    'price_data' => [
                        'currency' => $subscription->currency,
                        'unit_amount' => $subscription->amount,
                        'recurring' => ['interval' => $subscription->interval],
                        'product' => $product->id,
                    ],
                ]],
                'metadata' => ['subscription_id' => $subscription->id],
            ]);

            $subscription->update(['stripe_subscription_id' => $stripeSubscription->id]);
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

    public function billingPortal(Request $request)
    {
        $user = $request->user();

        abort_unless($user->stripe_customer_id, 404, 'No billing account found yet.');

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = BillingPortalSession::create([
            'customer' => $user->stripe_customer_id,
            'return_url' => route('portal.payments.index'),
        ]);

        return redirect()->away($session->url);
    }
}
