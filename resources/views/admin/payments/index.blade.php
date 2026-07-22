@extends('layouts.admin')

@section('title', 'Payments – Admin')
@section('page-title', 'Payments')

@section('content')

@php
    $statusColors = [
        'pending' => 'bg-gold/15 text-gold-dark',
        'paid' => 'bg-teal/15 text-teal-dark',
        'failed' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
        'canceled' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
    ];
    $statusDots = [
        'pending' => 'bg-gold',
        'paid' => 'bg-teal',
        'failed' => 'bg-red-500',
        'canceled' => 'bg-gray-400',
    ];
    $grandTotal = $oneTimeTotal + $subscriptionTotal;
    $totalPending = $payments->where('status', 'pending')->sum('amount');

    $rangeLabels = [
        'this_month' => 'This Month',
        'last_month' => 'Last Month',
        '7_days' => 'Last 7 Days',
        '14_days' => 'Last 2 Weeks',
        'custom' => 'Custom',
    ];

    $subscriptionStatusColors = [
        'pending' => 'bg-gold/15 text-gold-dark',
        'active' => 'bg-teal/15 text-teal-dark',
        'past_due' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
        'canceled' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
    ];
    $subscriptionStatusLabels = [
        'pending' => 'Pending',
        'active' => 'Active',
        'past_due' => 'Past Due',
        'canceled' => 'Canceled',
    ];
    $subscriptionStatusDots = [
        'pending' => 'bg-gold',
        'active' => 'bg-teal',
        'past_due' => 'bg-red-500',
        'canceled' => 'bg-gray-400',
    ];
    $pendingSubscriptionCount = $subscriptions->where('status', 'pending')->count();
@endphp

