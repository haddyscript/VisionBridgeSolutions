<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceSentMail;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Services\PaymentReconciler;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to, $range] = $this->resolveDateRange($request);

        $payments = Payment::with('project.user')->latest()->get();
        $subscriptions = Subscription::with('project.user')->latest()->get();

        // Collected totals are split so the business can see how much of its
        // revenue is one-time vs. recurring Care Plan payments, both scoped
        // to the selected date range — "Outstanding"/"Pending Maintenance
        // Plans"/"Total Requests" stay unfiltered since those are current
        // snapshots (a pending payment is pending regardless of when it was
        // created), not historical activity.
        $oneTimeTotal = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$from, $to])
            ->sum('amount');

        $subscriptionTotal = SubscriptionPayment::whereBetween('paid_at', [$from, $to])
            ->sum('amount_paid');

        return view('admin.payments.index', [
            'payments' => $payments,
            'subscriptions' => $subscriptions,
            'oneTimeTotal' => $oneTimeTotal,
            'subscriptionTotal' => $subscriptionTotal,
            'range' => $range,
            'rangeFrom' => $from->toDateString(),
            'rangeTo' => $to->toDateString(),
        ]);
    }

    /**
     * Resolves the ?range= (and, for custom, ?from=&to=) query params into a
     * concrete date window. Defaults to "This Month" when nothing is set.
     */
    private function resolveDateRange(Request $request): array
    {
        $range = $request->input('range', 'this_month');
        $now = now();

        switch ($range) {
            case 'last_month':
                $from = $now->copy()->subMonthNoOverflow()->startOfMonth();
                $to = $now->copy()->subMonthNoOverflow()->endOfMonth();
                break;
            case '7_days':
                $from = $now->copy()->subDays(7)->startOfDay();
                $to = $now->copy()->endOfDay();
                break;
            case '14_days':
                $from = $now->copy()->subDays(14)->startOfDay();
                $to = $now->copy()->endOfDay();
                break;
            case 'custom':
                $from = $request->filled('from')
                    ? Carbon::parse($request->input('from'))->startOfDay()
                    : $now->copy()->startOfMonth();
                $to = $request->filled('to')
                    ? Carbon::parse($request->input('to'))->endOfDay()
                    : $now->copy()->endOfDay();
                break;
            default:
                $range = 'this_month';
                $from = $now->copy()->startOfMonth();
                $to = $now->copy()->endOfMonth();
                break;
        }

        return [$from, $to, $range];
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $payment = $project->payments()->create([
            'description' => $validated['description'],
            'amount' => (int) round($validated['amount'] * 100),
        ]);

        Mail::to($project->user->email)->send(new InvoiceSentMail($payment->load('project.user')));

        return back()->with('status', 'Payment request created.');
    }

    public function destroy(Payment $payment)
    {
        abort_unless($payment->isPending(), 422, 'Only pending payment requests can be removed.');
        abort_if($payment->stripe_checkout_session_id, 422, 'This payment already has a Stripe checkout session in progress and can\'t be removed — the client may be mid-payment. Use "Sync with Stripe" to check its status first.');

        $payment->delete();

        return back()->with('status', 'Payment request removed.');
    }

    public function sync(Payment $payment, PaymentReconciler $reconciler)
    {
        $payment->load('project.user');

        return back()->with('status', $reconciler->reconcile($payment));
    }
}
