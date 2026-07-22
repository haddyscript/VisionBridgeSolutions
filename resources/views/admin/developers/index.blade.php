@extends('layouts.admin')

@section('title', 'Developers – Admin')
@section('page-title', 'Developers')

@section('content')

@php
    $statusColors = [
        'in_progress' => 'bg-gold/15 text-gold-dark',
        'waiting_on_visionbridge' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-500',
        'completed' => 'bg-teal/10 text-teal-dark',
    ];

    // Only revision/content requests (Uploads) carry a priority — same color
    // language as the project page's revision thread pills.
    $priorityColors = [
        'low' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
        'medium' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-500',
        'high' => 'bg-gold/15 text-gold-dark',
        'urgent' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
    ];

    // Workload stat boxes — Completed / In Progress / Waiting / Not Started,
    // muted gray when 0, richer tint + darker type when >0. Colors match the
    // same status language already used on Work Orders and Revision
    // Management, rather than introducing a one-off palette just for this page.
    $statBoxes = [
        'completed' => ['label' => 'Completed', 'activeBg' => 'bg-teal/25', 'activeText' => 'text-teal-dark dark:text-teal-300'],
        'in_progress' => ['label' => 'In Progress', 'activeBg' => 'bg-gold/25', 'activeText' => 'text-gold-dark'],
        'waiting_on_visionbridge' => ['label' => 'Waiting', 'activeBg' => 'bg-purple-100 dark:bg-purple-500/20', 'activeText' => 'text-purple-700 dark:text-purple-300'],
        'not_started' => ['label' => 'Not Started', 'activeBg' => 'bg-gray-200 dark:bg-gray-600', 'activeText' => 'text-navy dark:text-white'],
    ];

    // ── Dashboard summary — pure display aggregation over $roster/$developers/
    // $unassigned, already fully loaded by the controller; no new queries. ──
    $activeDeveloperCount = $developers->filter(fn ($d) => $d->is_active ?? true)->count();
    $workingNowCount = $roster->filter(fn ($row) => $row['activeItems']->isNotEmpty())->count();
    $availableDeveloperCount = $roster->filter(fn ($row) => ($row['developer']->is_active ?? true) && $row['activeItems']->isEmpty())->count();
    $openWorkOrderCount = $roster->sum(fn ($row) => $row['activeItems']->count()) + $unassigned->count();

    // Workload bar denominator — relative to the busiest developer on the
    // team right now, not a fabricated fixed "capacity" number that doesn't
    // exist anywhere in the data model.
    $maxActiveOnTeam = max($roster->max(fn ($row) => $row['activeItems']->count()) ?: 1, 1);
@endphp

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Every "Developer" job-title account, their current workload, and any Work Orders still waiting for a developer.</p>

{{-- ═══════════════════════════════════════════════════════════════════════
     SECTION 2 — Dashboard summary cards
     ═══════════════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="summary-card rounded-2xl border border-gray-200 dark:border-gray-700 bg-gradient-to-br from-teal/10 to-teal/[0.03] dark:from-teal/15 dark:to-transparent px-5 py-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
        <div class="w-10 h-10 rounded-xl bg-white/70 dark:bg-white/10 shadow-sm flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-teal-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8"/></svg>
        </div>
        <p class="summary-counter text-2xl font-bold text-navy dark:text-white" data-count="{{ $activeDeveloperCount }}">0</p>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">Active Developers</p>
    </div>

    <div class="summary-card rounded-2xl border border-gray-200 dark:border-gray-700 bg-gradient-to-br from-gold/10 to-gold/[0.03] dark:from-gold/15 dark:to-transparent px-5 py-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
        <div class="w-10 h-10 rounded-xl bg-white/70 dark:bg-white/10 shadow-sm flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <p class="summary-counter text-2xl font-bold text-navy dark:text-white" data-count="{{ $workingNowCount }}">0</p>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">Working Now</p>
    </div>

    <div class="summary-card rounded-2xl border border-gray-200 dark:border-gray-700 bg-gradient-to-br from-blue-50 to-blue-50/20 dark:from-blue-500/15 dark:to-transparent px-5 py-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
        <div class="w-10 h-10 rounded-xl bg-white/70 dark:bg-white/10 shadow-sm flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="summary-counter text-2xl font-bold text-navy dark:text-white" data-count="{{ $availableDeveloperCount }}">0</p>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">Available Developers</p>
    </div>

    <div class="summary-card rounded-2xl border border-gray-200 dark:border-gray-700 bg-gradient-to-br from-purple-50 to-purple-50/20 dark:from-purple-500/15 dark:to-transparent px-5 py-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
        <div class="w-10 h-10 rounded-xl bg-white/70 dark:bg-white/10 shadow-sm flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <p class="summary-counter text-2xl font-bold text-navy dark:text-white" data-count="{{ $openWorkOrderCount }}">0</p>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">Open Work Orders</p>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════
     SECTION 3 — Search + filters. Kept to the two controls that actually do
     something (search, workload) — the flex-wrap container is the
     future-proofing: more filters can drop in here later without layout
     changes, but adding non-functional placeholder dropdowns now would just
     be broken UI dressed up as a feature.
     ═══════════════════════════════════════════════════════════════════════ --}}
