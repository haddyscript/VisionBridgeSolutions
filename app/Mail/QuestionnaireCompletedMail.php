<?php

namespace App\Mail;

use App\Models\ProjectQuestionnaire;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuestionnaireCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ProjectQuestionnaire $questionnaire)
    {
    }

    public function build()
    {
        return $this->subject('Onboarding Questionnaire Completed — '.$this->questionnaire->project->name)
            ->view('emails.questionnaire-completed');
    }
}
