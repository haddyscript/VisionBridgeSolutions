<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class SubscriptionReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Subscription $subscription,
        public int $amountPaid,
        public Carbon $paidAt,
        public ?string $hostedInvoiceUrl = null,
    ) {
    }

    public function formattedAmountPaid(): string
    {
        return '$'.number_format($this->amountPaid / 100, 2);
    }

    public function build()
    {
        return $this->subject('Payment Receipt — '.$this->formattedAmountPaid().' — VisionBridge Solutions')
            ->view('emails.subscription-receipt')
            ->with(['formattedAmountPaid' => $this->formattedAmountPaid()]);
    }
}
