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
    $proposalColors = [
        'draft' => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
        'sent' => 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-300 ring-1 ring-inset ring-indigo-200 dark:ring-indigo-500/20',
        'under_review' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-800 dark:text-amber-400 ring-1 ring-inset ring-amber-200 dark:ring-amber-500/20',
        'accepted' => 'bg-teal/10 text-teal-dark ring-1 ring-inset ring-teal/20',
        'declined' => 'bg-red-50 dark:bg-red-500/10 text-red-500 ring-1 ring-inset ring-red-200 dark:ring-red-500/20',
    ];
@endphp

{{-- Controls: search + status filter — same toolbar pattern as All Projects,
     kept ready to grow (e.g. a proposal-status filter) without a layout change. --}}
<div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-5">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
        </svg>
        <input type="text" id="request-search" placeholder="Search client, email, or title..." autocomplete="off"
               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 dark:text-white pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
    </div>
    <select id="request-status-filter"
            class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        <option value="">All statuses</option>
        @foreach (\App\Models\ProjectRequest::STATUSES as $key => $label)
            <option value="{{ $key }}">{{ $label }}</option>
        @endforeach
    </select>
    <button type="button" data-modal="new-request-modal"
            class="modal-trigger inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-navy hover:bg-navy-light text-white text-sm font-semibold rounded-lg transition-colors whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Project Request
    </button>
</div>

@if ($requests->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No project requests yet.</p>
    </div>
@else
    <div id="requests-table" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Title</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Proposal</th>
                    <th class="px-5 py-3">Submitted</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($requests as $item)
                    @php $searchText = strtolower($item->user->name.' '.$item->user->email.' '.$item->title); @endphp
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30" data-search="{{ $searchText }}" data-status="{{ $item->status }}">
                        <td class="px-5 py-4 align-middle">
                            <p class="font-medium text-navy dark:text-white">{{ $item->user->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $item->user->email }}</p>
                        </td>
                        <td class="px-5 py-4 align-middle font-semibold text-navy dark:text-white">
                            {{ $item->title }}
                            @if ($item->isInternal())
                                <span class="inline-block ml-1.5 text-[0.65rem] font-bold uppercase tracking-wide px-1.5 py-0.5 rounded bg-navy/10 dark:bg-white/10 text-navy dark:text-white align-middle" title="Created internally by {{ $item->createdByAdmin?->name ?? 'an admin' }} — not submitted by the client">
                                    Internal
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 align-middle">
                            <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$item->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                                {{ \App\Models\ProjectRequest::STATUSES[$item->status] ?? $item->status }}
                            </span>
                        </td>
                        <td class="px-5 py-4 align-middle">
                            @if ($item->proposal_status)
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $proposalColors[$item->proposal_status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                                    {{ \App\Models\ProjectRequest::PROPOSAL_STATUSES[$item->proposal_status] ?? $item->proposal_status }}
                                </span>
                            @else
                                <span class="inline-block text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-700/50 text-gray-400 dark:text-gray-500">
                                    No Proposal
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 align-middle text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $item->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-4 align-middle text-right">
                            <a href="{{ route('admin.project-requests.show', $item) }}"
                               class="inline-flex items-center gap-1.5 border border-gray-200 dark:border-gray-600 hover:border-gold hover:bg-gold/10 text-gray-600 dark:text-gray-300 hover:text-gold-dark text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
                <tr id="requests-empty-filter" class="hidden">
                    <td colspan="6" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400">
                        No requests match your search or filter.
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Footer: record count now, pagination controls slot in unchanged as volume grows. --}}
        <div class="flex items-center justify-between gap-4 px-5 py-3 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-400 dark:text-gray-500">
            <span id="requests-count-label">Showing {{ $requests->count() }} of {{ $requests->total() }} request{{ $requests->total() === 1 ? '' : 's' }}</span>
        </div>
    </div>

    <div class="mt-6">
        {{ $requests->links() }}
    </div>

    <script>
        (function () {
            const search = document.getElementById('request-search');
            const statusFilter = document.getElementById('request-status-filter');
            const emptyRow = document.getElementById('requests-empty-filter');
            const countLabel = document.getElementById('requests-count-label');
            const rows = Array.from(document.querySelectorAll('#requests-table tbody tr[data-search]'));
            const totalCount = {{ $requests->total() }};

            function apply() {
                const q = (search.value || '').trim().toLowerCase();
                const status = statusFilter.value;
                let visible = 0;

                rows.forEach(function (row) {
                    const matchesSearch = !q || row.dataset.search.includes(q);
                    const matchesStatus = !status || row.dataset.status === status;
                    const show = matchesSearch && matchesStatus;
                    row.classList.toggle('hidden', !show);
                    if (show) visible++;
                });

                emptyRow.classList.toggle('hidden', visible > 0);
                countLabel.textContent = (q || status)
                    ? 'Showing ' + visible + ' of ' + rows.length + ' on this page'
                    : 'Showing ' + rows.length + ' of ' + totalCount + ' request' + (totalCount === 1 ? '' : 's');
            }

            search.addEventListener('input', apply);
            statusFilter.addEventListener('change', apply);
        })();
    </script>
@endif

{{-- New Project Request modal — the admin-created "internal work order" path, alongside the existing client-submitted one. --}}
<div id="new-request-modal" class="admin-modal hidden fixed inset-0 z-[60] items-center justify-center bg-black/40 px-4">
    <div class="admin-modal-panel bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xl w-full max-w-lg max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <p class="font-semibold text-navy dark:text-white">New Project Request</p>
            <button type="button" class="admin-modal-close w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors shrink-0" aria-label="Close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.project-requests.store') }}" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            <p class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50 rounded-lg px-3 py-2">
                For internal work not submitted by a client — e.g. research/feasibility work on an existing account. It's tied to a client for record-keeping but never appears in their portal or notifies them.
            </p>

            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Client</label>
                <select name="user_id" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                    <option value="">Select a client...</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Title</label>
                <input type="text" name="title" required placeholder="e.g. Unity Auto Group Development Research &amp; Feasibility"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Description</label>
                <textarea name="description" rows="3" required
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Priority</label>
                    <select name="priority" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                        @foreach (\App\Models\ProjectRequest::PRIORITIES as $value => $label)
                            <option value="{{ $value }}" {{ $value === 'medium' ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Due Date</label>
                    <input type="date" name="due_date" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Assign Developer (optional)</label>
                <select name="assigned_developer_id" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                    <option value="">Unassigned</option>
                    @foreach ($developers as $developer)
                        <option value="{{ $developer->id }}">{{ $developer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Proposal Document (optional)</label>
                <input type="file" name="proposal_document" class="w-full text-sm text-gray-600 dark:text-gray-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gold/15 file:text-gold-dark hover:file:bg-gold/25">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" class="admin-modal-close px-4 py-2 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-navy hover:bg-navy-light text-white text-sm font-semibold rounded-lg transition-colors">
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

    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('.admin-modal:not(.hidden)').forEach(closeAdminModal);
    });
</script>

@endsection
