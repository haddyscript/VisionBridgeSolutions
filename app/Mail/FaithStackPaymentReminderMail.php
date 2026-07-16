<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class FaithStackPaymentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Carbon $dueDate,
        public int $amountDue,
        public int $activeSubscriptionCount,
        public int $recurringPayoutCount,
        public int $oneTimePayoutCount,
        public float $rate,
        public int $daysUntilDue,
    ) {
    }

    public function build()
    {
        $subject = match (true) {
            $this->daysUntilDue > 0 => "FaithStack Payment Due in {$this->daysUntilDue} Days",
            $this->daysUntilDue === 0 => 'FaithStack Payment Due Today',
            default => 'FaithStack Payment Overdue — '.abs($this->daysUntilDue).' Day(s) Past Due',
        };

        return $this->subject($subject)
            ->view('emails.faithstack-payment-reminder');
    }
}
