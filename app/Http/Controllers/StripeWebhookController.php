<?php

namespace App\Http\Controllers;

use App\Mail\AdminPaymentNotificationMail;
use App\Mail\PaymentReceiptMail;
use App\Mail\SubscriptionReceiptMail;
use App\Mail\SubscriptionStatusAlertMail;
use App\Mail\SystemAlertMail;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
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

            if (Cache::add('system-alert:stripe-signature-failure', true, now()->addMinutes(15))) {
                Mail::to(config('mail.admin_address'))->send(new SystemAlertMail(
                    'Stripe Webhook Signature Verification Failed',
                    "A request to the Stripe webhook endpoint failed signature verification. This usually means a misconfigured webhook secret, or could indicate someone probing the endpoint. Further alerts of this type are suppressed for 15 minutes.",
                    ['Error' => $e->getMessage()],
                ));
            }

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
            $receiptUrl = $this->fetchReceiptUrl($session->id);

            $payment->update([
                'status' => 'paid',
                'stripe_payment_intent_id' => $session->payment_intent,
                'stripe_receipt_url' => $receiptUrl,
                'paid_at' => now(),
            ]);

            Mail::to($payment->project->user->email)->send(
                new PaymentReceiptMail($payment, $receiptUrl)
            );

            $this->notifyAdminOfPayment($payment);
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

        $receiptUrl = $this->fetchReceiptUrl($sessionId);

        $payment->update([
            'status' => 'paid',
            'stripe_payment_intent_id' => $paymentIntent->id,
            'stripe_receipt_url' => $receiptUrl,
            'paid_at' => now(),
        ]);

        Mail::to($payment->project->user->email)->send(
            new PaymentReceiptMail($payment, $receiptUrl)
        );

        $this->notifyAdminOfPayment($payment);
    }

    private function notifyAdminOfPayment(Payment $payment): void
    {
        Mail::to(config('mail.admin_address'))->send(new AdminPaymentNotificationMail(
            $payment,
            $payment->project->user->name,
            $payment->project->name,
            $payment->formattedAmount(),
            $payment->paid_at ?? now(),
        ));
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
        $subscription = Subscription::with('project.user')->where('stripe_subscription_id', $stripeSubscription->id)->first();

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

        $previousStatus = $subscription->status;
        $newStatus = $statusMap[$stripeSubscription->status] ?? $subscription->status;

        $subscription->update([
            'status' => $newStatus,
            'current_period_end' => $stripeSubscription->current_period_end
                ? Carbon::createFromTimestamp($stripeSubscription->current_period_end)
                : null,
        ]);

        if ($newStatus !== $previousStatus && in_array($newStatus, ['past_due', 'canceled'], true)) {
            Mail::to(config('mail.admin_address'))->send(new SubscriptionStatusAlertMail($subscription));
        }
    }

    private function handleSubscriptionDeleted($stripeSubscription): void
    {
        $subscription = Subscription::with('project.user')->where('stripe_subscription_id', $stripeSubscription->id)->first();

        if (! $subscription) {
            return;
        }

        $subscription->update([
            'status' => 'canceled',
            'canceled_at' => now(),
        ]);

        Mail::to(config('mail.admin_address'))->send(new SubscriptionStatusAlertMail($subscription));
    }

    private function handleInvoicePaymentSucceeded($invoice): void
    {
        $stripeSubscriptionId = $invoice->parent->subscription_details->subscription
            ?? $invoice->subscription
            ?? null;

        if (! $stripeSubscriptionId) {
            return;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $subscription = Subscription::with('project.user')->where('stripe_subscription_id', $stripeSubscriptionId)->first();
        $stripeSubscription = null;

        if (! $subscription) {
            $stripeSubscription = \Stripe\Subscription::retrieve($stripeSubscriptionId);
            $localId = $stripeSubscription->metadata->subscription_id ?? null;

            if ($localId) {
                $subscription = Subscription::with('project.user')->where('id', $localId)->first();
            }
        }

        if (! $subscription) {
            return;
        }

        if ($subscription->isPending()) {
            $stripeSubscription ??= \Stripe\Subscription::retrieve($stripeSubscriptionId);

            $subscription->update([
                'status' => 'active',
                'stripe_subscription_id' => $stripeSubscriptionId,
                'current_period_end' => $stripeSubscription->current_period_end
                    ? Carbon::createFromTimestamp($stripeSubscription->current_period_end)
                    : null,
            ]);
        }

        $paidAt = Carbon::createFromTimestamp($invoice->status_transitions->paid_at ?? $invoice->created);

        Mail::to($subscription->project->user->email)->send(
            new SubscriptionReceiptMail(
                $subscription,
                $invoice->amount_paid,
                $paidAt,
                $invoice->hosted_invoice_url,
            )
        );

        Mail::to(config('mail.admin_address'))->send(new AdminPaymentNotificationMail(
            $subscription,
            $subscription->project->user->name,
            $subscription->project->name,
            '$'.number_format($invoice->amount_paid / 100, 2),
            $paidAt,
        ));
    }
}
