<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FaithStackNewClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscription $subscription)
    {
    }

    public function build()
    {
        return $this->subject('New Website Care Plan Client — '.$this->subscription->project->user->name)
            ->view('emails.faithstack-new-client');
    }
}
