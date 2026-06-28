<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectLaunchedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Project $project)
    {
    }

    public function build()
    {
        return $this->subject('Your Website Is Live! 🎉')
            ->view('emails.project-launched');
    }
}
