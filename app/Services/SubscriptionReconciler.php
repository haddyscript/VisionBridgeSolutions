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
        if (! $subscription->isPending()) {
            return 'This maintenance plan is already '.$subscription->status.'.';
        }

        if (! $subscription->stripe_checkout_session_id) {
            return 'No checkout session is on record yet — start the plan first.';
        }

        Stripe::setApiKey(config('services.stripe.secret'));

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

        $subscription->update([
            'status' => 'active',
            'stripe_subscription_id' => $stripeSubscription->id,
            'current_period_end' => $stripeSubscription->current_period_end
                ? Carbon::createFromTimestamp($stripeSubscription->current_period_end)
                : null,
        ]);

        return 'Synced with Stripe — this plan is active.';
    }
}
