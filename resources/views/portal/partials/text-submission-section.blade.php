{{--
    $category: machine value (content, revision)
    $label: display label
    $placeholder: textarea placeholder
    $items: collection of uploads already filtered to this category
--}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 items-start">
        <div>
            <h3 class="font-semibold text-navy dark:text-white mb-1">{{ $label }}</h3>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Submit a new request below. To respond to an existing one, expand it on the right and use Reply.</p>

            <form method="POST" action="{{ route('portal.uploads.store', $project) }}" enctype="multipart/form-data" class="space-y-2">
                @csrf
                <input type="hidden" name="category" value="{{ $category }}">
                <textarea name="body" rows="3" placeholder="{{ $placeholder }}"
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"></textarea>
                <input type="file" name="file"
                       class="w-full text-sm text-gray-600 dark:text-gray-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy dark:text-white file:font-semibold file:text-sm hover:file:bg-gold/25">
                <div class="flex justify-end">
                    <button type="submit" class="shrink-0 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                        Submit
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:border-l lg:border-gray-200 dark:lg:border-gray-700 lg:pl-8">
            <h3 class="font-semibold text-navy dark:text-white mb-1">Revision History</h3>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">{{ $items->count() }} submission{{ $items->count() === 1 ? '' : 's' }} so far.</p>

            @if ($items->isEmpty())
                <p class="text-sm text-gray-400 dark:text-gray-500">{{ $why ?? 'Nothing submitted yet.' }}</p>
            @else
                <div id="revision-history-{{ $category }}" class="space-y-2.5 lg:max-h-[calc(100vh-200px)] lg:overflow-y-auto lg:pr-1">
            @foreach ($items as $item)
                @php
                    $borderColor = match ($item->status) {
                        'completed' => 'border-l-teal',
                        'in_progress' => 'border-l-gold',
                        'waiting_on_client' => 'border-l-purple-400',
                        'needs_approval' => 'border-l-orange-400',
                        'under_review' => 'border-l-blue-400',
                        default => 'border-l-red-400',
                    };
                    $statusBadgeColor = [
                        'completed' => 'bg-teal/10 text-teal-dark',
                        'in_progress' => 'bg-gold/15 text-gold-dark',
                        'waiting_on_client' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-500',
                        'needs_approval' => 'bg-orange-50 dark:bg-orange-500/10 text-orange-500',
                        'under_review' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-500',
                    ][$item->status] ?? 'bg-red-50 dark:bg-red-500/10 text-red-500';
                @endphp
                <details class="group rounded-lg border border-gray-200 dark:border-gray-700 border-l-4 {{ $borderColor }} px-4 py-3 {{ $item->isCompleted() ? 'opacity-60' : '' }}" {{ ! $item->isCompleted() ? 'open' : '' }}>
                    <summary class="sticky top-0 z-10 bg-white dark:bg-gray-800 flex items-center justify-between gap-4 cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $item->created_at->format('M j, Y \a\t g:ia') }}</span>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $statusBadgeColor }}">
                                @if ($item->isCompleted())
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @endif
                                {{ \App\Models\Upload::STATUSES[$item->status] ?? $item->status }}
                            </span>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            @if ($item->isDeletable())
                                <form method="POST" action="{{ route('portal.uploads.destroy', $item) }}" data-confirm="Remove this submission?" onclick="event.stopPropagation()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Remove" class="w-7 h-7 rounded-full text-gray-400 dark:text-gray-500 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                            @endif
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 transition-transform duration-200 group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </summary>

                    {{-- Your message bubble --}}
                    <div class="flex items-start justify-end gap-2 max-w-[75%] ml-auto mt-2">
                        <div class="rounded-2xl rounded-tr-sm bg-gold/10 px-3.5 py-2">
                            @if ($item->body)
                                <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line message-text max-h-24 overflow-hidden">{{ $item->body }}</p>
                                <button type="button" class="message-toggle hidden text-xs font-semibold text-navy dark:text-white hover:text-gold-dark mt-1">See more</button>
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
                                <div class="flex items-start justify-end gap-2 max-w-[75%] ml-auto mt-2">
                                    <div class="rounded-2xl rounded-tr-sm bg-gold/10 px-3.5 py-2">
                                        <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line message-text max-h-24 overflow-hidden">{{ $reply->body }}</p>
                                        <button type="button" class="message-toggle hidden text-xs font-semibold text-navy dark:text-white hover:text-gold-dark mt-1">See more</button>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $reply->created_at->format('M j, Y \a\t g:ia') }}</p>
                                    </div>
                                </div>
                            @else
                                {{-- VisionBridge reply bubble --}}
                                <div class="flex items-start gap-2 max-w-[75%] mt-2">
                                    <span class="w-7 h-7 rounded-full bg-navy text-gold text-xs font-bold flex items-center justify-center shrink-0">VB</span>
                                    <div class="rounded-2xl rounded-tl-sm bg-navy text-white px-3.5 py-2">
                                        <p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold mb-1">VisionBridge Team</p>
                                        <p class="text-sm whitespace-pre-line message-text max-h-24 overflow-hidden">{{ $reply->body }}</p>
                                        <button type="button" class="message-toggle hidden text-xs font-semibold text-gold hover:text-white mt-1">See more</button>
                                        <p class="text-xs text-white/40 mt-1">{{ $reply->created_at->format('M j, Y \a\t g:ia') }}</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <form id="client-reply-form-{{ $item->id }}" data-upload-id="{{ $item->id }}" method="POST" action="{{ route('portal.uploads.reply', $item) }}" class="ajax-client-reply-form mt-2 flex items-start gap-2">
                        @csrf
                        <textarea name="body" rows="2" placeholder="Write a reply..." required
                                  class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"></textarea>
                        <button type="submit" class="shrink-0 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                            Reply
                        </button>
                    </form>
                </details>
            @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
