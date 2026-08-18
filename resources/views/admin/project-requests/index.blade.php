@extends('layouts.admin')

@section('title', 'Project Requests – Admin')
@section('page-title', 'Project Requests')

@section('content')

@php
    $statusColors = [
        'pending' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-800 dark:text-amber-400 ring-1 ring-inset ring-amber-200 dark:ring-amber-500/20',
        'reviewed' => 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-300 ring-1 ring-inset ring-indigo-200 dark:ring-indigo-500/20',
        'converted' => 'bg-teal/10 text-teal-dark ring-1 ring-inset ring-teal/20',
        'declined' => 'bg-red-50 dark:bg-red-500/10 text-red-500 ring-1 ring-inset ring-red-200 dark:ring-red-500/20',
        'done' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 ring-1 ring-inset ring-emerald-200 dark:ring-emerald-500/20',
    ];
    $statusIcons = [
        'pending' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'reviewed' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
        'converted' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
        'declined' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
        'done' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    ];
    $proposalColors = [
        'draft' => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
        'sent' => 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-300 ring-1 ring-inset ring-indigo-200 dark:ring-indigo-500/20',
        'under_review' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-800 dark:text-amber-400 ring-1 ring-inset ring-amber-200 dark:ring-amber-500/20',
        'accepted' => 'bg-teal/10 text-teal-dark ring-1 ring-inset ring-teal/20',
        'declined' => 'bg-red-50 dark:bg-red-500/10 text-red-500 ring-1 ring-inset ring-red-200 dark:ring-red-500/20',
    ];
    $proposalIcons = [
        'draft' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
        'sent' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>',
        'under_review' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>',
        'accepted' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
        'declined' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
    ];
    $avatarPalette = ['bg-gold/15 text-gold-dark', 'bg-teal/10 text-teal-dark', 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-300', 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-300'];
    $avatarClass = fn ($id) => $avatarPalette[$id % count($avatarPalette)];
    $categoryColors = [
        'request' => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
        'proposal' => 'bg-gold/15 text-gold-dark',
    ];
@endphp

{{-- Sticky header block (KPI cards + toolbar) — stays in view below the
     layout's own sticky h-16 header while only the table/cards below scroll.
     Only sticky from sm: up — on mobile the 2-col stat grid + stacked
     toolbar make this block taller than the viewport, so pinning it there
     would cover the whole screen and leave the request list unreachable.
     bg-gray-50/dark:bg-navy-dark matches <body>'s background (layouts.admin)
     so content scrolling underneath doesn't show through, and a bottom
     border gives it a visible edge once something is actually behind it. --}}
<div class="sm:sticky sm:top-16 z-10 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 pt-2 pb-4 mb-3 bg-gray-50 dark:bg-navy-dark border-b border-gray-200 dark:border-gray-700">

{{-- ═══════════════════════════════════════════════════════════════════════
     HEADER — subtitle + KPI cards. The "Project Requests" H1 itself is
     owned by layouts.admin (shared across every admin page), left untouched.
     ═══════════════════════════════════════════════════════════════════════ --}}
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-7">
    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xl leading-relaxed">
        Review incoming website requests, manage proposals, and approve projects before assigning them to developers.
    </p>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 shrink-0">
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white/80 dark:bg-navy/80 px-4 py-3 text-center shadow-sm">
            <p class="text-xl font-bold text-navy dark:text-white">{{ $totalRequestCount }}</p>
            <p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 mt-0.5">Total Requests</p>
        </div>
        <div class="rounded-xl border border-amber-200/60 dark:border-amber-500/20 bg-amber-50/60 dark:bg-amber-500/5 px-4 py-3 text-center shadow-sm">
            <p class="text-xl font-bold text-amber-700 dark:text-amber-400">{{ $statusCounts['pending'] ?? 0 }}</p>
            <p class="text-[0.65rem] font-semibold uppercase tracking-wide text-amber-700/70 dark:text-amber-400/70 mt-0.5">Pending</p>
        </div>
        <div class="rounded-xl border border-indigo-200/60 dark:border-indigo-500/20 bg-indigo-50/60 dark:bg-indigo-500/5 px-4 py-3 text-center shadow-sm">
            <p class="text-xl font-bold text-indigo-600 dark:text-indigo-300">{{ $statusCounts['reviewed'] ?? 0 }}</p>
            <p class="text-[0.65rem] font-semibold uppercase tracking-wide text-indigo-600/70 dark:text-indigo-300/70 mt-0.5">Reviewed</p>
        </div>
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white/80 dark:bg-navy/80 px-4 py-3 text-center shadow-sm">
            <p class="text-xl font-bold text-navy dark:text-white">{{ $draftProposalCount }}</p>
            <p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 mt-0.5">Draft Proposals</p>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════
     TOOLBAR — search, status filter (with counts), New Project Request CTA
     ═══════════════════════════════════════════════════════════════════════ --}}
