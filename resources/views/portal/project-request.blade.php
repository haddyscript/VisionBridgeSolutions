@extends('layouts.portal')

@section('title', 'Request a New Project – Client Portal')
@section('page-title', 'Request a New Project')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    Ready to start a new website with us? Tell us a bit about it below and our team will reach out to get it set up.
</p>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-8">
    <form method="POST" action="{{ route('portal.project-requests.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Project Title</label>
            <input type="text" id="project-request-title" name="title" required value="{{ old('title') }}" placeholder="e.g. Mercy City Eleven22 Church Landing Page"
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
            @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Tell us about it</label>
            <textarea id="project-request-description" name="description" rows="6" required placeholder="What's the project, who's it for, and anything else we should know to get started?

• Target audience?
• Features needed?
• Reference websites you like?"
                      class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">{{ old('description') }}</textarea>
            @error('description')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Attachment dropzone --}}
        <div>
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Attachments <span class="font-normal text-gray-400 dark:text-gray-500">(optional)</span></label>
            <label for="project-request-attachment" id="attachment-dropzone"
                   class="flex flex-col items-center justify-center gap-1.5 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 px-4 py-6 text-center cursor-pointer transition-colors hover:border-gold/50 hover:bg-gold/5 dark:hover:bg-gold/5">
                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                <p class="text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold text-gold-dark">Click to upload</span> or drag and drop</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Attach design files, brand guidelines, or mockups (Max 25MB)</p>
                <input type="file" name="attachment" id="project-request-attachment" class="hidden">
            </label>
            <div id="attachment-filename" class="hidden mt-2 flex items-center gap-2 text-sm text-navy dark:text-white bg-gray-50 dark:bg-gray-900 rounded-lg px-3 py-2">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                <span id="attachment-filename-text" class="truncate flex-1"></span>
                <button type="button" id="attachment-remove" class="text-gray-400 hover:text-red-500 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <p id="attachment-error" class="hidden text-xs text-red-500 mt-1"></p>
            @error('attachment')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" id="project-request-submit" disabled
                    class="text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors opacity-50 bg-slate-400 text-white cursor-not-allowed">
                Send Request
            </button>
        </div>
    </form>
</div>