(function () {
    const historyEl = document.getElementById('revision-history-{{ $category }}');
    if (historyEl) {
        historyEl.scrollTop = historyEl.scrollHeight;
    }

    function initMessageToggles(scope) {
        (scope || document).querySelectorAll('.message-text').forEach(function (el) {
            if (el.dataset.toggleInit) return;
            if (el.offsetParent === null) return;
            el.dataset.toggleInit = '1';
            if (el.scrollHeight > el.clientHeight + 2) {
                const btn = el.nextElementSibling;
                if (btn && btn.classList.contains('message-toggle')) {
                    btn.classList.remove('hidden');
                    btn.addEventListener('click', function () {
                        const expanded = el.classList.toggle('message-expanded');
                        el.classList.toggle('max-h-24', !expanded);
                        el.classList.toggle('overflow-hidden', !expanded);
                        btn.textContent = expanded ? 'See less' : 'See more';
                    });
                }
            }
        });
    }

    initMessageToggles();

    document.querySelectorAll('details').forEach(function (details) {
        details.addEventListener('toggle', function () {
            if (details.open) initMessageToggles(details);
        });
    });

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
                    bubble.className = 'flex items-start justify-end gap-2 max-w-[75%] ml-auto mt-2';
                    bubble.innerHTML =
                        '<div class="rounded-2xl rounded-tr-sm bg-gold/10 px-3.5 py-2">' +
                            '<p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line message-text max-h-24 overflow-hidden"></p>' +
                            '<button type="button" class="message-toggle hidden text-xs font-semibold text-navy dark:text-white hover:text-gold-dark mt-1">See more</button>' +
                            '<p class="text-xs text-gray-400 dark:text-gray-500 mt-1"></p>' +
                        '</div>';
                    bubble.querySelector('.text-sm').textContent = data.body;
                    bubble.querySelector('.text-xs').textContent = data.sentAt;
                    repliesContainer.appendChild(bubble);
                    initMessageToggles(bubble);
                    if (historyEl) {
                        historyEl.scrollTop = historyEl.scrollHeight;
                    }

                    textarea.value = '';
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
