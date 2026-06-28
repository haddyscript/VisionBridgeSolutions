<?php

namespace App\Mail;

use App\Models\Project;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectSuspendedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Project $project, public Subscription $subscription)
    {
    }

    public function build()
    {
        return $this->subject('Action Needed: Your VisionBridge Account Has Been Suspended')
            ->view('emails.project-suspended');
    }
}
