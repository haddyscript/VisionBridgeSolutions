@extends('layouts.admin')

@section('title', 'FaithStack Payouts – Admin')
@section('page-title', 'FaithStack Payouts')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    One row per client payment — recurring Website Care Plan cycles and one-time project payments alike. Each sits
    in a 7-day verification window after it clears; a refund or dispute during that window automatically holds it
    for review. The transfer to FaithStack is still sent manually (Stripe can't pay out to the Philippines yet) —
    mark a row paid once you've sent it.
</p>

{{-- FaithStack Payout — Per Care Plan --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 mb-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-2">FaithStack Payout — Per Care Plan</p>
            @if ($carePlans->isEmpty())
                <p class="text-sm text-gold-dark font-medium">No Care Plans configured yet.</p>
            @else
                <div class="flex flex-wrap gap-x-6 gap-y-1">
                    @foreach ($carePlans as $plan)
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-semibold text-navy dark:text-white">{{ $plan->name }}</span>
                            — {{ $plan->faithstack_compensation !== null ? $plan->formattedFaithstackCompensation() : 'not set' }}
                        </p>
                    @endforeach
                </div>
            @endif
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                Set per plan on the <a href="{{ route('admin.care-plans.index') }}" class="text-gold-dark hover:underline">Care Plan Pricing</a> page —
                auto-fills the FaithStack Owed amount on every new recurring payout going forward. One-time project payments are always entered manually below.
            </p>
        </div>
        <form method="POST" action="{{ route('admin.partner-payouts.recalculate') }}" class="self-end"
              onsubmit="return confirm('This will fill in the FaithStack amount for every still-verifying Care Plan row using each plan\'s current payout amount. Continue?')">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:border-gold hover:text-gold text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Recalculate Care Plan Rows
            </button>
        </form>
    </div>
</div>

@if (auth()->user()->isSuperAdmin())
{{-- Log a Manual/Historical Payment — super-admin only, for direct fees paid to FaithStack outside the client-revenue-share flow (e.g. the original one-time website build), including backdated entries --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 mb-6">
    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-3">Log a Manual or Historical Payment to FaithStack</p>
    <form method="POST" action="{{ route('admin.partner-payouts.store') }}" enctype="multipart/form-data" class="grid sm:grid-cols-2 gap-3">
        @csrf
        <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Description</label>
            <input type="text" name="description" required placeholder="e.g. VisionBridge website development payment"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Amount (USD)</label>
            <input type="number" name="client_amount" step="0.01" min="0" required placeholder="300.00"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Payment Date</label>
            <input type="date" name="paid_at" required
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
        </div>
        <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Notes (reference/transaction #, payment method, original currency amount, etc.)</label>
            <textarea name="notes" rows="2" placeholder="e.g. Ref #12345, paid via bank transfer to GCash, original amount ₱17,000 PHP"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white"></textarea>
        </div>
        <div class="sm:col-span-2 receipts-field" data-max="3">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Receipts (image or PDF, up to 3, optional)</label>
            <label class="inline-flex items-center gap-1.5 cursor-pointer px-3 py-1.5 rounded-lg bg-gold/15 text-gold-dark text-sm font-semibold hover:bg-gold/25 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                <span class="receipts-field-label">Choose Receipts</span>
                <input type="file" name="receipts[]" accept=".jpg,.jpeg,.png,.pdf" multiple class="receipts-input hidden">
            </label>
            <div class="receipts-preview mt-2 flex flex-wrap gap-1.5"></div>
        </div>
        <div class="sm:col-span-2 flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-navy hover:bg-navy-light text-white text-sm font-semibold rounded-lg transition-colors">
                Log Payment
            </button>
        </div>
    </form>
</div>
@endif

{{-- FaithStack Payment Reminders --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 mb-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">Payment Reminders</p>
            @if ($faithstackDueDay)
                <p class="font-display text-2xl font-bold text-navy dark:text-white">Day {{ $faithstackDueDay }} of each month</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Emails {{ str_replace(',', ', ', $faithstackReminderEmail) }} 5 days before, on the due date, and then every day it stays unpaid — whenever there's a ready-to-send balance.</p>
            @else
                <p class="text-sm text-gold-dark font-medium">No due day set — automatic reminders are off.</p>
            @endif
        </div>
        <form method="POST" action="{{ route('admin.partner-payouts.set-reminder-settings') }}" class="flex flex-wrap items-end gap-3">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Due Day of Month</label>
                <input type="number" name="faithstack_payment_due_day" min="1" max="28"
                    value="{{ old('faithstack_payment_due_day', $faithstackDueDay ?: '') }}"
                    placeholder="e.g. 5"
                    class="w-24 rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Reminder Email(s)</label>
                <input type="text" name="faithstack_reminder_email"
                    value="{{ old('faithstack_reminder_email', $faithstackReminderEmail) }}"
                    placeholder="you@example.com, other@example.com"
                    class="w-72 rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                @error('faithstack_reminder_email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-navy hover:bg-navy-light text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save
            </button>
        </form>
    </div>
</div>

<div class="grid sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Still Verifying</p>
        <p class="font-display text-2xl font-bold text-navy dark:text-white">${{ number_format($totalVerifying / 100, 2) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Ready to Send</p>
        <p class="font-display text-2xl font-bold text-teal-dark">${{ number_format($totalReady / 100, 2) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Amount Not Yet Decided</p>
        <p class="font-display text-2xl font-bold text-gold-dark">{{ $totalUndecided }}</p>
    </div>
</div>

@php
    $payoutStatusLabels = ['pending' => 'Verifying', 'ready' => 'Ready to Send', 'flagged' => 'Held', 'paid' => 'Paid'];
    $payoutStatusDots = ['pending' => 'bg-gold', 'ready' => 'bg-teal', 'flagged' => 'bg-red-500', 'paid' => 'bg-teal'];
@endphp

@if ($payouts->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No payments recorded yet.</p>
    </div>
@else
    {{-- Search + status filter toolbar --}}
    <div class="flex flex-wrap items-center gap-3 mb-4">
        <div class="relative flex-1 min-w-[200px]">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
            <input type="text" id="payout-search" placeholder="Search by client or project name..."
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
        </div>
        <div class="relative w-full sm:w-48" id="payout-status-filter-wrap">
            <input type="hidden" id="payout-status-filter" value="">

            <button type="button" id="payout-status-filter-toggle" aria-haspopup="listbox" aria-expanded="false"
                    class="w-full flex items-center justify-between gap-2 rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm text-left focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                <span id="payout-status-filter-label" class="flex items-center gap-2 min-w-0 truncate text-navy dark:text-white">All Statuses</span>
                <svg id="payout-status-filter-chevron" class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div id="payout-status-filter-menu" class="hidden absolute z-20 left-0 right-0 mt-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                @foreach (['' => 'All Statuses'] + $payoutStatusLabels as $value => $label)
                    <button type="button" data-status-option="{{ $value }}" role="option" aria-selected="{{ $value === '' ? 'true' : 'false' }}"
                            class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $value === '' ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                        <span class="flex items-center gap-2">
                            @if ($value)
                                <span class="w-2 h-2 rounded-full shrink-0 {{ $payoutStatusDots[$value] ?? 'bg-gray-400' }}"></span>
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

    <p id="payout-empty-state" class="hidden text-sm text-gray-400 dark:text-gray-500 text-center py-6">No payments match your search.</p>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <tr>
                    <th class="px-5 py-3">
                        <button type="button" data-sort-key="client" class="payout-sort-btn inline-flex items-center gap-1 hover:text-navy dark:hover:text-white transition-colors">
                            Client
                            <svg class="w-3 h-3 sort-icon opacity-30 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </th>
                    <th class="px-5 py-3">For</th>
                    <th class="px-5 py-3">
                        <button type="button" data-sort-key="date" class="payout-sort-btn inline-flex items-center gap-1 hover:text-navy dark:hover:text-white transition-colors">
                            Date
                            <svg class="w-3 h-3 sort-icon opacity-30 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </th>
                    <th class="px-5 py-3">
                        <button type="button" data-sort-key="client-amount" class="payout-sort-btn inline-flex items-center gap-1 hover:text-navy dark:hover:text-white transition-colors">
                            Client Paid
                            <svg class="w-3 h-3 sort-icon opacity-30 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </th>
                    <th class="px-5 py-3">
                        <button type="button" data-sort-key="faithstack-amount" class="payout-sort-btn inline-flex items-center gap-1 hover:text-navy dark:hover:text-white transition-colors">
                            FaithStack Owed
                            <svg class="w-3 h-3 sort-icon opacity-30 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody id="payout-tbody" class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($payouts as $payout)
                    @php $project = $payout->project(); @endphp
                    <tr class="payout-row hover:bg-gray-50/60 cursor-pointer" data-modal="payout-modal-{{ $payout->id }}"
                        data-client="{{ strtolower($project?->user->name ?? '') }}"
                        data-project="{{ strtolower($project?->name ?? '') }}"
                        data-status="{{ $payout->status }}"
                        data-date="{{ $payout->created_at->timestamp }}"
                        data-client-amount="{{ $payout->client_amount }}"
                        data-faithstack-amount="{{ $payout->faithstack_amount ?? -1 }}">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white">{{ $project?->user->name ?? 'FaithStack (Direct)' }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $project?->name ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">
                            {{ $payout->sourceLabel() }}
                            @if ($payout->hasReceipts())
                                <span class="inline-flex items-center gap-1 ml-1.5 text-xs text-gray-400 dark:text-gray-500 align-middle">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    {{ $payout->receipts->count() }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $payout->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $payout->formattedClientAmount() }}</td>
                        <td class="px-5 py-3.5 font-semibold {{ $payout->hasFaithstackAmount() ? 'text-navy dark:text-white' : 'text-gold-dark' }}">
                            {{ $payout->formattedFaithstackAmount() }}
                        </td>
                        <td class="px-5 py-3.5">
                            @if ($payout->isPaid())
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-teal/15 text-teal-dark">
                                    Paid {{ $payout->paid_at?->format('M j, Y') }}
                                </span>
                                @if ($payout->wasEdited())
                                    <span class="block text-[11px] text-gray-400 dark:text-gray-500 mt-1" title="{{ $payout->edited_at->format('M j, Y g:ia') }}">
                                        Edited {{ $payout->edited_at->format('M j, Y') }}@if ($payout->editedBy) by {{ $payout->editedBy->name }} @endif
                                    </span>
                                @endif
                            @elseif ($payout->isFlagged())
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500" title="{{ $payout->flag_reason }}">
                                    Held — {{ $payout->flag_reason }}
                                </span>
                            @elseif ($payout->isReady())
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-teal/15 text-teal-dark">
                                    Ready to Send
                                </span>
                            @else
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-gold/15 text-gold-dark">
                                    Verifying — {{ $payout->timeUntilReady() }}
                                </span>
                            @endif
                        </td>
                        <td class="payout-row-actions px-5 py-3.5 text-right">
                            @if ($payout->isReady() || $payout->isFlagged())
                                <button type="button" data-modal="mark-paid-modal-{{ $payout->id }}"
                                        class="modal-trigger {{ $payout->isFlagged() ? 'text-red-500' : 'text-gold-dark' }} font-semibold hover:underline whitespace-nowrap">
                                    {{ $payout->isFlagged() ? 'Send Anyway' : 'Mark Paid' }}
                                </button>
                            @elseif ($payout->isPaid() && auth()->user()->isSuperAdmin())
                                <button type="button" data-modal="mark-paid-modal-{{ $payout->id }}"
                                        class="modal-trigger text-gray-400 dark:text-gray-500 hover:text-gold-dark font-semibold hover:underline whitespace-nowrap">
                                    Edit
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Payout detail modals — one per row, opened by clicking anywhere on the row except the actions cell. Same pattern as the admin Team page's access modal. --}}
    @foreach ($payouts as $payout)
        @php $modalProject = $payout->project(); @endphp
        <div id="payout-modal-{{ $payout->id }}" class="payout-modal hidden fixed inset-0 z-[60] items-center justify-center bg-black/40 px-4">
            <div class="payout-modal-panel bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xl w-full max-w-lg max-h-[85vh] overflow-y-auto">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <p class="font-semibold text-navy dark:text-white">{{ $modalProject?->user->name ?? 'FaithStack (Direct)' }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $modalProject?->name ?? $payout->sourceLabel() }}</p>
                    </div>
                    <button type="button" class="payout-modal-close w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors shrink-0" aria-label="Close">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-5 space-y-4 text-sm">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">For</p>
                            <p class="text-navy dark:text-white">{{ $payout->sourceLabel() }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Date</p>
                            <p class="text-navy dark:text-white">{{ $payout->created_at->format('M j, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Client Paid</p>
                            <p class="text-navy dark:text-white">{{ $payout->formattedClientAmount() }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">FaithStack Owed</p>
                            <p class="text-navy dark:text-white">{{ $payout->formattedFaithstackAmount() }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Status</p>
                            <p class="text-navy dark:text-white">
                                @if ($payout->isPaid())
                                    Paid {{ $payout->paid_at?->format('M j, Y') }}
                                @elseif ($payout->isFlagged())
                                    Held — {{ $payout->flag_reason }}
                                @elseif ($payout->isReady())
                                    Ready to Send
                                @else
                                    Verifying — {{ $payout->timeUntilReady() }}
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($payout->notes)
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1">Notes</p>
                            <p class="text-gray-600 dark:text-gray-300 whitespace-pre-wrap">{{ $payout->notes }}</p>
                        </div>
                    @endif

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-2">Receipts</p>
                        @if ($payout->receipts->isEmpty())
                            <p class="text-gray-400 dark:text-gray-500 text-xs">No receipt uploaded.</p>
                        @else
                            <div class="grid grid-cols-3 gap-2">
                                @foreach ($payout->receipts as $receipt)
                                    <a href="{{ route('admin.partner-payouts.receipts.show', $receipt) }}" target="_blank"
                                       class="block aspect-square rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:border-gold transition-colors">
                                        @if ($receipt->isImage())
                                            <img src="{{ route('admin.partner-payouts.receipts.show', $receipt) }}" class="w-full h-full object-cover" alt="Receipt">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-50 dark:bg-gray-900 text-gray-400">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ($payout->isReady() || $payout->isFlagged() || ($payout->isPaid() && auth()->user()->isSuperAdmin()))
            @php $isEditingPaid = $payout->isPaid(); @endphp
            {{-- Mark Paid / Edit modal — date paid, FaithStack amount, and up to 3 receipts. Editing an already-paid row is super-admin only. --}}
            <div id="mark-paid-modal-{{ $payout->id }}" class="payout-modal hidden fixed inset-0 z-[60] items-center justify-center bg-black/40 px-4">
                <div class="payout-modal-panel bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xl w-full max-w-md max-h-[85vh] overflow-y-auto">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                        <p class="font-semibold text-navy dark:text-white">{{ $isEditingPaid ? 'Edit Payout' : ($payout->isFlagged() ? 'Send Anyway' : 'Mark Paid') }} — {{ $modalProject?->user->name ?? 'FaithStack (Direct)' }}</p>
                        <button type="button" class="payout-modal-close w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors shrink-0" aria-label="Close">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('admin.partner-payouts.update', $payout) }}" enctype="multipart/form-data" class="p-5 space-y-4">
                        @csrf
                        @method('PATCH')

                        @if ($isEditingPaid)
                            <p class="text-xs text-gold-dark bg-gold/10 rounded-lg px-3 py-2">This payout was already marked paid — changes here are recorded with an "edited" timestamp for the record.</p>
                        @elseif ($payout->isFlagged())
                            <p class="text-xs text-red-500 bg-red-50 dark:bg-red-500/10 rounded-lg px-3 py-2">This payout was flagged — {{ $payout->flag_reason }}. Confirm you still want to send FaithStack this amount.</p>
                        @endif

                        @if ($isEditingPaid || ! $payout->hasFaithstackAmount())
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">FaithStack Amount (USD)</label>
                                <input type="number" name="faithstack_amount" step="0.01" min="0" placeholder="Amount" required
                                       value="{{ $payout->hasFaithstackAmount() ? number_format($payout->faithstack_amount / 100, 2, '.', '') : '' }}"
                                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Date Paid</label>
                            <input type="date" name="paid_at" required max="{{ now()->format('Y-m-d') }}" value="{{ $payout->paid_at?->format('Y-m-d') ?? now()->format('Y-m-d') }}"
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                        </div>

                        @if ($isEditingPaid && $payout->receipts->isNotEmpty())
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Already Attached</p>
                                <div class="grid grid-cols-4 gap-1.5">
                                    @foreach ($payout->receipts as $receipt)
                                        <a href="{{ route('admin.partner-payouts.receipts.show', $receipt) }}" target="_blank"
                                           class="block aspect-square rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:border-gold transition-colors">
                                            @if ($receipt->isImage())
                                                <img src="{{ route('admin.partner-payouts.receipts.show', $receipt) }}" class="w-full h-full object-cover" alt="Receipt">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-50 dark:bg-gray-900 text-gray-400">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                </div>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="receipts-field" data-max="3">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ $isEditingPaid ? 'Add More Receipts (optional)' : 'Receipts (image or PDF, up to 3, optional)' }}</label>
                            <label class="inline-flex items-center gap-1.5 cursor-pointer px-3 py-1.5 rounded-lg bg-gold/15 text-gold-dark text-sm font-semibold hover:bg-gold/25 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                <span class="receipts-field-label">Choose Receipts</span>
                                <input type="file" name="receipts[]" accept=".jpg,.jpeg,.png,.pdf" multiple class="receipts-input hidden">
                            </label>
                            <div class="receipts-preview mt-2 flex flex-wrap gap-1.5"></div>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" class="payout-modal-close px-4 py-2 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-navy hover:bg-navy-light text-white text-sm font-semibold rounded-lg transition-colors">
                                {{ $isEditingPaid ? 'Save Changes' : ($payout->isFlagged() ? 'Send Anyway' : 'Mark Paid') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach
@endif

<script>
    // FaithStack Payouts: search, status filter, and sortable columns —
    // same client-side pattern as the Care Plans tab on the Payments page,
    // minus the grouped history-row handling (payout rows don't expand).
    (function () {
        const tbody = document.getElementById('payout-tbody');
        if (!tbody) return;

        const searchInput = document.getElementById('payout-search');
        const statusFilter = document.getElementById('payout-status-filter');
        const emptyState = document.getElementById('payout-empty-state');

        function applyPayoutFilters() {
            const query = (searchInput?.value || '').trim().toLowerCase();
            const status = statusFilter?.value || '';
            let visibleCount = 0;

            tbody.querySelectorAll('.payout-row').forEach((row) => {
                const matchesQuery = !query || row.dataset.client.includes(query) || row.dataset.project.includes(query);
                const matchesStatus = !status || row.dataset.status === status;
                const visible = matchesQuery && matchesStatus;

                row.classList.toggle('hidden', !visible);
                if (visible) visibleCount++;
            });

            if (emptyState) emptyState.classList.toggle('hidden', visibleCount > 0);
        }

        searchInput?.addEventListener('input', applyPayoutFilters);

        // Status filter dropdown — same custom toggle/menu pattern used elsewhere.
        (function () {
            const wrap = document.getElementById('payout-status-filter-wrap');
            const toggle = document.getElementById('payout-status-filter-toggle');
            const menu = document.getElementById('payout-status-filter-menu');
            const chevron = document.getElementById('payout-status-filter-chevron');
            const hiddenInput = document.getElementById('payout-status-filter');
            const label = document.getElementById('payout-status-filter-label');
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
                    applyPayoutFilters();
                });
            });

            document.addEventListener('click', function (e) {
                if (!wrap.contains(e.target)) closeMenu();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeMenu();
            });
        })();

        // Sorting.
        let currentSort = { key: null, dir: 1 };
        const numericKeys = ['date', 'client-amount', 'faithstack-amount'];

        function sortPayoutsBy(key) {
            currentSort = currentSort.key === key ? { key: key, dir: -currentSort.dir } : { key: key, dir: 1 };

            const isNumeric = numericKeys.includes(key);
            const rows = Array.from(tbody.querySelectorAll('.payout-row'));

            rows.sort((a, b) => {
                const valA = isNumeric ? Number(a.dataset[toCamel(key)]) : a.dataset[toCamel(key)];
                const valB = isNumeric ? Number(b.dataset[toCamel(key)]) : b.dataset[toCamel(key)];
                if (valA < valB) return -1 * currentSort.dir;
                if (valA > valB) return 1 * currentSort.dir;
                return 0;
            });

            rows.forEach((row) => tbody.appendChild(row));

            document.querySelectorAll('.payout-sort-btn .sort-icon').forEach((icon) => {
                icon.classList.add('opacity-30');
                icon.classList.remove('rotate-180');
            });
            const activeBtn = document.querySelector('.payout-sort-btn[data-sort-key="' + key + '"] .sort-icon');
            if (activeBtn) {
                activeBtn.classList.remove('opacity-30');
                activeBtn.classList.toggle('rotate-180', currentSort.dir === -1);
            }
        }

        function toCamel(key) {
            return key.replace(/-([a-z])/g, (_, c) => c.toUpperCase());
        }

        document.querySelectorAll('.payout-sort-btn').forEach((btn) => {
            btn.addEventListener('click', () => sortPayoutsBy(btn.dataset.sortKey));
        });
    })();

    // Open a payout's detail modal when its row is clicked — but ignore
    // clicks that land on the Mark Paid form (or anything inside it).
    // Same pattern as the admin Team page's access modal.
    document.querySelectorAll('.payout-row').forEach((row) => {
        row.addEventListener('click', (e) => {
            if (e.target.closest('.payout-row-actions')) return;
            const modal = document.getElementById(row.dataset.modal);
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    function closePayoutModal(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // "Mark Paid" / "Send Anyway" buttons open their own modal instead of
    // submitting an inline row form directly.
    document.querySelectorAll('.modal-trigger').forEach((trigger) => {
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const modal = document.getElementById(trigger.dataset.modal);
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    document.querySelectorAll('.payout-modal').forEach((modal) => {
        modal.addEventListener('click', (e) => {
            if (!e.target.closest('.payout-modal-panel')) closePayoutModal(modal);
        });
        modal.querySelectorAll('.payout-modal-close').forEach((btn) => {
            btn.addEventListener('click', () => closePayoutModal(modal));
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('.payout-modal:not(.hidden)').forEach(closePayoutModal);
    });

    // Receipt pickers (manual-payment form + each row's Mark Paid form) —
    // the native multi-file input only shows a truncated "N files" label with
    // no way to see what's actually selected, so this renders a removable
    // filename chip per file plus a live "X of Y selected" count instead.
    document.querySelectorAll('.receipts-field').forEach((field) => {
        const input = field.querySelector('.receipts-input');
        const preview = field.querySelector('.receipts-preview');
        const label = field.querySelector('.receipts-field-label');
        const max = Number(field.dataset.max || 3);
        const defaultLabel = label ? label.textContent : '';

        function render() {
            const files = Array.from(input.files);
            preview.innerHTML = '';

            files.forEach((file, index) => {
                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-1 pl-2 pr-1 py-1 rounded-full bg-teal/15 text-teal-dark text-xs max-w-[9rem]';

                const name = document.createElement('span');
                name.className = 'truncate';
                name.textContent = file.name;
                chip.appendChild(name);

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'shrink-0 w-3.5 h-3.5 rounded-full flex items-center justify-center hover:bg-teal/30 leading-none';
                removeBtn.setAttribute('aria-label', 'Remove ' + file.name);
                removeBtn.textContent = '×';
                removeBtn.addEventListener('click', () => {
                    const dt = new DataTransfer();
                    Array.from(input.files).forEach((f, i) => {
                        if (i !== index) dt.items.add(f);
                    });
                    input.files = dt.files;
                    render();
                });
                chip.appendChild(removeBtn);

                preview.appendChild(chip);
            });

            if (label) {
                label.textContent = files.length ? `${files.length} of ${max} selected` : defaultLabel;
            }
        }

        input.addEventListener('change', () => {
            if (input.files.length > max) {
                window.alert(`You can attach up to ${max} receipts.`);
                const dt = new DataTransfer();
                Array.from(input.files).slice(0, max).forEach((f) => dt.items.add(f));
                input.files = dt.files;
            }
            render();
        });
    });
</script>

@endsection