{{-- Summary hero --}}
<div class="relative overflow-hidden rounded-2xl p-8 mb-8" style="background:linear-gradient(135deg,#111D33,#1B2A4A 60%,#1B2A4A);">
    <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full" style="background:radial-gradient(circle,rgba(201,168,76,0.18) 0%,transparent 70%);"></div>
    <div class="absolute -bottom-20 -left-10 w-56 h-56 rounded-full" style="background:radial-gradient(circle,rgba(42,157,143,0.14) 0%,transparent 70%);"></div>

    <div class="relative">
        <div class="flex items-center justify-between flex-wrap gap-3 mb-2">
            <p class="text-xs font-semibold uppercase tracking-widest text-gold">Payments Overview</p>

            {{-- Date range filter — applies to the revenue row below only --}}
            <div class="flex items-center gap-1.5 flex-wrap">
                @foreach (['this_month', 'last_month', '7_days', '14_days'] as $key)
                    <a href="{{ route('admin.payments.index', ['range' => $key]) }}"
                        class="px-3 py-1 rounded-full text-xs font-medium transition-colors {{ $range === $key ? 'bg-gold text-navy-dark' : 'bg-white/10 text-white/70 hover:bg-white/20' }}">
                        {{ $rangeLabels[$key] }}
                    </a>
                @endforeach
                <button type="button" onclick="document.getElementById('custom-range-form').classList.toggle('hidden')"
                    class="px-3 py-1 rounded-full text-xs font-medium transition-colors {{ $range === 'custom' ? 'bg-gold text-navy-dark' : 'bg-white/10 text-white/70 hover:bg-white/20' }}">
                    Custom
                </button>
            </div>
        </div>

        <form id="custom-range-form" method="GET" action="{{ route('admin.payments.index') }}"
            class="{{ $range === 'custom' ? '' : 'hidden' }} flex items-center flex-wrap gap-2 mb-4">
            <input type="hidden" name="range" value="custom">
            <input type="date" name="from" value="{{ $rangeFrom }}" required
                class="rounded-lg border-0 bg-white/10 text-white text-xs px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-gold">
            <span class="text-white/40 text-xs">to</span>
            <input type="date" name="to" value="{{ $rangeTo }}" required
                class="rounded-lg border-0 bg-white/10 text-white text-xs px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-gold">
            <button type="submit" class="px-3 py-1.5 rounded-lg bg-gold text-navy-dark text-xs font-semibold">Apply</button>
        </form>

        <h2 class="font-display text-2xl font-bold text-white mb-6">All client payment activity</h2>

        {{-- Revenue breakdown — scoped to the date range selected above --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <div class="rounded-xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                <p class="text-xs font-medium uppercase tracking-wide text-white/40 mb-1.5">One-Time Payments</p>
                <p class="font-display text-2xl font-bold text-white">${{ number_format($oneTimeTotal / 100, 2) }}</p>
            </div>
            <div class="rounded-xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                <p class="text-xs font-medium uppercase tracking-wide text-white/40 mb-1.5">Subscription Revenue</p>
                <p class="font-display text-2xl font-bold text-white">${{ number_format($subscriptionTotal / 100, 2) }}</p>
            </div>
            <div class="rounded-xl px-5 py-4" style="background:rgba(201,168,76,0.14);border:1px solid rgba(201,168,76,0.35);">
                <p class="text-xs font-medium uppercase tracking-wide text-gold mb-1.5">Total Collected ({{ $rangeLabels[$range] }})</p>
                <p class="font-display text-2xl font-bold text-white">${{ number_format($grandTotal / 100, 2) }}</p>
            </div>
        </div>

        {{-- Current-state snapshots — not affected by the date range --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                <p class="text-xs font-medium uppercase tracking-wide text-white/40 mb-1.5">Outstanding</p>
                <p class="font-display text-2xl font-bold text-white">${{ number_format($totalPending / 100, 2) }}</p>
            </div>
            <div class="rounded-xl px-5 py-4 cursor-pointer" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);" onclick="showPaymentsTab('maintenance')">
                <p class="text-xs font-medium uppercase tracking-wide text-white/40 mb-1.5">Pending Care Plans</p>
                <p class="font-display text-2xl font-bold text-white">{{ $pendingSubscriptionCount }}</p>
            </div>
            <div class="rounded-xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                <p class="text-xs font-medium uppercase tracking-wide text-white/40 mb-1.5">Total Requests</p>
                <p class="font-display text-2xl font-bold text-white">{{ $payments->count() }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Tabs --}}
<div class="flex items-center gap-1 border-b border-gray-200 dark:border-gray-700 mb-6">
    <button type="button" data-tab-button="one-time" onclick="showPaymentsTab('one-time')"
            class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold border-b-2 border-gold text-navy dark:text-white">
        One-Time Payments
    </button>
    <button type="button" data-tab-button="maintenance" onclick="showPaymentsTab('maintenance')"
            class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-navy transition-colors">
        Care Plans
        @if ($pendingSubscriptionCount > 0)
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gold/15 text-gold-dark">{{ $pendingSubscriptionCount }} pending</span>
        @endif
    </button>
</div>

<div id="panel-one-time" data-tab-panel="one-time">

@if ($payments->isEmpty())
    <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No payment requests yet.</p>
    </div>
@else
    <div class="bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-navy-dark text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Description</th>
                    <th class="px-5 py-3">Amount</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Date</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($payments as $payment)
                    <tr class="payment-row group cursor-pointer hover:bg-gold/5 dark:hover:bg-white/[0.03] transition-colors"
                        data-client="{{ $payment->project->user->name }}"
                        data-project="{{ $payment->project->name }}"
                        data-description="{{ $payment->description }}"
                        data-amount="{{ $payment->formattedAmount() }}"
                        data-status="{{ $payment->status }}"
                        data-status-label="{{ $payment->status === 'past_due' ? 'Past Due' : ucfirst($payment->status) }}"
                        data-currency="{{ strtoupper($payment->currency) }}"
                        data-created="{{ $payment->created_at->format('M j, Y \a\t g:i A') }}"
                        data-paid-at="{{ $payment->paid_at?->format('M j, Y \a\t g:i A') }}"
                        data-intent="{{ $payment->stripe_payment_intent_id }}"
                        data-project-url="{{ route('admin.projects.show', $payment->project) }}">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white">{{ $payment->project->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $payment->project->name }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $payment->description }}</td>
                        <td class="px-5 py-3.5 font-semibold text-navy dark:text-white">{{ $payment->formattedAmount() }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$payment->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$payment->status] ?? 'bg-gray-400' }}"></span>
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $payment->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-3">
                                @if ($payment->isPending() && $payment->stripe_checkout_session_id)
                                    <form method="POST" action="{{ route('admin.payments.sync', $payment) }}" onclick="event.stopPropagation()">
                                        @csrf
                                        <button type="submit" title="Check with Stripe whether this was actually paid" class="inline-flex items-center gap-1.5 text-xs font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-teal/15 hover:text-teal-dark border border-gray-200 dark:border-gray-700 px-3 py-1.5 rounded-full transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            Sync with Stripe
                                        </button>
                                    </form>
                                @endif
                                <span class="inline-flex items-center gap-1 text-gold-dark font-semibold group-hover:gap-1.5 transition-all">
                                    View
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

