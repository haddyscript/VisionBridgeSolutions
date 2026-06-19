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
            'pending' => 'bg-gold/15 text-gold-dark',
            'paid' => 'bg-teal/15 text-teal-dark',
            'active' => 'bg-teal/15 text-teal-dark',
            'past_due' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
            'failed' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
            'canceled' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
        ];
        $statusDots = [
            'pending' => 'bg-gold',
            'paid' => 'bg-teal',
            'active' => 'bg-teal',
            'past_due' => 'bg-red-500',
            'failed' => 'bg-red-500',
            'canceled' => 'bg-gray-400',
        ];
        $totalDue = $payments->where('status', 'pending')->sum('amount');
        $totalPaid = $payments->where('status', 'paid')->sum('amount');
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
                    <p class="text-xs font-medium uppercase tracking-wide text-white/40 mb-1.5">Amount Due</p>
                    <p class="font-display text-2xl font-bold text-white">${{ number_format($totalDue / 100, 2) }}</p>
                </div>
                <div class="rounded-xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                    <p class="text-xs font-medium uppercase tracking-wide text-white/40 mb-1.5">Total Paid</p>
                    <p class="font-display text-2xl font-bold text-white">${{ number_format($totalPaid / 100, 2) }}</p>
                </div>
                <div class="rounded-xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                    <p class="text-xs font-medium uppercase tracking-wide text-white/40 mb-1.5">Maintenance Plan</p>
                    @if ($subscription && ! $subscription->isCanceled())
                        <p class="font-display text-2xl font-bold text-white">{{ $subscription->isActive() ? 'Active' : ucfirst($subscription->status) }}</p>
                    @else
                        <p class="font-display text-2xl font-bold text-white/40">None</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Maintenance Plan --}}
    @if ($subscription && ! $subscription->isCanceled())
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 mb-6 hover:shadow-md transition-shadow">
            <div class="flex flex-wrap items-center justify-between gap-5">
                <div class="flex items-center gap-4">
                    <span class="w-12 h-12 rounded-xl bg-teal/10 flex items-center justify-center shrink-0">
                        <svg class="w-[1.375rem] h-[1.375rem] text-teal-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </span>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-0.5">Maintenance Plan</p>
                        <p class="font-display text-lg font-bold text-navy dark:text-white">{{ $subscription->description }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ $subscription->formattedAmount() }}
                            @if ($subscription->current_period_end)
                                &middot; renews {{ $subscription->current_period_end->format('M j, Y') }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full {{ $statusColors[$subscription->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$subscription->status] ?? 'bg-gray-400' }}"></span>
                        {{ $subscription->status === 'past_due' ? 'Past Due' : ucfirst($subscription->status) }}
                    </span>
                    @if ($subscription->isPending())
                        <form method="POST" action="{{ route('portal.subscriptions.checkout', $subscription) }}">
                            @csrf
                            <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                                Start Plan
                            </button>
                        </form>
                    @else
                        <a href="{{ route('portal.billing-portal') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 px-4 py-2.5 rounded-lg transition-colors">
                            Manage Billing
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- One-Time Payments --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Payment History</h3>

        @if ($payments->isEmpty())
            <div class="text-center py-10">
                <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h5M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/></svg>
                </div>
                <p class="text-sm text-gray-400 dark:text-gray-500">No payment requests yet. Your VisionBridge representative will let you know when one is ready.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($payments as $payment)
                    <div class="payment-row group relative flex flex-wrap items-center justify-between gap-4 rounded-xl border border-gray-100 dark:border-gray-700/60 px-5 py-4 cursor-pointer transition-all hover:border-gold/40 hover:shadow-lg hover:-translate-y-0.5"
                         data-description="{{ $payment->description }}"
                         data-amount="{{ $payment->formattedAmount() }}"
                         data-status="{{ $payment->status }}"
                         data-status-label="{{ $payment->status === 'past_due' ? 'Past Due' : ucfirst($payment->status) }}"
                         data-currency="{{ strtoupper($payment->currency) }}"
                         data-created="{{ $payment->created_at->format('M j, Y \a\t g:i A') }}"
                         data-paid-at="{{ $payment->paid_at?->format('M j, Y \a\t g:i A') }}"
                         data-intent="{{ $payment->stripe_payment_intent_id }}"
                         data-session="{{ $payment->stripe_checkout_session_id }}"
                         data-checkout-url="{{ $payment->isPending() ? route('portal.payments.checkout', $payment) : '' }}">
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
                            <span class="font-display text-lg font-bold text-navy dark:text-white">{{ $payment->formattedAmount() }}</span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full {{ $statusColors[$payment->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$payment->status] ?? 'bg-gray-400' }}"></span>
                                {{ ucfirst($payment->status) }}
                            </span>
                            @if ($payment->isPending())
                                <form method="POST" action="{{ route('portal.payments.checkout', $payment) }}" onclick="event.stopPropagation()">
                                    @csrf
                                    <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                                        Pay Now
                                    </button>
                                </form>
                            @endif
                            <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 group-hover:text-gold transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

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
                        <button type="button" onclick="copyTransactionId()" id="modal-intent" class="text-sm font-mono text-navy dark:text-white truncate hover:text-gold transition-colors" title="Click to copy"></button>
                    </div>

                    <div id="modal-pay-action" class="hidden pt-2">
                        <form id="modal-pay-form" method="POST" action="#">
                            @csrf
                            <button type="submit" class="block w-full text-center bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-3 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                                Pay Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif

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
            document.getElementById('modal-currency').textContent = d.currency;
            document.getElementById('modal-created').textContent = d.created;

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

            const payAction = document.getElementById('modal-pay-action');
            if (d.checkoutUrl) {
                document.getElementById('modal-pay-form').action = d.checkoutUrl;
                payAction.classList.remove('hidden');
            } else {
                payAction.classList.add('hidden');
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
