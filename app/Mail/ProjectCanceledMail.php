<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectCanceledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Project $project, public Payment $refundedPayment)
    {
    }

    public function build()
    {
        return $this->subject('Your VisionBridge Project Has Been Canceled')
            ->view('emails.project-canceled');
    }
}
