<?php

namespace App\Console\Commands;

use App\Mail\CarePlanReportsReadyMail;
use App\Models\CarePlanReport;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class GenerateCarePlanReports extends Command
{
    protected $signature = 'care-plan-reports:generate {--month= : Month to report on, YYYY-MM (defaults to last month)}';

    protected $description = 'Create draft monthly Care Plan reports for every active plan, pre-filled with that month\'s completed work, for an admin to review and send';

    public function handle(): int
    {
        $month = $this->option('month')
            ? Carbon::createFromFormat('Y-m', $this->option('month'))->startOfMonth()
            : now()->subMonthNoOverflow()->startOfMonth();

        $this->line("Drafting Care Plan reports for {$month->format('F Y')}...");

        $subscriptions = Subscription::with('project')
            ->whereIn('status', ['active', 'past_due'])
            ->whereHas('project')
            ->get();

        $created = 0;
        foreach ($subscriptions as $subscription) {
            if ($report = CarePlanReport::draftFor($subscription, $month)) {
                $created++;
                $this->line("Project #{$subscription->project_id}: draft created with ".count($report->work_items).' work item(s).');
            }
        }

        $this->line("Created {$created} new draft(s); ".($subscriptions->count() - $created).' already existed.');

        if ($created > 0) {
            Mail::to(config('mail.support_address'))->send(new CarePlanReportsReadyMail($month, $created));
        }

        return self::SUCCESS;
    }
}
