<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionStatusAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscription $subscription)
    {
    }

    public function build()
    {
        return $this->subject('Subscription '.ucfirst($this->subscription->status).' — '.$this->subscription->project->name)
            ->view('emails.subscription-status-alert');
    }
}
