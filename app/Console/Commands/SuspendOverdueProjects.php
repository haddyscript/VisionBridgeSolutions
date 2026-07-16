<?php

namespace App\Console\Commands;

use App\Mail\ProjectSuspendedMail;
use App\Mail\SystemAlertMail;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SuspendOverdueProjects extends Command
{
    protected $signature = 'projects:suspend-overdue';

    protected $description = 'Suspend portal access for any project whose Care Plan payment has stayed past_due beyond the grace period';

    public function handle(): int
    {
        $this->line('Checking past_due subscriptions with a past_due_at timestamp...');

        $candidates = Subscription::with('project.user')
            ->where('status', 'past_due')
            ->whereNotNull('past_due_at')
            ->get();

        $this->line("Found {$candidates->count()} past_due subscription(s) to check.");

        $subscriptions = $candidates->filter(fn (Subscription $subscription) => $subscription->isPastDueBeyondGrace());

        $this->line("{$subscriptions->count()} of those are beyond the ".Subscription::GRACE_PERIOD_DAYS."-day grace period.");

        $suspended = 0;

        foreach ($subscriptions as $subscription) {
            $project = $subscription->project;

            if (! $project) {
                $this->warn("Subscription #{$subscription->id} -> no associated project found, skipping.");
                continue;
            }

            if ($project->isSuspended()) {
                $this->line("Project \"{$project->name}\" -> already suspended, skipping.");
                continue;
            }

            $this->line("Project \"{$project->name}\" ({$project->user->name}) -> suspending for non-payment (past due since {$subscription->past_due_at->format('M j, Y')}).");

            $project->update(['suspended_at' => now()]);

            Mail::to($project->user->email)->send(new ProjectSuspendedMail($project, $subscription));
            Mail::to(config('mail.billing_address'))->send(new SystemAlertMail(
                'Project Suspended for Non-Payment — '.$project->name,
                "{$project->user->name}'s Care Plan payment has been past due beyond the ".Subscription::GRACE_PERIOD_DAYS." day grace period. Their portal access has been suspended automatically until payment is received.",
                [
                    'Client' => $project->user->name,
                    'Project' => $project->name,
                    'Amount Due' => $subscription->formattedAmount(),
                ],
            ));

            $suspended++;
        }

        $this->info("Suspended {$suspended} project(s) for non-payment.");

        return self::SUCCESS;
    }
}
