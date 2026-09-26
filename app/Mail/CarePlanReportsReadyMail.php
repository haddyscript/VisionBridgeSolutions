<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class CarePlanReportsReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Carbon $month, public int $count)
    {
    }

    public function build()
    {
        return $this->subject("{$this->count} Care Plan report(s) ready to review — {$this->month->format('F Y')}")
            ->view('emails.care-plan-reports-ready');
    }
}
