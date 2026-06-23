<?php

namespace App\Mail;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConsultationRescheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Consultation $consultation)
    {
    }

    public function build()
    {
        return $this->subject('Your Consultation Has Been Rescheduled — VisionBridge Solutions')
            ->view('emails.consultation-rescheduled');
    }
}
