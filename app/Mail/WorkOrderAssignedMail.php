<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WorkOrderAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $developer,
        public string $itemTitle,
        public string $itemType,
        public string $clientName,
        public string $url,
    ) {
    }

    public function build()
    {
        return $this->subject('New Work Order Assigned — '.$this->itemTitle)
            ->view('emails.work-order-assigned');
    }
}
