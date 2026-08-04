@extends('layouts.admin')

@section('title', 'All Projects – Admin')
@section('page-title', 'All Projects')

@section('content')

@php
    $statusLabels = [
        'onboarding'  => 'Onboarding',
        'in_progress' => 'In Progress',
        'review'      => 'In Review',
        'launched'    => 'Launched',
        'maintenance' => 'Care',
    ];
    $statusColors = [
        'onboarding'  => 'bg-gold/15 text-gold-dark',
        'in_progress' => 'bg-navy/10 text-navy dark:bg-white/10 dark:text-white',
        'review'      => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300',
        'launched'    => 'bg-teal/15 text-teal-dark',
        // Fixed contrast: was gray-100/gray-500 (hard to read) — now a readable
        // emerald, distinct from the teal "Launched" badge.
        'maintenance' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
    ];
    $statusDots = [
        'onboarding'  => 'bg-gold',
        'in_progress' => 'bg-navy dark:bg-white',
        'review'      => 'bg-indigo-400',
        'launched'    => 'bg-teal',
        'maintenance' => 'bg-emerald-500',
    ];
@endphp

{{-- Controls: search, status filter, new project --}}
<div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-5">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
        </svg>
        <input type="text" id="project-search" placeholder="Search client, email, or project..." autocomplete="off"
               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-navy-dark dark:text-white pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
    </div>
    <div class="w-full sm:w-52 shrink-0">
        @include('admin._dropdown', [
            'name' => 'status_filter',
            'domId' => 'project-status-filter',
            'options' => collect($statusLabels)->map(fn ($label, $key) => [
                'value' => $key,
                'label' => $label,
                'dot' => $statusDots[$key] ?? 'bg-gray-400',
            ])->values()->all(),
            'selected' => '',
            'placeholder' => 'All Statuses',
        ])
    </div>
    <a href="{{ route('admin.intake-submissions.index') }}"
       title="New projects start from an intake submission"
       class="inline-flex items-center justify-center gap-1.5 shrink-0 bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        New Project
    </a>
</div>

@if ($projects->isEmpty())
    <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No client projects yet.</p>
    </div>
