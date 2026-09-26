<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerPayout;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Super-admin Revenue dashboard. Read-only — computed live from the same
 * tables the Payments / Care Plans / FaithStack Payouts pages already use
 * (all amounts in cents), none of which are ever pruned, so there's no
 * separate snapshot table to keep in sync.
 *
 * Money in a month = one-time payments by paid_at + Care Plan invoices by
 * paid_at − refunds by refunded_at. FaithStack's share is PartnerPayout
 * rows by created_at (recorded when the client payment comes in; a manually
 * back-entered historical payout lands in the month it was entered).
 */
class RevenueController extends Controller
{
    private const MONTHS = 12;

    public function index()
    {
        $from = now()->startOfMonth()->subMonths(self::MONTHS - 1);

        $oneTime = Payment::whereNotNull('paid_at')->where('paid_at', '>=', $from)->get(['amount', 'paid_at']);
        $carePlan = SubscriptionPayment::where('paid_at', '>=', $from)->get(['amount_paid', 'paid_at']);
        $refunds = Payment::whereNotNull('refunded_at')->where('refunded_at', '>=', $from)->get(['refunded_amount', 'refunded_at']);
        $payouts = PartnerPayout::where('created_at', '>=', $from)->get(['faithstack_amount', 'created_at']);

        $sumByMonth = fn (Collection $rows, string $amount, string $date) => $rows
            ->groupBy(fn ($r) => $r->{$date}->format('Y-m'))
            ->map(fn ($g) => (int) $g->sum($amount));

        $oneTimeByMonth = $sumByMonth($oneTime, 'amount', 'paid_at');
        $carePlanByMonth = $sumByMonth($carePlan, 'amount_paid', 'paid_at');
        $refundsByMonth = $sumByMonth($refunds, 'refunded_amount', 'refunded_at');
        $faithstackByMonth = $sumByMonth($payouts, 'faithstack_amount', 'created_at');

        $months = collect(range(0, self::MONTHS - 1))->map(function ($i) use ($from, $oneTimeByMonth, $carePlanByMonth, $refundsByMonth, $faithstackByMonth) {
            $month = $from->copy()->addMonths($i);
            $key = $month->format('Y-m');
            $oneTime = $oneTimeByMonth->get($key, 0);
            $carePlan = $carePlanByMonth->get($key, 0);
            $refunds = $refundsByMonth->get($key, 0);
            $net = $oneTime + $carePlan - $refunds;
            $faithstack = $faithstackByMonth->get($key, 0);

            return [
                'month' => $month,
                'one_time' => $oneTime,
                'care_plan' => $carePlan,
                'refunds' => $refunds,
                'net' => $net,
                'faithstack' => $faithstack,
                'kept' => $net - $faithstack,
            ];
        });

        $thisMonth = $months->last();
        $lastMonth = $months->get(self::MONTHS - 2);

        // Monthly recurring income: every plan still billing (active, or
        // past_due and being retried), with yearly plans spread over 12.
        $billing = Subscription::with('project.user', 'maintenancePlan')
            ->whereIn('status', ['active', 'past_due'])
            ->get();
        $monthly = fn (Subscription $s) => $s->interval === 'year' ? intdiv((int) $s->amount, 12) : (int) $s->amount;

        $canceled = Subscription::with('project.user')
            ->where('status', 'canceled')
            ->where('canceled_at', '>=', now()->subDays(90))
            ->latest('canceled_at')
            ->get();

        $outstanding = Payment::with('project.user')
            ->where('status', 'pending')
            ->oldest()
            ->get();

        $unpaidFaithstack = PartnerPayout::where('status', '!=', 'paid')->sum('faithstack_amount');

        return view('admin.revenue.index', [
            'months' => $months,
            'thisMonth' => $thisMonth,
            'lastMonth' => $lastMonth,
            'change' => $lastMonth['net'] > 0 ? round(($thisMonth['net'] - $lastMonth['net']) / $lastMonth['net'] * 100) : null,
            'yearToDate' => $months->filter(fn ($m) => $m['month']->year === now()->year)->sum('net'),
            'mrr' => $billing->sum($monthly),
            'activeCount' => $billing->where('status', 'active')->count(),
            'pastDue' => $billing->where('status', 'past_due')->values(),
            'pastDueMrr' => $billing->where('status', 'past_due')->sum($monthly),
            'canceled' => $canceled,
            'lostMrr' => $canceled->sum($monthly),
            'outstanding' => $outstanding,
            'outstandingTotal' => (int) $outstanding->sum('amount'),
            'unpaidFaithstack' => (int) $unpaidFaithstack,
            'topClients' => $this->topClients(),
        ]);
    }

    /**
     * Lifetime money in per project (one-time net of refunds + every Care
     * Plan invoice), top 10.
     */
    private function topClients(): Collection
    {
        $oneTime = Payment::with('project.user')
            ->whereNotNull('paid_at')
            ->get(['project_id', 'amount', 'refunded_amount'])
            ->groupBy('project_id')
            ->map(fn ($g) => ['project' => $g->first()->project, 'total' => (int) ($g->sum('amount') - $g->sum('refunded_amount'))]);

        $carePlan = SubscriptionPayment::join('subscriptions', 'subscriptions.id', '=', 'subscription_payments.subscription_id')
            ->selectRaw('subscriptions.project_id, SUM(subscription_payments.amount_paid) as total')
            ->groupBy('subscriptions.project_id')
            ->pluck('total', 'project_id');

        $projectIds = $oneTime->keys()->merge($carePlan->keys())->unique();
        $missing = $projectIds->diff($oneTime->keys());
        $projects = Project::with('user')->whereIn('id', $missing)->get()->keyBy('id');

        return $projectIds
            ->map(fn ($id) => [
                'project' => $oneTime->get($id)['project'] ?? $projects->get($id),
                'total' => ($oneTime->get($id)['total'] ?? 0) + (int) $carePlan->get($id, 0),
            ])
            ->filter(fn ($row) => $row['project'])
            ->sortByDesc('total')
            ->take(10)
            ->values();
    }

    public static function money(int|float $cents): string
    {
        return '$'.number_format($cents / 100, 2);
    }
}
