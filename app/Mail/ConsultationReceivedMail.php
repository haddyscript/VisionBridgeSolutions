<?php

namespace App\Mail;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConsultationReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Consultation $consultation)
    {
    }

    public function build()
    {
        return $this->subject('We received your consultation request — VisionBridge Solutions')
            ->view('emails.consultation-received');
    }
}
