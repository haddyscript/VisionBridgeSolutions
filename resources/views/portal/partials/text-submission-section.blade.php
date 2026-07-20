{{--
    $category: machine value (content, revision)
    $label: display label
    $placeholder: textarea placeholder
    $items: collection of uploads already filtered to this category
--}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
    <div class="content-grid grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 items-start">
        <div class="new-request-col">
            <h3 class="font-semibold text-navy dark:text-white mb-1">{{ $label }}</h3>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Submit a new request below. To respond to an existing one, use the message box under Revision History.</p>

            <form method="POST" action="{{ route('portal.uploads.store', $project) }}" enctype="multipart/form-data" class="space-y-2">
                @csrf
                <input type="hidden" name="category" value="{{ $category }}">
                @if ($category === 'revision')
                    <input type="text" name="title" maxlength="150" required placeholder="Revision title (e.g. Homepage hero image)"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                @endif
                <textarea name="body" rows="3" placeholder="{{ $placeholder }}"
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"></textarea>
                <div class="upload-attach">
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 hover:border-gold hover:bg-gold/5 px-3.5 py-2 text-sm font-medium text-navy dark:text-white transition-colors">
                            <input type="file" name="files[]" multiple class="attach-input sr-only">
                            <svg class="w-4 h-4 text-gold-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 10-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            <span>Attach files</span>
                        </label>
                        <span class="attach-filelist flex flex-wrap items-center gap-2"></span>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">Optional — attach documents or images (up to 50MB each).</p>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="shrink-0 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                        Submit
                    </button>
                </div>
            </form>
        </div>

        <div class="history-col lg:border-l lg:border-gray-200 dark:lg:border-gray-700 lg:pl-8">
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
                                @if ($item->title)
                                    <p class="text-sm font-semibold text-navy dark:text-white truncate mt-1">{{ $item->title }}</p>
                                @endif
                                <p class="text-sm text-gray-600 dark:text-gray-300 truncate {{ $item->title ? '' : 'mt-1' }}">{{ $isMine ? 'You: ' : '' }}{{ \Illuminate\Support\Str::limit($previewText, 60) }}</p>
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
                            'closed' => 'border-l-gray-400',
                            'in_progress' => 'border-l-gold',
                            'waiting_on_client' => 'border-l-purple-400',
                            'needs_approval' => 'border-l-orange-400',
                            'under_review' => 'border-l-blue-400',
                            default => 'border-l-red-400',
                        };
                        $statusBadgeColor = [
                            'completed' => 'bg-teal/10 text-teal-dark',
                            'closed' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
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

                        @if ($item->title)
                            <h3 class="font-semibold text-navy dark:text-white mb-2">{{ $item->title }}</h3>
                        @endif

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

                        @if ($item->estimated_completion_date)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Estimated completion: <span class="font-medium text-navy dark:text-white">{{ $item->estimated_completion_date->format('M j, Y') }}</span></p>
                        @endif

                        @if ($item->isClosed() && $item->closed_reason)
                            <p class="text-xs text-gray-500 dark:text-gray-400 italic mb-3">Closed: {{ $item->closed_reason }}</p>
                        @endif

                        <div id="revision-thread-messages-{{ $item->id }}" class="thread-scroll space-y-2.5 lg:max-h-[calc(100vh-420px)] lg:overflow-y-auto lg:pr-1">
                            {{-- Your message bubble --}}
                            <div class="flex items-start justify-end gap-2 max-w-[75%] ml-auto">
                                <div class="rounded-2xl rounded-tr-sm bg-gold/10 px-3.5 py-2">
                                    @if ($item->body)
                                        <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line message-text max-h-24 overflow-hidden">{{ $item->body }}</p>
                                        <button type="button" class="message-toggle hidden text-xs font-semibold text-navy dark:text-white hover:text-gold-dark mt-1">See more</button>
                                    @endif
                                    @if ($item->path)
                                        <div class="{{ $item->body ? 'mt-2' : '' }} space-y-1.5">
                                            @foreach ($item->allAttachments() as $attachment)
                                                <a href="{{ $attachment->url }}" target="_blank" rel="noopener"
                                                   class="flex items-center gap-2 text-sm text-navy dark:text-white hover:text-gold-dark transition-colors">
                                                    <svg class="w-4 h-4 text-gold-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 10-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                    </svg>
                                                    <span class="truncate max-w-[200px]">{{ $attachment->original_name }}</span>
                                                    @if ($attachment->formattedSize)
                                                        <span class="text-xs text-gray-400 dark:text-gray-500">({{ $attachment->formattedSize }})</span>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
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

                        {{-- Reply composer — a distinct pill so it's clearly for replying to this thread --}}
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs font-medium text-gray-400 dark:text-gray-500 mb-2">Reply to this request</p>
                            <form data-upload-id="{{ $item->id }}" method="POST" action="{{ route('portal.uploads.reply', $item) }}"
                                  class="ajax-client-reply-form flex items-center gap-2 rounded-full border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 pl-4 pr-1.5 py-1 focus-within:border-gold focus-within:ring-2 focus-within:ring-gold/30 transition">
                                @csrf
                                <textarea name="body" rows="1" placeholder="Type a message…" required
                                          class="flex-1 resize-none bg-transparent border-0 py-2 text-sm text-navy dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-0"></textarea>
                                <button type="submit" title="Send" class="shrink-0 w-9 h-9 rounded-full bg-navy hover:bg-navy-light text-white flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<script>
(function () {
    const listEl = document.getElementById('revision-list-{{ $category }}');
    const grid = document.querySelector('.content-grid');
    const newRequestCol = document.querySelector('.new-request-col');
    const historyCol = document.querySelector('.history-col');

    // With a thread open, hide the "New Request" form and let the conversation
    // take the full card width; restore the split view when going back.
    function expandWorkspace(expand) {
        if (newRequestCol) newRequestCol.classList.toggle('hidden', expand);
        if (grid) grid.classList.toggle('lg:grid-cols-2', !expand);
        if (historyCol) {
            historyCol.classList.toggle('lg:border-l', !expand);
            historyCol.classList.toggle('lg:pl-8', !expand);
        }
    }

    function openThread(threadId) {
        if (listEl) listEl.classList.add('hidden');
        expandWorkspace(true);
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
        expandWorkspace(false);
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

    // New-request file attachment: lets a client pick files across multiple
    // dialog openings (each pick would otherwise replace the previous
    // selection) and remove any one of them individually, by keeping our own
    // running list and re-syncing it onto the real <input> via DataTransfer
    // before each submit.
    const attachInput = document.querySelector('.upload-attach .attach-input');
    if (attachInput) {
        const wrap = attachInput.closest('.upload-attach');
        const listEl = wrap.querySelector('.attach-filelist');
        let selectedFiles = [];

        function syncInputFiles() {
            const dt = new DataTransfer();
            selectedFiles.forEach(function (file) { dt.items.add(file); });
            attachInput.files = dt.files;
        }

        function renderFileList() {
            listEl.innerHTML = '';
            selectedFiles.forEach(function (file, index) {
                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 rounded-lg bg-gray-50 dark:bg-gray-900/60 border border-gray-200 dark:border-gray-700 px-2.5 py-1.5';
                chip.innerHTML =
                    '<svg class="w-3.5 h-3.5 text-gold-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' +
                    '<span class="truncate max-w-[160px]"></span>' +
                    '<button type="button" class="attach-remove text-gray-400 hover:text-red-500 transition-colors" title="Remove">' +
                        '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>' +
                    '</button>';
                chip.querySelector('span.truncate').textContent = file.name;
                chip.querySelector('.attach-remove').addEventListener('click', function () {
                    selectedFiles.splice(index, 1);
                    syncInputFiles();
                    renderFileList();
                });
                listEl.appendChild(chip);
            });
        }

        attachInput.addEventListener('change', function () {
            selectedFiles = selectedFiles.concat(Array.from(attachInput.files));
            syncInputFiles();
            renderFileList();
        });
    }

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
