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
    Visitor countries use <a href="https://db-ip.com" target="_blank" rel="noopener" class="underline hover:text-navy dark:hover:text-white">IP Geolocation by DB-IP</a>.
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

{{-- Conversions this month --}}
<div class="{{ $card }} p-5 mb-6">
    <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
        <div>
            <h3 class="text-sm font-bold text-navy dark:text-white">Leads &amp; Actions — This Month</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">What visitors actually did on the website. Client portal requests are counted separately, since they aren't new leads.</p>
        </div>
        <div class="text-right">
            <p class="text-2xl font-bold text-navy dark:text-white">{{ number_format($monthLeads) }} <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">new leads</span></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                @if ($conversionRate !== null)
                    from {{ number_format($month['visitors']) }} visitors · <span class="font-semibold text-teal">{{ number_format($conversionRate, 1) }}% conversion rate</span>
                @else
                    No visitors yet this month
                @endif
            </p>
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-7 gap-3">
        @foreach (\App\Models\SiteConversion::types() as $type => $label)
            @php $isLead = array_key_exists($type, \App\Models\SiteConversion::LEAD_TYPES); @endphp
            <div class="rounded-lg p-3 {{ $isLead ? 'bg-gold/10' : 'bg-gray-50 dark:bg-white/5' }}">
                <p class="text-xl font-bold text-navy dark:text-white">{{ number_format($conversionCounts[$type] ?? 0) }}</p>
                <p class="text-[11px] leading-tight text-gray-500 dark:text-gray-400 mt-0.5">{{ $label }}</p>
            </div>
        @endforeach
    </div>
</div>

{{-- Lead breakdowns (last 30 days) --}}
<div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
    @foreach ([
        ['Where Leads Came From', $leadSources, 'No leads yet in the last 30 days.'],
        ['Pages That Brought In Leads', $leadPages, 'No leads yet in the last 30 days.'],
        ['Leads by Ad Campaign', $leadCampaigns, 'No leads yet in the last 30 days.'],
    ] as [$title, $rows, $emptyText])
        @php $max = max(1, $rows->max() ?? 1); @endphp
        <div class="{{ $card }} p-5">
            <h3 class="text-sm font-bold text-navy dark:text-white mb-1">{{ $title }}</h3>
            <p class="text-[11px] text-gray-400 dark:text-gray-500 mb-3">Last 30 days · new leads</p>
            @forelse ($rows as $label => $total)
                <div class="mb-2.5">
                    <div class="flex justify-between gap-3 text-xs mb-1">
                        <span class="text-gray-600 dark:text-gray-300 truncate">{{ $label }}</span>
                        <span class="font-semibold text-navy dark:text-white shrink-0">{{ number_format($total) }}</span>
                    </div>
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-white/5">
                        <div class="h-1.5 rounded-full bg-gold" style="width: {{ round($total / $max * 100) }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $emptyText }}</p>
            @endforelse
        </div>
    @endforeach
</div>

