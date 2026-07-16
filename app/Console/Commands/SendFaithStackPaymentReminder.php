<?php

namespace App\Console\Commands;

use App\Mail\FaithStackPaymentReminderMail;
use App\Models\AppSetting;
use App\Models\PartnerPayout;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendFaithStackPaymentReminder extends Command
{
    protected $signature = 'payouts:send-faithstack-reminder';

    protected $description = 'Email the FaithStack payment reminder 5 days before, and again on, the configured monthly due day';

    public function handle(): int
    {
        $dueDay = (int) AppSetting::get('faithstack_payment_due_day', 0);

        if ($dueDay < 1) {
            $this->info('No FaithStack payment due day configured — skipping.');

            return self::SUCCESS;
        }

        // Due day is restricted to 1-28 in the settings form so this never
        // overflows into the wrong month regardless of how short a month is.
        $dueDate = today()->day($dueDay);
        if ($dueDate->lt(today())) {
            $dueDate = $dueDate->addMonthNoOverflow();
        }

        $daysUntilDue = today()->diffInDays($dueDate);

        if (! in_array($daysUntilDue, [5, 0], true)) {
            return self::SUCCESS;
        }

        $readyPayouts = PartnerPayout::where('status', 'ready')->get();
        $amountDue = (int) $readyPayouts->sum('faithstack_amount');

        if ($amountDue <= 0) {
            $this->info('Nothing ready to send FaithStack yet — skipping reminder.');

            return self::SUCCESS;
        }

        $emails = array_filter(array_map(
            'trim',
            explode(',', AppSetting::get('faithstack_reminder_email', 'johnnydavis45@yahoo.com,hadrianevarula@gmail.com'))
        ));

        Mail::to($emails)->send(new FaithStackPaymentReminderMail(
            dueDate: $dueDate,
            amountDue: $amountDue,
            activeSubscriptionCount: Subscription::where('status', 'active')->count(),
            readyPayoutCount: $readyPayouts->count(),
            rate: (float) AppSetting::get('faithstack_percentage', 0),
            daysUntilDue: $daysUntilDue,
        ));

        $this->info("Sent FaithStack payment reminder ({$daysUntilDue} day(s) until due).");

        return self::SUCCESS;
    }
}
