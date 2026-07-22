<?php

namespace App\Mail;

use App\Models\ProjectRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectRequestStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ProjectRequest $projectRequest)
    {
    }

    public function build()
    {
        return $this->subject($this->projectRequest->status === 'converted'
                ? 'Your Project Request Has Been Approved'
                : 'Update on Your Project Request')
            ->view('emails.project-request-status-changed');
    }
}
