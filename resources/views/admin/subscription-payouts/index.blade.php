@extends('layouts.admin')

@section('title', 'FaithStack Payouts – Admin')
@section('page-title', 'FaithStack Payouts')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    One row per Website Care Plan billing cycle. Per our partnership agreement, FaithStack's recurring compensation
    is sent manually after VisionBridge verifies the client's payment cleared — mark a row paid once you've sent it.
</p>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 mb-6 flex items-center justify-between">
    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pending FaithStack Payouts</p>
    <p class="font-display text-2xl font-bold text-navy dark:text-white">${{ number_format($totalPending / 100, 2) }}</p>
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
                            @else
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-gold/15 text-gold-dark">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            @unless ($payout->isPaid())
                                <form method="POST" action="{{ route('admin.subscription-payouts.update', $payout) }}" onsubmit="return confirm('Confirm you have sent FaithStack {{ $payout->formattedFaithstackAmount() }} for this billing cycle?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-gold-dark font-semibold hover:underline">Mark Paid to FaithStack</button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
