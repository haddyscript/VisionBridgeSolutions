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
        public int $readyPayoutCount,
        public float $rate,
        public int $daysUntilDue,
    ) {
    }

    public function build()
    {
        $subject = $this->daysUntilDue === 0
            ? 'FaithStack Payment Due Today'
            : "FaithStack Payment Due in {$this->daysUntilDue} Days";

        return $this->subject($subject)
            ->view('emails.faithstack-payment-reminder');
    }
}
