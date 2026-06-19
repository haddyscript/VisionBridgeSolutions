<?php

namespace App\Http\Controllers;

use App\Mail\PaymentReceiptMail;
use App\Mail\SubscriptionReceiptMail;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
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
            'invoice.payment_succeeded' => $this->handleInvoicePaymentSucceeded($event->data->object),
            'payment_intent.succeeded' => $this->handlePaymentIntentSucceeded($event->data->object),
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

        $payment = Payment::with('project.user')->where('stripe_checkout_session_id', $session->id)->first();

        if ($payment && $payment->isPending()) {
            $payment->update([
                'status' => 'paid',
                'stripe_payment_intent_id' => $session->payment_intent,
                'paid_at' => now(),
            ]);

            Mail::to($payment->project->user->email)->send(
                new PaymentReceiptMail($payment, $this->fetchReceiptUrl($session->id))
            );
        }
    }

    private function handlePaymentIntentSucceeded($paymentIntent): void
    {
        $sessionId = $paymentIntent->payment_details->order_reference ?? null;

        if (! $sessionId) {
            return;
        }

        $payment = Payment::with('project.user')->where('stripe_checkout_session_id', $sessionId)->first();

        if (! $payment || ! $payment->isPending()) {
            return;
        }

        $payment->update([
            'status' => 'paid',
            'stripe_payment_intent_id' => $paymentIntent->id,
            'paid_at' => now(),
        ]);

        Mail::to($payment->project->user->email)->send(
            new PaymentReceiptMail($payment, $this->fetchReceiptUrl($sessionId))
        );
    }

    private function fetchReceiptUrl(string $sessionId): ?string
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $session = Session::retrieve($sessionId, [
                'expand' => ['payment_intent.latest_charge'],
            ]);

            $paymentIntent = $session->payment_intent;

            if (is_string($paymentIntent)) {
                $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntent, [
                    'expand' => ['latest_charge'],
                ]);
            }

            $latestCharge = $paymentIntent?->latest_charge;

            if (is_string($latestCharge)) {
                $latestCharge = \Stripe\Charge::retrieve($latestCharge);
            }

            return $latestCharge?->receipt_url;
        } catch (ApiErrorException $e) {
            Log::warning('Could not fetch Stripe receipt URL.', ['error' => $e->getMessage()]);

            return null;
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
                ? Carbon::createFromTimestamp($stripeSubscription->current_period_end)
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

    private function handleInvoicePaymentSucceeded($invoice): void
    {
        if (! $invoice->subscription) {
            return;
        }

        $subscription = Subscription::with('project.user')->where('stripe_subscription_id', $invoice->subscription)->first();

        if (! $subscription) {
            return;
        }

        Mail::to($subscription->project->user->email)->send(
            new SubscriptionReceiptMail(
                $subscription,
                $invoice->amount_paid,
                Carbon::createFromTimestamp($invoice->status_transitions->paid_at ?? $invoice->created),
                $invoice->hosted_invoice_url,
            )
        );
    }
}