<div class="flex flex-wrap gap-3 mb-8">
    <div class="relative flex-1 min-w-[240px] max-w-xl">
        <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" id="developer-search" placeholder="Search developers by name…"
               class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 dark:text-white pl-11 pr-4 py-3 text-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 shadow-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold transition-shadow">
    </div>
    <div class="w-full sm:w-60">
        @include('admin._dropdown', [
            'name' => 'workload_filter',
            'domId' => 'workload-filter',
            'options' => [
                ['value' => 'all', 'label' => 'All Developers'],
                ['value' => 'active', 'label' => 'Has Active Work', 'dot' => 'bg-gold'],
                ['value' => 'idle', 'label' => 'Idle (No Active Work)', 'dot' => 'bg-gray-400'],
            ],
            'selected' => 'all',
        ])
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

    {{-- ═══════════════════════════════════════════════════════════════════
         SECTION 4-12 — Developer cards, the primary focus of the page
         ═══════════════════════════════════════════════════════════════════ --}}
    <div class="lg:col-span-2">
        @if ($developers->isEmpty())
            <div class="bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 p-10 text-center">
                <p class="text-gray-500 dark:text-gray-400">No team members have the "Developer" job title yet — set one on the Team Members page.</p>
            </div>
        @else
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                @foreach ($roster as $row)
                    @php
                        $hasActiveWork = $row['activeItems']->isNotEmpty();
                        $activeCount = $row['activeItems']->count();
                        $capacityPct = (int) round($activeCount / $maxActiveOnTeam * 100);
                        $capacityBarColor = $capacityPct > 85 ? 'bg-red-400' : ($capacityPct > 50 ? 'bg-gold' : 'bg-teal');
                        $capacityTextColor = $capacityPct > 85 ? 'text-red-500' : ($capacityPct > 50 ? 'text-gold-dark' : 'text-teal-dark');
                        $currentItem = $row['activeItems']->first();
                        $restActiveItems = $row['activeItems']->slice(1);
                    @endphp
                    <div class="developer-card bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 p-7 shadow-sm hover:shadow-lg hover:border-gold/40 dark:hover:border-gold/30 transition-all duration-200"
                         data-name="{{ strtolower($row['developer']->name) }}" data-has-active="{{ $hasActiveWork ? '1' : '0' }}">

                        {{-- SECTION 5 — Developer header --}}
                        <div class="flex items-start gap-3.5 mb-5">
                            <span class="w-13 h-13 rounded-2xl bg-gradient-to-br from-navy to-navy/75 text-gold text-base font-bold flex items-center justify-center shrink-0 shadow-sm" style="width:3.25rem;height:3.25rem;">
                                {{ strtoupper(substr($row['developer']->name, 0, 1)) }}
                            </span>
                            <div class="min-w-0 flex-1 pt-0.5">
                                <p class="font-bold text-navy dark:text-white leading-snug truncate">{{ $row['developer']->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $row['developer']->job_title ?? 'Developer' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $row['developer']->email }}</p>
                            </div>
                            @if ($row['developer']->is_active ?? true)
                                <span class="shrink-0 inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-teal/10 text-teal-dark">
                                    <span class="w-1.5 h-1.5 rounded-full bg-teal animate-pulse"></span>
                                    Active
                                </span>
                            @else
                                <span class="shrink-0 inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </div>

                        {{-- SECTION 6 — Workload capacity bar, relative to the
                             busiest developer on the team (no fabricated fixed
                             "capacity" ceiling exists in the data model). --}}
                        <div class="mb-5">
                            <div class="flex items-center justify-between mb-1.5">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Workload</p>
                                <p class="text-xs font-bold {{ $capacityTextColor }}">{{ $activeCount }} active</p>
                            </div>
                            <div class="h-2 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                <div class="workload-bar-fill h-full rounded-full {{ $capacityBarColor }}" data-target-pct="{{ $capacityPct }}" style="width:0%;"></div>
                            </div>
                        </div>

                        {{-- SECTION 7 — Statistics --}}
                        <div class="grid grid-cols-4 gap-2 mb-5">
                            @foreach ($statBoxes as $key => $box)
                                @php $count = $row['counts'][$key]; @endphp
                                <div class="text-center rounded-xl py-3 {{ $count > 0 ? $box['activeBg'] : 'bg-gray-50 dark:bg-gray-900' }}">
                                    <p class="text-lg font-bold {{ $count > 0 ? $box['activeText'] : 'text-gray-300 dark:text-gray-600' }}">{{ $count }}</p>
                                    <p class="text-[0.63rem] uppercase tracking-wide {{ $count > 0 ? 'text-gray-500 dark:text-gray-400' : 'text-gray-300 dark:text-gray-600' }}">{{ $box['label'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        {{-- SECTION 8 — Current Work Order, emphasized. The
                             underlying item row (link, status pill, reassign
                             dropdown) is unchanged — only its wrapper here is
                             new, so nothing about how it works changes. --}}
                        <p class="text-[0.65rem] font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2">Current Work Order</p>
                        @if ($currentItem)
                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 border-l-4 border-l-gold bg-gray-50/70 dark:bg-gray-900/40 px-2 mb-3">
                                @include('admin.developers._item-row', ['item' => $currentItem, 'statusColors' => $statusColors, 'developers' => $developers, 'assignedDeveloperId' => $row['developer']->id])
                            </div>
                            @if ($restActiveItems->isNotEmpty())
                                <p class="text-[0.65rem] font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1.5">Also Assigned ({{ $restActiveItems->count() }})</p>
                                <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-48 overflow-y-auto pr-1 rounded-lg border border-gray-100 dark:border-gray-700/60 mb-4">
                                    @foreach ($restActiveItems as $item)
                                        @include('admin.developers._item-row', ['item' => $item, 'statusColors' => $statusColors, 'developers' => $developers, 'assignedDeveloperId' => $row['developer']->id])
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="rounded-xl border border-dashed border-gray-200 dark:border-gray-700 py-5 text-center mb-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No active Work Orders right now.</p>
                            </div>
                        @endif

                        {{-- SECTION 12 — History button, sized/rounded to
                             match the search input and filter dropdown above. --}}
                        @if ($row['completedItems']->isNotEmpty())
                            <button type="button"
                                    class="developer-history-btn inline-flex items-center gap-1.5 text-xs font-semibold rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 hover:border-gold hover:text-gold-dark text-navy dark:text-white px-3 py-2 transition-colors"
                                    data-target="developer-history-{{ $row['developer']->id }}"
                                    data-developer-name="{{ $row['developer']->name }}"
                                    data-count="{{ $row['completedItems']->count() }}">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                History ({{ $row['completedItems']->count() }})
                            </button>
                            {{-- <template> content is inert (never rendered
                                 inline) until JS clones it into the shared
                                 modal below. --}}
                            <template id="developer-history-{{ $row['developer']->id }}">
                                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach ($row['completedItems'] as $item)
                                        @include('admin.developers._item-row', ['item' => $item, 'statusColors' => $statusColors, 'completed' => true])
                                    @endforeach
                                </div>
                            </template>
                        @endif
                    </div>
                @endforeach
            </div>
            <p id="developer-empty-state" class="hidden text-sm text-gray-500 dark:text-gray-400 text-center py-10">No developers match your search.</p>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         Unassigned — kept deliberately loud (red badge + icon + bordered
         card, not a plain heading), same as before this pass.
         ═══════════════════════════════════════════════════════════════════ --}}
    <div class="lg:col-span-1 lg:sticky lg:top-6">
        <div class="flex items-center gap-2 mb-3">
            @if ($unassigned->isNotEmpty())
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 dark:bg-red-500/15 shrink-0">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a1 1 0 00.86 1.5h18.64a1 1 0 00.86-1.5L13.71 3.86a1 1 0 00-1.42 0z"/></svg>
                </span>
            @endif
            <h3 class="font-bold text-base text-navy dark:text-white">Unassigned — Needs a Developer</h3>
            @if ($unassigned->isNotEmpty())
                <span class="flex items-center justify-center min-w-[1.75rem] h-7 px-2 rounded-full bg-red-500 text-white text-sm font-bold shrink-0">{{ $unassigned->count() }}</span>
            @endif
        </div>

        {{-- SECTION 9 — Polished empty state --}}
        @if ($unassigned->isEmpty())
            <div class="bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 p-8 text-center">
                <div class="w-14 h-14 rounded-full bg-teal/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-teal-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="font-bold text-navy dark:text-white mb-1">Great Work!</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Every Work Order currently has an assigned developer.<br>No pending assignments.</p>
            </div>
        @else
            <div class="bg-white dark:bg-navy rounded-2xl border-2 border-red-200 dark:border-red-500/30 divide-y divide-gray-100 dark:divide-gray-700 max-h-[calc(100vh-220px)] overflow-y-auto">
                <div class="sticky top-0 bg-red-50 dark:bg-red-500/10 px-4 py-2.5 border-b border-red-200 dark:border-red-500/30">
                    <p class="text-xs font-bold uppercase tracking-wide text-red-700 dark:text-red-400">{{ $unassigned->count() }} {{ $unassigned->count() === 1 ? 'item' : 'items' }} waiting for assignment</p>
                </div>
                @foreach ($unassigned as $item)
                    <div class="p-4">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <p class="text-sm font-semibold text-navy dark:text-white">{{ $item['client_name'] }}</p>
                            <span class="text-[0.65rem] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 shrink-0">{{ $item['created_at']->format('M j') }}</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1.5 flex flex-wrap items-center gap-1.5">
                            <span>{{ $item['type'] }}</span>
                            @if (! empty($item['priority']))
                                <span class="text-[0.65rem] font-semibold uppercase tracking-wide px-1.5 py-0.5 rounded-full {{ $priorityColors[$item['priority']] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                    {{ \App\Models\Upload::PRIORITIES[$item['priority']] ?? ucfirst($item['priority']) }}
                                </span>
                            @endif
                        </p>
                        <a href="{{ $item['url'] }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline block mb-1">{{ $item['title'] }}</a>
                        @if ($item['link'])
                            <a href="{{ $item['link'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-semibold text-gold-dark hover:underline mb-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                View File
                            </a>
                        @endif
                        <form method="POST" action="{{ $item['assign_url'] }}" class="mt-1.5 assign-developer-form">
                            @csrf
                            @method('PATCH')
                            @include('admin._dropdown', [
                                'name' => 'assigned_developer_id',
                                'domId' => 'unassigned-assign-'.$loop->index,
                                'options' => $developers->map(fn ($d) => ['value' => $d->id, 'label' => $d->name])->all(),
                                'selected' => null,
                                'placeholder' => 'Assign to…',
                                'autoSubmit' => true,
                            ])
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════
     SECTION 17 — Future placeholders. Visual only, clearly marked, no
     backend behind any of these yet.
     ═══════════════════════════════════════════════════════════════════════ --}}
