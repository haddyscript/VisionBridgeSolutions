<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\Invoice;
use Stripe\Stripe;

/**
 * Manual usage over SSH (also runs automatically twice a day via the
 * schedule in routes/console.php, if the server's cron is wired up):
 *
 *   ssh -p 65002 u290597841@45.130.228.160
 *   cd domains/vbs.johnnydavisglobalmission.org/laravel-app
 *   php artisan payments:retry-failed
 */
class RetryFailedPayments extends Command
{
    protected $signature = 'payments:retry-failed';

    protected $description = 'Force an immediate retry of every past-due Care Plan subscription\'s unpaid invoice, instead of waiting on Stripe\'s own automatic retry schedule';

    public function handle(): int
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $this->line('Checking past_due subscriptions with a Stripe subscription ID...');

        $subscriptions = Subscription::where('status', 'past_due')
            ->whereNotNull('stripe_subscription_id')
            ->get();

        $this->line("Found {$subscriptions->count()} past_due subscription(s) to check.");

        $retried = 0;
        $stillFailing = 0;

        foreach ($subscriptions as $subscription) {
            $this->line("Subscription #{$subscription->id} (Stripe: {$subscription->stripe_subscription_id}) -> checking latest invoice...");

            try {
                $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_subscription_id);

                if (! $stripeSubscription->latest_invoice) {
                    $this->warn("Subscription #{$subscription->id} -> no latest invoice on Stripe, skipping.");
                    continue;
                }

                $invoice = Invoice::retrieve($stripeSubscription->latest_invoice);

                if ($invoice->status !== 'open') {
                    $this->warn("Subscription #{$subscription->id} -> invoice {$invoice->id} status is \"{$invoice->status}\" (not open), skipping.");
                    continue;
                }

                $this->line("Subscription #{$subscription->id} -> attempting to pay invoice {$invoice->id} now...");

                // Triggers a real charge attempt against the customer's card
                // on file — success or failure fires the same
                // invoice.payment_succeeded / invoice.payment_failed webhooks
                // as any other attempt, so StripeWebhookController handles
                // the client email and status update exactly as usual. No
                // need to duplicate that logic here.
                Invoice::pay($invoice->id);

                $this->info("Subscription #{$subscription->id} -> retry attempted.");

                $retried++;
            } catch (ApiErrorException $e) {
                $this->warn("Subscription #{$subscription->id} -> retry failed: {$e->getMessage()}");

                Log::warning('Retry attempt failed for past-due subscription.', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);

                $stillFailing++;
            }
        }

        $this->info("Attempted retry on {$retried} invoice(s); {$stillFailing} still failing or unreachable.");

        return self::SUCCESS;
    }
}
