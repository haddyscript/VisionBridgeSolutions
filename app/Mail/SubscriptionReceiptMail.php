<?php

namespace App\Mail;

use App\Models\SubscriptionPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SubscriptionPayment $subscriptionPayment)
    {
    }

    public function build()
    {
        $subscription = $this->subscriptionPayment->subscription;

        return $this->subject('Payment Receipt — '.$this->subscriptionPayment->formattedAmountPaid().' — VisionBridge Solutions')
            ->view('emails.subscription-receipt')
            ->with([
                'subscription' => $subscription,
                'paidAt' => $this->subscriptionPayment->paid_at,
                'formattedAmountPaid' => $this->subscriptionPayment->formattedAmountPaid(),
                'receiptUrl' => route('portal.subscription-payments.receipt', $this->subscriptionPayment),
            ]);
    }
}
