<?php

namespace App\Services;

use App\Mail\AdminPaymentNotificationMail;
use App\Mail\PaymentReceiptMail;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Charge;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentReconciler
{
    public function reconcile(Payment $payment): string
    {
        if ($payment->isPaid()) {
            return 'This payment is already marked as paid.';
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        // The embedded checkout page creates a PaymentIntent directly (no
        // Checkout Session) — check that first since it's set immediately,
        // before the client even confirms their card.
        if ($payment->stripe_payment_intent_id) {
            return $this->reconcileFromPaymentIntent($payment);
        }

        if (! $payment->stripe_checkout_session_id) {
            return 'No payment attempt is on record for this payment yet — the client hasn\'t started checkout.';
        }

        return $this->reconcileFromCheckoutSession($payment);
    }

    private function reconcileFromPaymentIntent(Payment $payment): string
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($payment->stripe_payment_intent_id, [
                'expand' => ['latest_charge'],
            ]);
        } catch (ApiErrorException $e) {
            Log::warning('Could not retrieve Stripe PaymentIntent during manual payment sync.', ['error' => $e->getMessage()]);

            return 'Could not reach Stripe to check this payment. Try again shortly.';
        }

        if ($paymentIntent->status !== 'succeeded') {
            return 'Checked with Stripe — this payment has not been completed yet.';
        }

        $latestCharge = $paymentIntent->latest_charge;

        if (is_string($latestCharge)) {
            $latestCharge = Charge::retrieve($latestCharge);
        }

        return $this->markPaidAndNotify($payment, $latestCharge?->receipt_url);
    }

    private function reconcileFromCheckoutSession(Payment $payment): string
    {
        try {
            $session = Session::retrieve($payment->stripe_checkout_session_id, [
                'expand' => ['payment_intent.latest_charge'],
            ]);
        } catch (ApiErrorException $e) {
            Log::warning('Could not retrieve Stripe session during manual payment sync.', ['error' => $e->getMessage()]);

            return 'Could not reach Stripe to check this payment. Try again shortly.';
        }

        if ($session->payment_status !== 'paid') {
            return 'Checked with Stripe — this payment has not been completed yet.';
        }

        $paymentIntent = $session->payment_intent;

        if (is_string($paymentIntent)) {
            $paymentIntent = PaymentIntent::retrieve($paymentIntent, ['expand' => ['latest_charge']]);
        }

        $latestCharge = $paymentIntent?->latest_charge;

        if (is_string($latestCharge)) {
            $latestCharge = Charge::retrieve($latestCharge);
        }

        $payment->stripe_payment_intent_id = is_object($paymentIntent) ? $paymentIntent->id : $paymentIntent;

        return $this->markPaidAndNotify($payment, $latestCharge?->receipt_url);
    }

    private function markPaidAndNotify(Payment $payment, ?string $receiptUrl): string
    {
        $payment->status = 'paid';
        $payment->paid_at = now();
        $payment->save();

        Mail::to($payment->project->user->email)->send(
            new PaymentReceiptMail($payment, $receiptUrl)
        );

        Mail::to(config('mail.billing_address'))->send(new AdminPaymentNotificationMail(
            $payment,
            $payment->project->user->name,
            $payment->project->name,
            $payment->formattedAmount(),
            $payment->paid_at,
        ));

        return 'Synced with Stripe — this payment was actually paid. Marked as paid and sent the client a receipt.';
    }
}
