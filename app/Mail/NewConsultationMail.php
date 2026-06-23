<?php

namespace App\Mail;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewConsultationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Consultation $consultation)
    {
    }

    public function build()
    {
        return $this->subject('New Consultation Booking — '.$this->consultation->name)
            ->view('emails.new-consultation');
    }
}
