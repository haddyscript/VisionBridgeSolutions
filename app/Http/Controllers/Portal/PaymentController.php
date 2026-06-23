<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $project = $request->user()->projects()->with('payments', 'subscription')->first();

        return view('portal.payments', [
            'project' => $project,
            'payments' => $project ? $project->payments : collect(),
            'subscription' => $project ? $project->subscription : null,
        ]);
    }

    public function checkout(Request $request, Payment $payment)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $payment->project_id === $project->id, 403);
        abort_unless($payment->isPending(), 422, 'This payment has already been processed.');

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => $payment->currency,
                    'unit_amount' => $payment->amount,
                    'product_data' => [
                        'name' => $payment->description,
                    ],
                ],
            ]],
            'customer_email' => $request->user()->email,
            'success_url' => route('portal.payments.index').'?checkout=success',
            'cancel_url' => route('portal.payments.index').'?checkout=cancel',
            'metadata' => [
                'payment_id' => $payment->id,
            ],
        ]);

        $payment->update(['stripe_checkout_session_id' => $session->id]);

        return redirect()->away($session->url);
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
}
