@extends('layouts.admin')

@section('title', 'FaithStack Payouts – Admin')
@section('page-title', 'FaithStack Payouts')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    One row per Website Care Plan billing cycle. Each payout sits in a 7-day verification window after the client's
    payment clears — if a refund or dispute comes in during that window, it's automatically held for review.
    Once it's "Ready to Send," the transfer to FaithStack is still sent manually (Stripe can't pay out to the
    Philippines yet) — mark it paid once you've sent it.
</p>

<div class="grid sm:grid-cols-2 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Still Verifying</p>
        <p class="font-display text-2xl font-bold text-navy dark:text-white">${{ number_format($totalVerifying / 100, 2) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Ready to Send</p>
        <p class="font-display text-2xl font-bold text-teal-dark">${{ number_format($totalReady / 100, 2) }}</p>
    </div>
</div>

@if ($payouts->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No billing cycles recorded yet.</p>
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Plan</th>
                    <th class="px-5 py-3">Client Paid</th>
                    <th class="px-5 py-3">FaithStack Owed</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($payouts as $payout)
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white">{{ $payout->subscription->project->user->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $payout->subscription->project->name }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $payout->subscription->maintenancePlan?->name ?? $payout->subscription->description }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $payout->formattedClientAmount() }}</td>
                        <td class="px-5 py-3.5 font-semibold text-navy dark:text-white">{{ $payout->formattedFaithstackAmount() }}</td>
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
                            @if ($payout->isFlagged())
                                <form method="POST" action="{{ route('admin.subscription-payouts.update', $payout) }}" onsubmit="return confirm('This payout was flagged ({{ $payout->flag_reason }}). Send FaithStack {{ $payout->formattedFaithstackAmount() }} anyway?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-red-500 font-semibold hover:underline">Send Anyway</button>
                                </form>
                            @elseif ($payout->isReady())
                                <form method="POST" action="{{ route('admin.subscription-payouts.update', $payout) }}" onsubmit="return confirm('Confirm you have sent FaithStack {{ $payout->formattedFaithstackAmount() }} for this billing cycle?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-gold-dark font-semibold hover:underline">Mark Paid to FaithStack</button>
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
