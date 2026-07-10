{{--
    $category: machine value (image, video, logo, document, marketing)
    $label: display label
    $accept: input accept attribute
    $items: collection of uploads already filtered to this category
--}}
@php
    $isVisual = in_array($category, ['image', 'logo', 'video']);
    $formatHints = [
        'image' => 'PNG, JPEG, WebP, GIF',
        'logo' => 'PNG, SVG, JPEG',
        'video' => 'MP4, MOV, WebM',
        'document' => 'PDF, DOC, DOCX, XLS',
        'marketing' => 'PDF, PNG, JPEG',
    ];
    $formatHint = ($formatHints[$category] ?? 'common file types').' up to 50MB';
@endphp
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-navy dark:text-white">{{ $label }}</h3>
        <div class="flex items-center gap-3">
            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $items->count() }} file{{ $items->count() === 1 ? '' : 's' }}</span>
            @if ($items->isNotEmpty())
                <a href="{{ route('portal.category.download', $category) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gold-dark hover:underline">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                    Download All
                </a>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('portal.uploads.store', $project) }}" enctype="multipart/form-data" class="upload-form mb-5" data-category="{{ $category }}">
        @csrf
        <input type="hidden" name="category" value="{{ $category }}">
        <div class="upload-form-fields">
            <label class="upload-dropzone group flex flex-col items-center justify-center text-center gap-2 w-full rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50/60 dark:bg-gray-900/40 px-6 py-9 cursor-pointer transition-all hover:border-gold hover:bg-gold/5">
                <input type="file" name="file" accept="{{ $accept }}" required class="upload-input sr-only">
                <span class="w-12 h-12 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center transition-transform group-hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 16a4 4 0 01-.88-7.9A5 5 0 1115.9 6 5 5 0 0117 15.9M12 12v9m0-9l-3 3m3-3l3 3"/></svg>
                </span>
                <span class="text-sm font-medium text-navy dark:text-white">
                    Drag and drop your {{ strtolower($label) }} here, or <span class="text-gold-dark font-semibold">click to browse</span>
                </span>
                <span class="text-xs text-gray-400 dark:text-gray-500">Supports {{ $formatHint }}</span>
            </label>
        </div>
        <div class="upload-progress-wrap hidden mt-3">
            <div class="flex items-center justify-between mb-1.5">
                <span class="upload-progress-label text-xs font-medium text-gray-500 dark:text-gray-400">Uploading…</span>
                <span class="upload-progress-pct text-xs font-semibold text-navy dark:text-white">0%</span>
            </div>
            <div class="w-full h-2 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                <div class="upload-progress-bar h-full bg-gold rounded-full transition-all" style="width:0%"></div>
            </div>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
        <h4 class="text-sm font-semibold text-navy dark:text-white mb-4">Uploaded {{ $label }}</h4>

    @if ($items->isEmpty())
        <div class="flex flex-col items-center justify-center text-center py-12 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
            <span class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-300 dark:text-gray-500 flex items-center justify-center mb-3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </span>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No {{ strtolower($label) }} uploaded yet.</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 max-w-xs">Your files will appear here as a {{ $isVisual ? 'grid preview' : 'list' }} once added.</p>
        </div>

    @elseif ($isVisual)
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            @foreach ($items as $item)
                <div class="group relative">
                    <a href="{{ $item->url() }}" target="_blank" class="block aspect-square rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-700">
                        @if ($category === 'video')
                            <video src="{{ $item->url() }}" class="w-full h-full object-cover" preload="metadata" muted></video>
                            <div class="absolute inset-0 flex items-center justify-center bg-black/15">
                                <span class="w-9 h-9 rounded-full bg-white/85 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-navy dark:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </span>
                            </div>
                        @else
                            <img src="{{ $item->url() }}" alt="{{ $item->original_name }}" class="w-full h-full object-cover">
                        @endif
                    </a>
                    @if ($item->isDeletable())
                        <form method="POST" action="{{ route('portal.uploads.destroy', $item) }}" data-confirm="Remove this file?"
                              class="absolute top-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Remove" class="w-6 h-6 rounded-full bg-white/90 hover:bg-red-500 hover:text-white text-gray-500 dark:text-gray-400 flex items-center justify-center shadow">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    @endif
                    @if ($item->isApproved())
                        <span class="absolute top-1.5 left-1.5 inline-flex items-center gap-1 text-[0.65rem] font-semibold uppercase tracking-wide px-1.5 py-0.5 rounded-full bg-teal text-white shadow">
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            Approved
                        </span>
                    @endif
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-1.5">{{ $item->original_name }}</p>
                </div>
            @endforeach
        </div>

    @else
        @php
            $extColors = [
                'pdf' => ['bg' => 'bg-red-50 dark:bg-red-500/10', 'text' => 'text-red-500', 'border' => 'border-red-100'],
                'doc' => ['bg' => 'bg-navy/5 dark:bg-navy/10', 'text' => 'text-navy dark:text-white', 'border' => 'border-navy/10'],
                'docx' => ['bg' => 'bg-navy/5 dark:bg-navy/10', 'text' => 'text-navy dark:text-white', 'border' => 'border-navy/10'],
                'xls' => ['bg' => 'bg-teal-50', 'text' => 'text-teal-dark', 'border' => 'border-teal-100'],
                'xlsx' => ['bg' => 'bg-teal-50', 'text' => 'text-teal-dark', 'border' => 'border-teal-100'],
                'zip' => ['bg' => 'bg-gold/10', 'text' => 'text-gold-dark', 'border' => 'border-gold/20'],
            ];
            $default = ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-500 dark:text-gray-400', 'border' => 'border-gray-200 dark:border-gray-700'];
        @endphp
        <div class="space-y-2.5">
            @foreach ($items as $item)
                @php $colors = $extColors[$item->extension()] ?? $default; @endphp
                <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-3">
                    <a href="{{ $item->url() }}" target="_blank" class="flex items-center gap-3 min-w-0 group">
                        <span class="w-10 h-10 rounded-lg {{ $colors['bg'] }} {{ $colors['border'] }} border flex items-center justify-center shrink-0">
                            <span class="text-[0.6rem] font-bold uppercase {{ $colors['text'] }}">{{ $item->extension() ?: 'FILE' }}</span>
                        </span>
                        <span class="min-w-0">
                            <span class="flex items-center gap-2">
                                <span class="block text-sm font-medium text-navy dark:text-white group-hover:text-gold-dark truncate">{{ $item->original_name }}</span>
                                @if ($item->isApproved())
                                    <span class="shrink-0 inline-flex items-center gap-1 text-[0.65rem] font-semibold uppercase tracking-wide px-1.5 py-0.5 rounded-full bg-teal/10 text-teal-dark">
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        Approved
                                    </span>
                                @endif
                            </span>
                            <span class="block text-xs text-gray-400 dark:text-gray-500">
                                {{ $item->created_at->format('M j, Y') }}
                                @if ($item->formattedSize())
                                    &middot; {{ $item->formattedSize() }}
                                @endif
                            </span>
                        </span>
                    </a>
                    @if ($item->isDeletable())
                        <form method="POST" action="{{ route('portal.uploads.destroy', $item) }}" data-confirm="Remove this file?" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Remove" class="w-8 h-8 rounded-full text-gray-400 dark:text-gray-500 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 7V4.5A1.5 1.5 0 0111 3h2a1.5 1.5 0 011.5 1.5V7M4 7h16"/></svg>
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
    </div>
