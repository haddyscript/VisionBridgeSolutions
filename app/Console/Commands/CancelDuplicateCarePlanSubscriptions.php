<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

/**
 * One-time (but safe to re-run anytime) data fix for the duplicate-Subscription
 * bug in the old CarePlanAgreementController — a project could end up with an
 * orphaned, permanently 'pending' Subscription (created during onboarding)
 * sitting alongside a real 'active' one (from the public pre-account signup
 * flow) for the same plan. Only ever touches rows with no
 * stripe_subscription_id, meaning no real Stripe object exists for them yet —
 * this is purely a local status change, never a real Stripe cancellation.
 *
 * Usage over SSH:
 *   php artisan subscriptions:cancel-duplicates
 */
class CancelDuplicateCarePlanSubscriptions extends Command
{
    protected $signature = 'subscriptions:cancel-duplicates';

    protected $description = 'Cancel orphaned pending Care Plan subscriptions on projects that already have an active one';

    public function handle(): int
    {
        $canceled = 0;

        Subscription::where('status', 'pending')
            ->whereNotNull('maintenance_plan_id')
            ->whereNull('stripe_subscription_id')
            ->get()
            ->each(function (Subscription $pending) use (&$canceled) {
                $hasActiveSibling = Subscription::where('project_id', $pending->project_id)
                    ->where('id', '!=', $pending->id)
                    ->where('status', 'active')
                    ->exists();

                if (! $hasActiveSibling) {
                    return;
                }

                $pending->update(['status' => 'canceled', 'canceled_at' => now()]);
                $canceled++;

                $this->info("Canceled duplicate pending subscription {$pending->id} on project {$pending->project_id}.");
            });

        $this->info("Canceled {$canceled} orphaned duplicate subscription(s) total.");

        return self::SUCCESS;
    }
}
