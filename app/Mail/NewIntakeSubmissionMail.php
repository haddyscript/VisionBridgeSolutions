<?php

namespace App\Mail;

use App\Models\IntakeSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewIntakeSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public IntakeSubmission $submission)
    {
    }

    public function build()
    {
        return $this->subject('New Client Intake Submission — '.$this->submission->organization_name)
            ->view('emails.new-intake-submission');
    }
}
