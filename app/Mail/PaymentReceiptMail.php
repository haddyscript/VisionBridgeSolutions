<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Payment $payment, public ?string $receiptUrl = null)
    {
    }

    public function build()
    {
        return $this->subject('Payment Receipt — '.$this->payment->formattedAmount().' — VisionBridge Solutions')
            ->view('emails.payment-receipt');
    }
}
