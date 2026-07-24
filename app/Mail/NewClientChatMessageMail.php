<?php

namespace App\Mail;

use App\Models\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewClientChatMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ChatMessage $message)
    {
    }

    public function build()
    {
        return $this->subject('New Chat Message — '.$this->message->project->name)
            ->view('emails.new-client-chat-message');
    }
}
