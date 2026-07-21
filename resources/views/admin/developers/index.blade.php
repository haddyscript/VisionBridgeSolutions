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

    // Workload stat boxes — muted gray when 0, richer tint + darker type when >0.
    $statBoxes = [
        'not_started' => ['label' => 'Not Started', 'activeBg' => 'bg-gray-200 dark:bg-gray-600', 'activeText' => 'text-navy dark:text-white'],
        'in_progress' => ['label' => 'In Progress', 'activeBg' => 'bg-gold/25', 'activeText' => 'text-gold-dark'],
        'waiting_on_visionbridge' => ['label' => 'Waiting on VB', 'activeBg' => 'bg-purple-100 dark:bg-purple-500/20', 'activeText' => 'text-purple-700 dark:text-purple-300'],
        'completed' => ['label' => 'Completed', 'activeBg' => 'bg-teal/25', 'activeText' => 'text-teal-dark dark:text-teal-300'],
    ];
@endphp

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Every "Developer" job-title account, their current workload, and any Work Orders still waiting for a developer.</p>

{{-- Global controls --}}
<div class="flex flex-col sm:flex-row gap-3 mb-6">
    <div class="relative flex-1 max-w-sm">
        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" id="developer-search" placeholder="Search developers by name…"
               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 dark:text-white pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
    </div>
    <div class="w-full sm:w-56">
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

    {{-- Left: developer workload cards — wraps into 2 columns at xl as more developers are added --}}
    <div class="lg:col-span-2">
        @if ($developers->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
                <p class="text-gray-500 dark:text-gray-400">No team members have the "Developer" job title yet — set one on the Team Members page.</p>
            </div>
        @else
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                @foreach ($roster as $row)
                    @php $hasActiveWork = $row['activeItems']->isNotEmpty(); @endphp
                    <div class="developer-card bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6"
                         data-name="{{ strtolower($row['developer']->name) }}" data-has-active="{{ $hasActiveWork ? '1' : '0' }}">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-full bg-navy text-gold text-sm font-bold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($row['developer']->name, 0, 1)) }}
                                </span>
                                <div>
                                    <p class="font-semibold text-navy dark:text-white">{{ $row['developer']->name }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $row['developer']->email }}</p>
                                </div>
                            </div>
                            @if ($row['developer']->is_active ?? true)
                                <span class="text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-teal/10 text-teal-dark">Active</span>
                            @else
                                <span class="text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-red-50 text-red-500">Inactive</span>
                            @endif
                        </div>

                        {{-- Workload breakdown --}}
                        <div class="grid grid-cols-4 gap-2 mb-4">
                            @foreach ($statBoxes as $key => $box)
                                @php $count = $row['counts'][$key]; @endphp
                                <div class="text-center rounded-lg py-2.5 {{ $count > 0 ? $box['activeBg'] : 'bg-gray-50 dark:bg-gray-900' }}">
                                    <p class="text-lg font-bold {{ $count > 0 ? $box['activeText'] : 'text-gray-300 dark:text-gray-600' }}">{{ $count }}</p>
                                    <p class="text-[0.65rem] uppercase tracking-wide {{ $count > 0 ? 'text-gray-500 dark:text-gray-400' : 'text-gray-300 dark:text-gray-600' }}">{{ $box['label'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Active assigned items --}}
                        @if ($row['activeItems']->isEmpty())
                            <p class="text-sm text-gray-400 dark:text-gray-500">No active Work Orders right now.</p>
                        @else
                            <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-56 overflow-y-auto pr-1">
                                @foreach ($row['activeItems'] as $item)
                                    @include('admin.developers._item-row', ['item' => $item, 'statusColors' => $statusColors, 'developers' => $developers, 'assignedDeveloperId' => $row['developer']->id])
                                @endforeach
                            </div>
                        @endif

                        {{-- History — opens a modal with the full completed list
                             instead of expanding inline (which used to push the
                             rest of the card/grid around). A real bordered
                             button, not plain link-style text, so it reads as
                             clickable rather than just another label. --}}
                        @if ($row['completedItems']->isNotEmpty())
                            <button type="button"
                                    class="developer-history-btn mt-4 inline-flex items-center gap-1.5 text-xs font-semibold rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 hover:border-gold hover:text-gold-dark text-navy dark:text-white px-3 py-1.5 transition-colors"
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
            <p id="developer-empty-state" class="hidden text-sm text-gray-400 dark:text-gray-500 text-center py-10">No developers match your search.</p>
        @endif
    </div>

    {{-- Right: unassigned, side-by-side with developer availability. Made
         deliberately loud (red badge + icon + bordered card, not a plain
         heading) — this list is easy to overlook otherwise, and it's the
         one thing on this page that always needs a human to act on it. --}}
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

        @if ($unassigned->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-8 text-center">
                <svg class="w-8 h-8 text-teal-dark mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-gray-500 dark:text-gray-400">Everything is assigned. Nice.</p>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl border-2 border-red-200 dark:border-red-500/30 divide-y divide-gray-100 dark:divide-gray-700 max-h-[calc(100vh-220px)] overflow-y-auto">
                <div class="sticky top-0 bg-red-50 dark:bg-red-500/10 px-4 py-2.5 border-b border-red-200 dark:border-red-500/30">
                    <p class="text-xs font-bold uppercase tracking-wide text-red-700 dark:text-red-400">{{ $unassigned->count() }} {{ $unassigned->count() === 1 ? 'item' : 'items' }} waiting for assignment</p>
                </div>
                @foreach ($unassigned as $item)
                    <div class="p-4">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <p class="text-sm font-semibold text-navy dark:text-white">{{ $item['client_name'] }}</p>
                            <span class="text-[0.65rem] font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 shrink-0">{{ $item['created_at']->format('M j') }}</span>
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-1.5 flex flex-wrap items-center gap-1.5">
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

{{-- Full-page reload still happens on assign (see the form below) — this
     overlay just covers the wait with a spinner instead of the dropdown
     appearing to do nothing until the new page arrives. --}}
<div id="assign-loading-overlay" class="hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl px-6 py-5 flex items-center gap-3 shadow-lg">
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
    <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg max-h-[85vh] flex flex-col">
        <div class="flex items-center justify-between gap-4 px-5 py-4 border-b border-gray-100 dark:border-gray-700 shrink-0">
            <div class="min-w-0">
                <h3 id="developer-history-modal-title" class="font-bold text-navy dark:text-white truncate"></h3>
                <p class="text-xs text-gray-400 dark:text-gray-500">Completed Work Orders</p>
            </div>
            <button type="button" data-history-modal-close class="shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors" aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="developer-history-modal-body" class="overflow-y-auto px-5"></div>
    </div>
</div>

<script>
    document.querySelectorAll('.assign-developer-form').forEach((form) => {
        form.addEventListener('submit', () => {
            document.getElementById('assign-loading-overlay')?.classList.remove('hidden');
        });
    });

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