<div class="mt-10">
    <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-3">Coming Soon</p>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach ([
            ['label' => 'Recent Activity', 'desc' => 'A live feed of assignment and status changes.', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
            ['label' => 'Developer Timeline', 'desc' => 'Historical workload trends per developer.', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-9-4h.01M9 16h.01'],
            ['label' => 'Performance Analytics', 'desc' => 'Completion rate and turnaround charts.', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
        ] as $placeholder)
            <div class="rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30 px-5 py-6 text-center opacity-70">
                <div class="w-10 h-10 rounded-xl bg-white dark:bg-navy shadow-sm flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $placeholder['icon'] }}"/></svg>
                </div>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ $placeholder['label'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">{{ $placeholder['desc'] }}</p>
                <span class="inline-block mt-2 text-[0.6rem] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400">Coming Soon</span>
            </div>
        @endforeach
    </div>
</div>

{{-- Full-page reload still happens on assign (see the form below) — this
     overlay just covers the wait with a spinner instead of the dropdown
     appearing to do nothing until the new page arrives. --}}
<div id="assign-loading-overlay" class="hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center">
    <div class="bg-white dark:bg-navy rounded-xl px-6 py-5 flex items-center gap-3 shadow-lg">
        <svg class="w-5 h-5 animate-spin text-gold" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        <span class="text-sm font-semibold text-navy dark:text-white">Assigning developer…</span>
    </div>
</div>

{{-- Shared History modal — one instance, populated on click by cloning the
     clicked developer's <template> from above rather than one modal per
     developer card. --}}
<div id="developer-history-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-history-modal-close></div>
    <div class="relative bg-white dark:bg-navy rounded-xl shadow-xl w-full max-w-lg max-h-[85vh] flex flex-col">
        <div class="flex items-center justify-between gap-4 px-5 py-4 border-b border-gray-100 dark:border-gray-700 shrink-0">
            <div class="min-w-0">
                <h3 id="developer-history-modal-title" class="font-bold text-navy dark:text-white truncate"></h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Completed Work Orders</p>
            </div>
            <button type="button" data-history-modal-close class="shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors" aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="developer-history-modal-body" class="overflow-y-auto px-5"></div>
    </div>
</div>

<style>
    /* SECTION 16 — subtle, staggered fade-in on load. Pure CSS, no GSAP.
       Opacity only, deliberately no transform: animation-fill-mode:both
       would otherwise leave a permanent (if invisible) `transform` on
       .developer-card even after the animation ends — and any transform on
       an ancestor becomes the containing block for position:fixed
       descendants, which breaks the reassign dropdown's fixed-position
       menu (it computes coordinates relative to the viewport). */
    @keyframes dev-card-fade-in {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    .summary-card, .developer-card {
        animation: dev-card-fade-in 0.5s ease-out both;
    }
    .summary-card:nth-child(1) { animation-delay: 0s; }
    .summary-card:nth-child(2) { animation-delay: 0.06s; }
    .summary-card:nth-child(3) { animation-delay: 0.12s; }
    .summary-card:nth-child(4) { animation-delay: 0.18s; }
    .developer-card:nth-child(1) { animation-delay: 0.08s; }
    .developer-card:nth-child(2) { animation-delay: 0.14s; }
    .developer-card:nth-child(3) { animation-delay: 0.2s; }
    .developer-card:nth-child(4) { animation-delay: 0.26s; }
    @media (prefers-reduced-motion: reduce) {
        .summary-card, .developer-card { animation: none; }
    }
</style>

<script>
    document.querySelectorAll('.assign-developer-form').forEach((form) => {
        form.addEventListener('submit', () => {
            document.getElementById('assign-loading-overlay')?.classList.remove('hidden');
        });
    });

    // SECTION 16 — workload bars fill smoothly from 0 on load, and the
    // summary numbers count up, instead of just appearing at their final
    // value. Plain vanilla JS, no animation library.
    (function () {
        requestAnimationFrame(function () {
            document.querySelectorAll('.workload-bar-fill').forEach(function (bar) {
                bar.style.width = (bar.dataset.targetPct || 0) + '%';
            });
        });

        document.querySelectorAll('.summary-counter').forEach(function (el) {
            const target = parseInt(el.dataset.count || '0', 10);
            if (!target) { el.textContent = '0'; return; }
            const duration = 700;
            const start = performance.now();
            function tick(now) {
                const progress = Math.min((now - start) / duration, 1);
                el.textContent = Math.round(target * (1 - Math.pow(1 - progress, 3))); // ease-out cubic
                if (progress < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        });
    })();

    (function () {
        const modal = document.getElementById('developer-history-modal');
        const modalTitle = document.getElementById('developer-history-modal-title');
        const modalBody = document.getElementById('developer-history-modal-body');
        if (!modal || !modalTitle || !modalBody) return;

        function openHistoryModal(btn) {
            const template = document.getElementById(btn.dataset.target);
            if (!template) return;
            modalTitle.textContent = `${btn.dataset.developerName} — History (${btn.dataset.count})`;
            modalBody.innerHTML = '';
            modalBody.appendChild(template.content.cloneNode(true));
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeHistoryModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.querySelectorAll('.developer-history-btn').forEach((btn) => {
            btn.addEventListener('click', () => openHistoryModal(btn));
        });
        modal.querySelectorAll('[data-history-modal-close]').forEach((el) => {
            el.addEventListener('click', closeHistoryModal);
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeHistoryModal();
        });
    })();

    (function () {
        const searchInput = document.getElementById('developer-search');
        const workloadFilter = document.getElementById('workload-filter-input');
        const cards = document.querySelectorAll('.developer-card');
        const emptyState = document.getElementById('developer-empty-state');

        function applyFilters() {
            const query = (searchInput?.value || '').trim().toLowerCase();
            const workload = workloadFilter?.value || 'all';
            let visibleCount = 0;

            cards.forEach((card) => {
                const matchesName = !query || card.dataset.name.includes(query);
                const matchesWorkload = workload === 'all'
                    || (workload === 'active' && card.dataset.hasActive === '1')
                    || (workload === 'idle' && card.dataset.hasActive === '0');

                const visible = matchesName && matchesWorkload;
                card.classList.toggle('hidden', !visible);
                if (visible) visibleCount++;
            });

            if (emptyState) emptyState.classList.toggle('hidden', visibleCount !== 0);
        }

        searchInput?.addEventListener('input', applyFilters);
        workloadFilter?.addEventListener('change', applyFilters);
    })();
</script>

@endsection
