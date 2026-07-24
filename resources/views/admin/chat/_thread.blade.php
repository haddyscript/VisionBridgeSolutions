{{--
    The active conversation pane of the centralized /admin/chat inbox.
    $project is the selected conversation (see admin/chat/index.blade.php).
--}}
<div id="chat-thread" data-project-id="{{ $project->id }}" data-message-base-url="{{ url('/admin/chat-messages') }}" class="flex flex-col h-full">
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
            @php $isClient = $chatMessage->user_id === $project->user_id; @endphp
            <div class="chat-bubble group flex items-start {{ $isClient ? '' : 'justify-end' }} gap-2.5 max-w-[75%] {{ $isClient ? '' : 'ml-auto' }}"
                 data-message-id="{{ $chatMessage->id }}" data-own="{{ $isClient ? '0' : '1' }}" data-deleted="{{ $chatMessage->isDeleted() ? '1' : '0' }}">
                @if ($isClient)
                    <span class="w-7 h-7 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs font-bold flex items-center justify-center shrink-0">
                        {{ strtoupper(substr($project->user->name, 0, 1)) }}
                    </span>
                @endif
                <div class="chat-bubble-card relative rounded-2xl {{ $isClient ? 'rounded-tl-sm bg-gray-100 dark:bg-gray-700/60' : 'rounded-tr-sm bg-navy text-white' }} px-4 py-2.5">
                    @unless ($isClient)
                        <p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold mb-1">VisionBridge Team</p>
                    @endunless

                    <p class="chat-bubble-body text-sm {{ $isClient ? 'text-gray-700 dark:text-gray-200' : '' }} whitespace-pre-line {{ $chatMessage->isDeleted() ? 'italic opacity-70' : '' }}">{{ $chatMessage->isDeleted() ? 'This message was deleted' : $chatMessage->body }}</p>

                    <p class="chat-bubble-time text-[0.7rem] {{ $isClient ? 'text-gray-500 dark:text-gray-400' : 'text-white/40' }} mt-1.5">
                        <span class="chat-bubble-timestamp">{{ $chatMessage->created_at->diffForHumans() }}</span>
                        <span class="chat-bubble-edited {{ $chatMessage->isEdited() ? '' : 'hidden' }}"> · edited {{ $chatMessage->edited_at?->diffForHumans() }}</span>
                    </p>

                    @if (! $chatMessage->isDeleted())
                        <button type="button" class="chat-bubble-menu-btn absolute -top-2 {{ $isClient ? '-right-2' : '-left-2' }} opacity-0 group-hover:opacity-100 focus:opacity-100 w-6 h-6 rounded-full bg-white dark:bg-gray-700 shadow border border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-opacity">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 100-4 2 2 0 000 4zM10 12a2 2 0 100-4 2 2 0 000 4zM10 18a2 2 0 100-4 2 2 0 000 4z"/></svg>
                        </button>
                        <div class="chat-bubble-menu hidden absolute z-20 {{ $isClient ? 'right-0' : 'left-0' }} top-6 w-40 bg-white dark:bg-navy border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1">
                            @unless ($isClient)
                                <button type="button" class="chat-action-edit w-full text-left px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gold/10 hover:text-gold-dark transition-colors">Edit</button>
                                <button type="button" class="chat-action-delete-everyone w-full text-left px-3 py-2 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">Delete for everyone</button>
                            @endunless
                            <button type="button" class="chat-action-delete-me w-full text-left px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gold/10 hover:text-gold-dark transition-colors">Delete for me</button>
                        </div>
                    @endif
                </div>
                @unless ($isClient)
                    <span class="w-7 h-7 rounded-full bg-navy text-gold text-xs font-bold flex items-center justify-center shrink-0">VB</span>
                @endunless
            </div>
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

function adminCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
}

function adminChatMessageUrl(id) {
    return document.getElementById('chat-thread').dataset.messageBaseUrl + '/' + id;
}

