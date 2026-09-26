<?php

namespace App\Mail;

use App\Models\WebsiteCheck;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewWebsiteCheckLeadMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public WebsiteCheck $check)
    {
    }

    public function build()
    {
        return $this->subject("New Website Check Lead — {$this->check->name} ({$this->check->host()}, {$this->check->score}/100)")
            ->replyTo($this->check->email, $this->check->name)
            ->view('emails.new-website-check-lead');
    }
}
