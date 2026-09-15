@extends('layouts.admin')

@section('title', 'Intake Submissions – Admin')
@section('page-title', 'Intake Submissions')

@section('content')

@php
    $statusLabels = [
        'new'            => 'New',
        'contacted'      => 'Contacted',
        'converted'      => 'Converted',
        'reviewing'      => 'Reviewing',
        'follow_up'      => 'Follow-Up',
        'proposal_sent'  => 'Proposal Sent',
        'negotiating'    => 'Negotiating',
        'approved'       => 'Approved',
        'on_hold'        => 'On Hold',
        'not_interested' => 'Not Interested',
        'lost'           => 'Lost',
    ];
    // "New" gets a solid, high-contrast pill (unlike the others) since
    // these are the ones that actually need to grab attention on load.
    $statusColors = [
        'new'            => 'bg-indigo-600 text-white',
        'contacted'      => 'bg-amber-50 dark:bg-amber-500/10 text-amber-800 dark:text-amber-400 ring-1 ring-inset ring-amber-200 dark:ring-amber-500/20',
        'converted'      => 'bg-teal/10 text-teal-dark ring-1 ring-inset ring-teal/20',
        'reviewing'      => 'bg-blue-50 dark:bg-blue-500/10 text-blue-800 dark:text-blue-400 ring-1 ring-inset ring-blue-200 dark:ring-blue-500/20',
        'follow_up'      => 'bg-orange-50 dark:bg-orange-500/10 text-orange-800 dark:text-orange-400 ring-1 ring-inset ring-orange-200 dark:ring-orange-500/20',
        'proposal_sent'  => 'bg-purple-50 dark:bg-purple-500/10 text-purple-800 dark:text-purple-400 ring-1 ring-inset ring-purple-200 dark:ring-purple-500/20',
        'negotiating'    => 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-800 dark:text-cyan-400 ring-1 ring-inset ring-cyan-200 dark:ring-cyan-500/20',
        'approved'       => 'bg-green-50 dark:bg-green-500/10 text-green-800 dark:text-green-400 ring-1 ring-inset ring-green-200 dark:ring-green-500/20',
        'on_hold'        => 'bg-gray-100 dark:bg-gray-700/40 text-gray-600 dark:text-gray-300 ring-1 ring-inset ring-gray-200 dark:ring-gray-600/30',
        'not_interested' => 'bg-rose-50 dark:bg-rose-500/10 text-rose-800 dark:text-rose-400 ring-1 ring-inset ring-rose-200 dark:ring-rose-500/20',
        'lost'           => 'bg-red-50 dark:bg-red-500/10 text-red-800 dark:text-red-400 ring-1 ring-inset ring-red-200 dark:ring-red-500/20',
    ];
    // Solid dot colors for the status filter dropdown (admin._dropdown) —
    // same hue per status as $statusColors above, just a saturated dot
    // instead of a tinted badge background.
    $statusDots = [
        'new'            => 'bg-indigo-600',
        'contacted'      => 'bg-amber-500',
        'converted'      => 'bg-teal',
        'reviewing'      => 'bg-blue-500',
        'follow_up'      => 'bg-orange-500',
        'proposal_sent'  => 'bg-purple-500',
        'negotiating'    => 'bg-cyan-500',
        'approved'       => 'bg-green-500',
        'on_hold'        => 'bg-gray-400',
        'not_interested' => 'bg-rose-500',
        'lost'           => 'bg-red-500',
    ];
@endphp

{{-- Controls: search + status filter — same toolbar spirit as Project Requests
     (§15oo) and All Projects. Was a row of tabs, but with 11 statuses now
     (up from the original 3) that wrapped into a cramped multi-line mess —
     a single dropdown (admin._dropdown, same component the detail page's
     Status field uses) scales to however many statuses get added later
     without the toolbar growing taller. --}}