function buildAdminBubbleHtml(data) {
    const isClient = !!data.isFromClient;
    let html = '<div class="chat-bubble group flex items-start ' + (isClient ? '' : 'justify-end') + ' gap-2.5 max-w-[75%] ' + (isClient ? '' : 'ml-auto') + '" data-message-id="' + data.id + '" data-own="' + (isClient ? '0' : '1') + '" data-deleted="0">';
    if (isClient) {
        html += '<span class="w-7 h-7 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs font-bold flex items-center justify-center shrink-0"></span>';
    }
    html += '<div class="chat-bubble-card relative rounded-2xl ' + (isClient ? 'rounded-tl-sm bg-gray-100 dark:bg-gray-700/60' : 'rounded-tr-sm bg-navy text-white') + ' px-4 py-2.5">';
    if (!isClient) {
        html += '<p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold mb-1">VisionBridge Team</p>';
    }
    html += '<p class="chat-bubble-body text-sm ' + (isClient ? 'text-gray-700 dark:text-gray-200' : '') + ' whitespace-pre-line"></p>';
    html += '<p class="chat-bubble-time text-[0.7rem] ' + (isClient ? 'text-gray-500 dark:text-gray-400' : 'text-white/40') + ' mt-1.5">' +
        '<span class="chat-bubble-timestamp"></span>' +
        '<span class="chat-bubble-edited hidden"></span>' +
    '</p>';
    html += '<button type="button" class="chat-bubble-menu-btn absolute -top-2 ' + (isClient ? '-right-2' : '-left-2') + ' opacity-0 group-hover:opacity-100 focus:opacity-100 w-6 h-6 rounded-full bg-white dark:bg-gray-700 shadow border border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-opacity">' +
        '<svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 100-4 2 2 0 000 4zM10 12a2 2 0 100-4 2 2 0 000 4zM10 18a2 2 0 100-4 2 2 0 000 4z"/></svg>' +
    '</button>';
    html += '<div class="chat-bubble-menu hidden absolute z-20 ' + (isClient ? 'right-0' : 'left-0') + ' top-6 w-40 bg-white dark:bg-navy border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1">';
    if (!isClient) {
        html += '<button type="button" class="chat-action-edit w-full text-left px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gold/10 hover:text-gold-dark transition-colors">Edit</button>';
        html += '<button type="button" class="chat-action-delete-everyone w-full text-left px-3 py-2 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">Delete for everyone</button>';
    }
    html += '<button type="button" class="chat-action-delete-me w-full text-left px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gold/10 hover:text-gold-dark transition-colors">Delete for me</button>';
    html += '</div></div>';
    if (!isClient) {
        html += '<span class="w-7 h-7 rounded-full bg-navy text-gold text-xs font-bold flex items-center justify-center shrink-0">VB</span>';
    }
    html += '</div>';
    return html;
}

function appendAdminChatBubble(data) {
    const container = document.getElementById('chat-thread-messages');
    if (!container) return;
    if (container.querySelector('[data-message-id="' + data.id + '"]')) return; // already rendered — avoid double-append (own send + Pusher echo)

    const empty = document.getElementById('chat-thread-empty');
    if (empty) empty.remove();

    const wrapper = document.createElement('div');
    wrapper.innerHTML = buildAdminBubbleHtml(data);
    const bubble = wrapper.firstElementChild;

    if (data.isFromClient) {
        bubble.querySelector('span').textContent = (data.senderName || '?').charAt(0).toUpperCase();
    }
    bubble.querySelector('.chat-bubble-body').textContent = data.body;
    bubble.querySelector('.chat-bubble-timestamp').textContent = data.sentAt;

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
            'X-CSRF-TOKEN': adminCsrfToken(),
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
                'X-CSRF-TOKEN': adminCsrfToken(),
            },
        });
    }
}

/** Applies a live edit or delete pushed over Pusher — same DOM update the local action handlers use. */
function applyAdminChatUpdate(data) {
    const bubble = document.querySelector('.chat-bubble[data-message-id="' + data.id + '"]');
    if (!bubble) return;

    bubble.querySelector('.chat-bubble-body').textContent = data.body;
    const edited = bubble.querySelector('.chat-bubble-edited');
    if (edited) {
        edited.textContent = ' · edited ' + data.editedAt;
        edited.classList.remove('hidden');
    }
}

function applyAdminChatDeleted(data) {
    const bubble = document.querySelector('.chat-bubble[data-message-id="' + data.id + '"]');
    if (!bubble) return;

    bubble.dataset.deleted = '1';
    const body = bubble.querySelector('.chat-bubble-body');
    body.textContent = 'This message was deleted';
    body.classList.add('italic', 'opacity-70');
    bubble.querySelector('.chat-bubble-menu-btn')?.remove();
    bubble.querySelector('.chat-bubble-menu')?.remove();
}

