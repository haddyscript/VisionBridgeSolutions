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
        $this->line('Checking for orphaned pending Care Plan subscriptions (no stripe_subscription_id)...');

        $pendingSubscriptions = Subscription::where('status', 'pending')
            ->whereNotNull('maintenance_plan_id')
            ->whereNull('stripe_subscription_id')
            ->get();

        $this->line("Found {$pendingSubscriptions->count()} pending Care Plan subscription(s) with no Stripe ID to check.");

        $canceled = 0;

        $pendingSubscriptions->each(function (Subscription $pending) use (&$canceled) {
            $this->line("Subscription #{$pending->id} on project #{$pending->project_id} -> checking for an active sibling...");

            $hasActiveSibling = Subscription::where('project_id', $pending->project_id)
                ->where('id', '!=', $pending->id)
                ->where('status', 'active')
                ->exists();

            if (! $hasActiveSibling) {
                $this->line("Subscription #{$pending->id} -> no active sibling found, leaving as-is.");
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
