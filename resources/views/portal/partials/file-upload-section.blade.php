{{--
    $category: machine value (image, video, logo, document, marketing)
    $label: display label
    $accept: input accept attribute
    $items: collection of uploads already filtered to this category
--}}
@php
    $isVisual = in_array($category, ['image', 'logo', 'video']);
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
        <div class="upload-form-fields flex items-center gap-3">
            <input type="file" name="file" accept="{{ $accept }}" required
                   class="flex-1 text-sm text-gray-600 dark:text-gray-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy dark:text-white file:font-semibold file:text-sm hover:file:bg-gold/25">
            <button type="submit" class="upload-submit-btn shrink-0 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                Upload
            </button>
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

    @if ($items->isEmpty())
        <p class="text-sm text-gray-400 dark:text-gray-500">{{ $why ?? 'No files uploaded yet.' }}</p>

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

<script>
(function () {
    const form = document.currentScript.closest('div').querySelector('.upload-form');
    if (!form) return;

    const fields = form.querySelector('.upload-form-fields');
    const progressWrap = form.querySelector('.upload-progress-wrap');
    const progressBar = form.querySelector('.upload-progress-bar');
    const progressPct = form.querySelector('.upload-progress-pct');
    const progressLabel = form.querySelector('.upload-progress-label');
    const submitBtn = form.querySelector('.upload-submit-btn');

    function resetForm(message, isError) {
        progressLabel.textContent = message;
        progressLabel.classList.toggle('text-red-500', isError);
        progressBar.classList.toggle('bg-red-500', isError);

        if (!isError) {
            window.location.reload();
            return;
        }

        submitBtn.disabled = false;
        setTimeout(function () {
            fields.classList.remove('hidden');
            progressWrap.classList.add('hidden');
            progressBar.style.width = '0%';
            progressPct.textContent = '0%';
            progressLabel.classList.remove('text-red-500');
            progressBar.classList.remove('bg-red-500');
        }, 2500);
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        fields.classList.add('hidden');
        progressWrap.classList.remove('hidden');
        submitBtn.disabled = true;

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
    });
})();
</script>
