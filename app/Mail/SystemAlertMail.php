<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SystemAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $title,
        public string $message,
        public array $context = [],
    ) {
    }

    public function build()
    {
        return $this->subject('System Alert — '.$this->title)
            ->view('emails.system-alert');
    }
}
