<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectQuoteReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Project $project, public Payment $depositPayment)
    {
    }

    public function build()
    {
        return $this->subject('Your VisionBridge Project Quote Is Ready')
            ->view('emails.project-quote-ready');
    }
}
