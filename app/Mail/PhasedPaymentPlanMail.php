<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PhasedPaymentPlanMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<int, array{label: string, description?: string, amount: int}>  $phases  amounts in cents
     * @param  int  $carePlanAmount  monthly Care Plan amount in cents
     */
    public function __construct(
        public Project $project,
        public array $phases,
        public int $carePlanAmount,
    ) {
    }

    public function build()
    {
        return $this->subject('Your Updated Payment Plan — '.$this->project->name)
            ->view('emails.phased-payment-plan');
    }
}