</div>

<div id="panel-maintenance" data-tab-panel="maintenance" class="hidden">

@if ($subscriptions->isEmpty())
    <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No care plans yet.</p>
    </div>
@else
    {{-- Search + status filter toolbar --}}
    <div class="flex flex-wrap items-center gap-3 mb-4">
        <div class="relative flex-1 min-w-[200px]">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
            <input type="text" id="care-plan-search" placeholder="Search by client or project name..."
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500">
        </div>
        <div class="relative w-full sm:w-48" id="care-plan-status-filter-wrap">
            <input type="hidden" id="care-plan-status-filter" value="">

            <button type="button" id="care-plan-status-filter-toggle" aria-haspopup="listbox" aria-expanded="false"
                    class="w-full flex items-center justify-between gap-2 rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm text-left focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                <span id="care-plan-status-filter-label" class="flex items-center gap-2 min-w-0 truncate text-navy dark:text-white">All Statuses</span>
                <svg id="care-plan-status-filter-chevron" class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div id="care-plan-status-filter-menu" class="hidden absolute z-20 left-0 right-0 mt-1.5 bg-white dark:bg-navy border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                @foreach (['' => 'All Statuses'] + $subscriptionStatusLabels as $value => $label)
                    <button type="button" data-status-option="{{ $value }}" role="option" aria-selected="{{ $value === '' ? 'true' : 'false' }}"
                            class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $value === '' ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                        <span class="flex items-center gap-2">
                            @if ($value)
                                <span class="w-2 h-2 rounded-full shrink-0 {{ $subscriptionStatusDots[$value] ?? 'bg-gray-400' }}"></span>
                            @endif
                            {{ $label }}
                        </span>
                        <svg class="w-4 h-4 text-gold-dark shrink-0 {{ $value === '' ? '' : 'invisible' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <p id="care-plan-empty-state" class="hidden text-sm text-gray-500 dark:text-gray-400 text-center py-6">No care plans match your search.</p>

    <div class="bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-navy-dark text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3">
                        <button type="button" data-sort-key="client" class="care-plan-sort-btn inline-flex items-center gap-1 hover:text-navy dark:hover:text-white transition-colors">
                            Client
                            <svg class="w-3 h-3 sort-icon opacity-30 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </th>
                    <th class="px-5 py-3">Description</th>
                    <th class="px-5 py-3">
                        <button type="button" data-sort-key="amount" class="care-plan-sort-btn inline-flex items-center gap-1 hover:text-navy dark:hover:text-white transition-colors">
                            Amount
                            <svg class="w-3 h-3 sort-icon opacity-30 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">
                        <button type="button" data-sort-key="renews" class="care-plan-sort-btn inline-flex items-center gap-1 hover:text-navy dark:hover:text-white transition-colors">
                            Renews
                            <svg class="w-3 h-3 sort-icon opacity-30 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody id="care-plan-tbody" class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($subscriptions as $subscription)
                    <tr class="care-plan-row hover:bg-gray-50/60 dark:hover:bg-white/[0.03]"
                        data-group="sub-{{ $subscription->id }}"
                        data-client="{{ strtolower($subscription->project->user->name) }}"
                        data-project="{{ strtolower($subscription->project->name) }}"
                        data-status="{{ $subscription->status }}"
                        data-amount="{{ $subscription->amount }}"
                        data-renews="{{ $subscription->current_period_end?->timestamp ?? 0 }}">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white">{{ $subscription->project->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $subscription->project->name }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $subscription->description }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $subscription->formattedAmount() }}</td>
                        <td class="px-5 py-3.5">
                            @if ($subscription->cancel_at_period_end && $subscription->isActive())
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500">
                                    Cancels {{ $subscription->current_period_end?->format('M j') }}
                                </span>
                            @else
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $subscriptionStatusColors[$subscription->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                    {{ $subscriptionStatusLabels[$subscription->status] ?? $subscription->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $subscription->current_period_end?->format('M j, Y') ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            @if ($subscription->payments->isNotEmpty())
                                <button type="button" class="subscription-history-toggle inline-flex items-center gap-1 text-navy dark:text-white font-semibold hover:text-gold-dark mr-4" data-target="subscription-history-{{ $subscription->id }}">
                                    History ({{ $subscription->payments->count() }})
                                    <svg class="w-3.5 h-3.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            @endif
                            <a href="{{ route('admin.projects.show', $subscription->project) }}" class="text-gold-dark font-semibold hover:underline">View Project</a>
                        </td>
                    </tr>
                    @if ($subscription->payments->isNotEmpty())
                        <tr id="subscription-history-{{ $subscription->id }}" class="subscription-history-row hidden bg-gray-50/60 dark:bg-navy-dark/40" data-group="sub-{{ $subscription->id }}">
                            <td colspan="6" class="px-5 py-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2">Payment History</p>
                                <div class="space-y-1.5">
                                    @foreach ($subscription->payments as $subscriptionPayment)
                                        <div class="flex items-center justify-between gap-4 text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">{{ $subscriptionPayment->paid_at->format('M j, Y \a\t g:ia') }}</span>
                                            <span class="font-medium text-navy dark:text-white">{{ $subscriptionPayment->formattedAmountPaid() }}</span>
                                            @if ($subscriptionPayment->hosted_invoice_url)
                                                <a href="{{ $subscriptionPayment->hosted_invoice_url }}" target="_blank" rel="noopener" class="text-gold-dark hover:underline shrink-0">View Invoice</a>
                                            @else
                                                <span class="text-gray-600 dark:text-gray-300 shrink-0">—</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@endif

</div>

<script>
    function showPaymentsTab(tab) {
        document.querySelectorAll('[data-tab-panel]').forEach((el) => {
            el.classList.toggle('hidden', el.dataset.tabPanel !== tab);
        });
        document.querySelectorAll('[data-tab-button]').forEach((btn) => {
            const active = btn.dataset.tabButton === tab;
            btn.classList.toggle('border-gold', active);
            btn.classList.toggle('text-navy', active);
            btn.classList.toggle('dark:text-white', active);
            btn.classList.toggle('border-transparent', !active);
            btn.classList.toggle('text-gray-400', !active);
            btn.classList.toggle('dark:text-gray-500', !active);
        });
    }

    document.querySelectorAll('.subscription-history-toggle').forEach((btn) => {
        btn.addEventListener('click', () => {
            const row = document.getElementById(btn.dataset.target);
            if (!row) return;
            row.classList.toggle('hidden');
            btn.querySelector('svg').classList.toggle('rotate-90');
        });
    });

    // Care Plans: search, status filter, and sortable columns. Each main row
    // and its optional (collapsed-by-default) payment-history row share a
    // data-group value so sorting can move them together as a pair, and
    // filtering hides/collapses the history row whenever its parent is hidden.
    (function () {
        const tbody = document.getElementById('care-plan-tbody');
        if (!tbody) return;

        const searchInput = document.getElementById('care-plan-search');
        const statusFilter = document.getElementById('care-plan-status-filter');
        const emptyState = document.getElementById('care-plan-empty-state');

        function applyCarePlanFilters() {
            const query = (searchInput?.value || '').trim().toLowerCase();
            const status = statusFilter?.value || '';
            let visibleCount = 0;

            tbody.querySelectorAll('.care-plan-row').forEach((row) => {
                const matchesQuery = !query || row.dataset.client.includes(query) || row.dataset.project.includes(query);
                const matchesStatus = !status || row.dataset.status === status;
                const visible = matchesQuery && matchesStatus;

                row.classList.toggle('hidden', !visible);
                if (visible) visibleCount++;

                const historyRow = tbody.querySelector('.subscription-history-row[data-group="' + row.dataset.group + '"]');
                if (historyRow && !visible) {
                    historyRow.classList.add('hidden');
                    const toggleBtn = tbody.querySelector('.subscription-history-toggle[data-target="' + historyRow.id + '"]');
                    toggleBtn?.querySelector('svg')?.classList.remove('rotate-90');
                }
            });

            if (emptyState) emptyState.classList.toggle('hidden', visibleCount > 0);
        }

        searchInput?.addEventListener('input', applyCarePlanFilters);

        // Status filter dropdown — same custom toggle/menu pattern used for
        // the client-portal Payment History status filter, for consistency.
        (function () {
            const wrap = document.getElementById('care-plan-status-filter-wrap');
            const toggle = document.getElementById('care-plan-status-filter-toggle');
            const menu = document.getElementById('care-plan-status-filter-menu');
            const chevron = document.getElementById('care-plan-status-filter-chevron');
            const hiddenInput = document.getElementById('care-plan-status-filter');
            const label = document.getElementById('care-plan-status-filter-label');
            if (!wrap || !toggle || !menu || !hiddenInput || !label) return;

            function closeMenu() {
                menu.classList.add('hidden');
                toggle.setAttribute('aria-expanded', 'false');
                chevron.style.transform = '';
            }

            function openMenu() {
                menu.classList.remove('hidden');
                toggle.setAttribute('aria-expanded', 'true');
                chevron.style.transform = 'rotate(180deg)';
            }

            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                menu.classList.contains('hidden') ? openMenu() : closeMenu();
            });

            menu.querySelectorAll('[data-status-option]').forEach(function (option) {
                option.addEventListener('click', function () {
                    hiddenInput.value = option.dataset.statusOption;
                    label.textContent = option.textContent.trim();

                    menu.querySelectorAll('[data-status-option]').forEach(function (opt) {
                        const isSelected = opt === option;
                        opt.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                        opt.classList.toggle('text-gold-dark', isSelected);
                        opt.classList.toggle('font-semibold', isSelected);
                        opt.classList.toggle('text-gray-700', !isSelected);
                        opt.classList.toggle('dark:text-gray-300', !isSelected);
                        opt.querySelector('svg').classList.toggle('invisible', !isSelected);
                    });

                    closeMenu();
                    applyCarePlanFilters();
                });
            });

            document.addEventListener('click', function (e) {
                if (!wrap.contains(e.target)) closeMenu();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeMenu();
            });
        })();

        // Sorting — groups each main row with its history row (if any) so
        // they always move together, then re-appends every group in order.
        let currentSort = { key: null, dir: 1 };

        function sortCarePlansBy(key) {
            currentSort = currentSort.key === key ? { key: key, dir: -currentSort.dir } : { key: key, dir: 1 };

            const groups = new Map();
            tbody.querySelectorAll('.care-plan-row').forEach((row) => {
                groups.set(row.dataset.group, { main: row, history: null });
            });
            tbody.querySelectorAll('.subscription-history-row').forEach((row) => {
                const group = groups.get(row.dataset.group);
                if (group) group.history = row;
            });

            const isNumeric = key === 'amount' || key === 'renews';
            const sorted = Array.from(groups.values()).sort((a, b) => {
                const valA = isNumeric ? Number(a.main.dataset[key]) : a.main.dataset[key];
                const valB = isNumeric ? Number(b.main.dataset[key]) : b.main.dataset[key];
                if (valA < valB) return -1 * currentSort.dir;
                if (valA > valB) return 1 * currentSort.dir;
                return 0;
            });

            sorted.forEach(function (group) {
                tbody.appendChild(group.main);
                if (group.history) tbody.appendChild(group.history);
            });

            document.querySelectorAll('.care-plan-sort-btn .sort-icon').forEach((icon) => {
                icon.classList.add('opacity-30');
                icon.classList.remove('rotate-180');
            });
            const activeBtn = document.querySelector('.care-plan-sort-btn[data-sort-key="' + key + '"] .sort-icon');
            if (activeBtn) {
                activeBtn.classList.remove('opacity-30');
                activeBtn.classList.toggle('rotate-180', currentSort.dir === -1);
            }
        }

        document.querySelectorAll('.care-plan-sort-btn').forEach((btn) => {
            btn.addEventListener('click', () => sortCarePlansBy(btn.dataset.sortKey));
        });
    })();
</script>

{{-- Transaction Detail Modal --}}
<div id="payment-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div id="payment-modal-backdrop" class="absolute inset-0 bg-navy-dark/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>

    <div id="payment-modal-panel" class="relative w-full max-w-md transform scale-95 opacity-0 transition-all duration-300">
        <div class="relative overflow-hidden rounded-2xl shadow-2xl" style="background:linear-gradient(135deg,#111D33,#1B2A4A 60%,#1B2A4A);">
            <div class="absolute -top-20 -right-12 w-56 h-56 rounded-full" style="background:radial-gradient(circle,rgba(201,168,76,0.20) 0%,transparent 70%);"></div>
            <div class="absolute -bottom-24 -left-10 w-56 h-56 rounded-full" style="background:radial-gradient(circle,rgba(42,157,143,0.16) 0%,transparent 70%);"></div>

            <button type="button" onclick="closePaymentModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/70 hover:text-white transition-colors z-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="relative px-7 pt-8 pb-6 text-center">
                <div id="modal-status-icon" class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center"></div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-1">Transaction Details</p>
                <p id="modal-amount" class="font-display text-3xl font-bold text-white"></p>
                <p id="modal-description" class="text-sm text-white/50 mt-1"></p>
            </div>

            <div class="relative bg-white dark:bg-navy rounded-t-2xl px-7 py-6 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Client</span>
                    <span id="modal-client" class="text-sm font-semibold text-navy dark:text-white"></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Project</span>
                    <span id="modal-project" class="text-sm font-semibold text-navy dark:text-white"></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Status</span>
                    <span id="modal-status-badge" class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full"></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Currency</span>
                    <span id="modal-currency" class="text-sm font-semibold text-navy dark:text-white"></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Created</span>
                    <span id="modal-created" class="text-sm font-semibold text-navy dark:text-white"></span>
                </div>
                <div id="modal-paid-row" class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Paid On</span>
                    <span id="modal-paid-at" class="text-sm font-semibold text-navy dark:text-white"></span>
                </div>
                <div id="modal-intent-row" class="flex items-center justify-between gap-3">
                    <span class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 shrink-0">Transaction ID</span>
                    <button type="button" onclick="copyTransactionId()" id="modal-intent" class="text-sm font-mono text-navy dark:text-white truncate hover:text-gold transition-colors" title="Click to copy"></button>
                </div>

                <div class="pt-2">
                    <a id="modal-project-link" href="#" class="block text-center bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-3 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                        View Project
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const statusColors = {
            pending: 'bg-gold/15 text-gold-dark',
            paid: 'bg-teal/15 text-teal-dark',
            failed: 'bg-red-50 text-red-500',
            canceled: 'bg-gray-100 text-gray-500',
        };
        const statusDots = {
            pending: 'bg-gold',
            paid: 'bg-teal',
            failed: 'bg-red-500',
            canceled: 'bg-gray-400',
        };
        const statusIconBg = {
            pending: 'bg-gold/15',
            paid: 'bg-teal/15',
            failed: 'bg-red-500/15',
            canceled: 'bg-white/10',
        };
        const statusIconColor = {
            pending: 'text-gold',
            paid: 'text-teal',
            failed: 'text-red-400',
            canceled: 'text-white/50',
        };
        const statusIconPath = {
            pending: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            paid: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',
            failed: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>',
            canceled: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
        };

        const modal = document.getElementById('payment-modal');
        const backdrop = document.getElementById('payment-modal-backdrop');
        const panel = document.getElementById('payment-modal-panel');

        window.openPaymentModal = function (row) {
            const d = row.dataset;

            document.getElementById('modal-amount').textContent = d.amount;
            document.getElementById('modal-description').textContent = d.description;
            document.getElementById('modal-client').textContent = d.client;
            document.getElementById('modal-project').textContent = d.project;
            document.getElementById('modal-currency').textContent = d.currency;
            document.getElementById('modal-created').textContent = d.created;
            document.getElementById('modal-project-link').href = d.projectUrl;

            const badge = document.getElementById('modal-status-badge');
            badge.className = 'inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full ' + (statusColors[d.status] || 'bg-gray-100 text-gray-500');
            badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full ' + (statusDots[d.status] || 'bg-gray-400') + '"></span>' + d.statusLabel;

            const icon = document.getElementById('modal-status-icon');
            icon.className = 'w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center ' + (statusIconBg[d.status] || 'bg-white/10');
            icon.innerHTML = '<svg class="w-7 h-7 ' + (statusIconColor[d.status] || 'text-white/50') + '" fill="none" stroke="currentColor" viewBox="0 0 24 24">' + (statusIconPath[d.status] || statusIconPath.canceled) + '</svg>';

            const paidRow = document.getElementById('modal-paid-row');
            if (d.paidAt) {
                document.getElementById('modal-paid-at').textContent = d.paidAt;
                paidRow.classList.remove('hidden');
            } else {
                paidRow.classList.add('hidden');
            }

            const intentRow = document.getElementById('modal-intent-row');
            const intentEl = document.getElementById('modal-intent');
            if (d.intent) {
                intentEl.textContent = d.intent;
                intentEl.dataset.fullId = d.intent;
                intentRow.classList.remove('hidden');
            } else {
                intentRow.classList.add('hidden');
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(function () {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('scale-95', 'opacity-0');
            });
        };

        window.closePaymentModal = function () {
            backdrop.classList.add('opacity-0');
            panel.classList.add('scale-95', 'opacity-0');
            setTimeout(function () {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        };

        window.copyTransactionId = function () {
            const el = document.getElementById('modal-intent');
            const id = el.dataset.fullId;
            if (!id) return;
            navigator.clipboard.writeText(id).then(function () {
                const original = el.textContent;
                el.textContent = 'Copied!';
                setTimeout(function () { el.textContent = original; }, 1200);
            });
        };

        document.querySelectorAll('.payment-row').forEach(function (row) {
            row.addEventListener('click', function () {
                window.openPaymentModal(row);
            });
        });

        backdrop?.addEventListener('click', closePaymentModal);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closePaymentModal();
        });
    })();
</script>

@endsection
