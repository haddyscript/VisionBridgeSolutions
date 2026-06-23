<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountEmailChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $oldEmail, public string $newEmail)
    {
    }

    public function build()
    {
        return $this->subject('Your VisionBridge Account Email Was Changed')
            ->view('emails.account-email-changed');
    }
}