@else
    <div id="projects-table" class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-navy-dark text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Project</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Progress</th>
                    <th class="px-5 py-3">Files</th>
                    <th class="px-5 py-3">Revisions</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($projects as $project)
                    @php
                        $pct = $project->progressPercent();
                        $revisions = $project->uploads->where('category', 'revision');
                        $openRevisions = $revisions->where('status', '!=', 'completed')->count();
                        $overdueRevisions = $revisions->filter(fn ($upload) => $upload->isOverdue())->count();
                        // Data-integrity flag: "done" but revisions still unresolved.
                        $progressInconsistent = $pct >= 100 && ($openRevisions > 0 || $overdueRevisions > 0);
                        $searchText = strtolower($project->user->name.' '.$project->user->email.' '.$project->name);
                        $isNewClient = $project->user->created_at && $project->user->created_at->gt(now()->subDay());
                    @endphp
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30 {{ $isNewClient ? 'bg-gold/5 dark:bg-gold/10' : '' }}"
                        data-search="{{ $searchText }}" data-status="{{ $project->status }}">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white flex items-center gap-2">
                                {{ $project->user->name }}
                                @if ($isNewClient)
                                    <span class="inline-flex items-center text-[0.65rem] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gold/15 text-gold-dark ring-1 ring-gold/30" title="Joined {{ $project->user->created_at->diffForHumans() }}">New</span>
                                @endif
                                @if ($project->user->isOnline())
                                    <span class="inline-flex items-center gap-1 text-[0.65rem] font-semibold uppercase tracking-wide text-teal-dark">
                                        <span class="w-2 h-2 rounded-full bg-teal-dark" title="Online now"></span>
                                        Online
                                    </span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $project->user->email }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $project->name }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-500' }}">
                                {{ $statusLabels[$project->status] ?? $project->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2 min-w-[8.5rem]">
                                <div class="w-24 h-2 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                    <div class="h-full rounded-full {{ $progressInconsistent ? 'bg-amber-500' : 'bg-gold' }}" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-xs font-semibold text-navy dark:text-white">{{ $pct }}%</span>
                                @if ($progressInconsistent)
                                    <span class="text-amber-500" title="Marked 100% but still has unresolved revisions">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a1 1 0 00.86 1.5h18.64a1 1 0 00.86-1.5L13.71 3.86a1 1 0 00-1.42 0z"/></svg>
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $project->uploads->whereNotIn('category', ['content', 'revision'])->count() }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">
                            {{ $revisions->count() }}
                            @if ($openRevisions > 0)
                                <span class="ml-1 inline-block text-xs font-semibold px-2 py-0.5 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500">{{ $openRevisions }} open</span>
                            @endif
                            @if ($overdueRevisions > 0)
                                <span class="ml-1 inline-block text-xs font-semibold px-2 py-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400">{{ $overdueRevisions }} overdue</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.projects.show', $project) }}" class="text-gold-dark font-semibold hover:underline whitespace-nowrap">Manage</a>
                        </td>
                    </tr>
                @endforeach
                <tr id="projects-empty-filter" class="hidden">
                    <td colspan="7" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400">
                        No projects match your search or filter.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="projects-pagination" class="hidden flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4 text-sm text-gray-600 dark:text-gray-400">
        <p>Showing <span id="pagination-showing-start"></span>–<span id="pagination-showing-end"></span> of <span id="pagination-showing-total"></span></p>
        <div class="flex items-center gap-2">
            <button type="button" id="pagination-prev" class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-gray-700/30">Previous</button>
            <span id="pagination-page-info" class="px-2 whitespace-nowrap"></span>
            <button type="button" id="pagination-next" class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-gray-700/30">Next</button>
        </div>
    </div>

    <script>
        (function () {
            const PER_PAGE = 10;
            let currentPage = 1;

            const search = document.getElementById('project-search');
            const statusFilter = document.getElementById('project-status-filter-input');
            const emptyRow = document.getElementById('projects-empty-filter');
            const rows = Array.from(document.querySelectorAll('#projects-table tbody tr[data-search]'));

            const pagination = document.getElementById('projects-pagination');
            const prevBtn = document.getElementById('pagination-prev');
            const nextBtn = document.getElementById('pagination-next');
            const pageInfo = document.getElementById('pagination-page-info');
            const showingStart = document.getElementById('pagination-showing-start');
            const showingEnd = document.getElementById('pagination-showing-end');
            const showingTotal = document.getElementById('pagination-showing-total');

            function apply() {
                const q = (search.value || '').trim().toLowerCase();
                const status = statusFilter.value;

                const matches = rows.filter(function (row) {
                    const matchesSearch = !q || row.dataset.search.includes(q);
                    const matchesStatus = !status || row.dataset.status === status;
                    return matchesSearch && matchesStatus;
                });

                const totalPages = Math.max(1, Math.ceil(matches.length / PER_PAGE));
                if (currentPage > totalPages) currentPage = totalPages;

                const start = (currentPage - 1) * PER_PAGE;
                const pageRows = matches.slice(start, start + PER_PAGE);
                const visibleSet = new Set(pageRows);

                rows.forEach(function (row) {
                    row.classList.toggle('hidden', !visibleSet.has(row));
                });

                emptyRow.classList.toggle('hidden', matches.length > 0);
                pagination.classList.toggle('hidden', matches.length === 0);

                if (matches.length > 0) {
                    showingStart.textContent = start + 1;
                    showingEnd.textContent = Math.min(start + PER_PAGE, matches.length);
                    showingTotal.textContent = matches.length;
                    pageInfo.textContent = 'Page ' + currentPage + ' of ' + totalPages;
                    prevBtn.disabled = currentPage <= 1;
                    nextBtn.disabled = currentPage >= totalPages;
                }
            }

            search.addEventListener('input', function () { currentPage = 1; apply(); });
            statusFilter.addEventListener('change', function () { currentPage = 1; apply(); });
            prevBtn.addEventListener('click', function () { if (currentPage > 1) { currentPage--; apply(); } });
            nextBtn.addEventListener('click', function () { currentPage++; apply(); });

            apply();
        })();
    </script>
@endif

@endsection
