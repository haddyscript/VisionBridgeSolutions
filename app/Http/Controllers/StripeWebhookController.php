<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                config('services.stripe.webhook_secret'),
            );
        } catch (SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature verification failed.', ['error' => $e->getMessage()]);

            return response('Invalid signature.', 400);
        }

        match ($event->type) {
            'checkout.session.completed' => $this->handleCheckoutCompleted($event->data->object),
            'customer.subscription.updated' => $this->handleSubscriptionUpdated($event->data->object),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($event->data->object),
            default => null,
        };

        return response('Webhook handled.', 200);
    }

    private function handleCheckoutCompleted($session): void
    {
        if ($session->mode === 'subscription') {
            $subscription = Subscription::where('stripe_checkout_session_id', $session->id)->first();

            if ($subscription && $subscription->isPending()) {
                $subscription->update([
                    'status' => 'active',
                    'stripe_subscription_id' => $session->subscription,
                ]);
            }

            return;
        }

        $payment = Payment::where('stripe_checkout_session_id', $session->id)->first();

        if ($payment && $payment->isPending()) {
            $payment->update([
                'status' => 'paid',
                'stripe_payment_intent_id' => $session->payment_intent,
                'paid_at' => now(),
            ]);
        }
    }

    private function handleSubscriptionUpdated($stripeSubscription): void
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if (! $subscription) {
            return;
        }

        $statusMap = [
            'active' => 'active',
            'trialing' => 'active',
            'past_due' => 'past_due',
            'unpaid' => 'past_due',
            'incomplete' => 'past_due',
            'incomplete_expired' => 'canceled',
            'canceled' => 'canceled',
            'paused' => 'canceled',
        ];

        $subscription->update([
            'status' => $statusMap[$stripeSubscription->status] ?? $subscription->status,
            'current_period_end' => $stripeSubscription->current_period_end
                ? \Illuminate\Support\Carbon::createFromTimestamp($stripeSubscription->current_period_end)
                : null,
        ]);
    }

    private function handleSubscriptionDeleted($stripeSubscription): void
    {
        Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first()?->update([
            'status' => 'canceled',
            'canceled_at' => now(),
        ]);
    }
}
