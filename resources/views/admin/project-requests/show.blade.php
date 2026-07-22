@extends('layouts.admin')

@section('title', 'Project Request – Admin')
@section('page-title', 'Project Request')

@section('content')

<a href="{{ route('admin.project-requests.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white mb-5">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Back to Project Requests
</a>

{{-- Everything that saves via the single "Save Changes" button lives in this
     one form. It only visually wraps the left column — the Status dropdown
     and the Save button sit in the right sidebar and join it via the HTML5
     form="request-form" attribute, since a <form> can't be nested inside
     another <form> (Assign Developer / Developer Status below stay their
     own small auto-submit forms for that reason). --}}
<form id="request-form" method="POST" action="{{ route('admin.project-requests.update', $projectRequest) }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

        {{-- Left column (~60%): request info, proposal, internal notes --}}
        <div class="lg:col-span-3 space-y-6">

            <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <p class="font-semibold text-navy dark:text-white">{{ $projectRequest->user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $projectRequest->user->email }}</p>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 shrink-0 text-right">
                        @if ($projectRequest->isInternal())
                            <span class="inline-block text-[0.65rem] font-bold uppercase tracking-wide px-1.5 py-0.5 rounded bg-navy/10 dark:bg-white/10 text-navy dark:text-white mb-0.5">Internal</span><br>
                            Created {{ $projectRequest->created_at->format('M j, Y') }} by {{ $projectRequest->createdByAdmin?->name ?? 'an admin' }}
                        @else
                            Submitted {{ $projectRequest->created_at->format('M j, Y \a\t g:ia') }}
                        @endif
                    </span>
                </div>

                <h3 class="font-semibold text-navy dark:text-white mb-1">{{ $projectRequest->title }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $projectRequest->description }}</p>
                @if ($projectRequest->attachment_path)
                    <a href="{{ $projectRequest->attachmentUrl() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gold-dark hover:underline mt-3">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        {{ $projectRequest->attachment_original_name }}
                    </a>
                @endif

                <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <div>
                        <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Priority</label>
                        <select name="priority" form="request-form" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                            @foreach (\App\Models\ProjectRequest::PRIORITIES as $value => $label)
                                <option value="{{ $value }}" {{ old('priority', $projectRequest->priority) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Due Date</label>
                        <input type="date" name="due_date" form="request-form" value="{{ old('due_date', $projectRequest->due_date?->format('Y-m-d')) }}"
                               onclick="this.showPicker && this.showPicker()"
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-navy dark:text-white mb-4">Proposal</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Proposal Status</label>
                        @include('admin._dropdown', [
                            'name' => 'proposal_status',
                            'domId' => 'proposal-status',
                            'options' => collect(\App\Models\ProjectRequest::PROPOSAL_STATUSES)->map(fn ($label, $value) => [
                                'value' => $value,
                                'label' => $label,
                                'dot' => ['draft' => 'bg-gray-400', 'sent' => 'bg-indigo-400', 'under_review' => 'bg-amber-400', 'accepted' => 'bg-teal', 'declined' => 'bg-red-400'][$value] ?? 'bg-gray-400',
                            ])->values()->all(),
                            'selected' => old('proposal_status', $projectRequest->proposal_status),
                        ])
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Recommended Care Plan</label>
                        @include('admin._dropdown', [
                            'name' => 'recommended_care_plan_id',
                            'domId' => 'recommended-care-plan',
                            'options' => \App\Models\MaintenancePlan::orderBy('sort_order')->get()->map(fn ($plan) => ['value' => $plan->id, 'label' => $plan->name])->all(),
                            'selected' => $projectRequest->recommended_care_plan_id,
                            'placeholder' => 'None',
                        ])
                    </div>
                </div>

                @if (auth()->user()->isSuperAdmin())
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-1.5">
                            <label class="text-sm font-semibold text-navy dark:text-white">Estimated Project Value</label>
                            <span class="inline-block text-[0.6rem] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full bg-navy/10 dark:bg-white/10 text-navy dark:text-white">Staff Only</span>
                        </div>
                        <div class="relative max-w-xs">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 pointer-events-none">$</span>
                            <input type="number" name="estimated_value" step="0.01" min="0" value="{{ old('estimated_value', $projectRequest->estimated_value !== null ? number_format($projectRequest->estimated_value / 100, 2, '.', '') : '') }}" placeholder="0.00"
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 pl-7 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500">
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Proposal Document</label>

                    {{-- Unmissable either-way status — previously this was just a
                         small text link when a file existed and nothing at all
                         when it didn't, easy to miss either way. --}}
                    @if ($projectRequest->proposal_document_path)
                        <div class="flex items-center justify-between gap-3 mb-3 rounded-xl border border-teal/30 dark:border-teal/25 bg-teal/8 dark:bg-teal/10 px-4 py-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="shrink-0 w-9 h-9 rounded-full flex items-center justify-center bg-teal/15 text-teal-dark">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[0.65rem] font-bold uppercase tracking-wide text-teal-dark">Document on File</p>
                                    <p class="text-sm font-semibold text-navy dark:text-white truncate">{{ $projectRequest->proposal_document_original_name }}</p>
                                </div>
                            </div>
                            <a href="{{ $projectRequest->proposalDocumentUrl() }}" target="_blank" rel="noopener" class="shrink-0 text-xs font-semibold text-teal-dark hover:underline">View</a>
                        </div>
                    @else
                        <div class="flex items-center gap-2 mb-3 rounded-xl border border-amber-300/60 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-500/10 px-4 py-2.5">
                            <svg class="w-4 h-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                            <p class="text-xs font-semibold text-amber-700 dark:text-amber-400">No document uploaded yet</p>
                        </div>
                    @endif

                    <label id="proposal-document-dropzone" for="proposal-document-input"
                           class="flex flex-col items-center justify-center gap-1.5 w-full rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-gold dark:hover:border-gold bg-gray-50 dark:bg-navy-dark/50 px-4 py-7 text-center cursor-pointer transition-colors">
                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 16a4 4 0 01-.88-7.9A5 5 0 1115.9 6 5 5 0 0117 15.9M12 12v9m0-9l-3 3m3-3l3 3"/>
                        </svg>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-semibold text-gold-dark">{{ $projectRequest->proposal_document_path ? 'Click to replace' : 'Click to upload' }}</span>
                            or drag and drop
                        </p>
                        <p id="proposal-document-filename" class="text-xs text-gray-500 dark:text-gray-400">PDF, Word, or image — up to 25MB</p>
                        <input type="file" name="proposal_document" id="proposal-document-input" class="sr-only">
                    </label>
                </div>
            </div>

            <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-navy dark:text-white mb-1">Supporting Documents</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Specs, contracts, reference files — separate from the formal Proposal Document above.</p>

                @if ($projectRequest->attachments->isNotEmpty())
                    <div class="space-y-2 mb-4">
                        @foreach ($projectRequest->attachments as $attachment)
                            <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 dark:border-gray-700 px-3.5 py-2.5">
                                <a href="{{ $attachment->url() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm text-navy dark:text-white hover:text-gold-dark min-w-0">
                                    <svg class="w-4 h-4 text-gold-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span class="truncate">{{ $attachment->original_name }}</span>
                                    @if ($attachment->formattedSize())
                                        <span class="text-xs text-gray-500 dark:text-gray-400 shrink-0">({{ $attachment->formattedSize() }})</span>
                                    @endif
                                </a>
                                <form method="POST" action="{{ route('admin.project-requests.attachments.destroy', [$projectRequest, $attachment]) }}"
                                      onsubmit="return confirm('Remove this file?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Remove">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif

                @include('admin.project-requests._attachments-picker')
            </div>

            <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Internal Notes</label>
                <textarea name="admin_notes" rows="4" placeholder="Notes for setting this up as an actual project..."
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500">{{ old('admin_notes', $projectRequest->admin_notes) }}</textarea>

                <div class="flex items-start gap-3 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 px-4 py-3.5 mt-4">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-amber-800 dark:text-amber-300">
                        <span class="font-semibold">Heads up:</span> marking this "Converted to Project" doesn't create a project automatically — set up the second project for this client the same way new client projects are created today, then update the status here for your own records.
                    </p>
                </div>
            </div>
        </div>

        {{-- Right column (~40%, sticky): quick controls + the one save action --}}
        <div class="lg:col-span-2 lg:sticky lg:top-6 space-y-6">

            <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Assign Developer (Work Order)</label>
                    <form method="POST" action="{{ route('admin.project-requests.assign-developer', $projectRequest) }}">
                        @csrf
                        @method('PATCH')
                        @include('admin._dropdown', [
                            'name' => 'assigned_developer_id',
                            'domId' => 'assigned-developer',
                            'options' => \App\Models\User::developers()->map(fn ($d) => ['value' => $d->id, 'label' => $d->name])->all(),
                            'selected' => $projectRequest->assigned_developer_id,
                            'placeholder' => 'Unassigned',
                            'autoSubmit' => true,
                        ])
                    </form>
                </div>
                @if ($projectRequest->assigned_developer_id)
                    <div>
                        <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Developer Status</label>
                        @include('admin._dropdown', [
                            'name' => 'developer_status',
                            'domId' => 'developer-status',
                            'options' => collect(\App\Models\ProjectRequest::DEVELOPER_STATUSES)->map(fn ($label, $value) => [
                                'value' => $value,
                                'label' => $label,
                                'dot' => ['in_progress' => 'bg-gold', 'waiting_on_visionbridge' => 'bg-purple-400', 'completed' => 'bg-teal'][$value] ?? 'bg-gray-400',
                            ])->values()->all(),
                            'selected' => $projectRequest->developer_status,
                        ])
                        <p id="developer-status-toast" class="hidden text-xs mt-1.5"></p>
                    </div>
                @endif
            </div>

            <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Status</label>
                    @include('admin._dropdown', [
                        'name' => 'status',
                        'domId' => 'intake-status',
                        'options' => collect(\App\Models\ProjectRequest::STATUSES)->map(fn ($label, $value) => [
                            'value' => $value,
                            'label' => $label,
                            'dot' => ['pending' => 'bg-amber-400', 'reviewed' => 'bg-indigo-400', 'converted' => 'bg-teal', 'declined' => 'bg-red-400'][$value] ?? 'bg-gray-400',
                        ])->values()->all(),
                        'selected' => $projectRequest->status,
                        'formId' => 'request-form',
                    ])
                </div>

                <button type="submit" form="request-form" class="w-full bg-navy hover:bg-navy-light text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    Save Changes
                </button>
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center">Saves status, proposal, and internal notes together.</p>
            </div>
        </div>
    </div>
</form>

{{-- Custom-dropdown behavior is wired up automatically by admin/_dropdown.blade.php (@once script). --}}
<script>
(function () {
    // Developer Status — saves via fetch instead of a real form submit, so
    // picking a new status doesn't reload the whole page. Mirrors the same
    // AJAX pattern already used for this exact field on the Work Orders page
    // (saveWoStatusDropdown); the admin._dropdown partial dispatches a real
    // 'change' event on its hidden input specifically so page-specific JS
    // like this can hook into it without touching the shared partial.
    const devStatusInput = document.getElementById('developer-status-input');
    const devStatusToast = document.getElementById('developer-status-toast');
    if (!devStatusInput) return;

    let previousValue = devStatusInput.value;

    function showDevStatusToast(message, isError) {
        if (!devStatusToast) return;
        devStatusToast.textContent = message;
        devStatusToast.className = 'text-xs mt-1.5 ' + (isError ? 'text-red-500' : 'text-teal-dark dark:text-teal-light');
        clearTimeout(devStatusToast._hideTimer);
        devStatusToast._hideTimer = setTimeout(function () {
            devStatusToast.classList.add('hidden');
        }, 3000);
    }

    devStatusInput.addEventListener('change', function () {
        const value = devStatusInput.value;
        const savedPreviousValue = previousValue;
        previousValue = value;

        fetch('{{ route('admin.project-requests.developer-status', $projectRequest) }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ developer_status: value }),
        })
        .then(function (res) {
            if (!res.ok) throw new Error('Failed to update');
            showDevStatusToast('Developer status saved.', false);
        })
        .catch(function () {
            previousValue = savedPreviousValue;
            const revertOption = document.querySelector('#developer-status-menu [data-select-option="' + savedPreviousValue + '"]');
            if (revertOption) revertOption.click();
            showDevStatusToast('That change could not be saved — please try again.', true);
        });
    });
})();

(function () {
    // Proposal document dropzone — click-to-browse (native label/for
    // behavior) plus real drag-and-drop, with the filename swapped in once
    // a file is picked either way.
    (function () {
        const dropzone = document.getElementById('proposal-document-dropzone');
        const input = document.getElementById('proposal-document-input');
        const filename = document.getElementById('proposal-document-filename');
        if (!dropzone || !input || !filename) return;
        const defaultText = filename.textContent;

        function showFile(file) {
            filename.textContent = file ? file.name : defaultText;
            filename.classList.toggle('text-navy', !!file);
            filename.classList.toggle('dark:text-white', !!file);
            filename.classList.toggle('font-medium', !!file);
        }

        input.addEventListener('change', function () { showFile(input.files[0]); });

        ['dragenter', 'dragover'].forEach(function (evt) {
            dropzone.addEventListener(evt, function (e) {
                e.preventDefault();
                dropzone.classList.add('border-gold', 'bg-gold/5');
            });
        });
        ['dragleave', 'drop'].forEach(function (evt) {
            dropzone.addEventListener(evt, function (e) {
                e.preventDefault();
                dropzone.classList.remove('border-gold', 'bg-gold/5');
            });
        });
        dropzone.addEventListener('drop', function (e) {
            const file = e.dataTransfer.files[0];
            if (file) {
                input.files = e.dataTransfer.files;
                showFile(file);
            }
        });
    })();
})();
</script>

@endsection
