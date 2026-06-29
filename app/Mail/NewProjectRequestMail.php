<?php

namespace App\Mail;

use App\Models\ProjectRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewProjectRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ProjectRequest $projectRequest)
    {
    }

    public function build()
    {
        return $this->subject('New Project Request — '.$this->projectRequest->user->name)
            ->view('emails.new-project-request');
    }
}
