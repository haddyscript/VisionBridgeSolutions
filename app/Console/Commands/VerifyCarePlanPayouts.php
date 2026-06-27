<?php

namespace App\Console\Commands;

use App\Models\SubscriptionPayout;
use Illuminate\Console\Command;

class VerifyCarePlanPayouts extends Command
{
    protected $signature = 'payouts:verify';

    protected $description = "Promote Website Care Plan payouts to 'ready' once they've sat clean (no dispute/refund) for the verification window";

    public function handle(): int
    {
        $cutoff = now()->subDays(SubscriptionPayout::VERIFICATION_DAYS);

        $payouts = SubscriptionPayout::where('status', 'pending')
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
