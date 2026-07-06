<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionRenewalReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscription $subscription)
    {
    }

    public function build()
    {
        return $this->subject('Your Care Plan Renews Soon — '.$this->subscription->project->name)
            ->view('emails.subscription-renewal-reminder');
    }
}
