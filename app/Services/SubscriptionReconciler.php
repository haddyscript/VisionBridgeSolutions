<?php

namespace App\Services;

use App\Models\Subscription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class SubscriptionReconciler
{
    public function reconcile(Subscription $subscription): string
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        if ($subscription->stripe_subscription_id) {
            return $this->resyncFromStripeSubscription($subscription);
        }

        if (! $subscription->isPending()) {
            return 'This maintenance plan is already '.$subscription->status.'.';
        }

        if (! $subscription->stripe_checkout_session_id) {
            return 'No checkout session is on record yet — start the plan first.';
        }

        try {
            $session = Session::retrieve($subscription->stripe_checkout_session_id);
        } catch (ApiErrorException $e) {
            Log::warning('Could not retrieve Stripe session during subscription sync.', ['error' => $e->getMessage()]);

            return 'Could not reach Stripe to check this plan. Try again shortly.';
        }

        if ($session->status !== 'complete' || ! $session->subscription) {
            return 'Checked with Stripe — this plan has not been activated yet.';
        }

        $stripeSubscription = \Stripe\Subscription::retrieve($session->subscription);

        $periodEnd = $stripeSubscription->current_period_end
            ?? ($stripeSubscription->items->data[0]->current_period_end ?? null);

        $subscription->update([
            'status' => 'active',
            'stripe_subscription_id' => $stripeSubscription->id,
            'current_period_end' => $periodEnd ? Carbon::createFromTimestamp($periodEnd) : null,
        ]);

        return 'Synced with Stripe — this plan is active.';
    }

    private function resyncFromStripeSubscription(Subscription $subscription): string
    {
        try {
            $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_subscription_id);
        } catch (ApiErrorException $e) {
            Log::warning('Could not retrieve Stripe subscription during sync.', ['error' => $e->getMessage()]);

            return 'Could not reach Stripe to check this plan. Try again shortly.';
        }

        $statusMap = [
            'active' => 'active',
            'trialing' => 'active',
            'past_due' => 'past_due',
            'unpaid' => 'past_due',
            // 'incomplete' means the very first invoice has never been paid —
            // that's not the same as an active plan falling behind, so it
            // maps back to 'pending' (which is also what keeps the "Start
            // Plan" button showing so the client can retry).
            'incomplete' => 'pending',
            'incomplete_expired' => 'canceled',
            'canceled' => 'canceled',
            'paused' => 'canceled',
        ];

        $periodEnd = $stripeSubscription->current_period_end
            ?? ($stripeSubscription->items->data[0]->current_period_end ?? null);

        $cancelAtPeriodEnd = (bool) ($stripeSubscription->cancel_at_period_end ?? false)
            || ! empty($stripeSubscription->cancel_at);

        $subscription->update([
            'status' => $statusMap[$stripeSubscription->status] ?? $subscription->status,
            'current_period_end' => $periodEnd ? Carbon::createFromTimestamp($periodEnd) : null,
            'cancel_at_period_end' => $cancelAtPeriodEnd,
        ]);

        return 'Synced with Stripe — this plan is now '.$subscription->status.
            ($cancelAtPeriodEnd ? ' (scheduled to cancel)' : '').'.';
    }
}
