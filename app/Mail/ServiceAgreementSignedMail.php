<?php

namespace App\Mail;

use App\Models\ServiceAgreementSignature;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ServiceAgreementSignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ServiceAgreementSignature $signature)
    {
    }

    public function build()
    {
        $mail = $this->subject('Signed Service Agreement — '.$this->signature->project->name)
            ->view('emails.service-agreement-signed');

        if ($this->signature->pdf_path) {
            $mail->attachFromStorageDisk('local', $this->signature->pdf_path, 'VisionBridge-Service-Agreement.pdf');
        }

        return $mail;
    }
}
