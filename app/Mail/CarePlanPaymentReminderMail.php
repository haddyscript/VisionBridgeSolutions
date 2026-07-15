<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CarePlanPaymentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscription $subscription)
    {
    }

    public function build()
    {
        return $this->subject('A friendly reminder about your Care Plan — '.$this->subscription->project->name)
            ->view('emails.care-plan-payment-reminder');
    }
}
