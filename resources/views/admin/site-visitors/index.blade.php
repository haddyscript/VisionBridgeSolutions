@extends('layouts.admin')

@section('title', 'Website Visitors – Admin')
@section('page-title', 'Website Visitors')

@section('content')

@php
    $card = 'bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700';
    $maxDay = max(1, $chart->max('visitors'));
@endphp

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    Visits to the public website (not the client portal or admin). Bots and our own team are excluded.
    Detailed visit records, including IP addresses, are kept for {{ \App\Models\SiteVisit::RETENTION_DAYS }} days; the monthly totals below are kept permanently.
</p>

{{-- Stat cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach ([
        ['Today', $today],
        ['Last 7 Days', $week],
        ['This Month', $month],
    ] as [$label, $stat])
        <div class="{{ $card }} p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ $label }}</p>
            <p class="text-2xl font-bold text-navy dark:text-white mt-1">{{ number_format($stat['visitors']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">visitors · {{ number_format($stat['views']) }} page views</p>
        </div>
    @endforeach
    <div class="{{ $card }} p-5">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">All Time</p>
        <p class="text-2xl font-bold text-navy dark:text-white mt-1">{{ number_format($allTimeViews) }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">page views since tracking began</p>
    </div>
</div>

{{-- Daily chart --}}
<div class="{{ $card }} p-5 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-bold text-navy dark:text-white">Visitors per Day — Last 30 Days</h3>
    </div>
    <div class="flex items-end gap-1 h-40">
        @foreach ($chart as $day)
            <div class="group relative flex-1 h-full flex items-end">
                <div class="w-full rounded-t bg-gold/70 group-hover:bg-gold transition-colors"
                     style="height: {{ max(2, round($day['visitors'] / $maxDay * 100)) }}%"></div>
                <div class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-1 hidden group-hover:block whitespace-nowrap rounded-md bg-navy-dark text-white text-[11px] px-2 py-1 z-10">
                    {{ $day['label'] }}: {{ $day['visitors'] }} visitors, {{ $day['views'] }} views
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex justify-between text-[11px] text-gray-400 dark:text-gray-500 mt-2">
        <span>{{ $chart->first()['label'] }}</span>
        <span>{{ $chart->last()['label'] }}</span>
    </div>
</div>

{{-- Breakdowns (last 30 days, unique visitors) --}}
<div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
    @foreach ([
        ['Browsers', $browsers],
        ['Devices', $devices],
        ['Operating Systems', $platforms],
        ['Top Pages', $topPages],
        ['Where Visitors Came From', $topReferrers],
    ] as [$title, $rows])
        @php $max = max(1, $rows->max() ?? 1); @endphp
        <div class="{{ $card }} p-5">
            <h3 class="text-sm font-bold text-navy dark:text-white mb-1">{{ $title }}</h3>
            <p class="text-[11px] text-gray-400 dark:text-gray-500 mb-3">Last 30 days · unique visitors</p>
            @forelse ($rows as $label => $total)
                <div class="mb-2.5">
                    <div class="flex justify-between gap-3 text-xs mb-1">
                        <span class="text-gray-600 dark:text-gray-300 truncate">{{ $label }}</span>
                        <span class="font-semibold text-navy dark:text-white shrink-0">{{ number_format($total) }}</span>
                    </div>
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-white/5">
                        <div class="h-1.5 rounded-full bg-teal" style="width: {{ round($total / $max * 100) }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $title === 'Where Visitors Came From' ? 'No outside referrers yet — visitors typed the address or used a bookmark.' : 'No data yet.' }}</p>
            @endforelse
        </div>
    @endforeach
</div>

{{-- Monthly report (permanent) --}}
<div class="{{ $card }} mb-6 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-sm font-bold text-navy dark:text-white">Monthly Report</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kept permanently, even after detailed visit records are deleted. The current month updates live.</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-gray-400 dark:text-gray-500">
                    <th class="px-5 py-3 font-semibold">Month</th>
                    <th class="px-5 py-3 font-semibold text-right">Visitors</th>
                    <th class="px-5 py-3 font-semibold text-right">Page Views</th>
                    <th class="px-5 py-3 font-semibold">Top Browser</th>
                    <th class="px-5 py-3 font-semibold">Top Device</th>
                    <th class="px-5 py-3 font-semibold">Top Page</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($monthly as $stat)
                    <tr class="text-gray-600 dark:text-gray-300">
                        <td class="px-5 py-3 font-medium text-navy dark:text-white whitespace-nowrap">
                            {{ $stat->month->format('F Y') }}
                            @if ($stat->month->isSameMonth(now()))
                                <span class="ml-1.5 text-[10px] font-semibold uppercase px-1.5 py-0.5 rounded-full bg-gold/15 text-gold-dark">Live</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right font-semibold text-navy dark:text-white">{{ number_format($stat->unique_visitors) }}</td>
                        <td class="px-5 py-3 text-right">{{ number_format($stat->page_views) }}</td>
                        <td class="px-5 py-3">{{ array_key_first($stat->browsers ?? []) ?? '—' }}</td>
                        <td class="px-5 py-3">{{ array_key_first($stat->devices ?? []) ?? '—' }}</td>
                        <td class="px-5 py-3 truncate max-w-[14rem]">{{ array_key_first($stat->top_pages ?? []) ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Recent visitors --}}
<div class="{{ $card }} overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h3 class="text-sm font-bold text-navy dark:text-white">Recent Visitors</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">One row per page view, newest first.</p>
        </div>
        <form method="GET" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search IP, page, browser…"
                   class="w-56 max-w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500">
            <input type="date" name="date" value="{{ $date }}"
                   class="rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
            <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-4 py-2 rounded-lg transition-colors">Filter</button>
            @if ($search !== '' || $date)
                <a href="{{ route('admin.site-visitors.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white">Clear</a>
            @endif
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-gray-400 dark:text-gray-500">
                    <th class="px-5 py-3 font-semibold">Time</th>
                    <th class="px-5 py-3 font-semibold">IP Address</th>
                    <th class="px-5 py-3 font-semibold">Browser</th>
                    <th class="px-5 py-3 font-semibold">Device</th>
                    <th class="px-5 py-3 font-semibold">Page</th>
                    <th class="px-5 py-3 font-semibold">Came From</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($recent as $visit)
                    <tr class="text-gray-600 dark:text-gray-300 hover:bg-gray-50/60 dark:hover:bg-white/5" title="{{ $visit->user_agent }}">
                        <td class="px-5 py-3 whitespace-nowrap">{{ $visit->created_at->format('M j, g:i a') }}</td>
                        <td class="px-5 py-3 font-mono text-xs">{{ $visit->ip_address ?? '—' }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">{{ $visit->browser }} <span class="text-gray-400 dark:text-gray-500">· {{ $visit->platform }}</span></td>
                        <td class="px-5 py-3">{{ $visit->device }}</td>
                        <td class="px-5 py-3 truncate max-w-[14rem]">{{ $visit->path }}</td>
                        <td class="px-5 py-3 truncate max-w-[12rem]">{{ $visit->referrer_host ?? 'Direct' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500">No visits {{ $search !== '' || $date ? 'match your filter' : 'recorded yet' }}.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($recent->hasPages())
        <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700">{{ $recent->links() }}</div>
    @endif
</div>

@endsection
