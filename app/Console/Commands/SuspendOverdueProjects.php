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
        $subscriptions = Subscription::with('project.user')
            ->where('status', 'past_due')
            ->whereNotNull('past_due_at')
            ->get()
            ->filter(fn (Subscription $subscription) => $subscription->isPastDueBeyondGrace());

        $suspended = 0;

        foreach ($subscriptions as $subscription) {
            $project = $subscription->project;

            if (! $project || $project->isSuspended()) {
                continue;
            }

            $project->update(['suspended_at' => now()]);

            Mail::to($project->user->email)->send(new ProjectSuspendedMail($project, $subscription));
            Mail::to(config('mail.admin_address'))->send(new SystemAlertMail(
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
