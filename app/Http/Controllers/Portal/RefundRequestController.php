<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\NewRefundRequestMail;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RefundRequestController extends Controller
{
    public function store(Request $request, Payment $payment)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $payment->project_id === $project->id, 403);
        abort_unless($payment->isRefundRequestable(), 422, 'A refund can no longer be requested for this payment.');

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        $refundRequest = $payment->refundRequests()->create([
            'reason' => $validated['reason'],
        ]);

        $refundRequest->load('payment.project.user');

        dispatch(function () use ($refundRequest) {
            Mail::to(config('mail.billing_address'))->send(new NewRefundRequestMail($refundRequest));
        })->afterResponse();

        return back()->with('status', 'Your refund request has been submitted — our team will review it shortly.');
    }
}
