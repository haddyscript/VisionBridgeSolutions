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

        $subscriptions = Subscription::where('status', 'past_due')
            ->whereNotNull('stripe_subscription_id')
            ->get();

        $retried = 0;
        $stillFailing = 0;

        foreach ($subscriptions as $subscription) {
            try {
                $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_subscription_id);

                if (! $stripeSubscription->latest_invoice) {
                    continue;
                }

                $invoice = Invoice::retrieve($stripeSubscription->latest_invoice);

                if ($invoice->status !== 'open') {
                    continue;
                }

                // Triggers a real charge attempt against the customer's card
                // on file — success or failure fires the same
                // invoice.payment_succeeded / invoice.payment_failed webhooks
                // as any other attempt, so StripeWebhookController handles
                // the client email and status update exactly as usual. No
                // need to duplicate that logic here.
                Invoice::pay($invoice->id);

                $retried++;
            } catch (ApiErrorException $e) {
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
