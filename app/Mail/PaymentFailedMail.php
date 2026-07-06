<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscription $subscription, public int $amountDue)
    {
    }

    public function build()
    {
        return $this->subject('Payment Failed — '.$this->subscription->project->name)
            ->view('emails.payment-failed');
    }
}
