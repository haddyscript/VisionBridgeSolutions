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

        $template = $this->signature->template;

        if ($template->isPdfBased()) {
            // Two files when the agreement is an uploaded PDF: the signed
            // certificate (proof of signature) and the actual agreement
            // document it refers to.
            if ($this->signature->pdf_path) {
                $mail->attachFromStorageDisk('local', $this->signature->pdf_path, 'VisionBridge-Signature-Certificate.pdf');
            }

            if ($template->pdf_path) {
                $mail->attachFromStorageDisk('local', $template->pdf_path, 'VisionBridge-Service-Agreement.pdf');
            }
        } elseif ($this->signature->pdf_path) {
            $mail->attachFromStorageDisk('local', $this->signature->pdf_path, 'VisionBridge-Service-Agreement.pdf');
        }

        return $mail;
    }
}
