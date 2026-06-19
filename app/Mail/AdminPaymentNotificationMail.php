<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class AdminPaymentNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Payment|Subscription $transaction,
        public string $clientName,
        public string $projectName,
        public string $formattedAmount,
        public Carbon $paidAt,
    ) {
    }

    public function build()
    {
        return $this->subject('New Payment Received — '.$this->formattedAmount.' — VisionBridge Solutions')
            ->view('emails.admin-payment-notification');
    }
}
