<?php

namespace App\Mail;

use App\Models\RefundRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewRefundRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public RefundRequest $refundRequest)
    {
    }

    public function build()
    {
        return $this->subject('New Refund Request — '.$this->refundRequest->payment->formattedAmount())
            ->view('emails.new-refund-request');
    }
}