{{-- Tracking links for ads --}}
<div class="{{ $card }} p-5 mb-6">
    <h3 class="text-sm font-bold text-navy dark:text-white mb-1">Tracking Links for Ads &amp; Posts</h3>
    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
        Add tags to the end of any link you share so this report can tell exactly which ad or post each visitor and lead came from.
        Facebook and Google ad clicks are detected automatically, but tags also name the specific campaign.
    </p>
    <code class="block text-xs font-mono break-all rounded-lg bg-gray-50 dark:bg-white/5 text-navy dark:text-gray-200 px-3 py-2">{{ url('/') }}/?utm_source=facebook&amp;utm_medium=paid&amp;utm_campaign=fall-ministry-ad</code>
    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-2">Change <span class="font-mono">utm_source</span> to where the link is posted (facebook, instagram, email…) and <span class="font-mono">utm_campaign</span> to a short name for the ad.</p>
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
        ['Countries', $countries],
        ['Where Visitors Came From', $topReferrers],
        ['Ad Campaigns', $campaigns],
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
                <p class="text-xs text-gray-400 dark:text-gray-500">{{ match ($title) {
                    'Where Visitors Came From' => 'No outside referrers yet — visitors typed the address or used a bookmark.',
                    'Ad Campaigns' => 'No tagged ad links clicked yet — see Tracking Links above.',
                    'Countries' => 'No country data yet — recorded for visits from now on.',
                    default => 'No data yet.',
                } }}</p>
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
                    <th class="px-5 py-3 font-semibold text-right">New Leads</th>
                    <th class="px-5 py-3 font-semibold text-right">Conv. Rate</th>
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
                        @php $leads = $monthlyLeads[$stat->month->toDateString()] ?? 0; @endphp
                        <td class="px-5 py-3 text-right font-semibold text-navy dark:text-white">{{ number_format($leads) }}</td>
                        <td class="px-5 py-3 text-right">{{ $stat->unique_visitors > 0 ? number_format($leads / $stat->unique_visitors * 100, 1).'%' : '—' }}</td>
                        <td class="px-5 py-3">{{ array_key_first($stat->browsers ?? []) ?? '—' }}</td>
                        <td class="px-5 py-3">{{ array_key_first($stat->devices ?? []) ?? '—' }}</td>
                        <td class="px-5 py-3 truncate max-w-[14rem]">{{ array_key_first($stat->top_pages ?? []) ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Recent conversions --}}