<div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
    <div class="relative flex-1">
        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
        </svg>
        <input type="text" id="request-search" placeholder="Search client, email, or title…" autocomplete="off"
               class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-navy-dark dark:text-white pl-10 pr-4 py-2.5 text-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 shadow-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold transition-shadow">
    </div>
    <div class="w-full sm:w-56 shrink-0">
        @include('admin._dropdown', [
            'name' => 'status_filter',
            'domId' => 'request-status-filter',
            'options' => collect(\App\Models\ProjectRequest::STATUSES)->map(fn ($label, $key) => [
                'value' => $key,
                'label' => "{$label} (".($statusCounts[$key] ?? 0).')',
                'dot' => ['pending' => 'bg-amber-400', 'reviewed' => 'bg-indigo-400', 'converted' => 'bg-teal', 'declined' => 'bg-red-400', 'done' => 'bg-emerald-400'][$key] ?? 'bg-gray-400',
            ])->values()->all(),
            'selected' => '',
            'placeholder' => "All ({$totalRequestCount})",
        ])
    </div>
    <div class="w-full sm:w-48 shrink-0">
        @include('admin._dropdown', [
            'name' => 'category_filter',
            'domId' => 'request-category-filter',
            'options' => collect(\App\Models\ProjectRequest::CATEGORIES)->map(fn ($label, $key) => [
                'value' => $key,
                'label' => "{$label} (".($categoryCounts[$key] ?? 0).')',
                'dot' => ['request' => 'bg-gray-400', 'proposal' => 'bg-gold'][$key] ?? 'bg-gray-400',
            ])->values()->all(),
            'selected' => '',
            'placeholder' => 'All Categories',
        ])
    </div>
    <button type="button" data-modal="new-request-modal"
            class="modal-trigger inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-gradient-to-br from-gold via-gold to-gold-dark text-navy text-sm font-bold rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        New Project Request
    </button>
</div>

</div>

