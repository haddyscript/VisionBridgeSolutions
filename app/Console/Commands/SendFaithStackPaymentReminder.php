<?php

namespace App\Console\Commands;

use App\Mail\FaithStackPaymentReminderMail;
use App\Models\AppSetting;
use App\Models\PartnerPayout;
use App\Models\Payment;
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
        // Deliberately NOT rolled forward to next month once it's passed — an
        // unpaid ready balance should keep nagging daily until it's cleared,
        // not go quiet for weeks just because the calendar date slipped by.
        // (It does still go quiet once the *next* month's due date arrives —
        // see the class docblock... i.e. no carry-over past a full cycle.)
        $today = today();
        $dueDate = today()->day($dueDay);
        $daysUntilDue = (int) round(($dueDate->timestamp - $today->timestamp) / 86400);

        $this->line("Due day is set to {$dueDay}. This cycle's due date: {$dueDate->format('M j, Y')} ({$daysUntilDue} day(s) from today).");

        if ($daysUntilDue > 0 && $daysUntilDue !== 5) {
            $this->line('Not 5 days out yet, and not on/past the due date — skipping reminder for today.');

            return self::SUCCESS;
        }

        $this->line($daysUntilDue < 0
            ? 'Overdue by '.abs($daysUntilDue)." day(s) — checking ready-to-send payouts..."
            : 'On schedule to remind today — checking ready-to-send payouts...');

        $readyPayouts = PartnerPayout::where('status', 'ready')->get();
        $amountDue = (int) $readyPayouts->sum('faithstack_amount');

        $this->line("Found {$readyPayouts->count()} ready payout(s) totaling $".number_format($amountDue / 100, 2).'.');

        if ($amountDue <= 0) {
            $this->info('Nothing ready to send FaithStack yet — skipping reminder.');

            return self::SUCCESS;
        }

        // Recurring Care Plan cycles never use the global rate% — their amount is
        // always set directly from the plan's fixed FaithStack Compensation at
        // creation time (see StripeWebhookController::handleInvoicePaymentSucceeded).
        // The rate% only ever applies to one-time project payments, so the email
        // needs to describe these two buckets separately rather than assuming
        // one formula covers the whole ready total.
        $recurringPayoutCount = $readyPayouts->where('payable_type', Subscription::class)->count();
        $oneTimePayoutCount = $readyPayouts->where('payable_type', Payment::class)->count();

        $emails = array_filter(array_map(
            'trim',
            explode(',', AppSetting::get('faithstack_reminder_email', 'johnnydavis45@yahoo.com,hadrianevarula@gmail.com'))
        ));

        $this->line('Sending reminder to: '.implode(', ', $emails).'...');

        Mail::to($emails)->send(new FaithStackPaymentReminderMail(
            dueDate: $dueDate,
            amountDue: $amountDue,
            activeSubscriptionCount: Subscription::where('status', 'active')->count(),
            recurringPayoutCount: $recurringPayoutCount,
            oneTimePayoutCount: $oneTimePayoutCount,
            rate: (float) AppSetting::get('faithstack_percentage', 0),
            daysUntilDue: $daysUntilDue,
        ));

        $this->info($daysUntilDue < 0
            ? 'Sent FaithStack payment reminder (overdue by '.abs($daysUntilDue).' day(s)).'
            : "Sent FaithStack payment reminder ({$daysUntilDue} day(s) until due).");

        return self::SUCCESS;
    }
}