</div>

<script>
(function () {
    const form = document.currentScript.closest('div').querySelector('.upload-form');
    if (!form) return;

    const fields = form.querySelector('.upload-form-fields');
    const dropzone = form.querySelector('.upload-dropzone');
    const input = form.querySelector('.upload-input');
    const progressWrap = form.querySelector('.upload-progress-wrap');
    const progressBar = form.querySelector('.upload-progress-bar');
    const progressPct = form.querySelector('.upload-progress-pct');
    const progressLabel = form.querySelector('.upload-progress-label');

    const activeClasses = ['border-gold', 'bg-gold/10', 'ring-2', 'ring-gold/30'];

    ['dragenter', 'dragover'].forEach(function (evt) {
        dropzone.addEventListener(evt, function (e) {
            e.preventDefault();
            dropzone.classList.add(...activeClasses);
        });
    });
    ['dragleave', 'dragend', 'drop'].forEach(function (evt) {
        dropzone.addEventListener(evt, function (e) {
            e.preventDefault();
            dropzone.classList.remove(...activeClasses);
        });
    });

    dropzone.addEventListener('drop', function (e) {
        if (e.dataTransfer && e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            startUpload();
        }
    });
    input.addEventListener('change', function () {
        if (input.files.length) startUpload();
    });

    function resetForm(message, isError) {
        progressLabel.textContent = message;
        progressLabel.classList.toggle('text-red-500', isError);
        progressBar.classList.toggle('bg-red-500', isError);

        if (!isError) {
            window.location.reload();
            return;
        }

        setTimeout(function () {
            fields.classList.remove('hidden');
            progressWrap.classList.add('hidden');
            progressBar.style.width = '0%';
            progressPct.textContent = '0%';
            progressLabel.classList.remove('text-red-500');
            progressBar.classList.remove('bg-red-500');
            input.value = '';
        }, 2500);
    }

    function startUpload() {
        if (!input.files.length) return;

        fields.classList.add('hidden');
        progressWrap.classList.remove('hidden');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.upload.addEventListener('progress', function (evt) {
            if (!evt.lengthComputable) return;
            const pct = Math.round((evt.loaded / evt.total) * 100);
            progressBar.style.width = pct + '%';
            progressPct.textContent = pct + '%';
        });

        xhr.addEventListener('load', function () {
            let message = 'Something went wrong. Please try again.';
            try {
                message = JSON.parse(xhr.responseText).message || message;
            } catch (err) {}

            resetForm(message, xhr.status >= 400);
        });

        xhr.addEventListener('error', function () {
            resetForm('Something went wrong. Please try again.', true);
        });

        xhr.send(new FormData(form));
    }

    // Guard against a normal form submit (e.g. Enter) — always go through XHR.
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        startUpload();
    });
})();
</script>
