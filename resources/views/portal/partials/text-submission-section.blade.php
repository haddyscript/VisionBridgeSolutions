{{--
    $category: machine value (content, revision)
    $label: display label
    $placeholder: textarea placeholder
    $items: collection of uploads already filtered to this category
--}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-navy dark:text-white">{{ $label }}</h3>
        <span class="text-xs text-gray-400 dark:text-gray-500">{{ $items->count() }} submission{{ $items->count() === 1 ? '' : 's' }}</span>
    </div>

    <form method="POST" action="{{ route('portal.uploads.store', $project) }}" enctype="multipart/form-data" class="space-y-3 mb-6">
        @csrf
        <input type="hidden" name="category" value="{{ $category }}">
        <textarea name="body" rows="3" placeholder="{{ $placeholder }}"
                  class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"></textarea>
        <div class="flex items-center gap-3">
            <input type="file" name="file"
                   class="flex-1 text-sm text-gray-600 dark:text-gray-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy dark:text-white file:font-semibold file:text-sm hover:file:bg-gold/25">
            <button type="submit" class="shrink-0 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                Submit
            </button>
        </div>
    </form>

    @if ($items->isEmpty())
        <p class="text-sm text-gray-400 dark:text-gray-500">{{ $why ?? 'Nothing submitted yet.' }}</p>
    @else
        <div class="space-y-3">
            @foreach ($items as $item)
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-3.5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-2.5">
                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $item->created_at->format('M j, Y \a\t g:ia') }}</span>
                            @if ($item->status === 'addressed')
                                <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-teal/10 text-teal-dark">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    Addressed
                                </span>
                            @elseif ($item->status === 'in_progress')
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gold/15 text-gold-dark">In Progress</span>
                            @else
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500">Open</span>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('portal.uploads.destroy', $item) }}" onsubmit="return confirm('Remove this submission?')" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Remove" class="w-7 h-7 rounded-full text-gray-400 dark:text-gray-500 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-colors -mt-1 -mr-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>

                    {{-- Your message bubble --}}
                    <div class="flex items-start justify-end gap-2.5 max-w-[90%] ml-auto mt-2">
                        <div class="rounded-2xl rounded-tr-sm bg-gold/10 px-4 py-2.5">
                            @if ($item->body)
                                <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">{{ $item->body }}</p>
                            @endif
                            @if ($item->path)
                                <a href="{{ $item->url() }}" target="_blank"
                                   class="inline-flex items-center gap-2 text-sm text-navy dark:text-white hover:text-gold-dark transition-colors {{ $item->body ? 'mt-2' : '' }}">
                                    <svg class="w-4 h-4 text-gold-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 10-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                    <span class="truncate max-w-[200px]">{{ $item->original_name }}</span>
                                    @if ($item->formattedSize())
                                        <span class="text-xs text-gray-400 dark:text-gray-500">({{ $item->formattedSize() }})</span>
                                    @endif
                                </a>
                            @endif
                        </div>
                    </div>

                    <div id="replies-{{ $item->id }}">
                        @foreach ($item->replies as $reply)
                            @if ($reply->user_id === $item->user_id)
                                {{-- Your reply bubble --}}
                                <div class="flex items-start justify-end gap-2.5 max-w-[90%] ml-auto mt-3">
                                    <div class="rounded-2xl rounded-tr-sm bg-gold/10 px-4 py-2.5">
                                        <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">{{ $reply->body }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $reply->created_at->format('M j, Y \a\t g:ia') }}</p>
                                    </div>
                                </div>
                            @else
                                {{-- VisionBridge reply bubble --}}
                                <div class="flex items-start gap-2.5 max-w-[90%] mt-3">
                                    <span class="w-7 h-7 rounded-full bg-navy text-gold text-xs font-bold flex items-center justify-center shrink-0">VB</span>
                                    <div class="rounded-2xl rounded-tl-sm bg-navy text-white px-4 py-2.5">
                                        <p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold mb-1">VisionBridge Team</p>
                                        <p class="text-sm whitespace-pre-line">{{ $reply->body }}</p>
                                        <p class="text-xs text-white/40 mt-1">{{ $reply->created_at->format('M j, Y \a\t g:ia') }}</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div id="client-reply-toggle-{{ $item->id }}" class="flex justify-end mt-3">
                        <button type="button" onclick="document.getElementById('client-reply-form-{{ $item->id }}').classList.remove('hidden'); document.getElementById('client-reply-toggle-{{ $item->id }}').classList.add('hidden');" class="text-xs font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gold/15 hover:text-gold-dark px-3 py-1.5 rounded-full transition-colors">
                            Reply
                        </button>
                    </div>

                    <form id="client-reply-form-{{ $item->id }}" data-upload-id="{{ $item->id }}" method="POST" action="{{ route('portal.uploads.reply', $item) }}" class="ajax-client-reply-form hidden mt-3 flex items-start gap-2">
                        @csrf
                        <textarea name="body" rows="2" placeholder="Write a reply..." required
                                  class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"></textarea>
                        <button type="submit" class="shrink-0 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                            Reply
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
(function () {
    document.querySelectorAll('.ajax-client-reply-form').forEach(function (form) {
        if (form.dataset.bound) return;
        form.dataset.bound = '1';

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const uploadId = form.dataset.uploadId;
            const textarea = form.querySelector('textarea[name="body"]');
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnHtml = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML =
                '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">' +
                    '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
                    '<path class="opacity-75" fill="currentColor" d="M12 2a10 10 0 0110 10h-4a6 6 0 00-6-6V2z"></path>' +
                '</svg> Sending…';
            submitBtn.classList.add('inline-flex', 'items-center', 'gap-2');

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                },
                body: new FormData(form),
            })
                .then(function (response) {
                    if (!response.ok) throw new Error('Request failed');
                    return response.json();
                })
                .then(function (data) {
                    const repliesContainer = document.getElementById('replies-' + uploadId);
                    const bubble = document.createElement('div');
                    bubble.className = 'flex items-start justify-end gap-2.5 max-w-[90%] ml-auto mt-3';
                    bubble.innerHTML =
                        '<div class="rounded-2xl rounded-tr-sm bg-gold/10 px-4 py-2.5">' +
                            '<p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line"></p>' +
                            '<p class="text-xs text-gray-400 dark:text-gray-500 mt-1"></p>' +
                        '</div>';
                    bubble.querySelector('.text-sm').textContent = data.body;
                    bubble.querySelector('.text-xs').textContent = data.sentAt;
                    repliesContainer.appendChild(bubble);

                    textarea.value = '';
                    form.classList.add('hidden');
                    document.getElementById('client-reply-toggle-' + uploadId).classList.remove('hidden');
                })
                .catch(function () {
                    alert('Could not send the reply. Please try again.');
                })
                .finally(function () {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHtml;
                });
        });
    });
})();
</script>
