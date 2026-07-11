<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Internal (support@) notification for developer-side Work Order activity —
 * started, completed, or a question/comment left on an assigned item.
 */
class WorkOrderInternalUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $itemTitle,
        public string $itemType,
        public string $clientName,
        public string $developerName,
        public string $eventLabel,
        public ?string $note,
        public string $url,
    ) {
    }

    public function build()
    {
        return $this->subject('Work Order Update — '.$this->itemTitle)
            ->view('emails.work-order-internal-update');
    }
}