@php
    $requestStatusMeta = [
        'converted' => ['label' => 'Converted', 'pill' => 'bg-teal/10 text-teal-dark', 'dot' => 'bg-teal', 'icon' => 'M5 13l4 4L19 7'],
        'declined' => ['label' => 'Declined', 'pill' => 'bg-red-50 text-red-500', 'dot' => 'bg-red-400', 'icon' => 'M6 18L18 6M6 6l12 12'],
    ];
    $defaultStatusMeta = ['pill' => 'bg-gold/15 text-gold-dark', 'dot' => 'bg-gold', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'];

    $sortLink = fn (string $column) => route('portal.project-requests.show', array_filter([
        'search' => $search !== '' ? $search : null,
        'sort' => $column,
        'direction' => $sort === $column && $direction === 'asc' ? 'desc' : 'asc',
    ]));
    $sortIndicator = fn (string $column) => $sort !== $column ? '' : ($direction === 'asc' ? '↑' : '↓');
@endphp

<h3 class="font-semibold text-navy dark:text-white mb-3">Your Requests</h3>
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-4 border-b border-gray-100 dark:border-gray-700">
        <form method="GET" action="{{ route('portal.project-requests.show') }}" id="request-search-form">
            <input type="hidden" name="sort" value="{{ $sort }}">
            <input type="hidden" name="direction" value="{{ $direction }}">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>
                <input type="text" name="search" id="request-search-input" value="{{ $search }}" placeholder="Search by title or description..."
                       class="w-full rounded-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:text-white">
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left font-semibold text-gray-500 dark:text-gray-400 px-6 py-3 whitespace-nowrap">
                        <a href="{{ $sortLink('title') }}" class="inline-flex items-center gap-1 hover:text-navy dark:hover:text-white transition-colors">Request <span class="text-gold-dark">{{ $sortIndicator('title') }}</span></a>
                    </th>
                    <th class="text-left font-semibold text-gray-500 dark:text-gray-400 px-6 py-3 whitespace-nowrap">
                        <a href="{{ $sortLink('status') }}" class="inline-flex items-center gap-1 hover:text-navy dark:hover:text-white transition-colors">Status <span class="text-gold-dark">{{ $sortIndicator('status') }}</span></a>
                    </th>
                    <th class="text-left font-semibold text-gray-500 dark:text-gray-400 px-6 py-3 whitespace-nowrap">
                        <a href="{{ $sortLink('created_at') }}" class="inline-flex items-center gap-1 hover:text-navy dark:hover:text-white transition-colors">Submitted <span class="text-gold-dark">{{ $sortIndicator('created_at') }}</span></a>
                    </th>
                    <th class="text-right font-semibold text-gray-500 dark:text-gray-400 px-6 py-3 whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($requests as $item)
                    @php $meta = array_merge($defaultStatusMeta, $requestStatusMeta[$item->status] ?? ['label' => \App\Models\ProjectRequest::STATUSES[$item->status] ?? $item->status]); @endphp
                    <tr class="request-row hover:bg-gray-50 dark:hover:bg-gray-900/40 transition-colors"
                        data-title="{{ $item->title }}"
                        data-description="{{ $item->description }}"
                        data-status-label="{{ $meta['label'] }}"
                        data-status-pill="{{ $meta['pill'] }}"
                        data-date="{{ $item->created_at->format('M j, Y') }}"
                        data-attachment-url="{{ $item->attachment_path ? $item->attachmentUrl() : '' }}"
                        data-attachment-name="{{ $item->attachment_original_name }}">
                        <td class="px-6 py-4">
                            <span class="flex items-center gap-2 min-w-0">
                                <span class="w-2 h-2 rounded-full shrink-0 {{ $meta['dot'] }}"></span>
                                <span class="font-semibold text-navy dark:text-white truncate max-w-xs">{{ $item->title }}</span>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-2.5 py-0.5 rounded-full whitespace-nowrap {{ $meta['pill'] }}">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $meta['icon'] }}"/></svg>
                                {{ $meta['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $item->created_at->format('M j, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <button type="button" class="request-view-btn inline-flex items-center justify-center px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-xs font-bold text-navy dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700/60 transition-colors">View</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-12">
                            <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            </div>
                            <p class="text-sm text-gray-400 dark:text-gray-500">{{ $search !== '' ? 'No requests match your search.' : 'No project requests yet.' }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $requests->links('vendor.pagination.portal') }}
</div>

{{-- Request detail modal — one shared modal populated from the clicked row's data-* attributes --}}
<div id="request-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div id="request-modal-backdrop" class="absolute inset-0 bg-navy-dark/60 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>

    <div id="request-modal-panel" class="relative w-full max-w-lg max-h-[80vh] transform scale-95 opacity-0 transition-all duration-200">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-h-[80vh] flex flex-col overflow-hidden">
            <div class="shrink-0 px-6 pt-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-start justify-between gap-4 mb-3">
                    <h2 id="request-modal-title" class="font-display text-lg font-bold text-navy dark:text-white"></h2>
                    <button type="button" id="request-modal-close" class="shrink-0 w-8 h-8 rounded-full text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 dark:hover:text-gray-300 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <span id="request-modal-status" class="text-xs font-semibold uppercase tracking-wide px-2.5 py-0.5 rounded-full"></span>
                    <span id="request-modal-date" class="text-xs text-gray-400 dark:text-gray-500"></span>
                </div>
            </div>
            <div class="overflow-y-auto px-6 py-4">
                <p id="request-modal-description" class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line"></p>
                <a id="request-modal-attachment" href="#" target="_blank" rel="noopener" class="hidden items-center gap-1.5 text-xs font-semibold text-gold-dark hover:underline mt-4">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    <span id="request-modal-attachment-name"></span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const modal = document.getElementById('request-modal');
    const backdrop = document.getElementById('request-modal-backdrop');
    const panel = document.getElementById('request-modal-panel');
    const titleEl = document.getElementById('request-modal-title');
    const statusEl = document.getElementById('request-modal-status');
    const dateEl = document.getElementById('request-modal-date');
    const descriptionEl = document.getElementById('request-modal-description');
    const attachmentEl = document.getElementById('request-modal-attachment');
    const attachmentNameEl = document.getElementById('request-modal-attachment-name');
    const viewButtons = document.querySelectorAll('.request-view-btn');
    if (!modal || !viewButtons.length) return;

    function openModal(row) {
        titleEl.textContent = row.dataset.title;
        dateEl.textContent = 'Submitted ' + row.dataset.date;
        descriptionEl.textContent = row.dataset.description;

        statusEl.textContent = row.dataset.statusLabel;
        statusEl.className = 'text-xs font-semibold uppercase tracking-wide px-2.5 py-0.5 rounded-full ' + row.dataset.statusPill;

        if (row.dataset.attachmentUrl) {
            attachmentEl.href = row.dataset.attachmentUrl;
            attachmentNameEl.textContent = row.dataset.attachmentName;
            attachmentEl.classList.remove('hidden');
            attachmentEl.classList.add('inline-flex');
        } else {
            attachmentEl.classList.add('hidden');
            attachmentEl.classList.remove('inline-flex');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('scale-95', 'opacity-0');
        });
    }

    function closeModal() {
        backdrop.classList.add('opacity-0');
        panel.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }

    viewButtons.forEach((btn) => btn.addEventListener('click', () => openModal(btn.closest('.request-row'))));
    document.getElementById('request-modal-close').addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });
})();

