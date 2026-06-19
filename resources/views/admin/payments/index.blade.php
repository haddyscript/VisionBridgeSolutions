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
    $totalPaid = $payments->where('status', 'paid')->sum('amount');
    $totalPending = $payments->where('status', 'pending')->sum('amount');
@endphp

{{-- Summary hero --}}
<div class="relative overflow-hidden rounded-2xl p-8 mb-8" style="background:linear-gradient(135deg,#111D33,#1B2A4A 60%,#1B2A4A);">
    <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full" style="background:radial-gradient(circle,rgba(201,168,76,0.18) 0%,transparent 70%);"></div>
    <div class="absolute -bottom-20 -left-10 w-56 h-56 rounded-full" style="background:radial-gradient(circle,rgba(42,157,143,0.14) 0%,transparent 70%);"></div>

    <div class="relative">
        <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-2">Payments Overview</p>
        <h2 class="font-display text-2xl font-bold text-white mb-6">All client payment activity</h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                <p class="text-xs font-medium uppercase tracking-wide text-white/40 mb-1.5">Total Collected</p>
                <p class="font-display text-2xl font-bold text-white">${{ number_format($totalPaid / 100, 2) }}</p>
            </div>
            <div class="rounded-xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                <p class="text-xs font-medium uppercase tracking-wide text-white/40 mb-1.5">Outstanding</p>
                <p class="font-display text-2xl font-bold text-white">${{ number_format($totalPending / 100, 2) }}</p>
            </div>
            <div class="rounded-xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                <p class="text-xs font-medium uppercase tracking-wide text-white/40 mb-1.5">Total Requests</p>
                <p class="font-display text-2xl font-bold text-white">{{ $payments->count() }}</p>
            </div>
        </div>
    </div>
</div>

@if ($payments->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No payment requests yet.</p>
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
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
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $payment->project->name }}</p>
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
                    <span class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">Client</span>
                    <span id="modal-client" class="text-sm font-semibold text-navy dark:text-white"></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">Project</span>
                    <span id="modal-project" class="text-sm font-semibold text-navy dark:text-white"></span>
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
