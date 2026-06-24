<?php

namespace App\Mail;

use App\Models\UploadReply;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public UploadReply $reply)
    {
    }

    public function build()
    {
        return $this->subject('Client Replied — '.$this->reply->upload->user->name)
            ->view('emails.client-reply');
    }
}
