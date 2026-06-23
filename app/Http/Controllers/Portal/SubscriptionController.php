<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\SubscriptionReconciler;
use Illuminate\Http\Request;
use Stripe\BillingPortal\Session as BillingPortalSession;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    public function checkout(Request $request, Subscription $subscription)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $subscription->project_id === $project->id, 403);
        abort_unless($subscription->isPending(), 422, 'This maintenance plan has already been processed.');

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = CheckoutSession::create([
            'mode' => 'subscription',
            'payment_method_types' => ['card'],
            'customer' => $request->user()->getOrCreateStripeCustomerId(),
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => $subscription->currency,
                    'unit_amount' => $subscription->amount,
                    'recurring' => ['interval' => $subscription->interval],
                    'product_data' => [
                        'name' => $subscription->description,
                    ],
                ],
            ]],
            'success_url' => route('portal.payments.index').'?checkout=success',
            'cancel_url' => route('portal.payments.index').'?checkout=cancel',
            'metadata' => [
                'subscription_id' => $subscription->id,
            ],
            'subscription_data' => [
                'metadata' => [
                    'subscription_id' => $subscription->id,
                ],
            ],
        ]);

        $subscription->update(['stripe_checkout_session_id' => $session->id]);

        return redirect()->away($session->url);
    }

    public function refresh(Request $request, Subscription $subscription, SubscriptionReconciler $reconciler)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $subscription->project_id === $project->id, 403);

        return back()->with('status', $reconciler->reconcile($subscription));
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
