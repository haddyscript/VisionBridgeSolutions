<?php

namespace App\Services;

use App\Models\Subscription;
use Stripe\Stripe;

class CarePlanActivator
{
    /**
     * Creates the real Stripe Subscription for a pending local Subscription
     * using an already-confirmed payment method, and syncs the local status.
     * This is the second half of what Portal\SubscriptionController::confirm()
     * does for a client clicking "Start Plan" (price/product resolution +
     * Subscription::create), extracted here so it can also be triggered by
     * an admin marking a project "Completed" using the payment method the
     * client already saved during onboarding (Portal\CarePlanPaymentMethodController)
     * — no fresh card entry needed at that point.
     */
    public function activate(Subscription $subscription, string $stripeCustomerId, string $stripePaymentMethodId): \Stripe\Subscription
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        // Use the real Stripe product/price the boss set up in the
        // dashboard whenever this subscription is tied to one of the fixed
        // Care Plan tiers — fall back to building an ad-hoc product/price
        // for admin-created custom amounts not tied to a maintenance_plan_id.
        if ($subscription->maintenancePlan?->stripe_price_id) {
            $item = ['price' => $subscription->maintenancePlan->stripe_price_id];
        } else {
            $product = \Stripe\Product::create(['name' => $subscription->description]);

            $item = [
                'price_data' => [
                    'currency' => $subscription->currency,
                    'unit_amount' => $subscription->amount,
                    'recurring' => ['interval' => $subscription->interval],
                    'product' => $product->id,
                ],
            ];
        }

        $stripeSubscription = \Stripe\Subscription::create([
            'customer' => $stripeCustomerId,
            'default_payment_method' => $stripePaymentMethodId,
            'items' => [$item],
            'metadata' => ['subscription_id' => $subscription->id],
        ]);

        $subscription->update([
            'stripe_subscription_id' => $stripeSubscription->id,
            'status' => in_array($stripeSubscription->status, ['active', 'trialing'], true) ? 'active' : 'pending',
        ]);

        return $stripeSubscription;
    }
}
