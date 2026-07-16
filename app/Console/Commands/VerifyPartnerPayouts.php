<?php

namespace App\Console\Commands;

use App\Models\PartnerPayout;
use Illuminate\Console\Command;

class VerifyPartnerPayouts extends Command
{
    protected $signature = 'payouts:verify';

    protected $description = "Promote FaithStack payouts (Care Plan cycles and one-time project payments) to 'ready' once they've sat clean for the verification window";

    public function handle(): int
    {
        $cutoff = now()->subDays(PartnerPayout::VERIFICATION_DAYS);
        $this->line("Checking for pending payouts created on or before {$cutoff->format('M j, Y g:ia')}...");

        $payouts = PartnerPayout::where('status', 'pending')
            ->where('created_at', '<=', $cutoff)
            ->get();

        $this->line("Found {$payouts->count()} pending payout(s) past their verification window.");

        foreach ($payouts as $payout) {
            $this->line("Payout #{$payout->id} (created {$payout->created_at->format('M j, Y')}) -> marking ready to send.");

            $payout->update([
                'status' => 'ready',
                'ready_at' => now(),
            ]);
        }

        $this->info("Marked {$payouts->count()} payout(s) as ready to send.");

        return self::SUCCESS;
    }
}
