@extends('layouts.admin')

@section('title', 'Revision Management – Admin')
@section('page-title', 'Revision Management')

@section('content')

@php
    $statusColors = [
        'request_received' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
        'under_review' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-500',
        'in_progress' => 'bg-gold/15 text-gold-dark',
        'waiting_on_client' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-500',
        'needs_approval' => 'bg-orange-50 dark:bg-orange-500/10 text-orange-500',
        'completed' => 'bg-teal/10 text-teal-dark',
        'closed' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
    ];
    $statusDots = [
        'request_received' => 'bg-red-400',
        'under_review' => 'bg-blue-400',
        'in_progress' => 'bg-gold',
        'waiting_on_client' => 'bg-purple-400',
        'needs_approval' => 'bg-orange-400',
        'completed' => 'bg-teal',
        'closed' => 'bg-gray-400',
    ];
    $priorityColors = [
        'low' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
        'medium' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-500',
        'high' => 'bg-gold/15 text-gold-dark',
        'urgent' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
    ];
    $priorityDots = [
        'low' => 'bg-gray-400',
        'medium' => 'bg-blue-400',
        'high' => 'bg-gold',
        'urgent' => 'bg-red-400',
    ];
    $neutralPill = 'border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-navy dark:text-white';
    $checkIcon = '<svg data-option-check class="w-4 h-4 text-gold-dark shrink-0 %s" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
@endphp

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Every revision request across every project, in one place — update status, priority, and developer assignment right from this list. This reads the same data as each project's own Revisions tab, so the two always stay in sync.</p>

