<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionRenewalReminderMail;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendRenewalReminders extends Command
{
    protected $signature = 'subscriptions:send-renewal-reminders';

    protected $description = 'Email clients whose Care Plan is renewing within the reminder window';

    public function handle(): int
    {
        $this->line('Checking active subscriptions with a known current_period_end...');

        $candidates = Subscription::with('project.user')
            ->where('status', 'active')
            ->whereNotNull('current_period_end')
            ->get();

        $this->line("Found {$candidates->count()} active subscription(s) to check.");

        $subscriptions = $candidates->filter(fn (Subscription $subscription) => $subscription->needsRenewalReminder());

        $this->line("{$subscriptions->count()} of those are inside the reminder window and haven't been emailed for this period yet.");

        $sent = 0;

        foreach ($subscriptions as $subscription) {
            if (! $subscription->project?->user) {
                $this->warn("Subscription #{$subscription->id} -> no associated client found, skipping.");
                continue;
            }

            $this->line("Subscription #{$subscription->id} ({$subscription->project->user->name}) renews {$subscription->current_period_end->format('M j, Y')} -> sending reminder email.");

            Mail::to($subscription->project->user->email)->send(new SubscriptionRenewalReminderMail($subscription));

            $subscription->update(['renewal_reminder_period_end' => $subscription->current_period_end]);

            $sent++;
        }

        $this->info("Sent {$sent} renewal reminder(s).");

        return self::SUCCESS;
    }
}
