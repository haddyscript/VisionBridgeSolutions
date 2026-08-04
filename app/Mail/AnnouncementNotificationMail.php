<?php

namespace App\Mail;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AnnouncementNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Announcement $announcement,
    ) {
    }

    public function build()
    {
        return $this->subject('New Announcement — '.$this->announcement->title)
            ->view('emails.announcement-notification');
    }
}