<div class="{{ $card }} mb-6 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-sm font-bold text-navy dark:text-white">Recent Leads &amp; Actions</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">The latest 15, newest first. Click one to open the request.</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-gray-400 dark:text-gray-500">
                    <th class="px-5 py-3 font-semibold">Time</th>
                    <th class="px-5 py-3 font-semibold">Action</th>
                    <th class="px-5 py-3 font-semibold">Came From</th>
                    <th class="px-5 py-3 font-semibold">First Page Seen</th>
                    <th class="px-5 py-3 font-semibold">Submitted On</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($recentConversions as $conversion)
                    @php $url = $conversion->adminUrl(); @endphp
                    <tr class="text-gray-600 dark:text-gray-300 hover:bg-gray-50/60 dark:hover:bg-white/5">
                        <td class="px-5 py-3 whitespace-nowrap"><time data-local-time datetime="{{ $conversion->created_at->toIso8601String() }}">{{ $conversion->created_at->format('M j, g:i a') }} UTC</time></td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            @if ($url)
                                <a href="{{ $url }}" class="font-medium text-navy dark:text-white hover:text-gold">{{ $conversion->label() }}</a>
                            @else
                                <span class="font-medium text-navy dark:text-white">{{ $conversion->label() }}</span>
                            @endif
                            @unless ($conversion->isLead())
                                <span class="ml-1.5 text-[10px] font-semibold uppercase px-1.5 py-0.5 rounded-full bg-gray-100 dark:bg-white/10 text-gray-500 dark:text-gray-400">Client</span>
                            @endunless
                        </td>
                        <td class="px-5 py-3 truncate max-w-[12rem]">
                            {{ $conversion->source ?? 'Direct' }}
                            @if ($conversion->campaign)
                                <span class="text-gray-400 dark:text-gray-500">· {{ $conversion->campaign }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 truncate max-w-[14rem]">{{ $conversion->landing_page ?? '—' }}</td>
                        <td class="px-5 py-3 truncate max-w-[14rem]">{{ $conversion->last_page ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500">No leads or actions recorded yet — they'll appear here as forms are submitted.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Recent visitors (paging/filtering swaps just this card in place — see script below) --}}
<div id="recent-visitors" class="{{ $card }} overflow-hidden scroll-mt-24 transition-opacity">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h3 class="text-sm font-bold text-navy dark:text-white">Recent Visitors</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">One row per page view, newest first.</p>
        </div>
        <form method="GET" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search IP, page, country code…"
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
                    <th class="px-5 py-3 font-semibold">Country</th>
                    <th class="px-5 py-3 font-semibold">Browser</th>
                    <th class="px-5 py-3 font-semibold">Device</th>
                    <th class="px-5 py-3 font-semibold">Page</th>
                    <th class="px-5 py-3 font-semibold">Came From</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($recent as $visit)
                    <tr class="text-gray-600 dark:text-gray-300 hover:bg-gray-50/60 dark:hover:bg-white/5" title="{{ $visit->user_agent }}">
                        <td class="px-5 py-3 whitespace-nowrap"><time data-local-time datetime="{{ $visit->created_at->toIso8601String() }}">{{ $visit->created_at->format('M j, g:i a') }} UTC</time></td>
                        <td class="px-5 py-3 font-mono text-xs">{{ $visit->ip_address ?? '—' }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">{{ $visit->country ? \App\Models\SiteVisit::countryLabel($visit->country) : '—' }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">{{ $visit->browser }} <span class="text-gray-400 dark:text-gray-500">· {{ $visit->platform }}</span></td>
                        <td class="px-5 py-3">{{ $visit->device }}</td>
                        <td class="px-5 py-3 truncate max-w-[14rem]">{{ $visit->path }}</td>
                        <td class="px-5 py-3 truncate max-w-[12rem]">
                            {{ $visit->utm_source ?? $visit->referrer_host ?? 'Direct' }}
                            @if ($visit->utm_campaign)
                                <span class="text-gray-400 dark:text-gray-500">· {{ $visit->utm_campaign }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500">No visits {{ $search !== '' || $date ? 'match your filter' : 'recorded yet' }}.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($recent->hasPages())
        <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700">{{ $recent->links() }}</div>
    @endif
</div>

<script>
// Times are stored in UTC; show each viewer their own local time instead
// (the server-rendered text, suffixed "UTC", stays as the no-JS fallback).
function localizeTimes(root) {
    root.querySelectorAll('time[data-local-time]').forEach(function (el) {
        const date = new Date(el.getAttribute('datetime'));
        if (isNaN(date)) return;
        el.textContent = date.toLocaleString([], { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' });
        el.title = date.toUTCString();
    });
}
localizeTimes(document);

// Recent Visitors: pagination and the search/date filter fetch the page in
// the background and swap only this card, instead of a full reload that
// jumps back to the top. Falls back to normal navigation if the fetch fails.
(function () {
    const card = document.getElementById('recent-visitors');
    if (!card) return;

    function load(url, push) {
        card.classList.add('opacity-50', 'pointer-events-none');
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (res) {
                if (!res.ok) throw new Error(res.status);
                return res.text();
            })
            .then(function (html) {
                const fresh = new DOMParser().parseFromString(html, 'text/html').getElementById('recent-visitors');
                if (!fresh) throw new Error('missing card');
                card.innerHTML = fresh.innerHTML;
                localizeTimes(card);
                if (push) history.pushState({ recentVisitors: true }, '', url);
                if (card.getBoundingClientRect().top < 0) card.scrollIntoView({ behavior: 'smooth' });
            })
            .catch(function () { window.location.href = url; })
            .finally(function () { card.classList.remove('opacity-50', 'pointer-events-none'); });
    }

    card.addEventListener('click', function (e) {
        const link = e.target.closest('a[href]');
        if (!link || e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
        const url = new URL(link.href, window.location.href);
        if (url.pathname !== window.location.pathname) return;
        e.preventDefault();
        load(url.toString(), true);
    });

    card.addEventListener('submit', function (e) {
        const form = e.target;
        e.preventDefault();
        const url = new URL(window.location.pathname, window.location.href);
        new FormData(form).forEach(function (value, key) {
            if (value !== '') url.searchParams.set(key, value);
        });
        load(url.toString(), true);
    });

    window.addEventListener('popstate', function () {
        load(window.location.href, false);
    });
})();
</script>

@endsection
