<?php

namespace App\Mail;

use App\Models\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChatMessageRepliedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ChatMessage $message)
    {
    }

    public function build()
    {
        return $this->subject('VisionBridge Sent You a Message')
            ->view('emails.chat-message-replied');
    }
}
