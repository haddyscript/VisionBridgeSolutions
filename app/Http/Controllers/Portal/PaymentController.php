<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $project = $request->user()->projects()->with('payments', 'subscription.payments')->first();

        return view('portal.payments', [
            'project' => $project,
            'payments' => $project ? $project->payments : collect(),
            'subscription' => $project ? $project->subscription : null,
        ]);
    }

    /**
     * Branded in-page checkout (Stripe Elements) instead of redirecting to
     * Stripe's hosted Checkout page. A one-time PaymentIntent always exposes
     * its own client_secret directly, so — unlike the maintenance plan
     * Subscription flow — no SetupIntent-first workaround is needed here.
     */
    public function checkout(Request $request, Payment $payment)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $payment->project_id === $project->id, 403);
        abort_unless($payment->isPending(), 422, 'This payment has already been processed.');

        $validated = $request->validate([
            'timezone' => ['nullable', 'string', 'max:100'],
        ]);

        if (! empty($validated['timezone'])) {
            $payment->update(['timezone' => $validated['timezone']]);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        if ($payment->stripe_payment_intent_id) {
            $paymentIntent = \Stripe\PaymentIntent::retrieve($payment->stripe_payment_intent_id);
        } else {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'description' => $payment->description,
                'payment_method_types' => ['card'],
                'receipt_email' => $request->user()->email,
                'metadata' => ['payment_id' => $payment->id],
            ]);

            $payment->update(['stripe_payment_intent_id' => $paymentIntent->id]);
        }

        if ($paymentIntent->status === 'succeeded') {
            return redirect()->route('portal.payments.index')->with('status', 'This payment has already been processed.');
        }

        return view('portal.payment-checkout', [
            'payment' => $payment,
            'clientSecret' => $paymentIntent->client_secret,
            'stripeKey' => config('services.stripe.key'),
        ]);
    }

    public function receipt(Request $request, Payment $payment)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $payment->project_id === $project->id, 403);
        abort_unless($payment->isPaid(), 404);

        return view('portal.payment-receipt', [
            'payment' => $payment,
            'project' => $project,
        ]);
    }

    public function statement(Request $request)
    {
        $project = $request->user()->projects()->with('payments')->first();

        abort_unless($project, 404);

        $filename = 'visionbridge-statement-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($project) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Date', 'Description', 'Amount', 'Currency', 'Status', 'Paid On', 'Transaction ID']);

            foreach ($project->payments as $payment) {
                fputcsv($handle, [
                    $payment->created_at->format('Y-m-d'),
                    $payment->description,
                    number_format($payment->amount / 100, 2),
                    strtoupper($payment->currency),
                    ucfirst($payment->status),
                    $payment->paid_at?->format('Y-m-d') ?? '',
                    $payment->stripe_payment_intent_id ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
