@extends('layouts.admin')

@section('title', 'Revenue – Admin')
@section('page-title', 'Revenue')

@section('content')

@php
    $money = fn ($cents) => \App\Http\Controllers\Admin\RevenueController::money($cents);
    $card = 'bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700';
    $maxMonth = max(1, $months->max(fn ($m) => $m['one_time'] + $m['care_plan']));
@endphp

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    Money actually received from clients (one-time payments + Care Plan charges), minus refunds. Updated live from the Payments, Care Plans, and FaithStack Payouts records.
</p>

{{-- KPI cards --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="{{ $card }} p-5">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">This Month</p>
        <p class="text-2xl font-bold text-navy dark:text-white mt-1">{{ $money($thisMonth['net']) }}</p>
        <p class="text-xs mt-0.5 {{ $change === null ? 'text-gray-500 dark:text-gray-400' : ($change >= 0 ? 'text-teal' : 'text-red-500') }}">
            @if ($change === null)
                {{ $money($lastMonth['net']) }} last month
            @else
                {{ $change >= 0 ? '▲' : '▼' }} {{ abs($change) }}% vs last month ({{ $money($lastMonth['net']) }})
            @endif
        </p>
    </div>
    <div class="{{ $card }} p-5">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Monthly Care Plan Income</p>
        <p class="text-2xl font-bold text-navy dark:text-white mt-1">{{ $money($mrr) }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $activeCount }} active plan{{ $activeCount === 1 ? '' : 's' }}@if ($pastDue->isNotEmpty()) · <span class="text-amber-600 dark:text-amber-400">{{ $pastDue->count() }} past due</span>@endif</p>
    </div>
    <div class="{{ $card }} p-5">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ now()->year }} So Far</p>
        <p class="text-2xl font-bold text-navy dark:text-white mt-1">{{ $money($yearToDate) }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">net of refunds</p>
    </div>
    <div class="{{ $card }} p-5">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Waiting to Be Paid</p>
        <p class="text-2xl font-bold text-navy dark:text-white mt-1">{{ $money($outstandingTotal) }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $outstanding->count() }} pending one-time payment{{ $outstanding->count() === 1 ? '' : 's' }}</p>
    </div>
</div>

{{-- 12-month chart --}}
<div class="{{ $card }} p-5 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <h3 class="text-sm font-bold text-navy dark:text-white">Money In — Last 12 Months</h3>
        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-gold"></span>One-time projects</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-teal"></span>Care Plans</span>
        </div>
    </div>
    <div class="flex items-end gap-2 h-48">
        @foreach ($months as $m)
            @php $total = $m['one_time'] + $m['care_plan']; @endphp
            <div class="group relative flex-1 h-full flex flex-col justify-end">
                <div class="w-full flex flex-col justify-end rounded-t overflow-hidden" style="height: {{ $total ? max(2, round($total / $maxMonth * 100)) : 0 }}%">
                    <div class="w-full bg-gold/80 group-hover:bg-gold transition-colors" style="height: {{ $total ? round($m['one_time'] / $total * 100) : 0 }}%"></div>
                    <div class="w-full bg-teal/80 group-hover:bg-teal transition-colors" style="height: {{ $total ? round($m['care_plan'] / $total * 100) : 0 }}%"></div>
                </div>
                <div class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-1 hidden group-hover:block whitespace-nowrap rounded-md bg-navy-dark text-white text-[11px] px-2 py-1 z-10">
                    {{ $m['month']->format('M Y') }}: {{ $money($m['one_time']) }} one-time + {{ $money($m['care_plan']) }} Care Plans
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex gap-2 mt-2">
        @foreach ($months as $m)
            <span class="flex-1 text-center text-[10px] text-gray-400 dark:text-gray-500">{{ $m['month']->format('M') }}</span>
        @endforeach
    </div>
</div>

