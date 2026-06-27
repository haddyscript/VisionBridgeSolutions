<?php

namespace App\Http\Controllers;

use App\Mail\AdminPaymentNotificationMail;
use App\Mail\FaithStackNewClientMail;
use App\Mail\PaymentReceiptMail;
use App\Mail\SubscriptionReceiptMail;
use App\Mail\SubscriptionStatusAlertMail;
use App\Mail\SystemAlertMail;
use App\Mail\WelcomeClientMail;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPayout;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
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
            'charge.refunded' => $this->flagPayoutForInvoice($event->data->object->invoice ?? null, 'Refunded'),
            'charge.dispute.created' => $this->flagPayoutForDispute($event->data->object),
            default => null,
        };

        return response('Webhook handled.', 200);
    }

    private function handleCheckoutCompleted($session): void
    {
        if ($session->mode === 'subscription') {
            $subscription = Subscription::with('project.user', 'maintenancePlan')
                ->where('stripe_checkout_session_id', $session->id)->first();

            if ($subscription && $subscription->isPending()) {
                $subscription->update([
                    'status' => 'active',
                    'stripe_subscription_id' => $session->subscription,
                ]);

                // Only the new public Website Care Plan self-checkout flow links a
                // maintenance_plan_id — admin-created plans for already-onboarded
                // clients don't, and that client already has portal access.
                if ($subscription->maintenance_plan_id) {
                    $this->welcomeNewCarePlanClient($subscription);
                }
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

    /**
     * First-time onboarding for a client who self-subscribed to a Website
     * Care Plan from the public pricing page — sends them a password-setup
     * link for the Client Portal and notifies FaithStack of the new client
     * (separate from the VisionBridge admin payment notification, which
     * handleInvoicePaymentSucceeded already sends for every billing cycle).
     */
    private function welcomeNewCarePlanClient(Subscription $subscription): void
    {
        $user = $subscription->project->user;

        $resetToken = Password::createToken($user);
        $resetUrl = route('password.reset', ['token' => $resetToken, 'email' => $user->email]);

        Mail::to($user->email)->send(new WelcomeClientMail($user, $resetUrl));
        Mail::to(config('mail.faithstack_address'))->send(new FaithStackNewClientMail($subscription));
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

        // Stripe's "flexible" billing mode (2025+) moved current_period_end off the
        // subscription object and onto each subscription item, and represents a
        // scheduled cancellation via `cancel_at` rather than always setting the
        // `cancel_at_period_end` boolean. Check both shapes for compatibility.
        $periodEnd = $stripeSubscription->current_period_end
            ?? ($stripeSubscription->items->data[0]->current_period_end ?? null);

        $cancelAtPeriodEnd = (bool) ($stripeSubscription->cancel_at_period_end ?? false)
            || ! empty($stripeSubscription->cancel_at);

        $subscription->update([
            'status' => $newStatus,
            'current_period_end' => $periodEnd ? Carbon::createFromTimestamp($periodEnd) : null,
            'cancel_at_period_end' => $cancelAtPeriodEnd,
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

        $subscription = Subscription::with('project.user', 'maintenancePlan')->where('stripe_subscription_id', $stripeSubscriptionId)->first();
        $stripeSubscription = null;

        if (! $subscription) {
            $stripeSubscription = \Stripe\Subscription::retrieve($stripeSubscriptionId);
            $localId = $stripeSubscription->metadata->subscription_id ?? null;

            if ($localId) {
                $subscription = Subscription::with('project.user', 'maintenancePlan')->where('id', $localId)->first();
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

        // Track what VisionBridge owes FaithStack for this billing cycle. Starts
        // 'pending' — VerifyCarePlanPayouts promotes it to 'ready' after 7 clean
        // days (see SubscriptionPayout::VERIFICATION_DAYS), or flagPayoutForInvoice
        // below holds it if a dispute/refund comes in first. The actual transfer
        // to FaithStack is still a manual step (see partnership agreement — Stripe
        // can't pay out to the Philippines, so full automation isn't possible yet).
        // stripe_invoice_id is unique, so a duplicate webhook delivery is a no-op.
        if ($subscription->maintenance_plan_id && $subscription->maintenancePlan?->faithstack_compensation) {
            SubscriptionPayout::firstOrCreate(
                ['stripe_invoice_id' => $invoice->id],
                [
                    'subscription_id' => $subscription->id,
                    'client_amount' => $invoice->amount_paid,
                    'faithstack_amount' => $subscription->maintenancePlan->faithstack_compensation,
                ]
            );
        }
    }

    /**
     * A dispute means Stripe needs us to retrieve the underlying charge first
     * to find which invoice (and therefore which SubscriptionPayout) it belongs to.
     */
    private function flagPayoutForDispute($dispute): void
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $charge = \Stripe\Charge::retrieve($dispute->charge);
        } catch (ApiErrorException $e) {
            Log::warning('Could not retrieve disputed charge.', ['error' => $e->getMessage()]);

            return;
        }

        $this->flagPayoutForInvoice($charge->invoice ?? null, 'Disputed / chargeback');
    }

    /**
     * Holds (or flags, if already paid) the FaithStack payout tied to this
     * invoice and alerts VisionBridge — the actual money movement to FaithStack
     * stays a manual decision either way.
     */
    private function flagPayoutForInvoice(?string $invoiceId, string $reason): void
    {
        if (! $invoiceId) {
            return;
        }

        $payout = SubscriptionPayout::with('subscription.project.user', 'subscription.maintenancePlan')
            ->where('stripe_invoice_id', $invoiceId)
            ->first();

        if (! $payout) {
            return;
        }

        $wasAlreadyPaid = $payout->isPaid();

        $payout->update([
            'status' => $wasAlreadyPaid ? $payout->status : 'flagged',
            'flagged_at' => now(),
            'flag_reason' => $reason,
        ]);

        Mail::to(config('mail.admin_address'))->send(new SystemAlertMail(
            $wasAlreadyPaid
                ? "Care Plan Payment {$reason} After FaithStack Was Already Paid"
                : "Care Plan Payment {$reason} — FaithStack Payout Held",
            $wasAlreadyPaid
                ? "A client payment that FaithStack has already been paid for was just marked '{$reason}'. This needs manual review."
                : "A client payment was marked '{$reason}' during its 7-day verification window. The FaithStack payout for this cycle has been held and needs manual review before release.",
            [
                'Client' => $payout->subscription->project->user->name,
                'Plan' => $payout->subscription->maintenancePlan?->name ?? $payout->subscription->description,
                'FaithStack Amount' => $payout->formattedFaithstackAmount(),
                'Payout Status' => $payout->status,
            ],
        ));
    }
}
