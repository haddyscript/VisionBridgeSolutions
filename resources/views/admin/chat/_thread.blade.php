{{--
    The active conversation pane of the centralized /admin/chat inbox.
    $project is the selected conversation (see admin/chat/index.blade.php).
--}}
<div id="chat-thread" data-project-id="{{ $project->id }}" class="flex flex-col h-full">
    <div class="shrink-0 flex items-center gap-3 px-5 py-4 border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('admin.chat.index') }}" class="sm:hidden shrink-0 w-8 h-8 rounded-full text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-center transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <span class="w-9 h-9 rounded-full bg-navy text-gold text-sm font-bold flex items-center justify-center shrink-0">
            {{ strtoupper(substr($project->user->name, 0, 1)) }}
        </span>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-semibold text-navy dark:text-white truncate">{{ $project->user->name }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $project->name }}</p>
        </div>
        <a href="{{ route('admin.projects.show', $project) }}" class="hidden sm:inline-flex shrink-0 items-center gap-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-gold-dark border border-gray-200 dark:border-gray-600 hover:border-gold px-3 py-1.5 rounded-lg transition-colors">
            View Project
        </a>
    </div>

    <div id="chat-thread-messages" class="chat-thread-scroll flex-1 overflow-y-auto space-y-3 px-5 py-4">
        @forelse ($project->chatMessages as $chatMessage)
            @if ($chatMessage->user_id === $project->user_id)
                {{-- Client bubble --}}
                <div class="flex items-start gap-2.5 max-w-[75%]" data-message-id="{{ $chatMessage->id }}">
                    <span class="w-7 h-7 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs font-bold flex items-center justify-center shrink-0">
                        {{ strtoupper(substr($project->user->name, 0, 1)) }}
                    </span>
                    <div class="rounded-2xl rounded-tl-sm bg-gray-100 dark:bg-gray-700/60 px-4 py-2.5">
                        <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">{{ $chatMessage->body }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">{{ $chatMessage->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @else
                {{-- Admin bubble --}}
                <div class="flex items-start justify-end gap-2.5 max-w-[75%] ml-auto" data-message-id="{{ $chatMessage->id }}">
                    <div class="rounded-2xl rounded-tr-sm bg-navy text-white px-4 py-2.5">
                        <p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold mb-1">VisionBridge Team</p>
                        <p class="text-sm whitespace-pre-line">{{ $chatMessage->body }}</p>
                        <p class="text-xs text-white/40 mt-1.5">{{ $chatMessage->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="w-7 h-7 rounded-full bg-navy text-gold text-xs font-bold flex items-center justify-center shrink-0">VB</span>
                </div>
            @endif
        @empty
            <div id="chat-thread-empty" class="h-full flex items-center justify-center">
                <p class="text-sm text-gray-400 dark:text-gray-500">No messages yet — say hello to {{ $project->user->name }}.</p>
            </div>
        @endforelse
    </div>

    <form id="chat-thread-form" data-mark-read-url="{{ route('admin.chat.read', $project) }}"
          method="POST" action="{{ route('admin.chat.store', $project) }}"
          onsubmit="return submitAdminChatMessage(this, event)"
          class="shrink-0 flex items-center gap-2 px-5 py-4 border-t border-gray-200 dark:border-gray-700">
        @csrf
        <textarea name="body" rows="1" placeholder="Message {{ $project->user->name }}…" required
                  class="flex-1 resize-none rounded-full border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500"></textarea>
        <button type="submit" title="Send" class="shrink-0 w-10 h-10 rounded-full bg-navy hover:bg-navy-light text-white flex items-center justify-center transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
        </button>
    </form>
</div>

<script>
(function () {
    const container = document.getElementById('chat-thread-messages');
    if (container) container.scrollTop = container.scrollHeight;

    if (typeof markAdminChatRead === 'function') markAdminChatRead();
})();

function appendAdminChatBubble(data) {
    const container = document.getElementById('chat-thread-messages');
    if (!container) return;
    if (container.querySelector('[data-message-id="' + data.id + '"]')) return; // already rendered — avoid double-append (own send + Pusher echo)

    const empty = document.getElementById('chat-thread-empty');
    if (empty) empty.remove();

    const bubble = document.createElement('div');
    bubble.dataset.messageId = data.id;

    if (data.isFromClient) {
        bubble.className = 'flex items-start gap-2.5 max-w-[75%]';
        bubble.innerHTML =
            '<span class="w-7 h-7 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs font-bold flex items-center justify-center shrink-0"></span>' +
            '<div class="rounded-2xl rounded-tl-sm bg-gray-100 dark:bg-gray-700/60 px-4 py-2.5">' +
                '<p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line"></p>' +
                '<p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5"></p>' +
            '</div>';
        bubble.querySelector('span').textContent = (data.senderName || '?').charAt(0).toUpperCase();
        bubble.querySelector('.text-sm').textContent = data.body;
        bubble.querySelector('.text-xs').textContent = data.sentAt;
    } else {
        bubble.className = 'flex items-start justify-end gap-2.5 max-w-[75%] ml-auto';
        bubble.innerHTML =
            '<div class="rounded-2xl rounded-tr-sm bg-navy text-white px-4 py-2.5">' +
                '<p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold mb-1">VisionBridge Team</p>' +
                '<p class="text-sm whitespace-pre-line"></p>' +
                '<p class="text-xs text-white/40 mt-1.5"></p>' +
            '</div>' +
            '<span class="w-7 h-7 rounded-full bg-navy text-gold text-xs font-bold flex items-center justify-center shrink-0">VB</span>';
        bubble.querySelector('.text-sm').textContent = data.body;
        bubble.querySelector('.text-xs').textContent = data.sentAt;
    }

    container.appendChild(bubble);
    container.scrollTop = container.scrollHeight;
}

function submitAdminChatMessage(form, event) {
    event.preventDefault();

    const textarea = form.querySelector('textarea[name="body"]');
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnHtml = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML =
        '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">' +
            '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
            '<path class="opacity-75" fill="currentColor" d="M12 2a10 10 0 0110 10h-4a6 6 0 00-6-6V2z"></path>' +
        '</svg>';

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
            appendAdminChatBubble({
                id: data.id,
                body: data.body,
                sentAt: data.sentAt,
                isFromClient: false,
            });
            textarea.value = '';
        })
        .catch(function () {
            alert('Could not send the message. Please try again.');
        })
        .finally(function () {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
        });

    return false;
}

function markAdminChatRead() {
    const form = document.getElementById('chat-thread-form');
    if (form && form.dataset.markReadUrl) {
        fetch(form.dataset.markReadUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            },
        });
    }
}
</script>
