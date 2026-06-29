<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Services\SubscriptionReconciler;
use Illuminate\Http\Request;
use Stripe\BillingPortal\Session as BillingPortalSession;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    /**
     * Branded in-page checkout (Stripe Elements) instead of redirecting to
     * Stripe's hosted Checkout page. The Stripe Subscription is created
     * up front in 'default_incomplete' status — nothing is charged until
     * the client confirms their card on this page; the webhook flips our
     * local status to 'active' once the first invoice actually pays.
     */
    public function checkout(Request $request, Subscription $subscription)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $subscription->project_id === $project->id, 403);
        abort_unless($subscription->isPending(), 422, 'This maintenance plan has already been processed.');

        Stripe::setApiKey(config('services.stripe.secret'));

        if ($subscription->stripe_subscription_id) {
            // Client returned after an earlier failed/abandoned attempt — reuse
            // the existing incomplete subscription instead of creating another.
            $stripeSubscription = \Stripe\Subscription::retrieve([
                'id' => $subscription->stripe_subscription_id,
                'expand' => ['latest_invoice.payment_intent'],
            ]);
        } else {
            // Unlike Checkout Sessions, the Subscriptions API doesn't accept
            // inline product_data on price_data — it needs a real Product id.
            $product = \Stripe\Product::create(['name' => $subscription->description]);

            $stripeSubscription = \Stripe\Subscription::create([
                'customer' => $request->user()->getOrCreateStripeCustomerId(),
                'items' => [[
                    'price_data' => [
                        'currency' => $subscription->currency,
                        'unit_amount' => $subscription->amount,
                        'recurring' => ['interval' => $subscription->interval],
                        'product' => $product->id,
                    ],
                ]],
                'payment_behavior' => 'default_incomplete',
                'payment_settings' => ['save_default_payment_method' => 'on_subscription'],
                'expand' => ['latest_invoice.payment_intent'],
                'metadata' => [
                    'subscription_id' => $subscription->id,
                ],
            ]);

            $subscription->update(['stripe_subscription_id' => $stripeSubscription->id]);
        }

        $paymentIntent = $stripeSubscription->latest_invoice->payment_intent;

        // Already paid (e.g. the client double-clicked, or the webhook beat us
        // here) — nothing left to confirm, send them straight back.
        if ($paymentIntent->status === 'succeeded') {
            return redirect()->route('portal.payments.index')->with('status', 'This maintenance plan is already active.');
        }

        return view('portal.subscription-checkout', [
            'subscription' => $subscription,
            'clientSecret' => $paymentIntent->client_secret,
            'stripeKey' => config('services.stripe.key'),
        ]);
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
