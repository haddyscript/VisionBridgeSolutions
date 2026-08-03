<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectInProgressMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Project $project)
    {
    }

    public function build()
    {
        return $this->subject('Development Has Started On Your Website')
            ->view('emails.project-in-progress');
    }
}
