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

        $payouts = PartnerPayout::where('status', 'pending')
            ->where('created_at', '<=', $cutoff)
            ->get();

        foreach ($payouts as $payout) {
            $payout->update([
                'status' => 'ready',
                'ready_at' => now(),
            ]);
        }

        $this->info("Marked {$payouts->count()} payout(s) as ready to send.");

        return self::SUCCESS;
    }
}
