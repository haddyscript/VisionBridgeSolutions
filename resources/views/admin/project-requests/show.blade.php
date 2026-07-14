@extends('layouts.admin')

@section('title', 'Project Request – Admin')
@section('page-title', 'Project Request')

@section('content')

<a href="{{ route('admin.project-requests.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white mb-5">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Back to Project Requests
</a>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <div class="flex items-center justify-between gap-4 mb-4">
        <div>
            <p class="font-semibold text-navy dark:text-white">{{ $projectRequest->user->name }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $projectRequest->user->email }}</p>
        </div>
        <span class="text-xs text-gray-400 dark:text-gray-500">Submitted {{ $projectRequest->created_at->format('M j, Y \a\t g:ia') }}</span>
    </div>

    <h3 class="font-semibold text-navy dark:text-white mb-1">{{ $projectRequest->title }}</h3>
    <p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $projectRequest->description }}</p>
    @if ($projectRequest->attachment_path)
        <a href="{{ $projectRequest->attachmentUrl() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gold-dark hover:underline mt-3">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
            {{ $projectRequest->attachment_original_name }}
        </a>
    @endif
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Assign Developer (Work Order)</label>
            <form method="POST" action="{{ route('admin.project-requests.assign-developer', $projectRequest) }}">
                @csrf
                @method('PATCH')
                @include('admin.project-requests._dropdown', [
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
                <form method="POST" action="{{ route('admin.project-requests.developer-status', $projectRequest) }}">
                    @csrf
                    @method('PATCH')
                    @include('admin.project-requests._dropdown', [
                        'name' => 'developer_status',
                        'domId' => 'developer-status',
                        'options' => collect(\App\Models\ProjectRequest::DEVELOPER_STATUSES)->map(fn ($label, $value) => [
                            'value' => $value,
                            'label' => $label,
                            'dot' => ['in_progress' => 'bg-gold', 'waiting_on_visionbridge' => 'bg-purple-400', 'completed' => 'bg-teal'][$value] ?? 'bg-gray-400',
                        ])->values()->all(),
                        'selected' => $projectRequest->developer_status,
                        'autoSubmit' => true,
                    ])
                </form>
            </div>
        @endif
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <h3 class="font-semibold text-navy dark:text-white mb-4">Proposal</h3>
    <form method="POST" action="{{ route('admin.project-requests.proposal', $projectRequest) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PATCH')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Proposal Status</label>
                @include('admin.project-requests._dropdown', [
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
                @include('admin.project-requests._dropdown', [
                    'name' => 'recommended_care_plan_id',
                    'domId' => 'recommended-care-plan',
                    'options' => \App\Models\MaintenancePlan::orderBy('sort_order')->get()->map(fn ($plan) => ['value' => $plan->id, 'label' => $plan->name])->all(),
                    'selected' => $projectRequest->recommended_care_plan_id,
                    'placeholder' => 'None',
                ])
            </div>
        </div>
        @if (auth()->user()->isSuperAdmin())
            <div>
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Estimated Project Value <span class="text-xs font-normal text-gray-400">(staff only)</span></label>
                <input type="number" name="estimated_value" step="0.01" min="0" value="{{ old('estimated_value', $projectRequest->estimated_value !== null ? number_format($projectRequest->estimated_value / 100, 2, '.', '') : '') }}" placeholder="0.00"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
            </div>
        @endif
        <div>
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Proposal Document</label>
            @if ($projectRequest->proposal_document_path)
                <a href="{{ $projectRequest->proposalDocumentUrl() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gold-dark hover:underline mb-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    {{ $projectRequest->proposal_document_original_name }}
                </a>
            @endif
            <input type="file" name="proposal_document" class="w-full text-sm text-gray-600 dark:text-gray-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gold/15 file:text-gold-dark hover:file:bg-gold/25">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-navy hover:bg-navy-light text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                Save Proposal
            </button>
        </div>
    </form>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
    <form method="POST" action="{{ route('admin.project-requests.update', $projectRequest) }}" class="space-y-4">
        @csrf
        @method('PATCH')
        <div>
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Status</label>
            @include('admin.project-requests._dropdown', [
                'name' => 'status',
                'domId' => 'intake-status',
                'options' => collect(\App\Models\ProjectRequest::STATUSES)->map(fn ($label, $value) => [
                    'value' => $value,
                    'label' => $label,
                    'dot' => ['pending' => 'bg-amber-400', 'reviewed' => 'bg-indigo-400', 'converted' => 'bg-teal', 'declined' => 'bg-red-400'][$value] ?? 'bg-gray-400',
                ])->values()->all(),
                'selected' => $projectRequest->status,
            ])
        </div>
        <div>
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Internal Notes</label>
            <textarea name="admin_notes" rows="4" placeholder="Notes for setting this up as an actual project..."
                      class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">{{ old('admin_notes', $projectRequest->admin_notes) }}</textarea>
        </div>
        <p class="text-xs text-gray-400 dark:text-gray-500">
            Marking this "Converted to Project" doesn't create a project automatically — set up the second project for this client the same way new client projects are created today, then update the status here for your own records.
        </p>
        <div class="flex justify-end">
            <button type="submit" class="bg-navy hover:bg-navy-light text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                Save
            </button>
        </div>
    </form>
</div>

{{-- Drives every custom dropdown from the partial above — one shared handler
     instead of per-instance JS, since all 5 on this page behave identically
     except for the auto-submit flag. --}}
<script>
(function () {
    document.querySelectorAll('[data-custom-select]').forEach(function (wrap) {
        const domId = wrap.id.replace(/-wrap$/, '');
        const toggle = document.getElementById(domId + '-toggle');
        const menu = document.getElementById(domId + '-menu');
        const chevron = toggle?.querySelector('svg');
        const hiddenInput = document.getElementById(domId + '-input');
        const label = document.getElementById(domId + '-label');
        const labelText = document.getElementById(domId + '-label-text');
        const autoSubmit = wrap.dataset.autoSubmit === '1';
        if (!toggle || !menu || !hiddenInput || !label || !labelText) return;

        function closeMenu() {
            menu.classList.add('hidden');
            toggle.setAttribute('aria-expanded', 'false');
            if (chevron) chevron.style.transform = '';
        }

        function openMenu() {
            document.querySelectorAll('[data-custom-select]').forEach(function (otherWrap) {
                if (otherWrap === wrap) return;
                document.getElementById(otherWrap.id.replace(/-wrap$/, '') + '-menu')?.classList.add('hidden');
            });
            menu.classList.remove('hidden');
            toggle.setAttribute('aria-expanded', 'true');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        }

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            menu.classList.contains('hidden') ? openMenu() : closeMenu();
        });

        menu.querySelectorAll('[data-select-option]').forEach(function (option) {
            option.addEventListener('click', function () {
                const value = option.dataset.selectOption;
                const optLabel = option.dataset.selectLabel;
                const dot = option.dataset.selectDot || '';

                hiddenInput.value = value;
                labelText.textContent = optLabel;
                label.classList.remove('text-gray-400');
                label.classList.add('text-navy', 'dark:text-white');

                let dotEl = label.querySelector('span.w-2');
                if (dot) {
                    if (!dotEl) {
                        dotEl = document.createElement('span');
                        label.insertBefore(dotEl, labelText);
                    }
                    dotEl.className = 'w-2 h-2 rounded-full shrink-0 ' + dot;
                } else if (dotEl) {
                    dotEl.remove();
                }

                menu.querySelectorAll('[data-select-option]').forEach(function (opt) {
                    const isSelected = opt === option;
                    opt.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                    opt.classList.toggle('text-gold-dark', isSelected);
                    opt.classList.toggle('font-semibold', isSelected);
                    opt.classList.toggle('text-gray-700', !isSelected);
                    opt.classList.toggle('dark:text-gray-300', !isSelected);
                    opt.classList.toggle('text-gray-500', false);
                    const check = opt.querySelector('svg');
                    if (check) check.classList.toggle('invisible', !isSelected);
                });

                closeMenu();

                if (autoSubmit && hiddenInput.form) {
                    hiddenInput.form.requestSubmit();
                }
            });
        });

        document.addEventListener('click', function (e) {
            if (!wrap.contains(e.target)) closeMenu();
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id$="-menu"]').forEach(function (m) { m.classList.add('hidden'); });
        }
    });
})();
</script>

@endsection