(function () {
    const form = document.getElementById('request-search-form');
    const input = document.getElementById('request-search-input');
    if (!form || !input) return;

    let debounceTimer;
    input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => form.submit(), 400);
    });
})();
</script>

<script>
    (function () {
        const dropzone = document.getElementById('attachment-dropzone');
        const input = document.getElementById('project-request-attachment');
        const filenameRow = document.getElementById('attachment-filename');
        const filenameText = document.getElementById('attachment-filename-text');
        const removeBtn = document.getElementById('attachment-remove');
        const errorEl = document.getElementById('attachment-error');
        const maxBytes = 25 * 1024 * 1024;

        function showFile(file) {
            if (!file) return;

            if (file.size > maxBytes) {
                errorEl.textContent = 'That file is larger than 25MB — please choose a smaller one.';
                errorEl.classList.remove('hidden');
                input.value = '';
                return;
            }

            errorEl.classList.add('hidden');
            filenameText.textContent = file.name;
            filenameRow.classList.remove('hidden');
            dropzone.classList.add('hidden');
        }

        function clearFile() {
            input.value = '';
            filenameRow.classList.add('hidden');
            dropzone.classList.remove('hidden');
        }

        input.addEventListener('change', function () {
            showFile(input.files[0]);
        });

        removeBtn.addEventListener('click', function (e) {
            e.preventDefault();
            clearFile();
        });

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
            e.preventDefault();
            const file = e.dataTransfer.files[0];
            if (!file) return;
            input.files = e.dataTransfer.files;
            showFile(file);
        });
    })();

    (function () {
        const title = document.getElementById('project-request-title');
        const description = document.getElementById('project-request-description');
        const submitBtn = document.getElementById('project-request-submit');

        function validate() {
            const valid = title.value.trim().length > 0 && description.value.trim().length > 0;

            submitBtn.disabled = !valid;
            submitBtn.classList.toggle('opacity-50', !valid);
            submitBtn.classList.toggle('bg-slate-400', !valid);
            submitBtn.classList.toggle('cursor-not-allowed', !valid);
            submitBtn.classList.toggle('bg-navy', valid);
            submitBtn.classList.toggle('hover:bg-navy-light', valid);
            submitBtn.classList.toggle('cursor-pointer', valid);
        }

        title.addEventListener('input', validate);
        description.addEventListener('input', validate);
        validate();
    })();
</script>

@endsection
