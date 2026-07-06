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
        $subscriptions = Subscription::with('project.user')
            ->where('status', 'active')
            ->whereNotNull('current_period_end')
            ->get()
            ->filter(fn (Subscription $subscription) => $subscription->needsRenewalReminder());

        $sent = 0;

        foreach ($subscriptions as $subscription) {
            if (! $subscription->project?->user) {
                continue;
            }

            Mail::to($subscription->project->user->email)->send(new SubscriptionRenewalReminderMail($subscription));

            $subscription->update(['renewal_reminder_period_end' => $subscription->current_period_end]);

            $sent++;
        }

        $this->info("Sent {$sent} renewal reminder(s).");

        return self::SUCCESS;
    }
}
