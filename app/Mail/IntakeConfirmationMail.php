<?php

namespace App\Mail;

use App\Models\IntakeSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IntakeConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public IntakeSubmission $submission)
    {
    }

    public function build()
    {
        return $this->subject('We\'ve Received Your Project Details — VisionBridge Solutions')
            ->view('emails.intake-confirmation');
    }
}
