{{--
    Client <-> Admin chat for this project. Unlike the Uploads/Revisions
    thread (_text-thread.blade.php), there's only ever one running
    conversation per project, so no list/detail toggle is needed — just an
    always-visible scrollback + composer, WhatsApp-style.
--}}
<div id="chat-thread" data-project-id="{{ $project->id }}" class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-navy dark:text-white">Chat with {{ $project->user->name }}</h3>
        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $project->chatMessages->count() }} message{{ $project->chatMessages->count() === 1 ? '' : 's' }}</span>
    </div>

    <div id="chat-thread-messages" class="chat-thread-scroll space-y-3 max-h-[calc(100vh-420px)] overflow-y-auto pr-1 mb-4">
        @forelse ($project->chatMessages as $chatMessage)
            @if ($chatMessage->user_id === $project->user_id)
                {{-- Client bubble --}}
                <div class="flex items-start gap-2.5 max-w-[85%]" data-message-id="{{ $chatMessage->id }}">
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
                <div class="flex items-start justify-end gap-2.5 max-w-[85%] ml-auto" data-message-id="{{ $chatMessage->id }}">
                    <div class="rounded-2xl rounded-tr-sm bg-navy text-white px-4 py-2.5">
                        <p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold mb-1">VisionBridge Team</p>
                        <p class="text-sm whitespace-pre-line">{{ $chatMessage->body }}</p>
                        <p class="text-xs text-white/40 mt-1.5">{{ $chatMessage->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="w-7 h-7 rounded-full bg-navy text-gold text-xs font-bold flex items-center justify-center shrink-0">VB</span>
                </div>
            @endif
        @empty
            <p id="chat-thread-empty" class="text-sm text-gray-500 dark:text-gray-400 text-center py-6">No messages yet — say hello.</p>
        @endforelse
    </div>

    <form id="chat-thread-form" data-mark-read-url="{{ route('admin.chat.read', $project) }}"
          method="POST" action="{{ route('admin.chat.store', $project) }}"
          onsubmit="return submitAdminChatMessage(this, event)"
          class="pt-3 border-t border-gray-200 dark:border-gray-700 flex items-center gap-2">
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
function appendAdminChatBubble(data) {
    const container = document.getElementById('chat-thread-messages');
    if (!container) return;
    if (container.querySelector('[data-message-id="' + data.id + '"]')) return; // already rendered — avoid double-append (own send + Pusher echo)

    const empty = document.getElementById('chat-thread-empty');
    if (empty) empty.remove();

    const bubble = document.createElement('div');
    bubble.dataset.messageId = data.id;

    if (data.isFromClient) {
        bubble.className = 'flex items-start gap-2.5 max-w-[85%]';
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
        bubble.className = 'flex items-start justify-end gap-2.5 max-w-[85%] ml-auto';
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