{{-- Toast notifications for inline dropdown saves — see the script at the
     bottom of this file for showRevisionToast(). Fixed/overlay, so its
     position in the DOM doesn't matter; kept outside the @if/@else below so
     it's always present regardless of whether there are any revisions. --}}
<div id="rev-toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 w-80 max-w-[90vw]" aria-live="polite" aria-atomic="true"></div>

@if ($revisions->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No revision requests yet.</p>
    </div>
@else
    {{-- ─── Filter toolbar — custom pill dropdowns instead of native <select>,
         matching the styled listbox pattern already used on the per-project
         Revisions tab (colored option dots, checkmarks, floating menu). ───── --}}
    <div class="flex flex-wrap items-center gap-2.5 mb-4">

        {{-- Client filter --}}
        <div class="relative" data-rev-dd data-rev-dd-kind="filter" id="rev-filter-client" data-value="">
            <button type="button" data-rev-dd-toggle aria-haspopup="listbox" aria-expanded="false"
                    class="inline-flex items-center gap-1.5 text-sm font-medium rounded-lg pl-3 pr-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-gold transition-colors {{ $neutralPill }}">
                <svg class="w-3.5 h-3.5 shrink-0 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span data-rev-dd-label>All Clients</span>
                <svg data-rev-dd-chevron class="w-3.5 h-3.5 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div data-rev-dd-menu class="hidden absolute z-30 left-0 mt-1.5 w-56 max-h-72 overflow-y-auto bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                <button type="button" data-rev-dd-option="" role="option" aria-selected="true" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors text-gold-dark font-semibold">
                    <span data-option-label>All Clients</span>
                    {!! sprintf($checkIcon, '') !!}
                </button>
                @foreach ($clients as $client)
                    <button type="button" data-rev-dd-option="{{ $client->id }}" role="option" aria-selected="false" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors text-gray-700 dark:text-gray-300">
                        <span data-option-label class="truncate">{{ $client->name }}</span>
                        {!! sprintf($checkIcon, 'invisible') !!}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Project filter --}}
        <div class="relative" data-rev-dd data-rev-dd-kind="filter" id="rev-filter-project" data-value="">
            <button type="button" data-rev-dd-toggle aria-haspopup="listbox" aria-expanded="false"
                    class="inline-flex items-center gap-1.5 text-sm font-medium rounded-lg pl-3 pr-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-gold transition-colors {{ $neutralPill }}">
                <svg class="w-3.5 h-3.5 shrink-0 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                <span data-rev-dd-label>All Projects</span>
                <svg data-rev-dd-chevron class="w-3.5 h-3.5 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div data-rev-dd-menu class="hidden absolute z-30 left-0 mt-1.5 w-56 max-h-72 overflow-y-auto bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                <button type="button" data-rev-dd-option="" role="option" aria-selected="true" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors text-gold-dark font-semibold">
                    <span data-option-label>All Projects</span>
                    {!! sprintf($checkIcon, '') !!}
                </button>
                @foreach ($projects as $project)
                    <button type="button" data-rev-dd-option="{{ $project->id }}" role="option" aria-selected="false" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors text-gray-700 dark:text-gray-300">
                        <span data-option-label class="truncate">{{ $project->name }}</span>
                        {!! sprintf($checkIcon, 'invisible') !!}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Status filter --}}
        <div class="relative" data-rev-dd data-rev-dd-kind="filter" id="rev-filter-status" data-value="">
            <button type="button" data-rev-dd-toggle aria-haspopup="listbox" aria-expanded="false"
                    class="inline-flex items-center gap-1.5 text-sm font-medium rounded-lg pl-3 pr-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-gold transition-colors {{ $neutralPill }}">
                <span data-rev-dd-label>All Statuses</span>
                <svg data-rev-dd-chevron class="w-3.5 h-3.5 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div data-rev-dd-menu class="hidden absolute z-30 left-0 mt-1.5 w-56 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                <button type="button" data-rev-dd-option="" role="option" aria-selected="true" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors text-gold-dark font-semibold">
                    <span data-option-label>All Statuses</span>
                    {!! sprintf($checkIcon, '') !!}
                </button>
                @foreach (\App\Models\Upload::STATUSES as $value => $label)
                    <button type="button" data-rev-dd-option="{{ $value }}" role="option" aria-selected="false" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors text-gray-700 dark:text-gray-300">
                        <span class="flex items-center gap-2" data-option-label><span class="w-2 h-2 rounded-full shrink-0 {{ $statusDots[$value] }}"></span>{{ $label }}</span>
                        {!! sprintf($checkIcon, 'invisible') !!}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Priority filter --}}
        <div class="relative" data-rev-dd data-rev-dd-kind="filter" id="rev-filter-priority" data-value="">
            <button type="button" data-rev-dd-toggle aria-haspopup="listbox" aria-expanded="false"
                    class="inline-flex items-center gap-1.5 text-sm font-medium rounded-lg pl-3 pr-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-gold transition-colors {{ $neutralPill }}">
                <span data-rev-dd-label>All Priorities</span>
                <svg data-rev-dd-chevron class="w-3.5 h-3.5 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div data-rev-dd-menu class="hidden absolute z-30 left-0 mt-1.5 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                <button type="button" data-rev-dd-option="" role="option" aria-selected="true" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors text-gold-dark font-semibold">
                    <span data-option-label>All Priorities</span>
                    {!! sprintf($checkIcon, '') !!}
                </button>
                @foreach (\App\Models\Upload::PRIORITIES as $value => $label)
                    <button type="button" data-rev-dd-option="{{ $value }}" role="option" aria-selected="false" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors text-gray-700 dark:text-gray-300">
                        <span class="flex items-center gap-2" data-option-label><span class="w-2 h-2 rounded-full shrink-0 {{ $priorityDots[$value] }}"></span>{{ $label }}</span>
                        {!! sprintf($checkIcon, 'invisible') !!}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Developer filter --}}
        <div class="relative" data-rev-dd data-rev-dd-kind="filter" id="rev-filter-developer" data-value="">
            <button type="button" data-rev-dd-toggle aria-haspopup="listbox" aria-expanded="false"
                    class="inline-flex items-center gap-1.5 text-sm font-medium rounded-lg pl-3 pr-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-gold transition-colors {{ $neutralPill }}">
                <span data-rev-dd-label>All Developers</span>
                <svg data-rev-dd-chevron class="w-3.5 h-3.5 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div data-rev-dd-menu class="hidden absolute z-30 left-0 mt-1.5 w-52 max-h-72 overflow-y-auto bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                <button type="button" data-rev-dd-option="" role="option" aria-selected="true" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors text-gold-dark font-semibold">
                    <span data-option-label>All Developers</span>
                    {!! sprintf($checkIcon, '') !!}
                </button>
                <button type="button" data-rev-dd-option="unassigned" role="option" aria-selected="false" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors text-gray-700 dark:text-gray-300">
                    <span data-option-label>Unassigned</span>
                    {!! sprintf($checkIcon, 'invisible') !!}
                </button>
                @foreach ($developers as $developer)
                    <button type="button" data-rev-dd-option="{{ $developer->id }}" role="option" aria-selected="false" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors text-gray-700 dark:text-gray-300">
                        <span data-option-label class="truncate">{{ $developer->name }}</span>
                        {!! sprintf($checkIcon, 'invisible') !!}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Date range — plain native inputs, not a dropdown --}}
        <div class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
            <label for="rev-filter-date-from" class="text-xs">From</label>
            <input type="date" id="rev-filter-date-from" onchange="filterRevisions()" class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-2.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            <label for="rev-filter-date-to" class="text-xs">To</label>
            <input type="date" id="rev-filter-date-to" onchange="filterRevisions()" class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-2.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        </div>

        <button type="button" onclick="clearRevisionFilters()" class="text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white underline">Clear filters</button>
    </div>

    <p id="rev-filter-empty" class="hidden text-sm text-gray-400 dark:text-gray-500 mb-4">No revision requests match these filters.</p>

    <div id="revisions-table-wrap" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
        <table id="revisions-table" class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <tr>
                    <th class="px-5 py-3">Client / Project</th>
                    <th class="px-5 py-3">Request</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Priority</th>
                    <th class="px-5 py-3">Developer</th>
                    <th class="px-5 py-3">Requested</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($revisions as $item)
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30"
                        data-client="{{ $item->user_id }}"
                        data-project="{{ $item->project_id }}"
                        data-status="{{ $item->status }}"
                        data-priority="{{ $item->priority }}"
                        data-developer="{{ $item->assigned_developer_id ?? 'unassigned' }}"
                        data-date="{{ $item->created_at->format('Y-m-d') }}">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white">{{ $item->user->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $item->project->name }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300 max-w-xs">
                            @if ($item->title)
                                <p class="font-semibold text-navy dark:text-white truncate">{{ $item->title }}</p>
                            @endif
                            <p class="truncate {{ $item->title ? 'text-xs text-gray-400 dark:text-gray-500' : '' }}">{{ \Illuminate\Support\Str::limit($item->body ?? $item->original_name ?? 'Revision #'.$item->id, 60) }}</p>
                            @if ($item->isOverdue())
                                <span class="inline-block mt-1 text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400">Overdue</span>
                            @endif
                        </td>

                        {{-- Status — inline pill dropdown, saves via AJAX on select --}}
                        <td class="px-5 py-3.5">
                            <div class="relative" data-rev-dd data-rev-dd-kind="status" data-value="{{ $item->status }}"
                                 data-rev-dd-url="{{ route('admin.uploads.status', $item) }}">
                                <button type="button" data-rev-dd-toggle data-color-class="{{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-500' }}"
                                        aria-haspopup="listbox" aria-expanded="false"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold rounded-full pl-3 pr-2 py-1 focus:outline-none focus:ring-2 focus:ring-gold transition-colors {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-500' }}">
                                    <span data-rev-dd-label>{{ \App\Models\Upload::STATUSES[$item->status] ?? $item->status }}</span>
                                    <svg data-rev-dd-chevron class="w-3.5 h-3.5 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div data-rev-dd-menu class="hidden absolute z-20 left-0 mt-1.5 w-56 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                                    @foreach (\App\Models\Upload::STATUSES as $value => $label)
                                        <button type="button" data-rev-dd-option="{{ $value }}" data-color-class="{{ $statusColors[$value] ?? 'bg-gray-100 text-gray-500' }}" role="option" aria-selected="{{ $item->status === $value ? 'true' : 'false' }}"
                                                class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $item->status === $value ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                            <span class="flex items-center gap-2" data-option-label><span class="w-2 h-2 rounded-full shrink-0 {{ $statusDots[$value] ?? 'bg-gray-400' }}"></span>{{ $label }}</span>
                                            {!! sprintf($checkIcon, $item->status === $value ? '' : 'invisible') !!}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </td>

                        {{-- Priority — inline pill dropdown; preserves the existing estimated completion date --}}
                        <td class="px-5 py-3.5">
                            <div class="relative" data-rev-dd data-rev-dd-kind="priority" data-value="{{ $item->priority }}"
                                 data-rev-dd-url="{{ route('admin.uploads.details', $item) }}"
                                 data-estimated-completion-date="{{ $item->estimated_completion_date?->format('Y-m-d') }}">
                                <button type="button" data-rev-dd-toggle data-color-class="{{ $priorityColors[$item->priority] ?? $priorityColors['medium'] }}"
                                        aria-haspopup="listbox" aria-expanded="false"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold rounded-full pl-3 pr-2 py-1 focus:outline-none focus:ring-2 focus:ring-gold transition-colors {{ $priorityColors[$item->priority] ?? $priorityColors['medium'] }}">
                                    <span data-rev-dd-label>{{ \App\Models\Upload::PRIORITIES[$item->priority] ?? ucfirst($item->priority) }}</span>
                                    <svg data-rev-dd-chevron class="w-3.5 h-3.5 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div data-rev-dd-menu class="hidden absolute z-20 left-0 mt-1.5 w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                                    @foreach (\App\Models\Upload::PRIORITIES as $value => $label)
                                        <button type="button" data-rev-dd-option="{{ $value }}" data-color-class="{{ $priorityColors[$value] }}" role="option" aria-selected="{{ $item->priority === $value ? 'true' : 'false' }}"
                                                class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $item->priority === $value ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                            <span class="flex items-center gap-2" data-option-label><span class="w-2 h-2 rounded-full shrink-0 {{ $priorityDots[$value] }}"></span>{{ $label }}</span>
                                            {!! sprintf($checkIcon, $item->priority === $value ? '' : 'invisible') !!}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </td>

                        {{-- Developer — inline pill dropdown (plain, no color) --}}
                        <td class="px-5 py-3.5">
                            <div class="relative" data-rev-dd data-rev-dd-kind="developer" data-value="{{ $item->assigned_developer_id }}"
                                 data-rev-dd-url="{{ route('admin.uploads.assign-developer', $item) }}">
                                <button type="button" data-rev-dd-toggle aria-haspopup="listbox" aria-expanded="false"
                                        class="inline-flex items-center gap-1.5 text-xs font-medium rounded-lg pl-2.5 pr-2 py-1.5 border focus:outline-none focus:ring-2 focus:ring-gold hover:border-gray-400 dark:hover:border-gray-500 transition-colors {{ $item->assigned_developer_id ? 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-navy dark:text-white' : 'border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-400 dark:text-gray-500' }}">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span data-rev-dd-label>{{ $item->assignedDeveloper->name ?? 'Unassigned' }}</span>
                                    <svg data-rev-dd-chevron class="w-3.5 h-3.5 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div data-rev-dd-menu class="hidden absolute z-20 left-0 mt-1.5 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                                    <button type="button" data-rev-dd-option="" role="option" aria-selected="{{ ! $item->assigned_developer_id ? 'true' : 'false' }}"
                                            class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ ! $item->assigned_developer_id ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                        <span data-option-label>Unassigned</span>
                                        {!! sprintf($checkIcon, ! $item->assigned_developer_id ? '' : 'invisible') !!}
                                    </button>
                                    @foreach ($developers as $developer)
                                        <button type="button" data-rev-dd-option="{{ $developer->id }}" role="option" aria-selected="{{ $item->assigned_developer_id === $developer->id ? 'true' : 'false' }}"
                                                class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $item->assigned_developer_id === $developer->id ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                            <span data-option-label class="truncate">{{ $developer->name }}</span>
                                            {!! sprintf($checkIcon, $item->assigned_developer_id === $developer->id ? '' : 'invisible') !!}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $item->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.projects.show', $item->project) }}?tab=revision" class="text-gold-dark font-semibold hover:underline whitespace-nowrap">Open in Project</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        {{-- ─── Pagination — 15 per page, client-side (all rows already loaded
             for instant filtering above; this just slices the filtered set) ── --}}
        <div class="flex items-center justify-between gap-4 px-5 py-3 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-400 dark:text-gray-500">
            <span id="rev-page-summary"></span>
            <div class="flex items-center gap-2">
                <button type="button" id="rev-page-prev" onclick="changeRevisionPage(-1)"
                        class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                    Prev
                </button>
                <span id="rev-page-indicator" class="px-2 text-navy dark:text-white font-medium"></span>
                <button type="button" id="rev-page-next" onclick="changeRevisionPage(1)"
                        class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                    Next
                </button>
            </div>
        </div>
    </div>
