<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RefundRequestApprovedMail;
use App\Mail\RefundRequestDeclinedMail;
use App\Mail\SystemAlertMail;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\Stripe;

class RefundRequestController extends Controller
{
    public function index()
    {
        $refundRequests = RefundRequest::with('payment.project.user')->latest()->get();

        return view('admin.refund-requests.index', [
            'refundRequests' => $refundRequests,
        ]);
    }

    public function update(Request $request, RefundRequest $refundRequest)
    {
        abort_unless($refundRequest->isPending(), 422, 'This refund request has already been decided.');

        $validated = $request->validate([
            'status' => ['required', 'in:approved,declined'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $payment = $refundRequest->payment;

        if ($validated['status'] === 'declined') {
            $refundRequest->update([
                'status' => 'declined',
                'admin_notes' => $validated['admin_notes'] ?? null,
                'decided_at' => now(),
            ]);

            Mail::to($payment->project->user->email)->send(new RefundRequestDeclinedMail($refundRequest));

            return back()->with('status', 'Refund request declined.');
        }

        abort_if($payment->isRefunded(), 422, 'This payment has already been refunded.');

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Same fee-deduction approach as the automatic review-window
            // refund (Portal\ProjectReviewController::cancel) — refund the
            // full amount minus Stripe's own processing fee, not the gross.
            $paymentIntent = PaymentIntent::retrieve($payment->stripe_payment_intent_id, [
                'expand' => ['latest_charge.balance_transaction'],
            ]);

            $stripeFee = $paymentIntent->latest_charge?->balance_transaction?->fee ?? 0;
            $refundAmount = max(0, $payment->amount - $stripeFee);

            $refund = Refund::create([
                'payment_intent' => $payment->stripe_payment_intent_id,
                'amount' => $refundAmount,
            ]);
        } catch (ApiErrorException $e) {
            Mail::to(config('mail.billing_address'))->send(new SystemAlertMail(
                'Refund Approval Failed',
                "An admin approved a refund request, but the Stripe refund failed. This needs manual handling.",
                [
                    'Client' => $payment->project->user->name,
                    'Payment' => $payment->description,
                    'Error' => $e->getMessage(),
                ],
            ));

            return back()->withErrors(['refund' => "Could not reach Stripe to process this refund ({$e->getMessage()}). The request was NOT marked approved — try again or process it directly in the Stripe dashboard."]);
        }

        $payment->update([
            'status' => 'refunded',
            'refunded_amount' => $refundAmount,
            'refunded_at' => now(),
            'stripe_refund_id' => $refund->id,
        ]);

        $refundRequest->update([
            'status' => 'approved',
            'admin_notes' => $validated['admin_notes'] ?? null,
            'decided_at' => now(),
        ]);

        Mail::to($payment->project->user->email)->send(new RefundRequestApprovedMail($refundRequest));

        return back()->with('status', 'Refund approved and processed.');
    }
}
