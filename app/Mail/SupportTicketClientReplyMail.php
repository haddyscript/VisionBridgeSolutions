<?php

namespace App\Mail;

use App\Models\SupportTicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketClientReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SupportTicketReply $reply)
    {
    }

    public function build()
    {
        return $this->subject('Client Replied — '.$this->reply->ticket->subject)
            ->view('emails.support-ticket-client-reply');
    }
}
