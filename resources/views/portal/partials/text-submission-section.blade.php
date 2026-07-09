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
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Submit a new request below. To respond to an existing one, use the message box under Revision History.</p>

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
                {{-- Conversation list --}}
                <div id="revision-list-{{ $category }}" class="revision-list space-y-2 lg:max-h-[calc(100vh-260px)] lg:overflow-y-auto lg:pr-1">
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
                            $lastReply = $item->replies->last();
                            $isMine = ! $lastReply || $lastReply->user_id === $item->user_id;
                            $previewText = $lastReply->body ?? $item->body ?? $item->original_name ?? '';
                            $unreadCount = $item->unreadRepliesCount();
                        @endphp
                        <button type="button" class="revision-list-item w-full text-left flex items-center gap-3 rounded-lg border border-gray-200 dark:border-gray-700 border-l-4 {{ $borderColor }} px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $item->isCompleted() ? 'opacity-60' : '' }}" data-thread="revision-thread-{{ $item->id }}" data-mark-read-url="{{ route('portal.uploads.read', $item) }}">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $item->created_at->format('M j, Y') }}</span>
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full shrink-0 {{ $statusBadgeColor }}">
                                        @if ($item->isCompleted())
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                        {{ \App\Models\Upload::STATUSES[$item->status] ?? $item->status }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-300 truncate mt-1">{{ $isMine ? 'You: ' : '' }}{{ \Illuminate\Support\Str::limit($previewText, 60) }}</p>
                            </div>
                            <span class="unread-badge shrink-0 min-w-[1.25rem] h-5 px-1.5 rounded-full bg-teal text-white text-xs font-semibold flex items-center justify-center {{ $unreadCount === 0 ? 'hidden' : '' }}">{{ $unreadCount }}</span>
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @endforeach
                </div>

                {{-- Individual conversation threads (one shown at a time) --}}
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
                    <div id="revision-thread-{{ $item->id }}" class="revision-thread hidden">
                        <button type="button" class="revision-back inline-flex items-center gap-1 text-sm font-semibold text-navy dark:text-white hover:text-gold-dark mb-3 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Back to all requests
                        </button>

                        <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 dark:border-gray-700 border-l-4 {{ $borderColor }} px-4 py-2 mb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $item->created_at->format('M j, Y \a\t g:ia') }}</span>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $statusBadgeColor }}">
                                    @if ($item->isCompleted())
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                    {{ \App\Models\Upload::STATUSES[$item->status] ?? $item->status }}
                                </span>
                            </div>
                            @if ($item->isDeletable())
                                <form method="POST" action="{{ route('portal.uploads.destroy', $item) }}" data-confirm="Remove this submission?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Remove" class="w-7 h-7 rounded-full text-gray-400 dark:text-gray-500 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-colors shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div id="revision-thread-messages-{{ $item->id }}" class="thread-scroll space-y-2.5 lg:max-h-[calc(100vh-420px)] lg:overflow-y-auto lg:pr-1">
                            {{-- Your message bubble --}}
                            <div class="flex items-start justify-end gap-2 max-w-[75%] ml-auto">
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
                        </div>

                        {{-- WhatsApp-style composer for this conversation --}}
                        <form data-upload-id="{{ $item->id }}" method="POST" action="{{ route('portal.uploads.reply', $item) }}"
                              class="ajax-client-reply-form mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 flex items-center gap-2">
                            @csrf
                            <textarea name="body" rows="1" placeholder="Type a message…" required
                                      class="flex-1 resize-none rounded-full border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"></textarea>
                            <button type="submit" title="Send" class="shrink-0 w-10 h-10 rounded-full bg-navy hover:bg-navy-light text-white flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<script>
(function () {
    const listEl = document.getElementById('revision-list-{{ $category }}');

    function openThread(threadId) {
        if (listEl) listEl.classList.add('hidden');
        document.querySelectorAll('.revision-thread').forEach(function (thread) {
            thread.classList.toggle('hidden', thread.id !== threadId);
        });
        const thread = document.getElementById(threadId);
        if (thread) {
            initMessageToggles(thread);
            const scrollEl = thread.querySelector('.thread-scroll');
            if (scrollEl) scrollEl.scrollTop = scrollEl.scrollHeight;
        }
    }

    function closeThread() {
        document.querySelectorAll('.revision-thread').forEach(function (thread) {
            thread.classList.add('hidden');
        });
        if (listEl) listEl.classList.remove('hidden');
    }

    document.querySelectorAll('.revision-list-item').forEach(function (row) {
        row.addEventListener('click', function () {
            openThread(row.dataset.thread);

            const badge = row.querySelector('.unread-badge');
            if (badge && !badge.classList.contains('hidden') && row.dataset.markReadUrl) {
                badge.classList.add('hidden');
                fetch(row.dataset.markReadUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    },
                });
            }
        });
    });

    document.querySelectorAll('.revision-back').forEach(function (btn) {
        btn.addEventListener('click', closeThread);
    });

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
                    const scrollEl = form.closest('.revision-thread')?.querySelector('.thread-scroll');
                    if (scrollEl) {
                        scrollEl.scrollTop = scrollEl.scrollHeight;
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
