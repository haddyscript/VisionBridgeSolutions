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

<h3 class="font-semibold text-navy dark:text-white mb-3">Your Requests</h3>
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
    @forelse ($requests as $item)
        <div class="px-6 py-4">
            <div class="flex items-center justify-between gap-4 mb-1">
                <p class="text-sm font-semibold text-navy dark:text-white">{{ $item->title }}</p>
                <span class="text-xs font-semibold uppercase tracking-wide px-2.5 py-0.5 rounded-full {{ $item->status === 'converted' ? 'bg-teal/10 text-teal-dark' : ($item->status === 'declined' ? 'bg-red-50 text-red-500' : 'bg-gold/15 text-gold-dark') }}">
                    {{ \App\Models\ProjectRequest::STATUSES[$item->status] ?? $item->status }}
                </span>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 whitespace-pre-line">{{ $item->description }}</p>
            @if ($item->attachment_path)
                <a href="{{ $item->attachmentUrl() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gold-dark hover:underline mt-2">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    {{ $item->attachment_original_name }}
                </a>
            @endif
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Submitted {{ $item->created_at->format('M j, Y') }}</p>
        </div>
    @empty
        <div class="text-center py-12">
            <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            </div>
            <p class="text-sm text-gray-400 dark:text-gray-500">No project requests yet.</p>
        </div>
    @endforelse
</div>

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
