<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public ?string $resetUrl = null)
    {
    }

    public function build()
    {
        return $this->subject('Welcome to VisionBridge Solutions — Your Client Portal Is Ready')
            ->view('emails.welcome-client', ['resetUrl' => $this->resetUrl]);
    }
}
