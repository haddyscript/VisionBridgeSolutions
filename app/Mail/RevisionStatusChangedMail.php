<?php

namespace App\Mail;

use App\Models\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RevisionStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Upload $upload)
    {
    }

    public function build()
    {
        return $this->subject('Update on Your '.(\App\Http\Controllers\Portal\CategoryController::CATEGORIES[$this->upload->category]['label'] ?? 'Submission'))
            ->view('emails.revision-status-changed');
    }
}
