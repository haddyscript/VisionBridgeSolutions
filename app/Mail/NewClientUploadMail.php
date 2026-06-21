<?php

namespace App\Mail;

use App\Models\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewClientUploadMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Upload $upload)
    {
    }

    public function build()
    {
        return $this->subject('New Client Upload — '.$this->upload->project->name)
            ->view('emails.new-client-upload');
    }
}
