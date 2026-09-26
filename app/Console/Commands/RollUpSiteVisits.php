<?php

namespace App\Console\Commands;

use App\Models\SiteVisit;
use App\Models\SiteVisitMonthlyStat;
use Illuminate\Console\Command;

class RollUpSiteVisits extends Command
{
    protected $signature = 'visits:rollup';

    protected $description = 'Save monthly website visitor totals, then delete raw visit records (IP addresses) older than the retention window';

    public function handle(): int
    {
        // Only the current and previous month are recalculated — both are
        // always well inside the retention window, so their raw rows are
        // complete. Older months keep whatever was saved while they were.
        foreach ([now()->subMonthNoOverflow(), now()] as $month) {
            $stat = SiteVisitMonthlyStat::rollUp($month);
            $this->line("{$month->format('F Y')}: {$stat->unique_visitors} visitor(s), {$stat->page_views} page view(s) saved.");
        }

        $deleted = SiteVisit::where('created_at', '<', now()->subDays(SiteVisit::RETENTION_DAYS))->delete();
        $this->line("Deleted {$deleted} raw visit record(s) older than ".SiteVisit::RETENTION_DAYS.' days.');

        return self::SUCCESS;
    }
}
