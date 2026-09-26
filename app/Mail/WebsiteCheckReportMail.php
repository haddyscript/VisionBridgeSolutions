<?php

namespace App\Mail;

use App\Models\WebsiteCheck;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WebsiteCheckReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public WebsiteCheck $check)
    {
    }

    public function build()
    {
        return $this->subject("Your Website Check Report — {$this->check->host()} scored {$this->check->score}/100")
            ->view('emails.website-check-report');
    }
}