@endif

<style>
    @keyframes rev-toast-in { from { transform: translateX(115%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes rev-toast-out { from { transform: translateX(0); opacity: 1; } to { transform: translateX(115%); opacity: 0; } }
    @keyframes rev-toast-shrink { from { width: 100%; } to { width: 0%; } }
    .rev-toast { animation: rev-toast-in .4s cubic-bezier(.16,1,.3,1); }
    .rev-toast-out { animation: rev-toast-out .25s ease-in forwards; }
    .rev-toast-progress { animation: rev-toast-shrink 4000ms linear forwards; }
</style>

<script>
    const REVISIONS_PER_PAGE = 15;
    let revisionsCurrentPage = 1;

    // ─── Toast notifications ────────────────────────────────────────────────
    // A small, self-dismissing confirmation for every inline save on this
    // page — since these dropdowns save instantly with no page reload, a
    // toast is the only feedback the admin gets that anything happened.
    function showRevisionToast(message, type = 'success', title = null) {
        const container = document.getElementById('rev-toast-container');
        if (!container) return;

        const isError = type === 'error';
        const toast = document.createElement('div');
        toast.className = 'rev-toast pointer-events-auto relative overflow-hidden rounded-xl border shadow-xl bg-white/97 dark:bg-gray-800/97 backdrop-blur-sm px-4 py-3 flex items-start gap-3 '
            + (isError ? 'border-red-200 dark:border-red-500/30' : 'border-teal/25 dark:border-teal/25');

        toast.innerHTML = `
            <span class="absolute top-0 left-0 right-0 h-[3px] ${isError ? 'bg-red-400' : 'bg-gradient-to-r from-teal via-gold to-teal'}"></span>
            <span class="shrink-0 mt-0.5 w-8 h-8 rounded-full flex items-center justify-center ${isError ? 'bg-red-50 dark:bg-red-500/10 text-red-500' : 'bg-teal/10 text-teal-dark'}">
                ${isError
                    ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>'
                    : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>'}
            </span>
            <div class="flex-1 min-w-0 pt-0.5">
                <p class="text-sm font-semibold ${isError ? 'text-red-600 dark:text-red-400' : 'text-navy dark:text-white'}">${title || (isError ? 'Update failed' : 'Saved')}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-snug">${message}</p>
            </div>
            <button type="button" data-rev-toast-dismiss class="shrink-0 text-gray-300 hover:text-gray-500 dark:text-gray-600 dark:hover:text-gray-300 transition-colors" aria-label="Dismiss notification">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <span class="rev-toast-progress absolute bottom-0 left-0 h-[2px] ${isError ? 'bg-red-300' : 'bg-teal/40'}"></span>
        `;

        const dismiss = () => {
            toast.classList.add('rev-toast-out');
            toast.addEventListener('animationend', () => toast.remove(), { once: true });
        };

        toast.querySelector('[data-rev-toast-dismiss]').addEventListener('click', dismiss);
        setTimeout(dismiss, 4000);
        container.appendChild(toast);
    }

    // ─── Generic pill-dropdown component ───────────────────────────────────
    // One implementation drives every dropdown on this page (5 filters + the
    // 3 inline-edit dropdowns per row): a button toggle showing the current
    // selection as a colored pill, and a floating listbox menu with dots +
    // checkmarks. `kind === 'filter'` just updates the filter/pagination
    // state; any other kind saves the change via the same admin.uploads.*
    // endpoints the per-project Revisions tab already uses.
    function closeRevDropdown(wrap) {
        wrap.querySelector('[data-rev-dd-menu]')?.classList.add('hidden');
        const toggle = wrap.querySelector('[data-rev-dd-toggle]');
        toggle?.setAttribute('aria-expanded', 'false');
        const chevron = wrap.querySelector('[data-rev-dd-chevron]');
        if (chevron) chevron.style.transform = '';
    }

    function closeAllRevDropdowns() {
        document.querySelectorAll('[data-rev-dd]').forEach(closeRevDropdown);
    }

    function openRevDropdown(wrap) {
        closeAllRevDropdowns();
        wrap.querySelector('[data-rev-dd-menu]')?.classList.remove('hidden');
        const toggle = wrap.querySelector('[data-rev-dd-toggle]');
        toggle?.setAttribute('aria-expanded', 'true');
        const chevron = wrap.querySelector('[data-rev-dd-chevron]');
        if (chevron) chevron.style.transform = 'rotate(180deg)';
    }

    function applyRevDropdownSelection(wrap, option, value) {
        const toggle = wrap.querySelector('[data-rev-dd-toggle]');
        const label = wrap.querySelector('[data-rev-dd-label]');

        if (label) {
            const inner = option.querySelector('[data-option-label]');
            label.innerHTML = inner ? inner.innerHTML : option.textContent.trim();
        }
        if (toggle?.dataset.colorClass) toggle.classList.remove(...toggle.dataset.colorClass.split(' '));
        const newColorClass = option.dataset.colorClass;
        if (newColorClass && toggle) {
            toggle.classList.add(...newColorClass.split(' '));
            toggle.dataset.colorClass = newColorClass;
        }

        wrap.querySelectorAll('[data-rev-dd-option]').forEach((opt) => {
            const isSelected = opt === option;
            opt.setAttribute('aria-selected', isSelected ? 'true' : 'false');
            opt.classList.toggle('text-gold-dark', isSelected);
            opt.classList.toggle('font-semibold', isSelected);
            opt.classList.toggle('text-gray-700', !isSelected);
            opt.classList.toggle('dark:text-gray-300', !isSelected);
            opt.querySelector('[data-option-check]')?.classList.toggle('invisible', !isSelected);
        });

        wrap.dataset.value = value;
    }

    function revertRevDropdown(wrap, previousValue) {
        const option = wrap.querySelector('[data-rev-dd-option="' + previousValue + '"]');
        if (option) applyRevDropdownSelection(wrap, option, previousValue);
    }

    // Mirrors the same admin.uploads.* endpoints the per-project Revisions tab
    // uses — this is inline editing of the same data, not a separate copy.
    function saveRevDropdownField(wrap, value, previousValue) {
        const kind = wrap.dataset.revDdKind;
        const fieldMap = { status: 'status', priority: 'priority', developer: 'assigned_developer_id' };
        const field = fieldMap[kind];
        const body = { [field]: value };

        if (kind === 'status' && value === 'closed') {
            body.closed_reason = wrap.dataset.pendingReason || '';
        }
        if (kind === 'priority') {
            body.estimated_completion_date = wrap.dataset.estimatedCompletionDate || '';
        }

        fetch(wrap.dataset.revDdUrl, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(body),
        })
        .then((res) => {
            if (!res.ok) throw new Error('Failed to update');

            const row = wrap.closest('tr');
            if (row) row.dataset[kind === 'developer' ? 'developer' : kind] = value || 'unassigned';
            renderRevisionsPage();

            const displayLabel = wrap.querySelector('[data-rev-dd-label]')?.textContent.trim() || '';
            const clientName = row?.querySelector('td:first-child p')?.textContent.trim() || 'this request';
            const messages = {
                status: `${clientName}'s revision is now "${displayLabel}".`,
                priority: `Priority for ${clientName} set to ${displayLabel}.`,
                developer: value ? `${clientName}'s revision assigned to ${displayLabel}.` : `${clientName}'s revision is now unassigned.`,
            };
            showRevisionToast(messages[kind] || 'Your change was saved.', 'success');
        })
        .catch(() => {
            revertRevDropdown(wrap, previousValue);
            showRevisionToast('That change could not be saved — please try again.', 'error');
        });
    }

    function initRevDropdown(wrap) {
        if (wrap.dataset.bound) return;
        wrap.dataset.bound = '1';

        const toggle = wrap.querySelector('[data-rev-dd-toggle]');
        const menu = wrap.querySelector('[data-rev-dd-menu]');
        if (!toggle || !menu) return;

        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.contains('hidden') ? openRevDropdown(wrap) : closeRevDropdown(wrap);
        });

        menu.querySelectorAll('[data-rev-dd-option]').forEach((option) => {
            option.addEventListener('click', () => {
                const value = option.getAttribute('data-rev-dd-option');
                const previousValue = wrap.dataset.value ?? '';
                const kind = wrap.dataset.revDdKind;

                if (kind === 'status' && value === 'closed') {
                    const reason = prompt('Reason for closing this revision request:');
                    if (!reason) { closeRevDropdown(wrap); return; }
                    wrap.dataset.pendingReason = reason;
                }

                applyRevDropdownSelection(wrap, option, value);
                closeRevDropdown(wrap);

                if (kind === 'filter') {
                    filterRevisions();
                    return;
                }

                saveRevDropdownField(wrap, value, previousValue);
            });
        });
    }

    document.querySelectorAll('[data-rev-dd]').forEach(initRevDropdown);

    document.addEventListener('click', () => closeAllRevDropdowns());
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeAllRevDropdowns(); });

    // ─── Filtering + pagination ─────────────────────────────────────────────
    function rowMatchesFilters(row) {
        const client = document.getElementById('rev-filter-client')?.dataset.value || '';
        const project = document.getElementById('rev-filter-project')?.dataset.value || '';
        const status = document.getElementById('rev-filter-status')?.dataset.value || '';
        const priority = document.getElementById('rev-filter-priority')?.dataset.value || '';
        const developer = document.getElementById('rev-filter-developer')?.dataset.value || '';
        const from = document.getElementById('rev-filter-date-from')?.value || '';
        const to = document.getElementById('rev-filter-date-to')?.value || '';

        return (!client || row.dataset.client === client)
            && (!project || row.dataset.project === project)
            && (!status || row.dataset.status === status)
            && (!priority || row.dataset.priority === priority)
            && (!developer || row.dataset.developer === developer)
            && (!from || row.dataset.date >= from)
            && (!to || row.dataset.date <= to);
    }

    function renderRevisionsPage() {
        const rows = Array.from(document.querySelectorAll('#revisions-table tbody tr'));
        const matched = rows.filter(rowMatchesFilters);
        const total = matched.length;
        const totalPages = Math.max(1, Math.ceil(total / REVISIONS_PER_PAGE));
        revisionsCurrentPage = Math.min(Math.max(revisionsCurrentPage, 1), totalPages);

        const start = (revisionsCurrentPage - 1) * REVISIONS_PER_PAGE;
        const end = start + REVISIONS_PER_PAGE;
        const pageRows = new Set(matched.slice(start, end));

        rows.forEach((row) => row.classList.toggle('hidden', !pageRows.has(row)));

        document.getElementById('rev-filter-empty')?.classList.toggle('hidden', total > 0);

        const summary = document.getElementById('rev-page-summary');
        if (summary) {
            summary.textContent = total === 0
                ? 'No matching revision requests'
                : `Showing ${start + 1}–${Math.min(end, total)} of ${total}`;
        }
        const indicator = document.getElementById('rev-page-indicator');
        if (indicator) indicator.textContent = `Page ${revisionsCurrentPage} of ${totalPages}`;

        const prevBtn = document.getElementById('rev-page-prev');
        const nextBtn = document.getElementById('rev-page-next');
        if (prevBtn) prevBtn.disabled = revisionsCurrentPage <= 1;
        if (nextBtn) nextBtn.disabled = revisionsCurrentPage >= totalPages;
    }

    function filterRevisions() {
        revisionsCurrentPage = 1;
        renderRevisionsPage();
    }

    function changeRevisionPage(delta) {
        revisionsCurrentPage += delta;
        renderRevisionsPage();
    }

    function clearRevisionFilters() {
        ['rev-filter-client', 'rev-filter-project', 'rev-filter-status', 'rev-filter-priority', 'rev-filter-developer'].forEach((id) => {
            const wrap = document.getElementById(id);
            const allOption = wrap?.querySelector('[data-rev-dd-option=""]');
            if (wrap && allOption) applyRevDropdownSelection(wrap, allOption, '');
        });
        document.getElementById('rev-filter-date-from').value = '';
        document.getElementById('rev-filter-date-to').value = '';
        filterRevisions();
    }

    renderRevisionsPage();
</script>

@endsection
