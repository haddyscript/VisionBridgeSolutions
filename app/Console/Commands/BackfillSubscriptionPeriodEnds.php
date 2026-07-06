<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

/**
 * One-time (but safe to re-run anytime) data fix for subscriptions activated
 * via handleCheckoutCompleted() before it was fixed to set current_period_end
 * itself — those rows were left with a permanently null current_period_end,
 * silently breaking the renewal-reminder feature for them. Re-fetches the
 * real value from Stripe for any active subscription still missing it.
 *
 * Usage over SSH:
 *   php artisan subscriptions:backfill-period-end
 */
class BackfillSubscriptionPeriodEnds extends Command
{
    protected $signature = 'subscriptions:backfill-period-end';

    protected $description = 'Backfill current_period_end from Stripe for active subscriptions where it is missing';

    public function handle(): int
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $subscriptions = Subscription::where('status', 'active')
            ->whereNotNull('stripe_subscription_id')
            ->whereNull('current_period_end')
            ->get();

        $fixed = 0;
        $failed = 0;

        foreach ($subscriptions as $subscription) {
            try {
                $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_subscription_id);

                $periodEnd = $stripeSubscription->current_period_end
                    ?? ($stripeSubscription->items->data[0]->current_period_end ?? null);

                if (! $periodEnd) {
                    $this->warn("Subscription {$subscription->id}: Stripe returned no period end either, skipping.");
                    continue;
                }

                $subscription->update(['current_period_end' => Carbon::createFromTimestamp($periodEnd)]);
                $fixed++;
            } catch (ApiErrorException $e) {
                Log::warning('Could not backfill current_period_end.', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
                $failed++;
            }
        }

        $this->info("Backfilled {$fixed} subscription(s); {$failed} failed to reach Stripe.");

        return self::SUCCESS;
    }
}
