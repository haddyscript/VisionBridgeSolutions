<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WorkOrderInstructionsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $developer,
        public string $itemTitle,
        public string $instructions,
        public string $url,
    ) {
    }

    public function build()
    {
        return $this->subject('New Instructions — '.$this->itemTitle)
            ->view('emails.work-order-instructions');
    }
}
