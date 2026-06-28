<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectRestoredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Project $project)
    {
    }

    public function build()
    {
        return $this->subject('Your VisionBridge Account Has Been Restored')
            ->view('emails.project-restored');
    }
}