{{-- Monthly breakdown --}}
<div class="{{ $card }} mb-6 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-sm font-bold text-navy dark:text-white">Monthly Breakdown</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">FaithStack's share is what was recorded on the FaithStack Payouts page for that month; "VisionBridge Keeps" is money in minus that share (before Stripe fees).</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-gray-400 dark:text-gray-500">
                    <th class="px-5 py-3 font-semibold">Month</th>
                    <th class="px-5 py-3 font-semibold text-right">One-Time</th>
                    <th class="px-5 py-3 font-semibold text-right">Care Plans</th>
                    <th class="px-5 py-3 font-semibold text-right">Refunds</th>
                    <th class="px-5 py-3 font-semibold text-right">Money In</th>
                    <th class="px-5 py-3 font-semibold text-right">FaithStack Share</th>
                    <th class="px-5 py-3 font-semibold text-right">VisionBridge Keeps</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($months->reverse() as $m)
                    <tr class="text-gray-600 dark:text-gray-300">
                        <td class="px-5 py-3 font-medium text-navy dark:text-white whitespace-nowrap">{{ $m['month']->format('F Y') }}</td>
                        <td class="px-5 py-3 text-right">{{ $money($m['one_time']) }}</td>
                        <td class="px-5 py-3 text-right">{{ $money($m['care_plan']) }}</td>
                        <td class="px-5 py-3 text-right {{ $m['refunds'] ? 'text-red-500' : '' }}">{{ $m['refunds'] ? '−'.$money($m['refunds']) : '—' }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-navy dark:text-white">{{ $money($m['net']) }}</td>
                        <td class="px-5 py-3 text-right">{{ $money($m['faithstack']) }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-teal">{{ $money($m['kept']) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-gray-200 dark:border-gray-600 text-navy dark:text-white font-semibold">
                    <td class="px-5 py-3">12-Month Total</td>
                    <td class="px-5 py-3 text-right">{{ $money($months->sum('one_time')) }}</td>
                    <td class="px-5 py-3 text-right">{{ $money($months->sum('care_plan')) }}</td>
                    <td class="px-5 py-3 text-right">{{ $months->sum('refunds') ? '−'.$money($months->sum('refunds')) : '—' }}</td>
                    <td class="px-5 py-3 text-right">{{ $money($months->sum('net')) }}</td>
                    <td class="px-5 py-3 text-right">{{ $money($months->sum('faithstack')) }}</td>
                    <td class="px-5 py-3 text-right text-teal">{{ $money($months->sum('kept')) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mb-6">
    {{-- Waiting to be paid --}}
    <div class="{{ $card }} overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-navy dark:text-white">Waiting to Be Paid</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pending one-time payment requests, oldest first — includes future phases queued ahead of time.</p>
            </div>
            <span class="text-sm font-bold text-navy dark:text-white">{{ $money($outstandingTotal) }}</span>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-96 overflow-y-auto">
            @forelse ($outstanding as $payment)
                @php $days = (int) $payment->created_at->diffInDays(now()); @endphp
                <a href="{{ $payment->project ? route('admin.projects.show', $payment->project) : '#' }}" class="flex items-center justify-between gap-4 px-5 py-3 hover:bg-gray-50/60 dark:hover:bg-white/5">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-navy dark:text-white truncate">{{ $payment->project?->user?->name ?? 'Unknown client' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $payment->description }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-semibold text-navy dark:text-white">{{ $money($payment->amount) }}</p>
                        <p class="text-[11px] {{ $days > 30 ? 'text-red-500' : ($days > 14 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400 dark:text-gray-500') }}">{{ $days === 0 ? 'Requested today' : "Requested {$days} day".($days === 1 ? '' : 's').' ago' }}</p>
                    </div>
                </a>
            @empty
                <p class="px-5 py-8 text-center text-sm text-gray-400 dark:text-gray-500">Nothing waiting — every payment request is paid.</p>
            @endforelse
        </div>
    </div>

    {{-- Top clients --}}
    <div class="{{ $card }} overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-bold text-navy dark:text-white">Top Clients (All Time)</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total money in per project — one-time payments (after refunds) plus every Care Plan charge.</p>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse ($topClients as $row)
                <a href="{{ route('admin.projects.show', $row['project']) }}" class="flex items-center justify-between gap-4 px-5 py-3 hover:bg-gray-50/60 dark:hover:bg-white/5">
                    <div class="min-w-0 flex items-center gap-3">
                        <span class="w-6 text-xs font-bold text-gray-400 dark:text-gray-500">{{ $loop->iteration }}</span>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-navy dark:text-white truncate">{{ $row['project']->user?->name ?? 'Unknown client' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $row['project']->name }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-navy dark:text-white shrink-0">{{ $money($row['total']) }}</span>
                </a>
            @empty
                <p class="px-5 py-8 text-center text-sm text-gray-400 dark:text-gray-500">No payments received yet.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Past due --}}
    <div class="{{ $card }} overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-bold text-navy dark:text-white">Care Plans Past Due</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $money($pastDueMrr) }}/month at risk. Stripe is retrying these cards.</p>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse ($pastDue as $sub)
                <a href="{{ $sub->project ? route('admin.projects.show', $sub->project) : '#' }}" class="flex items-center justify-between gap-3 px-5 py-3 hover:bg-gray-50/60 dark:hover:bg-white/5">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-navy dark:text-white truncate">{{ $sub->project?->user?->name ?? 'Unknown client' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $sub->past_due_at ? 'Since '.$sub->past_due_at->format('M j') : 'Past due' }}</p>
                    </div>
                    <span class="text-sm font-semibold text-amber-600 dark:text-amber-400 shrink-0">{{ $sub->formattedAmount() }}</span>
                </a>
            @empty
                <p class="px-5 py-6 text-center text-sm text-gray-400 dark:text-gray-500">None — every plan is paid up.</p>
            @endforelse
        </div>
    </div>

    {{-- Canceled --}}
    <div class="{{ $card }} overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-bold text-navy dark:text-white">Care Plans Canceled (Last 90 Days)</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $money($lostMrr) }}/month in lost income.</p>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse ($canceled as $sub)
                <div class="flex items-center justify-between gap-3 px-5 py-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-navy dark:text-white truncate">{{ $sub->project?->user?->name ?? 'Unknown client' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Canceled {{ $sub->canceled_at->format('M j, Y') }}</p>
                    </div>
                    <span class="text-sm font-semibold text-red-500 shrink-0">{{ $sub->formattedAmount() }}</span>
                </div>
            @empty
                <p class="px-5 py-6 text-center text-sm text-gray-400 dark:text-gray-500">No cancellations in the last 90 days.</p>
            @endforelse
        </div>
    </div>

    {{-- FaithStack --}}
    <div class="{{ $card }} p-5">
        <h3 class="text-sm font-bold text-navy dark:text-white">FaithStack</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">Owed to FaithStack but not yet marked paid (all statuses except Paid).</p>
        <p class="text-2xl font-bold text-navy dark:text-white">{{ $money($unpaidFaithstack) }}</p>
        <div class="mt-4 space-y-1.5 text-xs text-gray-500 dark:text-gray-400">
            <div class="flex justify-between"><span>FaithStack share, last 12 months</span><span class="font-semibold text-navy dark:text-white">{{ $money($months->sum('faithstack')) }}</span></div>
            <div class="flex justify-between"><span>VisionBridge kept, last 12 months</span><span class="font-semibold text-teal">{{ $money($months->sum('kept')) }}</span></div>
        </div>
        <a href="{{ route('admin.partner-payouts.index') }}" class="inline-block mt-4 text-sm font-semibold text-gold-dark hover:text-gold">Open FaithStack Payouts →</a>
    </div>
</div>

@endsection
