<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Project;
use App\Services\PaymentReconciler;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('project.user')->latest()->get();

        return view('admin.payments.index', [
            'payments' => $payments,
        ]);
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $project->payments()->create([
            'description' => $validated['description'],
            'amount' => (int) round($validated['amount'] * 100),
        ]);

        return back()->with('status', 'Payment request created.');
    }

    public function destroy(Payment $payment)
    {
        abort_unless($payment->isPending(), 422, 'Only pending payment requests can be removed.');

        $payment->delete();

        return back()->with('status', 'Payment request removed.');
    }

    public function sync(Payment $payment, PaymentReconciler $reconciler)
    {
        $payment->load('project.user');

        return back()->with('status', $reconciler->reconcile($payment));
    }
}
