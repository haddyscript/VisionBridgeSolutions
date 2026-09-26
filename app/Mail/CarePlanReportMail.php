<?php

namespace App\Mail;

use App\Models\CarePlanReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CarePlanReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CarePlanReport $report)
    {
    }

    public function build()
    {
        return $this->subject("Your {$this->report->monthLabel()} Website Care Report")
            ->view('emails.care-plan-report');
    }
}
