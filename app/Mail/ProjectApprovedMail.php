<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Project $project, public Payment $finalPayment)
    {
    }

    public function build()
    {
        return $this->subject('Client Approved — '.$this->project->name)
            ->view('emails.project-approved');
    }
}
