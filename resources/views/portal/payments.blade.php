@extends('layouts.portal')

@section('title', 'Payments – Client Portal')
@section('page-title', 'Payments')

@section('content')

@if (request('checkout') === 'success')
    <div class="mb-6 flex items-center gap-3 rounded-xl px-5 py-4" style="background:rgba(42,157,143,0.08);border:1px solid rgba(42,157,143,0.25);">
        <span class="w-9 h-9 rounded-full bg-teal/15 flex items-center justify-center shrink-0">
            <svg class="w-[1.125rem] h-[1.125rem] text-teal-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
        </span>
        <p class="text-sm text-teal-dark font-medium">Payment received! It may take a moment to reflect below.</p>
    </div>
@elseif (request('checkout') === 'cancel')
    <div class="mb-6 flex items-center gap-3 rounded-xl px-5 py-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
        <span class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center shrink-0">
            <svg class="w-[1.125rem] h-[1.125rem] text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </span>
        <p class="text-sm text-gray-600 dark:text-gray-300 font-medium">Checkout was canceled. No charge was made.</p>
    </div>
@endif

@if (! $project)

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No project has been set up for your account yet. Please contact your VisionBridge representative.</p>
    </div>

@else

    @php
        $statusColors = [
            // Amber (not the brand gold used for buttons/accents everywhere
            // else on this page) so an unpaid invoice actually stands out as
            // something the client needs to act on, not just a neutral status.
            'pending' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400',
            'paid' => 'bg-teal/15 text-teal-dark',
            'active' => 'bg-teal/15 text-teal-dark',
            'past_due' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
            'failed' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
            'canceled' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
        ];
        $statusDots = [
            'pending' => 'bg-amber-500',
            'paid' => 'bg-teal',
            'active' => 'bg-teal',
            'past_due' => 'bg-red-500',
            'failed' => 'bg-red-500',
            'canceled' => 'bg-gray-400',
        ];
        // Non-technical clients read "Pending" as "someone else is handling
        // this," not "I need to pay this." Only the one-time Payment rows
        // get the clearer label — Care Plan status keeps "Pending" since it
        // means "not started yet," a different action (Start Plan).
        $paymentStatusLabel = fn ($payment) => $payment->status === 'pending' ? 'Payment Needed' : ucfirst($payment->status);
        $totalDue = $payments->where('status', 'pending')->sum('amount');
        $totalPaid = $payments->where('status', 'paid')->sum('amount')
            + ($subscription ? $subscription->payments->sum('amount_paid') : 0);

        // Unified Payment History — one-time payments and Care Plan invoice
        // payments merged into a single chronological list (view-level only;
        // Portal\PaymentController and the Stripe-facing checkout/receipt/
        // statement logic are untouched) so clients see one history instead
        // of two separately-counted cards.
        $paymentItems = $payments->map(fn ($payment) => ['kind' => 'payment', 'date' => $payment->created_at, 'model' => $payment]);
        $subscriptionPaymentItems = $subscription
            ? $subscription->payments->map(fn ($sp) => ['kind' => 'subscription_payment', 'date' => $sp->paid_at, 'model' => $sp])
            : collect();
        $allPaymentItems = $paymentItems->concat($subscriptionPaymentItems)->sortByDesc('date')->values();
    @endphp

    {{-- Billing Overview hero --}}
    <div class="relative overflow-hidden rounded-2xl p-8 mb-8" style="background:linear-gradient(135deg,#111D33,#1B2A4A 60%,#1B2A4A);">
        <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full" style="background:radial-gradient(circle,rgba(201,168,76,0.18) 0%,transparent 70%);"></div>
        <div class="absolute -bottom-20 -left-10 w-56 h-56 rounded-full" style="background:radial-gradient(circle,rgba(42,157,143,0.14) 0%,transparent 70%);"></div>

        <div class="relative">
            <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-2">Billing Overview</p>
            <h2 class="font-display text-2xl font-bold text-white mb-6">Your account at a glance</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                    <p class="text-xs font-semibold uppercase tracking-wide text-white/75 mb-1.5">Amount Due</p>
                    <p class="font-sans text-2xl font-extrabold tracking-tight text-white">${{ number_format($totalDue / 100, 2) }}</p>
                </div>
                <div class="rounded-xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                    <p class="text-xs font-semibold uppercase tracking-wide text-white/75 mb-1.5">Total Paid</p>
                    <p class="font-sans text-2xl font-extrabold tracking-tight text-white">${{ number_format($totalPaid / 100, 2) }}</p>
                </div>
                <div class="rounded-xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                    <p class="text-xs font-semibold uppercase tracking-wide text-white/75 mb-1.5">Care Plan</p>
                    @if ($subscription)
                        <p class="font-sans text-2xl font-extrabold tracking-tight {{ $subscription->isCanceled() ? 'text-white/50' : 'text-white' }}">{{ $subscription->status === 'past_due' ? 'Past Due' : ucfirst($subscription->status) }}</p>
                    @else
                        <p class="font-sans text-2xl font-extrabold tracking-tight text-white/50">None</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Maintenance Plan --}}
    @if ($subscription)
        @include('portal.partials.subscription-card', ['subscription' => $subscription])
    @endif

    {{-- Payment History — one-time payments and Care Plan invoices, unified --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
            <button type="button" id="payment-history-toggle" aria-expanded="true" aria-controls="payment-history-body" class="group inline-flex items-center gap-2 text-left">
                <h3 class="font-display text-lg font-bold text-navy dark:text-white">Payment History <span class="text-gray-400 dark:text-gray-500 font-normal">({{ $allPaymentItems->count() }})</span></h3>
                <svg id="payment-history-chevron" class="w-4 h-4 text-gray-400 group-hover:text-navy dark:group-hover:text-white transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div class="flex items-center gap-4">
                @if ($payments->isNotEmpty())
                    <a href="{{ route('portal.payments.statement') }}" class="inline-flex items-center gap-1.5 text-sm text-gold-dark hover:underline">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                        Download Statement
                    </a>
                @endif
                @if ($totalDue > 0)
                    <a href="{{ route('portal.faq') }}#how-to-pay" class="inline-flex items-center gap-1.5 text-sm text-gold-dark hover:underline">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        How do I pay an invoice?
                    </a>
                @endif
            </div>
        </div>

        <div id="payment-history-body">
        @if ($allPaymentItems->isEmpty())
            <div class="text-center py-10">
                <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h5M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/></svg>
                </div>
                <p class="text-sm text-gray-400 dark:text-gray-500">No payment requests yet. Your VisionBridge representative will let you know when one is ready.</p>
            </div>
        @else
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <div class="relative flex-1 min-w-[200px]">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                    <input type="text" id="payment-search" placeholder="Search payments..."
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                </div>
                <div class="relative w-full sm:w-48" id="payment-status-filter-wrap">
                    <input type="hidden" id="payment-status-filter" value="">

                    <button type="button" id="payment-status-filter-toggle" aria-haspopup="listbox" aria-expanded="false"
                            class="w-full flex items-center justify-between gap-2 rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm text-left focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                        <span id="payment-status-filter-label" class="flex items-center gap-2 min-w-0 truncate text-navy dark:text-white">All Statuses</span>
                        <svg id="payment-status-filter-chevron" class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="payment-status-filter-menu" class="hidden absolute z-20 left-0 right-0 mt-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                        @foreach (['' => 'All Statuses', 'pending' => 'Payment Needed', 'paid' => 'Paid', 'failed' => 'Failed', 'canceled' => 'Canceled'] as $value => $label)
                            <button type="button" data-status-option="{{ $value }}" role="option" aria-selected="{{ $value === '' ? 'true' : 'false' }}"
                                    class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $value === '' ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                <span class="flex items-center gap-2">
                                    @if ($value)
                                        <span class="w-2 h-2 rounded-full shrink-0 {{ $statusDots[$value] ?? 'bg-gray-400' }}"></span>
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

            <p id="payment-empty-state" class="hidden text-sm text-gray-400 dark:text-gray-500 text-center py-6">No payments match your search.</p>

            @foreach ($allPaymentItems->groupBy(fn ($entry) => $entry['date']->format('F Y')) as $monthLabel => $monthItems)
                <div class="payment-month-group">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mt-5 mb-2.5">{{ $monthLabel }}</p>
                    <div class="space-y-3">
                        @foreach ($monthItems as $entry)
                            @if ($entry['kind'] === 'payment')
                                @php $payment = $entry['model']; @endphp
                                <div class="payment-row group relative flex flex-wrap items-center justify-between gap-4 rounded-xl border border-gray-100 dark:border-gray-700/60 px-5 py-4 cursor-pointer transition-all hover:border-gold/40 hover:shadow-lg hover:-translate-y-0.5"
                                     data-kind="payment"
                                     data-search="{{ strtolower($payment->description) }}"
                                     data-description="{{ $payment->description }}"
                                     data-amount="{{ $payment->formattedAmount() }}"
                                     data-status="{{ $payment->status }}"
                                     data-status-label="{{ $payment->status === 'past_due' ? 'Past Due' : $paymentStatusLabel($payment) }}"
                                     data-currency="{{ strtoupper($payment->currency) }}"
                                     data-created="{{ $payment->created_at->format('M j, Y \a\t g:i A') }}"
                                     data-paid-at="{{ $payment->paid_at?->format('M j, Y \a\t g:i A') }}"
                                     data-intent="{{ $payment->stripe_payment_intent_id }}"
                                     data-session="{{ $payment->stripe_checkout_session_id }}"
                                     data-checkout-url="{{ $payment->isPending() ? route('portal.payments.checkout', $payment) : '' }}"
                                     data-receipt-url="{{ $payment->isPaid() ? route('portal.payments.receipt', $payment) : '' }}"
                                     data-refund-request-url="{{ $payment->isRefundRequestable() ? route('portal.payments.refund-request', $payment) : '' }}">
                                    <span class="absolute left-0 top-3 bottom-3 w-1 rounded-full {{ $statusDots[$payment->status] ?? 'bg-gray-400' }} opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                    <div class="flex items-center gap-4">
                                        <span class="w-10 h-10 rounded-lg bg-navy/5 dark:bg-white/5 flex items-center justify-center shrink-0 transition-transform group-hover:scale-110">
                                            <svg class="w-[1.125rem] h-[1.125rem] text-navy dark:text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 0v6m0-6L4 21"/></svg>
                                        </span>
                                        <div>
                                            <p class="font-medium text-navy dark:text-white">{{ $payment->description }}</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $payment->created_at->format('M j, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="font-sans font-extrabold text-lg text-navy dark:text-white">{{ $payment->formattedAmount() }}</span>
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full {{ $statusColors[$payment->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$payment->status] ?? 'bg-gray-400' }}"></span>
                                            {{ $paymentStatusLabel($payment) }}
                                        </span>
                                        @if ($payment->isPending())
                                            <form method="POST" action="{{ route('portal.payments.checkout', $payment) }}" onclick="event.stopPropagation()" class="js-payment-checkout-form">
                                                @csrf
                                                <input type="hidden" name="timezone" class="js-timezone-input">
                                                <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                                                    Pay Now
                                                </button>
                                            </form>
                                        @endif
                                        <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 group-hover:text-gold transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </div>
                                </div>
                            @else
                                @php $subscriptionPayment = $entry['model']; @endphp
                                <a href="{{ route('portal.subscription-payments.receipt', $subscriptionPayment) }}" target="_blank" rel="noopener"
                                   class="payment-row group relative flex flex-wrap items-center justify-between gap-4 rounded-xl border border-gray-100 dark:border-gray-700/60 px-5 py-4 transition-all hover:border-gold/40 hover:shadow-lg hover:-translate-y-0.5"
                                   data-kind="subscription_payment"
                                   data-search="{{ strtolower($subscription->description) }}"
                                   data-status="paid">
                                    <span class="absolute left-0 top-3 bottom-3 w-1 rounded-full bg-teal opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                    <div class="flex items-center gap-4">
                                        <span class="w-10 h-10 rounded-lg bg-navy/5 dark:bg-white/5 flex items-center justify-center shrink-0 transition-transform group-hover:scale-110">
                                            <svg class="w-[1.125rem] h-[1.125rem] text-navy dark:text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        </span>
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <p class="font-medium text-navy dark:text-white">{{ $subscription->description }}</p>
                                                <span class="text-[0.65rem] font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-teal/10 text-teal-dark">Care Plan</span>
                                            </div>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $subscriptionPayment->paid_at->format('M j, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="font-sans font-extrabold text-lg text-navy dark:text-white">{{ $subscriptionPayment->formattedAmountPaid() }}</span>
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full bg-teal/15 text-teal-dark">
                                            <span class="w-1.5 h-1.5 rounded-full bg-teal"></span>
                                            Paid
                                        </span>
                                        <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 group-hover:text-gold transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
        </div>
    </div>

    <script>
        (function () {
            const btn = document.getElementById('payment-history-toggle');
            const body = document.getElementById('payment-history-body');
            const chevron = document.getElementById('payment-history-chevron');
            if (!btn || !body) return;

            function apply(open) {
                body.classList.toggle('hidden', !open);
                btn.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (chevron) chevron.style.transform = open ? '' : 'rotate(-90deg)';
            }

            // Remember the client's choice across visits; collapsed by default.
            apply(localStorage.getItem('paymentHistoryOpen') === 'true');

            btn.addEventListener('click', function () {
                const open = body.classList.contains('hidden');
                apply(open);
                localStorage.setItem('paymentHistoryOpen', open ? 'true' : 'false');
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

                <div class="relative bg-white dark:bg-gray-800 rounded-t-2xl px-7 py-6 space-y-4">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500 shrink-0">Description</span>
                        <span id="modal-description-row" class="text-sm font-semibold text-navy dark:text-white text-right"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">Status</span>
                        <span id="modal-status-badge" class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">Currency</span>
                        <span id="modal-currency" class="text-sm font-semibold text-navy dark:text-white"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">Created</span>
                        <span id="modal-created" class="text-sm font-semibold text-navy dark:text-white"></span>
                    </div>
                    <div id="modal-paid-row" class="flex items-center justify-between">
                        <span class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">Paid On</span>
                        <span id="modal-paid-at" class="text-sm font-semibold text-navy dark:text-white"></span>
                    </div>
                    <div id="modal-intent-row" class="flex items-center justify-between gap-3">
                        <span class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500 shrink-0">Transaction ID</span>
                        <button type="button" onclick="copyTransactionId()" id="modal-intent" class="inline-flex items-center gap-1.5 text-sm font-mono text-navy dark:text-white hover:text-gold transition-colors" title="Click to copy">
                            <span id="modal-intent-text" class="truncate max-w-[160px]"></span>
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>

                    <div id="modal-pay-action" class="hidden pt-2">
                        <form id="modal-pay-form" method="POST" action="#" class="js-payment-checkout-form">
                            @csrf
                            <input type="hidden" name="timezone" class="js-timezone-input">
                            <button type="submit" class="block w-full text-center bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-3 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                                Pay Now
                            </button>
                        </form>
                    </div>

                    <div id="modal-receipt-action" class="hidden pt-2">
                        <a id="modal-receipt-link" href="#" target="_blank" class="block w-full text-center bg-navy hover:bg-navy-light text-white text-sm font-semibold px-5 py-3 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                            View Receipt
                        </a>
                    </div>

                    <div id="modal-refund-action" class="hidden pt-2">
                        <button type="button" id="modal-refund-toggle" onclick="toggleRefundForm()"
                            class="block w-full text-center bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-navy dark:text-white text-sm font-semibold px-5 py-3 rounded-lg transition-colors">
                            Request a Refund
                        </button>
                        <form id="modal-refund-form" method="POST" action="#" class="hidden mt-3 space-y-2">
                            @csrf
                            <textarea name="reason" required maxlength="2000" rows="3" placeholder="Tell us why you're requesting a refund..."
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"></textarea>
                            <button type="submit" class="block w-full text-center bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                                Submit Refund Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Maintenance Plan Detail Modal --}}
    <div id="subscription-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div id="subscription-modal-backdrop" class="absolute inset-0 bg-navy-dark/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>

        <div id="subscription-modal-panel" class="relative w-full max-w-md transform scale-95 opacity-0 transition-all duration-300">
            <div class="relative overflow-hidden rounded-2xl shadow-2xl" style="background:linear-gradient(135deg,#111D33,#1B2A4A 60%,#1B2A4A);">
                <div class="absolute -top-20 -right-12 w-56 h-56 rounded-full" style="background:radial-gradient(circle,rgba(201,168,76,0.20) 0%,transparent 70%);"></div>
                <div class="absolute -bottom-24 -left-10 w-56 h-56 rounded-full" style="background:radial-gradient(circle,rgba(42,157,143,0.16) 0%,transparent 70%);"></div>

                <button type="button" onclick="closeSubscriptionModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/70 hover:text-white transition-colors z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <div class="relative px-7 pt-8 pb-6 text-center">
                    <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center bg-teal/15">
                        <svg class="w-7 h-7 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-1">Care Plan</p>
                    <p id="sub-modal-amount" class="font-display text-3xl font-bold text-white"></p>
                    <p id="sub-modal-description" class="text-sm text-white/50 mt-1"></p>
                </div>

                <div class="relative bg-white dark:bg-gray-800 rounded-t-2xl px-7 py-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">Status</span>
                        <span id="sub-modal-status-badge" class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">Started</span>
                        <span id="sub-modal-started" class="text-sm font-semibold text-navy dark:text-white"></span>
                    </div>
                    <div id="sub-modal-period-row" class="flex items-center justify-between">
                        <span id="sub-modal-period-label" class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500"></span>
                        <span id="sub-modal-period-end" class="text-sm font-semibold text-navy dark:text-white"></span>
                    </div>

                    <div id="sub-modal-start-action" class="hidden pt-2">
                        <form id="sub-modal-start-form" method="POST" action="#">
                            @csrf
                            <button type="submit" class="block w-full text-center bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-3 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                                Start Plan
                            </button>
                        </form>
                    </div>

                    <div id="sub-modal-billing-action" class="hidden pt-2">
                        <a id="sub-modal-billing-link" href="#" class="block w-full text-center bg-navy hover:bg-navy-light text-white text-sm font-semibold px-5 py-3 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                            Manage Billing
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif

{{-- Transient toast for no-reload actions like Refresh Status --}}
<div id="toast" class="fixed bottom-6 right-6 z-50 max-w-sm transform translate-y-2 opacity-0 transition-all duration-300 pointer-events-none">
    <div class="flex items-center gap-2.5 text-sm font-medium text-teal-dark dark:text-teal-light bg-white dark:bg-gray-800 border border-teal/30 shadow-lg rounded-lg px-4 py-3">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        <span id="toast-message"></span>
    </div>
</div>

<script>
    (function () {
        // Stamps the payer's own browser timezone onto each "Pay Now" form so
        // the receipt email can show the correct local time instead of the
        // server's UTC default.
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        document.querySelectorAll('.js-timezone-input').forEach(function (input) {
            input.value = timezone;
        });
    })();
</script>

<script>
    (function () {
        let toastTimeout;
        window.showToast = function (message) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-message').textContent = message;

            clearTimeout(toastTimeout);
            toast.classList.remove('translate-y-2', 'opacity-0');

            toastTimeout = setTimeout(function () {
                toast.classList.add('translate-y-2', 'opacity-0');
            }, 3000);
        };
    })();
</script>

<script>
    (function () {
        const statusColors = {
            pending: 'bg-gold/15 text-gold-dark',
            paid: 'bg-teal/15 text-teal-dark',
            active: 'bg-teal/15 text-teal-dark',
            past_due: 'bg-red-50 text-red-500',
            failed: 'bg-red-50 text-red-500',
            canceled: 'bg-gray-100 text-gray-500',
        };
        const statusDots = {
            pending: 'bg-gold',
            paid: 'bg-teal',
            active: 'bg-teal',
            past_due: 'bg-red-500',
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
            document.getElementById('modal-description-row').textContent = d.description;
            document.getElementById('modal-currency').textContent = d.currency;
            document.getElementById('modal-created').textContent = d.created;

            // Payment rows use a distinct amber treatment for "pending" (see
            // the matching PHP-side comment) so it reads as "you need to pay
            // this" — kept as a local override rather than editing the
            // shared statusColors/statusDots/statusIcon* objects above,
            // since those are also used by the Care Plan modal where
            // "pending" means something else (not started yet).
            const badgeColorClass = d.status === 'pending' ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400' : (statusColors[d.status] || 'bg-gray-100 text-gray-500');
            const badgeDotClass = d.status === 'pending' ? 'bg-amber-500' : (statusDots[d.status] || 'bg-gray-400');
            const iconBgClass = d.status === 'pending' ? 'bg-amber-500/15' : (statusIconBg[d.status] || 'bg-white/10');
            const iconColorClass = d.status === 'pending' ? 'text-amber-400' : (statusIconColor[d.status] || 'text-white/50');

            const badge = document.getElementById('modal-status-badge');
            badge.className = 'inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full ' + badgeColorClass;
            badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full ' + badgeDotClass + '"></span>' + d.statusLabel;

            const icon = document.getElementById('modal-status-icon');
            icon.className = 'w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center ' + iconBgClass;
            icon.innerHTML = '<svg class="w-7 h-7 ' + iconColorClass + '" fill="none" stroke="currentColor" viewBox="0 0 24 24">' + (statusIconPath[d.status] || statusIconPath.canceled) + '</svg>';

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
                document.getElementById('modal-intent-text').textContent = d.intent;
                intentEl.dataset.fullId = d.intent;
                intentRow.classList.remove('hidden');
            } else {
                intentRow.classList.add('hidden');
            }

            const payAction = document.getElementById('modal-pay-action');
            if (d.checkoutUrl) {
                document.getElementById('modal-pay-form').action = d.checkoutUrl;
                payAction.classList.remove('hidden');
            } else {
                payAction.classList.add('hidden');
            }

            const receiptAction = document.getElementById('modal-receipt-action');
            if (d.receiptUrl) {
                document.getElementById('modal-receipt-link').href = d.receiptUrl;
                receiptAction.classList.remove('hidden');
            } else {
                receiptAction.classList.add('hidden');
            }

            const refundAction = document.getElementById('modal-refund-action');
            const refundForm = document.getElementById('modal-refund-form');
            refundForm.classList.add('hidden');
            document.getElementById('modal-refund-toggle').classList.remove('hidden');
            refundForm.reset();
            if (d.refundRequestUrl) {
                refundForm.action = d.refundRequestUrl;
                refundAction.classList.remove('hidden');
            } else {
                refundAction.classList.add('hidden');
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(function () {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('scale-95', 'opacity-0');
            });
        };

        window.toggleRefundForm = function () {
            document.getElementById('modal-refund-form').classList.remove('hidden');
            document.getElementById('modal-refund-toggle').classList.add('hidden');
        };

        window.closePaymentModal = function () {
            backdrop.classList.add('opacity-0');
            panel.classList.add('scale-95', 'opacity-0');
            setTimeout(function () {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        };

        const subModal = document.getElementById('subscription-modal');
        const subBackdrop = document.getElementById('subscription-modal-backdrop');
        const subPanel = document.getElementById('subscription-modal-panel');

        window.openSubscriptionModal = function (card) {
            const d = card.dataset;
            const cancelAtPeriodEnd = d.cancelAtPeriodEnd === '1';

            document.getElementById('sub-modal-amount').textContent = d.amount;
            document.getElementById('sub-modal-description').textContent = d.description;
            document.getElementById('sub-modal-started').textContent = d.started;

            const badge = document.getElementById('sub-modal-status-badge');
            const badgeStatus = cancelAtPeriodEnd && d.status === 'active' ? 'past_due' : d.status;
            badge.className = 'inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full ' + (statusColors[badgeStatus] || 'bg-gray-100 text-gray-500');
            badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full ' + (statusDots[badgeStatus] || 'bg-gray-400') + '"></span>' + (cancelAtPeriodEnd && d.status === 'active' ? 'Canceling' : d.statusLabel);

            const periodRow = document.getElementById('sub-modal-period-row');
            if (d.periodEnd) {
                document.getElementById('sub-modal-period-label').textContent = cancelAtPeriodEnd ? 'Cancels On' : 'Renews On';
                document.getElementById('sub-modal-period-end').textContent = d.periodEnd;
                periodRow.classList.remove('hidden');
            } else {
                periodRow.classList.add('hidden');
            }

            const startAction = document.getElementById('sub-modal-start-action');
            if (d.checkoutUrl) {
                document.getElementById('sub-modal-start-form').action = d.checkoutUrl;
                startAction.classList.remove('hidden');
            } else {
                startAction.classList.add('hidden');
            }

            const billingAction = document.getElementById('sub-modal-billing-action');
            if (d.billingPortalUrl) {
                document.getElementById('sub-modal-billing-link').href = d.billingPortalUrl;
                billingAction.classList.remove('hidden');
            } else {
                billingAction.classList.add('hidden');
            }

            subModal.classList.remove('hidden');
            subModal.classList.add('flex');
            requestAnimationFrame(function () {
                subBackdrop.classList.remove('opacity-0');
                subPanel.classList.remove('scale-95', 'opacity-0');
            });
        };

        window.closeSubscriptionModal = function () {
            subBackdrop.classList.add('opacity-0');
            subPanel.classList.add('scale-95', 'opacity-0');
            setTimeout(function () {
                subModal.classList.add('hidden');
                subModal.classList.remove('flex');
            }, 200);
        };

        function bindSubscriptionCard(root) {
            const cards = root.querySelectorAll('.subscription-card');
            const all = root.matches && root.matches('.subscription-card') ? [root, ...cards] : cards;
            all.forEach(function (card) {
                if (card.dataset.cardBound) return;
                card.dataset.cardBound = '1';
                card.addEventListener('click', function () {
                    window.openSubscriptionModal(card);
                });
            });
        }

        bindSubscriptionCard(document);

        subBackdrop?.addEventListener('click', closeSubscriptionModal);

        // No-reload submission for the Maintenance Plan's Refresh Status form: swaps
        // in the freshly rendered card HTML instead of doing a full page navigation.
        function bindAjaxForms(root) {
            root.querySelectorAll('form[data-ajax-target]').forEach(function (form) {
                if (form.dataset.ajaxBound) return;
                form.dataset.ajaxBound = '1';

                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const targetIds = form.dataset.ajaxTarget.split(' ').filter(Boolean);
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalBtnHtml = submitBtn ? submitBtn.innerHTML : null;

                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML =
                            '<svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">' +
                                '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
                                '<path class="opacity-75" fill="currentColor" d="M12 2a10 10 0 0110 10h-4a6 6 0 00-6-6V2z"></path>' +
                            '</svg> Refreshing…';
                        submitBtn.classList.add('inline-flex', 'items-center', 'gap-2');
                    }

                    fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: new FormData(form),
                    })
                        .then(function (response) { return response.text(); })
                        .then(function (html) {
                            const doc = new DOMParser().parseFromString(html, 'text/html');

                            targetIds.forEach(function (id) {
                                const freshEl = doc.getElementById(id);
                                const liveEl = document.getElementById(id);
                                if (freshEl && liveEl) {
                                    liveEl.replaceWith(freshEl);
                                    bindSubscriptionCard(freshEl);
                                    bindAjaxForms(freshEl);
                                }
                            });

                            const flash = doc.getElementById('flash-status-banner');
                            if (flash && window.showToast) {
                                window.showToast(flash.textContent.trim());
                            }
                        })
                        .catch(function () {
                            alert('Something went wrong. Please try again.');
                        })
                        .finally(function () {
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalBtnHtml;
                            }
                        });
                });
            });
        }

        bindAjaxForms(document);

        window.copyTransactionId = function () {
            const el = document.getElementById('modal-intent');
            const textEl = document.getElementById('modal-intent-text');
            const id = el.dataset.fullId;
            if (!id) return;
            navigator.clipboard.writeText(id).then(function () {
                const original = textEl.textContent;
                textEl.textContent = 'Copied!';
                setTimeout(function () { textEl.textContent = original; }, 1200);
            });
        };

        // Status filter — custom-styled dropdown instead of a native <select>,
        // whose browser-drawn option list can't be restyled to match the page.
        // Dispatches a real 'change' event on the hidden input so the existing
        // filter logic below (applyPaymentFilters) needs zero changes.
        (function () {
            const wrap = document.getElementById('payment-status-filter-wrap');
            const toggle = document.getElementById('payment-status-filter-toggle');
            const menu = document.getElementById('payment-status-filter-menu');
            const chevron = document.getElementById('payment-status-filter-chevron');
            const hiddenInput = document.getElementById('payment-status-filter');
            const label = document.getElementById('payment-status-filter-label');
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
                    hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });

            document.addEventListener('click', function (e) {
                if (!wrap.contains(e.target)) closeMenu();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeMenu();
            });
        })();

        const searchInput = document.getElementById('payment-search');
        const statusFilter = document.getElementById('payment-status-filter');
        const emptyState = document.getElementById('payment-empty-state');

        function applyPaymentFilters() {
            if (!searchInput || !statusFilter) return;

            const query = searchInput.value.trim().toLowerCase();
            const status = statusFilter.value;
            let visibleCount = 0;

            document.querySelectorAll('.payment-month-group').forEach(function (group) {
                let groupHasVisible = false;

                group.querySelectorAll('.payment-row').forEach(function (row) {
                    const matchesQuery = !query || row.dataset.search.includes(query);
                    const matchesStatus = !status || row.dataset.status === status;
                    const visible = matchesQuery && matchesStatus;

                    row.classList.toggle('hidden', !visible);
                    if (visible) {
                        groupHasVisible = true;
                        visibleCount++;
                    }
                });

                group.classList.toggle('hidden', !groupHasVisible);
            });

            emptyState.classList.toggle('hidden', visibleCount > 0);
        }

        searchInput?.addEventListener('input', applyPaymentFilters);
        statusFilter?.addEventListener('change', applyPaymentFilters);

        document.querySelectorAll('.payment-row').forEach(function (row) {
            // Care Plan invoice rows are plain links to their own receipt
            // page (no refund/pay affordances to show), so they skip the
            // transaction detail modal built for one-time Payment rows.
            if (row.dataset.kind === 'subscription_payment') return;

            row.addEventListener('click', function () {
                window.openPaymentModal(row);
            });
        });

        backdrop?.addEventListener('click', closePaymentModal);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closePaymentModal();
                closeSubscriptionModal();
            }
        });
    })();
</script>

@endsection
