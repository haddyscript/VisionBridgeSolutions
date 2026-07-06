<?php

namespace App\Mail;

use App\Models\RefundRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RefundRequestApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public RefundRequest $refundRequest)
    {
    }

    public function build()
    {
        return $this->subject('Your Refund Has Been Processed')
            ->view('emails.refund-request-approved');
    }
}
