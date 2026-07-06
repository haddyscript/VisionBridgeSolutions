<?php

namespace App\Mail;

use App\Models\RefundRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RefundRequestDeclinedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public RefundRequest $refundRequest)
    {
    }

    public function build()
    {
        return $this->subject('Update on Your Refund Request')
            ->view('emails.refund-request-declined');
    }
}
