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
    ];
    $statusIcons = [
        'pending' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'reviewed' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
        'converted' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
        'declined' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
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
@endphp

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
    <select id="request-status-filter"
            class="rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-navy-dark dark:text-white px-3.5 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold transition-shadow">
        <option value="">All ({{ $totalRequestCount }})</option>
        @foreach (\App\Models\ProjectRequest::STATUSES as $key => $label)
            <option value="{{ $key }}">{{ $label }} ({{ $statusCounts[$key] ?? 0 }})</option>
        @endforeach
    </select>
    <button type="button" data-modal="new-request-modal"
            class="modal-trigger inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-gradient-to-br from-gold via-gold to-gold-dark text-navy text-sm font-bold rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        New Project Request
    </button>
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
        <table class="w-full text-sm" style="border-collapse:separate;border-spacing:0 10px;">
            <thead class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-5 pb-2">Client</th>
                    <th class="px-5 pb-2">Title</th>
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
                        data-search="{{ $searchText }}" data-status="{{ $item->status }}"
                        onclick="window.location='{{ route('admin.project-requests.show', $item) }}'">
                        <td class="px-5 py-4 align-middle rounded-l-xl border-y border-l border-gray-100 dark:border-gray-700 group-hover:border-gold/30">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold shrink-0 {{ $avatarClass($item->user->id) }}">
                                    {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                </span>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy dark:text-white truncate">{{ $item->user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $item->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 align-middle border-y border-gray-100 dark:border-gray-700 group-hover:border-gold/30 max-w-xs">
                            <p class="font-semibold text-navy dark:text-white leading-snug">{{ $item->title }}</p>
                            <div class="flex flex-wrap items-center gap-1 mt-1.5">
                                @if ($item->isInternal())
                                    <span class="inline-block text-[0.65rem] font-bold uppercase tracking-wide px-1.5 py-0.5 rounded bg-navy/10 dark:bg-white/10 text-navy dark:text-white" title="Created internally by {{ $item->createdByAdmin?->name ?? 'an admin' }} — not submitted by the client">
                                        Internal
                                    </span>
                                @endif
                                @if ($item->priority === 'urgent')
                                    <span class="inline-block text-[0.65rem] font-bold uppercase tracking-wide px-1.5 py-0.5 rounded bg-red-50 dark:bg-red-500/10 text-red-500">
                                        Urgent
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4 align-middle border-y border-gray-100 dark:border-gray-700 group-hover:border-gold/30">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$item->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $statusIcons[$item->status] ?? '' !!}</svg>
                                {{ \App\Models\ProjectRequest::STATUSES[$item->status] ?? $item->status }}
                            </span>
                        </td>
                        <td class="px-5 py-4 align-middle border-y border-gray-100 dark:border-gray-700 group-hover:border-gold/30">
                            @if ($item->proposal_status)
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $proposalColors[$item->proposal_status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $proposalIcons[$item->proposal_status] ?? '' !!}</svg>
                                    {{ \App\Models\ProjectRequest::PROPOSAL_STATUSES[$item->proposal_status] ?? $item->proposal_status }}
                                </span>
                            @else
                                <span class="inline-block text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400">
                                    No Proposal
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 align-middle border-y border-gray-100 dark:border-gray-700 group-hover:border-gold/30 whitespace-nowrap">
                            <p class="font-medium text-gray-700 dark:text-gray-300">{{ $item->created_at->format('M j, Y') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-5 py-4 align-middle text-right rounded-r-xl border-y border-r border-gray-100 dark:border-gray-700 group-hover:border-gold/30">
                            <a href="{{ route('admin.project-requests.show', $item) }}" onclick="event.stopPropagation()"
                               class="inline-flex items-center gap-1.5 border border-gray-200 dark:border-gray-600 hover:border-gold hover:bg-gold/10 hover:shadow-sm hover:-translate-y-0.5 text-gray-600 dark:text-gray-300 hover:text-gold-dark text-xs font-semibold px-3 py-1.5 rounded-lg transition-all duration-200">
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
            <span id="requests-count-label">Showing {{ $requests->count() }} of {{ $requests->total() }} request{{ $requests->total() === 1 ? '' : 's' }}</span>
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
               data-search="{{ $searchText }}" data-status="{{ $item->status }}">
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

    <div class="mt-6">
        {{ $requests->links() }}
    </div>

    <script>
        (function () {
            const search = document.getElementById('request-search');
            const statusFilter = document.getElementById('request-status-filter');
            const emptyDesktop = document.getElementById('requests-empty-filter-desktop');
            const emptyMobile = document.getElementById('requests-empty-filter-mobile');
            const countLabel = document.getElementById('requests-count-label');
            // Both the desktop <tr> rows and mobile <a> cards share the same
            // [data-search]/[data-status] contract, so one filter pass drives both.
            const items = Array.from(document.querySelectorAll('[data-search]'));
            const totalCount = {{ $requests->total() }};

            function apply() {
                const q = (search.value || '').trim().toLowerCase();
                const status = statusFilter.value;
                let visibleDesktop = 0;
                let visibleMobile = 0;

                items.forEach(function (el) {
                    const matchesSearch = !q || el.dataset.search.includes(q);
                    const matchesStatus = !status || el.dataset.status === status;
                    const show = matchesSearch && matchesStatus;
                    el.classList.toggle('hidden', !show);
                    if (show) {
                        if (el.matches('tr')) visibleDesktop++; else visibleMobile++;
                    }
                });

                if (emptyDesktop) emptyDesktop.classList.toggle('hidden', visibleDesktop > 0);
                if (emptyMobile) emptyMobile.classList.toggle('hidden', visibleMobile > 0);

                if (countLabel) {
                    countLabel.textContent = (q || status)
                        ? 'Showing ' + visibleDesktop + ' of ' + (items.length / 2) + ' on this page'
                        : 'Showing ' + (items.length / 2) + ' of ' + totalCount + ' request' + (totalCount === 1 ? '' : 's');
                }
            }

            search.addEventListener('input', apply);
            statusFilter.addEventListener('change', apply);
        })();
    </script>
@endif

{{-- New Project Request modal — the admin-created "internal work order" path,
     alongside the existing client-submitted one. No backdrop-blur/glass or
     transform-animation on the panel here, deliberately — the client/
     priority/developer dropdowns inside it (admin._dropdown) use real
     position:fixed, viewport-relative coordinates, and either of those CSS
     properties on an ancestor would make this panel their containing block
     instead (same class of bug hit and fixed on the Team page). --}}
<div id="new-request-modal" class="admin-modal hidden fixed inset-0 z-[60] items-center justify-center bg-black/40 px-4">
    <div class="admin-modal-panel bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 shadow-2xl w-full max-w-lg max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <p class="font-bold text-navy dark:text-white">New Project Request</p>
            <button type="button" class="admin-modal-close w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors shrink-0" aria-label="Close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="new-request-form" method="POST" action="{{ route('admin.project-requests.store') }}" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            <p class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-navy-dark/50 rounded-lg px-3 py-2">
                For internal work not submitted by a client — e.g. research/feasibility work on an existing account. It's tied to a client for record-keeping but never appears in their portal or notifies them.
            </p>

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
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Description</label>
                <textarea name="description" rows="3" required
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">{{ old('description') }}</textarea>
                @error('description')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

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
                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
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

            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Proposal Document (optional)</label>
                <input type="file" name="proposal_document" class="w-full text-sm text-gray-600 dark:text-gray-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gold/15 file:text-gold-dark hover:file:bg-gold/25">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Supporting Documents (optional)</label>
                @include('admin.project-requests._attachments-picker')
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" class="admin-modal-close px-4 py-2 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-navy hover:bg-navy-light text-white text-sm font-semibold rounded-lg transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.modal-trigger').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const modal = document.getElementById(trigger.dataset.modal);
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    function closeAdminModal(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

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
    @if ($errors->has('user_id') || $errors->has('title') || $errors->has('description') || old('title') !== null)
        (function () {
            const modal = document.getElementById('new-request-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        })();
    @endif

    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('.admin-modal:not(.hidden)').forEach(closeAdminModal);
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
    @media (prefers-reduced-motion: reduce) {
        #requests-table, #requests-cards { animation: none; }
    }
</style>

@endsection