@if ($requests->isEmpty())
    {{-- ═══════════════════════════════════════════════════════════════
         EMPTY STATE (no requests exist at all)
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 py-16 px-6 text-center">
        <div class="w-16 h-16 rounded-full bg-gold/10 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <p class="font-bold text-navy dark:text-white mb-1.5">No Project Requests Yet</p>
        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto leading-relaxed mb-5">Projects submitted by clients will appear here.</p>
        <button type="button" data-modal="new-request-modal"
                class="modal-trigger inline-flex items-center gap-1.5 px-5 py-2.5 bg-gradient-to-br from-gold via-gold to-gold-dark text-navy text-sm font-bold rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Create First Project Request
        </button>
    </div>
@else
    {{-- ═══════════════════════════════════════════════════════════════
         DESKTOP/TABLET — real <table>, rows read as separate rounded
         cards via border-collapse:separate + border-spacing (first/last
         <td> get the corner radius). No position:fixed content lives
         inside a row (the View link is a plain <a>), so a transform-based
         hover lift here is safe.
         ═══════════════════════════════════════════════════════════════ --}}
    <div id="requests-table" class="hidden md:block bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-xs" style="border-collapse:separate;border-spacing:0 6px;">
            <thead class="text-left text-[0.65rem] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-5 pb-2">Client</th>
                    <th class="px-5 pb-2">Title</th>
                    <th class="px-5 pb-2">Category</th>
                    <th class="px-5 pb-2">Status</th>
                    <th class="px-5 pb-2">Proposal</th>
                    <th class="px-5 pb-2">Submitted</th>
                    <th class="px-5 pb-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $item)
                    @php $searchText = strtolower($item->user->name.' '.$item->user->email.' '.$item->title); @endphp
                    <tr class="request-row group bg-gray-50/60 dark:bg-navy-dark/40 hover:bg-white dark:hover:bg-gray-800 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer"
                        data-search="{{ $searchText }}" data-status="{{ $item->status }}" data-category="{{ $item->category }}"
                        onclick="window.location='{{ route('admin.project-requests.show', $item) }}'">
                        <td class="px-5 py-2 align-middle rounded-l-xl border-y border-l border-gray-100 dark:border-gray-700 group-hover:border-gold/30">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-[0.65rem] font-bold shrink-0 {{ $avatarClass($item->user->id) }}">
                                    {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                </span>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy dark:text-white truncate">{{ $item->user->name }}</p>
                                    <p class="text-[0.7rem] text-gray-500 dark:text-gray-400 truncate">{{ $item->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-2 align-middle border-y border-gray-100 dark:border-gray-700 group-hover:border-gold/30 max-w-xs">
                            <p class="font-semibold text-navy dark:text-white leading-snug">{{ $item->title }}</p>
                            <div class="flex flex-wrap items-center gap-1 mt-1">
                                @if ($item->isInternal())
                                    <span class="inline-block text-[0.6rem] font-bold uppercase tracking-wide px-1.5 py-0.5 rounded bg-navy/10 dark:bg-white/10 text-navy dark:text-white" title="Created internally by {{ $item->createdByAdmin?->name ?? 'an admin' }} — not submitted by the client">
                                        Internal
                                    </span>
                                @endif
                                @if ($item->priority === 'urgent')
                                    <span class="inline-block text-[0.6rem] font-bold uppercase tracking-wide px-1.5 py-0.5 rounded bg-red-50 dark:bg-red-500/10 text-red-500">
                                        Urgent
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-2 align-middle border-y border-gray-100 dark:border-gray-700 group-hover:border-gold/30">
                            <span class="inline-block text-[0.65rem] font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $categoryColors[$item->category] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                                {{ \App\Models\ProjectRequest::CATEGORIES[$item->category] ?? $item->category }}
                            </span>
                        </td>
                        <td class="px-5 py-2 align-middle border-y border-gray-100 dark:border-gray-700 group-hover:border-gold/30">
                            <span class="inline-flex items-center gap-1 text-[0.65rem] font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $statusColors[$item->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $statusIcons[$item->status] ?? '' !!}</svg>
                                {{ \App\Models\ProjectRequest::STATUSES[$item->status] ?? $item->status }}
                            </span>
                        </td>
                        <td class="px-5 py-2 align-middle border-y border-gray-100 dark:border-gray-700 group-hover:border-gold/30">
                            @if ($item->proposal_status)
                                <span class="inline-flex items-center gap-1 text-[0.65rem] font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $proposalColors[$item->proposal_status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $proposalIcons[$item->proposal_status] ?? '' !!}</svg>
                                    {{ \App\Models\ProjectRequest::PROPOSAL_STATUSES[$item->proposal_status] ?? $item->proposal_status }}
                                </span>
                            @else
                                <span class="inline-block text-[0.65rem] font-medium px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400">
                                    No Proposal
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-2 align-middle border-y border-gray-100 dark:border-gray-700 group-hover:border-gold/30 whitespace-nowrap">
                            <p class="font-medium text-gray-700 dark:text-gray-300">{{ $item->created_at->format('M j, Y') }}</p>
                            <p class="text-[0.7rem] text-gray-500 dark:text-gray-400">{{ $item->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-5 py-2 align-middle text-right rounded-r-xl border-y border-r border-gray-100 dark:border-gray-700 group-hover:border-gold/30">
                            <a href="{{ route('admin.project-requests.show', $item) }}" onclick="event.stopPropagation()"
                               class="inline-flex items-center gap-1.5 border border-gray-200 dark:border-gray-600 hover:border-gold hover:bg-gold/10 hover:shadow-sm hover:-translate-y-0.5 text-gray-600 dark:text-gray-300 hover:text-gold-dark text-[0.7rem] font-semibold px-2.5 py-1 rounded-lg transition-all duration-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View Details
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        {{-- SEARCH-EMPTY-RESULT state (desktop) --}}
        <div id="requests-empty-filter-desktop" class="hidden py-14 text-center px-5">
            <svg class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
            <p class="font-semibold text-navy dark:text-white mb-1">No matching requests found</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Try another keyword.</p>
        </div>

        <div class="flex items-center justify-between gap-4 px-5 py-3 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400">
            <span id="requests-count-label-desktop"></span>
            <div class="flex items-center gap-2">
                <button type="button" id="requests-page-prev-desktop" onclick="changeRequestsPage(-1)"
                        class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                    Prev
                </button>
                <span id="requests-page-indicator-desktop" class="px-2 text-navy dark:text-white font-medium"></span>
                <button type="button" id="requests-page-next-desktop" onclick="changeRequestsPage(1)"
                        class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                    Next
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         MOBILE — stacked cards instead of a horizontally-scrolling table.
         Same data, same routes, independent markup so each layout can be
         styled properly for its context.
         ═══════════════════════════════════════════════════════════════ --}}
    <div id="requests-cards" class="md:hidden space-y-3">
        @foreach ($requests as $item)
            @php $searchText = strtolower($item->user->name.' '.$item->user->email.' '.$item->title); @endphp
            <a href="{{ route('admin.project-requests.show', $item) }}"
               class="request-card block bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm active:scale-[0.99] transition-transform duration-150 p-4"
               data-search="{{ $searchText }}" data-status="{{ $item->status }}" data-category="{{ $item->category }}">
                <div class="flex items-start gap-3 mb-3">
                    <span class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold shrink-0 {{ $avatarClass($item->user->id) }}">
                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-navy dark:text-white truncate">{{ $item->user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $item->user->email }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>

                <p class="font-semibold text-navy dark:text-white leading-snug mb-1.5">{{ $item->title }}</p>
                <div class="flex flex-wrap items-center gap-1 mb-3">
                    @if ($item->isInternal())
                        <span class="inline-block text-[0.65rem] font-bold uppercase tracking-wide px-1.5 py-0.5 rounded bg-navy/10 dark:bg-white/10 text-navy dark:text-white">Internal</span>
                    @endif
                    @if ($item->priority === 'urgent')
                        <span class="inline-block text-[0.65rem] font-bold uppercase tracking-wide px-1.5 py-0.5 rounded bg-red-50 dark:bg-red-500/10 text-red-500">Urgent</span>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-1.5 mb-3">
                    <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $categoryColors[$item->category] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                        {{ \App\Models\ProjectRequest::CATEGORIES[$item->category] ?? $item->category }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$item->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $statusIcons[$item->status] ?? '' !!}</svg>
                        {{ \App\Models\ProjectRequest::STATUSES[$item->status] ?? $item->status }}
                    </span>
                    @if ($item->proposal_status)
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $proposalColors[$item->proposal_status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $proposalIcons[$item->proposal_status] ?? '' !!}</svg>
                            {{ \App\Models\ProjectRequest::PROPOSAL_STATUSES[$item->proposal_status] ?? $item->proposal_status }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                    <div>
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ $item->created_at->format('M j, Y') }}</p>
                        <p class="text-[0.65rem] text-gray-500 dark:text-gray-400">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-gold-dark">
                        View Details
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </a>
        @endforeach

        <div id="requests-empty-filter-mobile" class="hidden bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 py-12 text-center px-5">
            <svg class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
            <p class="font-semibold text-navy dark:text-white mb-1">No matching requests found</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Try another keyword.</p>
        </div>
    </div>

    <div class="md:hidden flex items-center justify-between gap-4 mt-3 px-1 text-xs text-gray-500 dark:text-gray-400">
        <span id="requests-count-label-mobile"></span>
        <div class="flex items-center gap-2">
            <button type="button" id="requests-page-prev-mobile" onclick="changeRequestsPage(-1)"
                    class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                Prev
            </button>
            <span id="requests-page-indicator-mobile" class="px-2 text-navy dark:text-white font-medium"></span>
            <button type="button" id="requests-page-next-mobile" onclick="changeRequestsPage(1)"
                    class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                Next
            </button>
        </div>
    </div>

    <script>
        (function () {
            const PER_PAGE = 15;
            let currentPage = 1;

            const search = document.getElementById('request-search');
            const statusFilter = document.getElementById('request-status-filter-input');
            const categoryFilter = document.getElementById('request-category-filter-input');
            const emptyDesktop = document.getElementById('requests-empty-filter-desktop');
            const emptyMobile = document.getElementById('requests-empty-filter-mobile');
            // The desktop <tr> rows and mobile <a> cards are two independent
            // renderings of the same $requests collection in the same order,
            // so a row and its corresponding card always match/mismatch the
            // same filters together — each list is paginated on its own,
            // but always lands on the same page range.
            const rows = Array.from(document.querySelectorAll('#requests-table tbody tr'));
            const cards = Array.from(document.querySelectorAll('#requests-cards > a[data-search]'));

            function matchesFilters(el, q, status, category) {
                const matchesSearch = !q || el.dataset.search.includes(q);
                const matchesStatus = !status || el.dataset.status === status;
                const matchesCategory = !category || el.dataset.category === category;
                return matchesSearch && matchesStatus && matchesCategory;
            }

            function updatePageControls(suffix, total, totalPages) {
                const countLabel = document.getElementById('requests-count-label-' + suffix);
                const indicator = document.getElementById('requests-page-indicator-' + suffix);
                const prevBtn = document.getElementById('requests-page-prev-' + suffix);
                const nextBtn = document.getElementById('requests-page-next-' + suffix);

                if (countLabel) {
                    if (total === 0) {
                        countLabel.textContent = 'No matching requests found';
                    } else {
                        const start = (currentPage - 1) * PER_PAGE + 1;
                        const end = Math.min(currentPage * PER_PAGE, total);
                        countLabel.textContent = 'Showing ' + start + '–' + end + ' of ' + total + ' request' + (total === 1 ? '' : 's');
                    }
                }
                if (indicator) indicator.textContent = 'Page ' + currentPage + ' of ' + totalPages;
                if (prevBtn) prevBtn.disabled = currentPage <= 1;
                if (nextBtn) nextBtn.disabled = currentPage >= totalPages;
            }

            function render() {
                const q = (search.value || '').trim().toLowerCase();
                const status = statusFilter.value;
                const category = categoryFilter.value;

                const matchedRows = rows.filter((el) => matchesFilters(el, q, status, category));
                const matchedCards = cards.filter((el) => matchesFilters(el, q, status, category));
                const total = matchedRows.length;
                const totalPages = Math.max(1, Math.ceil(total / PER_PAGE));
                currentPage = Math.min(Math.max(currentPage, 1), totalPages);

                const start = (currentPage - 1) * PER_PAGE;
                const end = start + PER_PAGE;
                const pageRows = new Set(matchedRows.slice(start, end));
                const pageCards = new Set(matchedCards.slice(start, end));

                rows.forEach((el) => el.classList.toggle('hidden', !pageRows.has(el)));
                cards.forEach((el) => el.classList.toggle('hidden', !pageCards.has(el)));

                if (emptyDesktop) emptyDesktop.classList.toggle('hidden', total > 0);
                if (emptyMobile) emptyMobile.classList.toggle('hidden', total > 0);

                updatePageControls('desktop', total, totalPages);
                updatePageControls('mobile', total, totalPages);
            }

            function applyFilters() {
                currentPage = 1;
                render();
            }

            window.changeRequestsPage = function (delta) {
                currentPage += delta;
                render();
            };

            search.addEventListener('input', applyFilters);
            statusFilter.addEventListener('change', applyFilters);
            categoryFilter.addEventListener('change', applyFilters);

            render();
        })();
    </script>
@endif

{{-- New Project Request modal — the admin-created "internal work order" path,
     alongside the existing client-submitted one. IMPORTANT: no transform
     (scale/translate) or backdrop-filter/blur on .admin-modal-panel or any
     of its ancestors — the client/priority/developer dropdowns inside it
     (admin._dropdown) render via real position:fixed, viewport-relative
     coordinates computed on open, and a transformed/filtered ancestor
     becomes their containing block instead, misplacing the menu (same class
     of bug hit and fixed on the Team page). The panel's own entrance is
     plain opacity-only for the same reason. The backdrop below IS blurred
     (backdrop-blur-sm) — that's safe since it's a sibling of the panel, not
     an ancestor, so it never touches the dropdowns' containing block. It
     does need `relative` on .admin-modal-panel to work correctly though: a
     position:absolute backdrop paints above a position:static panel
     regardless of DOM order, which visually looked like the blur was
     "bleeding" onto the panel but was actually the backdrop sitting on top
     of it the whole time (and silently swallowing every click as a
     backdrop click) — `relative` fixes the stacking, not the blur. --}}
<div id="new-request-modal" class="admin-modal hidden fixed inset-0 z-[60] items-center justify-center px-4">
    <div class="absolute inset-0 bg-navy-dark/60 backdrop-blur-sm opacity-0 transition-opacity duration-200" data-modal-backdrop></div>

    {{-- relative is required here: the backdrop above is position:absolute
         (a "positioned" element), and without an explicit position on this
         panel too, CSS paints positioned elements above static ones
         regardless of DOM order — the backdrop would sit visually on top of
         this panel (blocking/absorbing every click as a backdrop click,
         even though the panel's opaque background still shows through it
         fine, which is what made this so easy to miss). --}}
    <div class="admin-modal-panel relative bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden opacity-0 transition-opacity duration-200 flex flex-col">
        {{-- Rich gradient header — the one deliberately elevated moment on
             this page, matching the gold "wow" the trigger button already
             promises, instead of a flat text bar. Decorative glow blobs live
             INSIDE this header (not wrapping the form below), so animating
             them with transform is safe. --}}
        <div class="relative overflow-hidden shrink-0 px-5 pt-5 pb-5" style="background:linear-gradient(135deg,#111D33,#1B2A4A 65%,#1B2A4A);">
            <div class="absolute -top-10 -right-10 w-36 h-36 rounded-full pointer-events-none new-request-glow" style="background:radial-gradient(circle,rgba(201,168,76,0.30) 0%,transparent 70%);"></div>
            <div class="absolute -bottom-12 -left-8 w-32 h-32 rounded-full pointer-events-none new-request-glow" style="background:radial-gradient(circle,rgba(42,157,143,0.20) 0%,transparent 70%); animation-delay:1.2s;"></div>

            <div class="relative flex items-start gap-3">
                <span class="w-10 h-10 rounded-xl bg-gold/20 text-gold flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                <div class="min-w-0 flex-1 pt-0.5">
                    <p class="font-display text-lg font-bold text-white leading-tight">New Project Request</p>
                    <p class="text-xs text-white/60 mt-0.5 leading-relaxed">For internal work not submitted by a client — never appears in their portal or notifies them.</p>
                </div>
                <button type="button" class="admin-modal-close shrink-0 w-8 h-8 rounded-lg flex items-center justify-center text-white/60 hover:text-white hover:bg-white/10 transition-colors" aria-label="Close">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- min-h-0 is required here: a flex child sized by flex-1 alone
             ignores the parent's height cap and grows to its full content
             height instead, so overflow-y-auto never actually triggers —
             the panel's own overflow-hidden just silently clips whatever
             doesn't fit, with no scrollbar to reach it. --}}
        <form id="new-request-form" method="POST" action="{{ route('admin.project-requests.store') }}" enctype="multipart/form-data" class="flex-1 min-h-0 overflow-y-auto p-5 space-y-5">
            @csrf

            {{-- Section: the request itself --}}
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Client</label>
                    @include('admin._dropdown', [
                        'name' => 'user_id',
                        'domId' => 'new-request-client',
                        'options' => $clients->map(fn ($client) => [
                            'value' => $client->id,
                            'label' => $client->projects->isNotEmpty()
                                ? "{$client->name} — {$client->projects->first()->name} ({$client->email})"
                                : "{$client->name} ({$client->email})",
                        ])->all(),
                        'selected' => old('user_id'),
                        'placeholder' => 'Select a client...',
                    ])
                    @error('user_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Title</label>
                    <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Unity Auto Group Development Research &amp; Feasibility"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                    @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Category</label>
                    @include('admin._dropdown', [
                        'name' => 'category',
                        'domId' => 'new-request-category',
                        'options' => collect(\App\Models\ProjectRequest::CATEGORIES)->map(fn ($label, $value) => [
                            'value' => $value,
                            'label' => $label,
                            'dot' => ['request' => 'bg-gray-400', 'proposal' => 'bg-gold'][$value] ?? 'bg-gray-400',
                        ])->values()->all(),
                        'selected' => old('category', 'request'),
                    ])
                    @error('category')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Description</label>
                    <textarea name="description" rows="3" required
                              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">{{ old('description') }}</textarea>
                    @error('description')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Section: scheduling & ownership --}}
            <div class="space-y-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <p class="text-[0.65rem] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Scheduling &amp; Ownership</p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Priority</label>
                        @include('admin._dropdown', [
                            'name' => 'priority',
                            'domId' => 'new-request-priority',
                            'options' => collect(\App\Models\ProjectRequest::PRIORITIES)->map(fn ($label, $value) => [
                                'value' => $value,
                                'label' => $label,
                                'dot' => ['low' => 'bg-gray-400', 'medium' => 'bg-indigo-400', 'high' => 'bg-gold', 'urgent' => 'bg-red-500'][$value] ?? 'bg-gray-400',
                            ])->values()->all(),
                            'selected' => old('priority', 'medium'),
                        ])
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" onclick="this.showPicker && this.showPicker()" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Assign Developer (optional)</label>
                    @include('admin._dropdown', [
                        'name' => 'assigned_developer_id',
                        'domId' => 'new-request-developer',
                        'options' => $developers->map(fn ($d) => ['value' => $d->id, 'label' => $d->name])->all(),
                        'selected' => old('assigned_developer_id'),
                        'placeholder' => 'Unassigned',
                    ])
                </div>
            </div>

            {{-- Section: attachments --}}
            <div class="space-y-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <p class="text-[0.65rem] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Attachments</p>

                <div class="proposal-doc-picker">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Proposal Document (optional)</label>
                    <label class="inline-flex items-center gap-2 cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-navy-dark hover:border-gold hover:bg-gold/5 px-3.5 py-2 text-sm font-medium text-navy dark:text-white transition-colors">
                        <input type="file" name="proposal_document" class="proposal-doc-input sr-only" accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.rtf,.odt,.jpg,.jpeg,.png,.gif,.webp,.zip">
                        <svg class="w-4 h-4 text-gold-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Choose file</span>
                    </label>
                    {{-- Hidden until a file is picked — shows exactly what will be uploaded (name, size, and a real thumbnail for images) so an admin can catch a wrong file before submitting, instead of trusting the browser's bare "No file chosen" text. --}}
                    <div class="proposal-doc-preview hidden mt-2 flex items-center justify-between gap-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-navy-dark/60 px-3 py-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="shrink-0 w-8 h-8 rounded flex items-center justify-center bg-gold/15 text-gold-dark overflow-hidden">
                                <svg class="proposal-doc-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <img class="proposal-doc-thumb hidden w-full h-full object-cover" alt="">
                            </span>
                            <div class="min-w-0">
                                <p class="proposal-doc-name text-sm font-medium text-navy dark:text-white truncate"></p>
                                <p class="proposal-doc-size text-xs text-gray-500 dark:text-gray-400"></p>
                            </div>
                        </div>
                        <button type="button" class="proposal-doc-remove shrink-0 text-gray-400 hover:text-red-500 transition-colors" title="Remove">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Supporting Documents (optional)</label>
                    @include('admin.project-requests._attachments-picker')
                </div>
            </div>
        </form>

        {{-- Footer pinned outside the scrollable form area (associated via
             the button's form="" attribute) — stays reachable on a form this
             long instead of scrolling away with the fields. --}}
        <div class="shrink-0 flex justify-end gap-2 px-5 py-4 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-navy">
            <button type="button" class="admin-modal-close px-4 py-2 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white transition-colors">
                Cancel
            </button>
            <button type="submit" form="new-request-form" id="new-request-submit"
                    class="inline-flex items-center gap-1.5 px-5 py-2 bg-gradient-to-br from-gold via-gold to-gold-dark text-navy text-sm font-bold rounded-lg shadow-sm hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Create Request
            </button>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.proposal-doc-picker').forEach((wrap) => {
        const input = wrap.querySelector('.proposal-doc-input');
        const preview = wrap.querySelector('.proposal-doc-preview');
        const nameEl = wrap.querySelector('.proposal-doc-name');
        const sizeEl = wrap.querySelector('.proposal-doc-size');
        const thumbImg = wrap.querySelector('.proposal-doc-thumb');
        const thumbIcon = wrap.querySelector('.proposal-doc-icon');
        const removeBtn = wrap.querySelector('.proposal-doc-remove');

        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!file) {
                preview.classList.add('hidden');
                return;
            }

            nameEl.textContent = file.name;
            sizeEl.textContent = formatFileSize(file.size);

            if (file.type.startsWith('image/')) {
                thumbImg.src = URL.createObjectURL(file);
                thumbImg.classList.remove('hidden');
                thumbIcon.classList.add('hidden');
            } else {
                thumbImg.classList.add('hidden');
                thumbIcon.classList.remove('hidden');
            }

            preview.classList.remove('hidden');
        });

        removeBtn.addEventListener('click', () => {
            input.value = '';
            preview.classList.add('hidden');
        });
    });

    // Opacity-only open/close (see the big comment on #new-request-modal for
    // why this never touches transform) — fades the backdrop and panel in
    // together, then auto-focuses the first field so typing can start right
    // away without an extra click.
    function openAdminModal(modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        // Locks the page behind the modal so scrolling/clicking through to
        // it (and the layout shift that comes with it) isn't possible while
        // the modal's open — otherwise a scroll gesture over a
        // non-scrollable part of the modal (the header/footer) falls
        // through to the page, which can shift enough to turn a later click
        // into an accidental backdrop click that closes the modal.
        document.body.classList.add('overflow-hidden');
        requestAnimationFrame(() => {
            modal.querySelector('[data-modal-backdrop]')?.classList.remove('opacity-0');
            modal.querySelector('.admin-modal-panel')?.classList.remove('opacity-0');
        });
        const firstField = modal.querySelector('.admin-modal-panel button[id$="-toggle"], .admin-modal-panel input:not([type=hidden]), .admin-modal-panel textarea');
        if (firstField) setTimeout(() => firstField.focus(), 150);
    }

    function closeAdminModal(modal) {
        modal.querySelector('[data-modal-backdrop]')?.classList.add('opacity-0');
        modal.querySelector('.admin-modal-panel')?.classList.add('opacity-0');
        document.body.classList.remove('overflow-hidden');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }

    document.querySelectorAll('.modal-trigger').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const modal = document.getElementById(trigger.dataset.modal);
            if (modal) openAdminModal(modal);
        });
    });

    document.querySelectorAll('.admin-modal').forEach((modal) => {
        modal.addEventListener('click', (e) => {
            if (!e.target.closest('.admin-modal-panel')) closeAdminModal(modal);
        });
        modal.querySelectorAll('.admin-modal-close').forEach((btn) => {
            btn.addEventListener('click', () => closeAdminModal(modal));
        });
    });

    // Reopen the New Project Request modal automatically if the server
    // rejected the submission (e.g. no client picked) — otherwise the
    // redirect-back-with-errors would land on a closed modal and the errors
    // rendered inside it would be invisible.
    @if ($errors->has('user_id') || $errors->has('title') || $errors->has('category') || $errors->has('description') || old('title') !== null)
        (function () {
            const modal = document.getElementById('new-request-modal');
            if (modal) openAdminModal(modal);
        })();
    @endif

    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('.admin-modal:not(.hidden)').forEach(closeAdminModal);
    });

    // This form is a real full-page POST (no ajax) — a large multipart
    // upload can take a moment, so disable the button and show a spinner
    // instead of leaving it clickable (and double-submittable) while
    // waiting. No need to reset it: success navigates away, and a
    // validation failure reloads the page fresh (button state resets with
    // it) and reopens the modal via the block above.
    document.getElementById('new-request-form')?.addEventListener('submit', function () {
        const btn = document.getElementById('new-request-submit');
        if (!btn) return;
        btn.disabled = true;
        btn.classList.add('opacity-70', 'cursor-wait');
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Creating…';
    });
</script>

<style>
    @keyframes request-fade-in {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    /* Opacity-only, and not applied to .request-row / .request-card — both
       hover animations above already provide plenty of motion, and this
       page's rows/cards don't contain any position:fixed content anyway
       (unlike Team/Developers), so this is purely a "keep it subtle"
       choice, not a safety one. */
    #requests-table, #requests-cards {
        animation: request-fade-in 0.35s ease-out both;
    }

    /* New Project Request modal's decorative header glow — lives on plain
       empty <div>s inside the gradient header (not wrapping the form), so
       animating them with transform never touches the dropdown-positioning
       constraint documented on the modal itself. */
    @keyframes new-request-glow-float {
        0%, 100% { transform: translateY(0) scale(1); }
        50%      { transform: translateY(-6px) scale(1.08); }
    }
    .new-request-glow {
        animation: new-request-glow-float 5s ease-in-out infinite;
    }

    @media (prefers-reduced-motion: reduce) {
        #requests-table, #requests-cards, .new-request-glow { animation: none; }
    }
</style>

@endsection