<div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-5">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
        </svg>
        <input type="text" id="submission-search" placeholder="Search organization or contact..." autocomplete="off"
               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-navy-dark dark:text-white pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
    </div>
    <div class="w-full sm:w-56 shrink-0">
        @include('admin._dropdown', [
            'name' => 'status_filter',
            'domId' => 'submission-status-filter',
            'options' => collect($statusLabels)->map(fn ($label, $value) => [
                'value' => $value,
                'label' => $label,
                'dot' => $statusDots[$value] ?? 'bg-gray-400',
            ])->values()->all(),
            'selected' => '',
            'placeholder' => 'All Submissions',
        ])
    </div>
    <a href="{{ route('admin.intake-submissions.create') }}"
        class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-gold hover:bg-gold-dark text-navy text-sm font-semibold rounded-lg transition-colors shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        New Intake
    </a>
</div>

@if ($submissions->isEmpty())
    <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No intake submissions yet.</p>
    </div>
@else
    <div id="submissions-table" class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-navy-dark text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3">Organization</th>
                    <th class="px-5 py-3">Contact</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Files</th>
                    <th class="px-5 py-3">Submitted</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($submissions as $submission)
                    @php
                        // Display-only Title Case (doesn't touch the stored value) — the
                        // raw organization_name is free-typed client input, so casing
                        // varies wildly ("DEMO PROJECT" vs "grace church"); normalizing
                        // it here keeps the column scanning smoothly regardless of how
                        // the client originally typed it.
                        $orgDisplayName = \Illuminate\Support\Str::title(strtolower($submission->organization_name));
                        $hasFiles = $submission->files_count > 0;
                        $searchText = strtolower($submission->organization_name.' '.$submission->contact_name.' '.$submission->contact_email);
                    @endphp
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30" data-search="{{ $searchText }}" data-status="{{ $submission->status }}">
                        <td class="px-5 py-3.5 align-middle">
                            <p class="font-medium text-navy dark:text-white">{{ $orgDisplayName }}</p>
                            @if ($submission->organization_type)
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $submission->organization_type }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 align-middle">
                            <p class="text-gray-700 dark:text-gray-300">{{ $submission->contact_name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $submission->contact_email }}</p>
                        </td>
                        <td class="px-5 py-3.5 align-middle">
                            <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$submission->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                                {{ $statusLabels[$submission->status] ?? $submission->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 align-middle">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full {{ $hasFiles ? 'bg-teal/10 text-teal-dark' : 'bg-gray-100 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400' }}">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                {{ $submission->files_count }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 align-middle text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $submission->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-3.5 align-middle text-right">
                            <a href="{{ route('admin.intake-submissions.show', $submission) }}"
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
                <tr id="submissions-empty-filter" class="hidden">
                    <td colspan="6" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400">
                        No submissions match your search or filter.
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="flex items-center justify-between gap-4 px-5 py-3 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400">
            <span id="submissions-count-label">Showing {{ $submissions->count() }} of {{ $submissions->total() }} submission{{ $submissions->total() === 1 ? '' : 's' }}</span>
        </div>
    </div>

    <div class="mt-6">
        {{ $submissions->links() }}
    </div>

    <script>
        (function () {
            const search = document.getElementById('submission-search');
            const statusFilterInput = document.getElementById('submission-status-filter-input');
            const emptyRow = document.getElementById('submissions-empty-filter');
            const countLabel = document.getElementById('submissions-count-label');
            const rows = Array.from(document.querySelectorAll('#submissions-table tbody tr[data-search]'));
            const totalCount = {{ $submissions->total() }};
            let activeStatus = '';

            function apply() {
                const q = (search.value || '').trim().toLowerCase();
                let visible = 0;

                rows.forEach(function (row) {
                    const matchesSearch = !q || row.dataset.search.includes(q);
                    const matchesStatus = !activeStatus || row.dataset.status === activeStatus;
                    const show = matchesSearch && matchesStatus;
                    row.classList.toggle('hidden', !show);
                    if (show) visible++;
                });

                emptyRow.classList.toggle('hidden', visible > 0);
                countLabel.textContent = (q || activeStatus)
                    ? 'Showing ' + visible + ' of ' + rows.length + ' on this page'
                    : 'Showing ' + rows.length + ' of ' + totalCount + ' submission' + (totalCount === 1 ? '' : 's');
            }

            search.addEventListener('input', apply);

            if (statusFilterInput) {
                statusFilterInput.addEventListener('change', function () {
                    activeStatus = statusFilterInput.value;
                    apply();
                });
            }
        })();
    </script>
@endif

@endsection
