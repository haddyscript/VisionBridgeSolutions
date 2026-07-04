<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\ProjectApprovedMail;
use App\Mail\ProjectCanceledMail;
use App\Mail\SystemAlertMail;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\Stripe;

class ProjectReviewController extends Controller
{
    public function approve(Request $request)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $project->status === 'review', 404);

        $project->update(['client_approved_at' => now()]);

        $finalPayment = $project->finalPayment();

        if (! $finalPayment && $project->total_price !== null) {
            $depositAmount = $project->depositPayment()?->amount ?? 0;

            $finalPayment = $project->payments()->create([
                'description' => 'Final 50% Project Payment',
                'kind' => 'final',
                'amount' => max(0, $project->total_price - $depositAmount),
            ]);
        }

        if ($finalPayment) {
            Mail::to(config('mail.support_address'))->send(new ProjectApprovedMail($project, $finalPayment));
        }

        return back()->with('status', 'Thanks for approving! Your final payment request is ready in the Payments tab.');
    }

    public function cancel(Request $request)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $project->isReviewWindowOpen(), 422, 'The review/cancellation window for this project has closed.');

        $payment = $project->depositPayment();

        abort_unless($payment && $payment->isPaid(), 422, 'No paid deposit found to refund.');

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
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
                'Client Cancellation Refund Failed',
                "A client tried to cancel and get refunded during their review period, but the Stripe refund failed. This needs manual handling.",
                [
                    'Client' => $project->user->name,
                    'Project' => $project->name,
                    'Error' => $e->getMessage(),
                ],
            ));

            return back()->withErrors(['cancel' => "We couldn't process your refund automatically. Our team has been notified and will follow up shortly."]);
        }

        $payment->update([
            'status' => 'refunded',
            'refunded_amount' => $refundAmount,
            'refunded_at' => now(),
            'stripe_refund_id' => $refund->id,
        ]);

        $project->update(['status' => 'canceled']);

        Mail::to($project->user->email)->send(new ProjectCanceledMail($project, $payment));
        Mail::to(config('mail.billing_address'))->send(new SystemAlertMail(
            'Client Canceled During Review Period',
            "{$project->user->name} canceled their project during the review window. A partial refund (minus Stripe's fee) has been issued automatically.",
            [
                'Client' => $project->user->name,
                'Project' => $project->name,
                'Original Payment' => $payment->formattedAmount(),
                'Refunded' => $payment->formattedRefundedAmount(),
            ],
        ));

        return redirect()->route('portal.dashboard')->with('status', 'Your project has been canceled and a refund has been issued.');
    }
}
