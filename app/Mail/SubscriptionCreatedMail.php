<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscription $subscription)
    {
    }

    public function build()
    {
        return $this->subject('Your Care Plan Is Now Active — '.$this->subscription->project->name)
            ->view('emails.subscription-created');
    }
}
