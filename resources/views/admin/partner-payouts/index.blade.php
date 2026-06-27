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

@if ($payouts->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No payments recorded yet.</p>
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">For</th>
                    <th class="px-5 py-3">Client Paid</th>
                    <th class="px-5 py-3">FaithStack Owed</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($payouts as $payout)
                    @php $project = $payout->project(); @endphp
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white">{{ $project?->user->name ?? '—' }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $project?->name ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $payout->sourceLabel() }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $payout->formattedClientAmount() }}</td>
                        <td class="px-5 py-3.5 font-semibold {{ $payout->hasFaithstackAmount() ? 'text-navy dark:text-white' : 'text-gold-dark' }}">
                            {{ $payout->formattedFaithstackAmount() }}
                        </td>
                        <td class="px-5 py-3.5">
                            @if ($payout->isPaid())
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-teal/15 text-teal-dark">
                                    Paid {{ $payout->paid_at?->format('M j, Y') }}
                                </span>
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
                                    Verifying — {{ $payout->daysUntilReady() }}d left
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            @if ($payout->isReady() || $payout->isFlagged())
                                <form method="POST" action="{{ route('admin.partner-payouts.update', $payout) }}" class="flex items-center justify-end gap-2"
                                      onsubmit="return confirm('{{ $payout->isFlagged() ? 'This payout was flagged ('.$payout->flag_reason.'). Send FaithStack anyway?' : 'Confirm you have sent FaithStack this amount?' }}')">
                                    @csrf
                                    @method('PATCH')
                                    @unless ($payout->hasFaithstackAmount())
                                        <input type="number" name="faithstack_amount" step="0.01" min="0" placeholder="Amount" required
                                               class="w-24 rounded-lg border border-gray-300 dark:border-gray-600 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                                    @endunless
                                    <button type="submit" class="{{ $payout->isFlagged() ? 'text-red-500' : 'text-gold-dark' }} font-semibold hover:underline whitespace-nowrap">
                                        {{ $payout->isFlagged() ? 'Send Anyway' : 'Mark Paid' }}
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