(function () {
    const container = document.getElementById('chat-thread-messages');
    if (!container) return;

    // Menu buttons/dropdowns are built per-bubble (server-rendered and
    // JS-appended alike), so delegation on the scroll container catches
    // every one without needing to re-bind after each append.
    container.addEventListener('click', function (e) {
        const menuBtn = e.target.closest('.chat-bubble-menu-btn');
        if (menuBtn) {
            const menu = menuBtn.nextElementSibling;
            const alreadyOpen = !menu.classList.contains('hidden');
            container.querySelectorAll('.chat-bubble-menu').forEach(function (m) { m.classList.add('hidden'); });
            if (!alreadyOpen) menu.classList.remove('hidden');
            return;
        }

        const editBtn = e.target.closest('.chat-action-edit');
        if (editBtn) {
            editBtn.closest('.chat-bubble-menu').classList.add('hidden');
            startEditingAdminBubble(editBtn.closest('.chat-bubble'));
            return;
        }

        const deleteEveryoneBtn = e.target.closest('.chat-action-delete-everyone');
        if (deleteEveryoneBtn) {
            deleteEveryoneBtn.closest('.chat-bubble-menu').classList.add('hidden');
            if (confirm('Delete this message for everyone? This cannot be undone.')) {
                deleteAdminBubbleForEveryone(deleteEveryoneBtn.closest('.chat-bubble'));
            }
            return;
        }

        const deleteMeBtn = e.target.closest('.chat-action-delete-me');
        if (deleteMeBtn) {
            deleteMeBtn.closest('.chat-bubble-menu').classList.add('hidden');
            deleteAdminBubbleForMe(deleteMeBtn.closest('.chat-bubble'));
            return;
        }

        if (!e.target.closest('.chat-bubble-menu')) {
            container.querySelectorAll('.chat-bubble-menu').forEach(function (m) { m.classList.add('hidden'); });
        }
    });
})();

function startEditingAdminBubble(bubble) {
    const id = bubble.dataset.messageId;
    const bodyEl = bubble.querySelector('.chat-bubble-body');
    const originalText = bodyEl.textContent.trim();
    const isOwn = bubble.dataset.own === '1';

    const form = document.createElement('div');
    form.className = 'chat-bubble-edit-form';
    form.innerHTML =
        '<textarea rows="2" class="w-full min-w-[12rem] resize-none rounded-lg border-0 px-2 py-1.5 text-sm ' + (isOwn ? 'bg-white/10 text-white placeholder-white/50' : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200') + ' focus:outline-none focus:ring-2 focus:ring-gold/50"></textarea>' +
        '<div class="flex items-center justify-end gap-2 mt-1.5">' +
            '<button type="button" class="chat-edit-cancel text-xs font-semibold ' + (isOwn ? 'text-white/70' : 'text-gray-500 dark:text-gray-400') + ' hover:underline">Cancel</button>' +
            '<button type="button" class="chat-edit-save text-xs font-semibold ' + (isOwn ? 'text-gold' : 'text-gold-dark') + ' hover:underline">Save</button>' +
        '</div>';

    bodyEl.classList.add('hidden');
    bubble.querySelector('.chat-bubble-time').classList.add('hidden');
    bubble.querySelector('.chat-bubble-card').appendChild(form);

    const textarea = form.querySelector('textarea');
    textarea.value = originalText;
    textarea.focus();

    form.querySelector('.chat-edit-cancel').addEventListener('click', function () {
        form.remove();
        bodyEl.classList.remove('hidden');
        bubble.querySelector('.chat-bubble-time').classList.remove('hidden');
    });

    form.querySelector('.chat-edit-save').addEventListener('click', function () {
        const newBody = textarea.value.trim();
        if (!newBody) return;

        fetch(adminChatMessageUrl(id), {
            method: 'PATCH',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': adminCsrfToken(),
            },
            body: new URLSearchParams({ body: newBody }),
        })
            .then(function (response) {
                if (!response.ok) throw new Error('Request failed');
                return response.json();
            })
            .then(function (data) {
                bodyEl.textContent = data.body;
                const edited = bubble.querySelector('.chat-bubble-edited');
                edited.textContent = ' · edited ' + data.editedAt;
                edited.classList.remove('hidden');
                form.remove();
                bodyEl.classList.remove('hidden');
                bubble.querySelector('.chat-bubble-time').classList.remove('hidden');
            })
            .catch(function () {
                alert('Could not save the edit. Please try again.');
            });
    });
}

function deleteAdminBubbleForEveryone(bubble) {
    const id = bubble.dataset.messageId;

    fetch(adminChatMessageUrl(id), {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': adminCsrfToken(),
        },
    })
        .then(function (response) {
            if (!response.ok) throw new Error('Request failed');
            applyAdminChatDeleted({ id: id });
        })
        .catch(function () {
            alert('Could not delete the message. Please try again.');
        });
}

function deleteAdminBubbleForMe(bubble) {
    const id = bubble.dataset.messageId;

    fetch(adminChatMessageUrl(id) + '/hide', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': adminCsrfToken(),
        },
    })
        .then(function (response) {
            if (!response.ok) throw new Error('Request failed');
            bubble.remove();
        })
        .catch(function () {
            alert('Could not remove the message. Please try again.');
        });
}
</script>
