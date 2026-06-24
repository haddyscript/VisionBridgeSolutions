<?php

namespace App\Mail;

use App\Models\UploadReply;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UploadRepliedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public UploadReply $reply)
    {
    }

    public function build()
    {
        return $this->subject('VisionBridge Replied to Your Submission')
            ->view('emails.upload-replied');
    }
}
