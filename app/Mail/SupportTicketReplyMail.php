<?php

namespace App\Mail;

use App\Models\SupportTicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SupportTicketReply $reply, public string $url)
    {
    }

    public function build()
    {
        return $this->subject('New Reply — '.$this->reply->ticket->subject)
            ->view('emails.support-ticket-reply');
    }
}
