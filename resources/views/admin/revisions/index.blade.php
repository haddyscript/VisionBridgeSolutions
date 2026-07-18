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
    $priorityColors = [
        'low' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
        'medium' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-500',
        'high' => 'bg-gold/15 text-gold-dark',
        'urgent' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
    ];
@endphp

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Every revision request across every project, in one place — update status, priority, and developer assignment right from this list. This reads the same data as each project's own Revisions tab, so the two always stay in sync.</p>

@if ($revisions->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No revision requests yet.</p>
    </div>
@else
    {{-- ─── Filter toolbar ─────────────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center gap-2.5 mb-4">
        <select id="rev-filter-client" onchange="filterRevisions()" class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            <option value="">All clients</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}">{{ $client->name }}</option>
            @endforeach
        </select>
        <select id="rev-filter-project" onchange="filterRevisions()" class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            <option value="">All projects</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}">{{ $project->name }}</option>
            @endforeach
        </select>
        <select id="rev-filter-status" onchange="filterRevisions()" class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            <option value="">All statuses</option>
            @foreach (\App\Models\Upload::STATUSES as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        <select id="rev-filter-priority" onchange="filterRevisions()" class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            <option value="">All priorities</option>
            @foreach (\App\Models\Upload::PRIORITIES as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        <select id="rev-filter-developer" onchange="filterRevisions()" class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            <option value="">All developers</option>
            <option value="unassigned">Unassigned</option>
            @foreach ($developers as $developer)
                <option value="{{ $developer->id }}">{{ $developer->name }}</option>
            @endforeach
        </select>
        <div class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
            <label for="rev-filter-date-from" class="text-xs">From</label>
            <input type="date" id="rev-filter-date-from" onchange="filterRevisions()" class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-2.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            <label for="rev-filter-date-to" class="text-xs">To</label>
            <input type="date" id="rev-filter-date-to" onchange="filterRevisions()" class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-2.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        </div>
        <button type="button" onclick="clearRevisionFilters()" class="text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white underline">Clear filters</button>
        <span id="rev-filter-count" class="text-xs text-gray-400 dark:text-gray-500 ml-auto"></span>
    </div>
    <p id="rev-filter-empty" class="hidden text-sm text-gray-400 dark:text-gray-500 mb-4">No revision requests match these filters.</p>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
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
                            <p class="truncate">{{ \Illuminate\Support\Str::limit($item->body ?? $item->original_name ?? 'Revision #'.$item->id, 60) }}</p>
                            @if ($item->isOverdue())
                                <span class="inline-block mt-1 text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400">Overdue</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <select onchange="updateRevisionField(this, '{{ route('admin.uploads.status', $item) }}', 'status')"
                                    data-current="{{ $item->status }}" data-colors="status"
                                    class="revision-inline-select text-xs font-semibold rounded-full pl-3 pr-2 py-1 border-0 focus:outline-none focus:ring-2 focus:ring-gold {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-500' }}">
                                @foreach (\App\Models\Upload::STATUSES as $value => $label)
                                    <option value="{{ $value }}" {{ $item->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-5 py-3.5">
                            <select onchange="updateRevisionField(this, '{{ route('admin.uploads.details', $item) }}', 'priority')"
                                    data-current="{{ $item->priority }}" data-colors="priority"
                                    data-extra-field="estimated_completion_date" data-extra-value="{{ $item->estimated_completion_date?->format('Y-m-d') }}"
                                    class="revision-inline-select text-xs font-semibold rounded-full pl-3 pr-2 py-1 border-0 focus:outline-none focus:ring-2 focus:ring-gold {{ $priorityColors[$item->priority] ?? $priorityColors['medium'] }}">
                                @foreach (\App\Models\Upload::PRIORITIES as $value => $label)
                                    <option value="{{ $value }}" {{ $item->priority === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-5 py-3.5">
                            <select onchange="updateRevisionField(this, '{{ route('admin.uploads.assign-developer', $item) }}', 'assigned_developer_id')"
                                    data-current="{{ $item->assigned_developer_id }}"
                                    class="revision-inline-select text-xs font-medium rounded-lg px-2.5 py-1.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-navy dark:text-white focus:outline-none focus:ring-2 focus:ring-gold">
                                <option value="">Unassigned</option>
                                @foreach ($developers as $developer)
                                    <option value="{{ $developer->id }}" {{ $item->assigned_developer_id === $developer->id ? 'selected' : '' }}>{{ $developer->name }}</option>
                                @endforeach
                            </select>
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
    </div>
@endif

<script>
    const revisionStatusColors = {
        request_received: 'bg-red-50 dark:bg-red-500/10 text-red-500',
        under_review: 'bg-blue-50 dark:bg-blue-500/10 text-blue-500',
        in_progress: 'bg-gold/15 text-gold-dark',
        waiting_on_client: 'bg-purple-50 dark:bg-purple-500/10 text-purple-500',
        needs_approval: 'bg-orange-50 dark:bg-orange-500/10 text-orange-500',
        completed: 'bg-teal/10 text-teal-dark',
        closed: 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
    };
    const revisionPriorityColors = {
        low: 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
        medium: 'bg-blue-50 dark:bg-blue-500/10 text-blue-500',
        high: 'bg-gold/15 text-gold-dark',
        urgent: 'bg-red-50 dark:bg-red-500/10 text-red-500',
    };
    const revisionColorSets = { status: revisionStatusColors, priority: revisionPriorityColors };
    const revisionColorTokens = [...new Set(
        [...Object.values(revisionStatusColors), ...Object.values(revisionPriorityColors)]
            .flatMap(c => c.split(' '))
    )];

    // Mirrors the same admin.uploads.* endpoints the per-project Revisions tab
    // uses — this is inline editing of the same data, not a separate copy.
    function updateRevisionField(select, url, field) {
        const value = select.value;
        const previousValue = select.dataset.current || '';

        const body = { [field]: value };

        if (field === 'status' && value === 'closed') {
            const reason = prompt('Reason for closing this revision request:');
            if (!reason) {
                select.value = previousValue;
                return;
            }
            body.closed_reason = reason;
        }

        if (field === 'priority') {
            body.estimated_completion_date = select.dataset.extraValue || '';
        }

        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(body),
        })
        .then(res => {
            if (!res.ok) throw new Error('Failed to update');

            const colors = revisionColorSets[select.dataset.colors];
            if (colors) {
                select.classList.remove(...revisionColorTokens, 'bg-gray-100', 'text-gray-500');
                select.classList.add(...(colors[value] || 'bg-gray-100 text-gray-500').split(' '));
            }
            select.dataset.current = value;
            select.closest('tr').dataset[field === 'assigned_developer_id' ? 'developer' : field] = value || 'unassigned';
        })
        .catch(() => {
            select.value = previousValue;
            alert('Could not save that change. Please try again.');
        });
    }

    function filterRevisions() {
        const client = document.getElementById('rev-filter-client').value;
        const project = document.getElementById('rev-filter-project').value;
        const status = document.getElementById('rev-filter-status').value;
        const priority = document.getElementById('rev-filter-priority').value;
        const developer = document.getElementById('rev-filter-developer').value;
        const from = document.getElementById('rev-filter-date-from').value;
        const to = document.getElementById('rev-filter-date-to').value;

        let visibleCount = 0;

        document.querySelectorAll('#revisions-table tbody tr').forEach(row => {
            const visible = (!client || row.dataset.client === client)
                && (!project || row.dataset.project === project)
                && (!status || row.dataset.status === status)
                && (!priority || row.dataset.priority === priority)
                && (!developer || row.dataset.developer === developer)
                && (!from || row.dataset.date >= from)
                && (!to || row.dataset.date <= to);

            row.classList.toggle('hidden', !visible);
            if (visible) visibleCount++;
        });

        document.getElementById('rev-filter-empty')?.classList.toggle('hidden', visibleCount > 0);
        const countEl = document.getElementById('rev-filter-count');
        if (countEl) countEl.textContent = `Showing ${visibleCount} of ${document.querySelectorAll('#revisions-table tbody tr').length}`;
    }

    function clearRevisionFilters() {
        ['rev-filter-client', 'rev-filter-project', 'rev-filter-status', 'rev-filter-priority', 'rev-filter-developer', 'rev-filter-date-from', 'rev-filter-date-to']
            .forEach(id => document.getElementById(id).value = '');
        filterRevisions();
    }

    document.addEventListener('DOMContentLoaded', filterRevisions);
</script>

@endsection
