@extends('layouts.portal')

@section('title', 'Chat – Client Portal')
@section('page-title', 'Chat')

@section('content')

@if (! $project)

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No project has been set up for your account yet. Please contact your VisionBridge representative.</p>
    </div>

@else

    <div id="chat-thread" data-project-id="{{ $project->id }}" data-message-base-url="{{ url('/portal/chat-messages') }}"
         class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden flex flex-col h-[calc(100vh-180px)] min-h-[28rem]">

        {{-- Header --}}
        <div class="shrink-0 flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <span class="relative w-11 h-11 rounded-full bg-gradient-to-br from-navy to-navy-light flex items-center justify-center shrink-0 shadow-sm">
                <span class="text-gold text-sm font-bold">VB</span>
                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-teal border-2 border-white dark:border-gray-800"></span>
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-navy dark:text-white">VisionBridge Team</p>
                <p class="text-xs text-teal-dark dark:text-teal-light">Usually replies within a few hours</p>
            </div>
            <span class="text-xs text-gray-400 dark:text-gray-500 shrink-0">{{ $messages->count() }} message{{ $messages->count() === 1 ? '' : 's' }}</span>
        </div>

        {{-- Messages --}}
        <div id="chat-thread-messages" class="chat-thread-scroll flex-1 overflow-y-auto space-y-4 px-6 py-5 bg-gray-50/60 dark:bg-gray-900/30">
            @forelse ($messages as $chatMessage)
                @php $isOwn = $chatMessage->user_id === $project->user_id; @endphp
                <div class="chat-bubble group flex items-end {{ $isOwn ? 'justify-end' : '' }} gap-2 max-w-[75%] {{ $isOwn ? 'ml-auto' : '' }}"
                     data-message-id="{{ $chatMessage->id }}" data-own="{{ $isOwn ? '1' : '0' }}" data-deleted="{{ $chatMessage->isDeleted() ? '1' : '0' }}">
                    @unless ($isOwn)
                        <span class="w-8 h-8 rounded-full bg-gradient-to-br from-navy to-navy-light text-gold text-xs font-bold flex items-center justify-center shrink-0 shadow-sm">VB</span>
                    @endunless
                    <div class="chat-bubble-card relative rounded-2xl {{ $isOwn ? 'rounded-br-md bg-gradient-to-br from-gold to-gold-dark' : 'rounded-bl-md bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700' }} px-4 py-2.5 shadow-sm">
                        @unless ($isOwn)
                            <p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold-dark mb-1">VisionBridge Team</p>
                        @endunless

                        <p class="chat-bubble-body text-sm {{ $isOwn ? 'text-navy-dark font-medium' : 'text-gray-700 dark:text-gray-200' }} whitespace-pre-line {{ $chatMessage->isDeleted() ? 'italic opacity-70' : '' }}">{{ $chatMessage->isDeleted() ? 'This message was deleted' : $chatMessage->body }}</p>

                        <p class="chat-bubble-time text-[0.7rem] {{ $isOwn ? 'text-navy-dark/60 text-right' : 'text-gray-400 dark:text-gray-500' }} mt-1">
                            <span class="chat-bubble-timestamp">{{ $chatMessage->created_at->diffForHumans() }}</span>
                            <span class="chat-bubble-edited {{ $chatMessage->isEdited() ? '' : 'hidden' }}"> · edited {{ $chatMessage->edited_at?->diffForHumans() }}</span>
                        </p>

                        @if (! $chatMessage->isDeleted())
                            <button type="button" class="chat-bubble-menu-btn absolute -top-2 {{ $isOwn ? '-left-2' : '-right-2' }} opacity-0 group-hover:opacity-100 focus:opacity-100 w-6 h-6 rounded-full bg-white dark:bg-gray-700 shadow border border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-opacity">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 100-4 2 2 0 000 4zM10 12a2 2 0 100-4 2 2 0 000 4zM10 18a2 2 0 100-4 2 2 0 000 4z"/></svg>
                            </button>
                            <div class="chat-bubble-menu hidden absolute z-20 {{ $isOwn ? 'left-0' : 'right-0' }} top-6 w-40 bg-white dark:bg-navy border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1">
                                @if ($isOwn)
                                    <button type="button" class="chat-action-edit w-full text-left px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gold/10 hover:text-gold-dark transition-colors">Edit</button>
                                    <button type="button" class="chat-action-delete-everyone w-full text-left px-3 py-2 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">Delete for everyone</button>
                                @endif
                                <button type="button" class="chat-action-delete-me w-full text-left px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gold/10 hover:text-gold-dark transition-colors">Delete for me</button>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div id="chat-thread-empty" class="h-full flex flex-col items-center justify-center text-center">
                    <div class="w-14 h-14 rounded-full bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No messages yet — say hello, we're here to help.</p>
                </div>
            @endforelse
        </div>

        {{-- Composer --}}
        <form id="chat-thread-form" data-mark-read-url="{{ route('portal.chat.read', $project) }}" data-no-loading-overlay
              method="POST" action="{{ route('portal.chat.store', $project) }}"
              onsubmit="return submitPortalChatMessage(this, event)"
              class="shrink-0 flex items-center gap-3 px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
            @csrf
            <textarea name="body" rows="1" placeholder="Type a message…" required
                      class="flex-1 resize-none rounded-full border border-gray-200 dark:border-gray-600 px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold/50 focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500 shadow-sm"></textarea>
            <button type="button" id="chat-mic-btn" title="Voice input" class="hidden shrink-0 w-11 h-11 rounded-full border border-gray-200 dark:border-gray-600 text-gray-400 hover:text-gold-dark hover:border-gold flex items-center justify-center transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z"/>
                </svg>
            </button>
            <button type="submit" title="Send" class="shrink-0 w-11 h-11 rounded-full bg-gradient-to-br from-gold to-gold-dark hover:shadow-md text-navy-dark flex items-center justify-center transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </form>
    </div>

    {{-- Delete-for-everyone confirm modal — same backdrop-fade/scale-in pattern used on the Book a Consultation page --}}
    <div id="chat-delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div id="chat-delete-backdrop" class="absolute inset-0 bg-navy-dark/60 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>

        <div id="chat-delete-panel" class="relative w-full max-w-sm transform scale-95 opacity-0 transition-all duration-200">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6">
                <div class="w-11 h-11 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/></svg>
                </div>
                <h2 class="font-display text-lg font-bold text-navy dark:text-white mb-2">Delete this message?</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">It'll be removed for everyone in this conversation. This can't be undone.</p>
                <div class="flex justify-end gap-2.5">
                    <button type="button" id="chat-delete-cancel" class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button type="button" id="chat-delete-confirm" class="px-4 py-2.5 rounded-lg text-sm font-semibold bg-red-500 hover:bg-red-600 text-white transition-colors">
                        Delete for Everyone
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function () {
        const container = document.getElementById('chat-thread-messages');
        if (container) container.scrollTop = container.scrollHeight;

        fetch(document.getElementById('chat-thread-form').dataset.markReadUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            },
        })
            .then(function (response) { return response.ok ? response.json() : null; })
            .then(function (data) {
                if (!data || !data.count) return;
                // The sidebar's own "Chat" badge is rendered once at page load
                // and won't otherwise reflect that this thread was just read.
                const badge = document.getElementById('portal-chat-nav-badge');
                if (!badge) return;
                const remaining = (parseInt(badge.textContent, 10) || 0) - data.count;
                if (remaining > 0) {
                    badge.textContent = remaining;
                } else {
                    badge.remove();
                }
            });
    })();

    /**
     * Voice-to-text via the browser's own SpeechRecognition API — no
     * third-party service, no API key, entirely client-side. Supported in
     * Chrome/Edge/Safari; the mic button stays hidden in browsers without it
     * (Firefox) rather than showing a button that wouldn't work.
     */
    (function () {
        const micBtn = document.getElementById('chat-mic-btn');
        const textarea = document.querySelector('#chat-thread-form textarea[name="body"]');
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!micBtn || !textarea || !SpeechRecognition) return;

        micBtn.classList.remove('hidden');

        const recognition = new SpeechRecognition();
        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.lang = 'en-US';

        let listening = false;
        let baseText = '';

        function setListeningUI(active) {
            listening = active;
            micBtn.classList.toggle('text-red-500', active);
            micBtn.classList.toggle('border-red-300', active);
            micBtn.classList.toggle('animate-pulse', active);
        }

        recognition.addEventListener('result', function (e) {
            let transcript = '';
            for (let i = 0; i < e.results.length; i++) {
                transcript += e.results[i][0].transcript;
            }
            textarea.value = (baseText + transcript).trim();
        });

        recognition.addEventListener('error', function (e) {
            setListeningUI(false);
            if (e.error === 'not-allowed' || e.error === 'service-not-allowed') {
                alert('Microphone access was blocked. Please allow microphone access in your browser to use voice input.');
            }
        });

        recognition.addEventListener('end', function () {
            setListeningUI(false);
        });

        micBtn.addEventListener('click', function () {
            if (listening) {
                recognition.stop();
                return;
            }

            baseText = textarea.value.trim() ? textarea.value.trim() + ' ' : '';
            try {
                recognition.start();
                setListeningUI(true);
            } catch (err) {
                // Already running — ignore.
            }
        });
    })();

    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    }

    function chatMessageUrl(id) {
        return document.getElementById('chat-thread').dataset.messageBaseUrl + '/' + id;
    }

    function buildPortalBubbleHtml(data) {
        const isOwn = !!data.isFromClient;
        let html = '<div class="chat-bubble group flex items-end ' + (isOwn ? 'justify-end' : '') + ' gap-2 max-w-[75%] ' + (isOwn ? 'ml-auto' : '') + '" data-message-id="' + data.id + '" data-own="' + (isOwn ? '1' : '0') + '" data-deleted="0">';
        if (!isOwn) {
            html += '<span class="w-8 h-8 rounded-full bg-gradient-to-br from-navy to-navy-light text-gold text-xs font-bold flex items-center justify-center shrink-0 shadow-sm">VB</span>';
        }
        html += '<div class="chat-bubble-card relative rounded-2xl ' + (isOwn ? 'rounded-br-md bg-gradient-to-br from-gold to-gold-dark' : 'rounded-bl-md bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700') + ' px-4 py-2.5 shadow-sm">';
        if (!isOwn) {
            html += '<p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold-dark mb-1">VisionBridge Team</p>';
        }
        html += '<p class="chat-bubble-body text-sm ' + (isOwn ? 'text-navy-dark font-medium' : 'text-gray-700 dark:text-gray-200') + ' whitespace-pre-line"></p>';
        html += '<p class="chat-bubble-time text-[0.7rem] ' + (isOwn ? 'text-navy-dark/60 text-right' : 'text-gray-400 dark:text-gray-500') + ' mt-1">' +
            '<span class="chat-bubble-timestamp"></span>' +
            '<span class="chat-bubble-edited hidden"></span>' +
        '</p>';
        html += '<button type="button" class="chat-bubble-menu-btn absolute -top-2 ' + (isOwn ? '-left-2' : '-right-2') + ' opacity-0 group-hover:opacity-100 focus:opacity-100 w-6 h-6 rounded-full bg-white dark:bg-gray-700 shadow border border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-opacity">' +
            '<svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 100-4 2 2 0 000 4zM10 12a2 2 0 100-4 2 2 0 000 4zM10 18a2 2 0 100-4 2 2 0 000 4z"/></svg>' +
        '</button>';
        html += '<div class="chat-bubble-menu hidden absolute z-20 ' + (isOwn ? 'left-0' : 'right-0') + ' top-6 w-40 bg-white dark:bg-navy border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1">';
        if (isOwn) {
            html += '<button type="button" class="chat-action-edit w-full text-left px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gold/10 hover:text-gold-dark transition-colors">Edit</button>';
            html += '<button type="button" class="chat-action-delete-everyone w-full text-left px-3 py-2 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">Delete for everyone</button>';
        }
        html += '<button type="button" class="chat-action-delete-me w-full text-left px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gold/10 hover:text-gold-dark transition-colors">Delete for me</button>';
        html += '</div></div></div>';
        return html;
    }

    function appendPortalChatBubble(data) {
        const container = document.getElementById('chat-thread-messages');
        if (!container) return;
        if (container.querySelector('[data-message-id="' + data.id + '"]')) return; // already rendered — avoid double-append (own send + Pusher echo)

        const empty = document.getElementById('chat-thread-empty');
        if (empty) empty.remove();

        const wrapper = document.createElement('div');
        wrapper.innerHTML = buildPortalBubbleHtml(data);
        const bubble = wrapper.firstElementChild;
        bubble.querySelector('.chat-bubble-body').textContent = data.body;
        bubble.querySelector('.chat-bubble-timestamp').textContent = data.sentAt;

        container.appendChild(bubble);
        container.scrollTop = container.scrollHeight;
    }

    function submitPortalChatMessage(form, event) {
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
                'X-CSRF-TOKEN': csrfToken(),
            },
            body: new FormData(form),
        })
            .then(function (response) {
                if (!response.ok) throw new Error('Request failed');
                return response.json();
            })
            .then(function (data) {
                appendPortalChatBubble({
                    id: data.id,
                    body: data.body,
                    sentAt: data.sentAt,
                    isFromClient: true,
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

    /** Applies a live edit or delete pushed over Pusher — same DOM update the local action handlers use. */
    function applyPortalChatUpdate(data) {
        const bubble = document.querySelector('.chat-bubble[data-message-id="' + data.id + '"]');
        if (!bubble) return;

        bubble.querySelector('.chat-bubble-body').textContent = data.body;
        const edited = bubble.querySelector('.chat-bubble-edited');
        if (edited) {
            edited.textContent = ' · edited ' + data.editedAt;
            edited.classList.remove('hidden');
        }
    }

    function applyPortalChatDeleted(data) {
        const bubble = document.querySelector('.chat-bubble[data-message-id="' + data.id + '"]');
        if (!bubble) return;

        bubble.dataset.deleted = '1';
        const body = bubble.querySelector('.chat-bubble-body');
        body.textContent = 'This message was deleted';
        body.classList.add('italic', 'opacity-70');
        bubble.querySelector('.chat-bubble-menu-btn')?.remove();
        bubble.querySelector('.chat-bubble-menu')?.remove();
    }

    let chatDeleteConfirmCallback = null;

    function openChatDeleteModal(onConfirm) {
        chatDeleteConfirmCallback = onConfirm;
        const modal = document.getElementById('chat-delete-modal');
        const backdrop = document.getElementById('chat-delete-backdrop');
        const panel = document.getElementById('chat-delete-panel');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(function () {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('scale-95', 'opacity-0');
        });
    }

    function closeChatDeleteModal() {
        const modal = document.getElementById('chat-delete-modal');
        const backdrop = document.getElementById('chat-delete-backdrop');
        const panel = document.getElementById('chat-delete-panel');

        backdrop.classList.add('opacity-0');
        panel.classList.add('scale-95', 'opacity-0');
        setTimeout(function () {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
        chatDeleteConfirmCallback = null;
    }

    (function () {
        document.getElementById('chat-delete-cancel')?.addEventListener('click', closeChatDeleteModal);
        document.getElementById('chat-delete-backdrop')?.addEventListener('click', closeChatDeleteModal);
        document.getElementById('chat-delete-confirm')?.addEventListener('click', function () {
            const callback = chatDeleteConfirmCallback;
            closeChatDeleteModal();
            if (callback) callback();
        });
    })();

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
                startEditingBubble(editBtn.closest('.chat-bubble'));
                return;
            }

            const deleteEveryoneBtn = e.target.closest('.chat-action-delete-everyone');
            if (deleteEveryoneBtn) {
                deleteEveryoneBtn.closest('.chat-bubble-menu').classList.add('hidden');
                const bubbleToDelete = deleteEveryoneBtn.closest('.chat-bubble');
                openChatDeleteModal(function () {
                    deleteBubbleForEveryone(bubbleToDelete);
                });
                return;
            }

            const deleteMeBtn = e.target.closest('.chat-action-delete-me');
            if (deleteMeBtn) {
                deleteMeBtn.closest('.chat-bubble-menu').classList.add('hidden');
                deleteBubbleForMe(deleteMeBtn.closest('.chat-bubble'));
                return;
            }

            if (!e.target.closest('.chat-bubble-menu')) {
                container.querySelectorAll('.chat-bubble-menu').forEach(function (m) { m.classList.add('hidden'); });
            }
        });
    })();

    const CHAT_EDIT_WIDTH_CLASSES = ['w-72', 'sm:w-96', 'max-w-full'];

    function startEditingBubble(bubble) {
        const id = bubble.dataset.messageId;
        const bodyEl = bubble.querySelector('.chat-bubble-body');
        const originalText = bodyEl.textContent.trim();
        const isOwn = bubble.dataset.own === '1';
        const card = bubble.querySelector('.chat-bubble-card');

        const form = document.createElement('div');
        form.className = 'chat-bubble-edit-form';
        form.innerHTML =
            '<textarea rows="4" class="w-full resize-y rounded-lg border-0 px-2 py-1.5 text-sm ' + (isOwn ? 'bg-white/60 text-navy-dark placeholder-navy-dark/50' : 'bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-200') + ' focus:outline-none focus:ring-2 focus:ring-gold/50"></textarea>' +
            '<div class="flex items-center justify-end gap-2 mt-1.5">' +
                '<button type="button" class="chat-edit-cancel text-xs font-semibold ' + (isOwn ? 'text-navy-dark/70' : 'text-gray-500 dark:text-gray-400') + ' hover:underline">Cancel</button>' +
                '<button type="button" class="chat-edit-save text-xs font-semibold ' + (isOwn ? 'text-navy-dark' : 'text-gold-dark') + ' hover:underline">Save</button>' +
            '</div>';

        card.classList.add(...CHAT_EDIT_WIDTH_CLASSES);
        bodyEl.classList.add('hidden');
        bubble.querySelector('.chat-bubble-time').classList.add('hidden');
        card.appendChild(form);

        const textarea = form.querySelector('textarea');
        textarea.value = originalText;
        textarea.focus();

        function exitEditMode() {
            form.remove();
            card.classList.remove(...CHAT_EDIT_WIDTH_CLASSES);
            bodyEl.classList.remove('hidden');
            bubble.querySelector('.chat-bubble-time').classList.remove('hidden');
        }

        form.querySelector('.chat-edit-cancel').addEventListener('click', exitEditMode);

        form.querySelector('.chat-edit-save').addEventListener('click', function () {
            const newBody = textarea.value.trim();
            if (!newBody) return;

            fetch(chatMessageUrl(id), {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
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
                    exitEditMode();
                })
                .catch(function () {
                    alert('Could not save the edit. Please try again.');
                });
        });
    }

    function deleteBubbleForEveryone(bubble) {
        const id = bubble.dataset.messageId;

        fetch(chatMessageUrl(id), {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
            },
        })
            .then(function (response) {
                if (!response.ok) throw new Error('Request failed');
                applyPortalChatDeleted({ id: id });
            })
            .catch(function () {
                alert('Could not delete the message. Please try again.');
            });
    }

    function deleteBubbleForMe(bubble) {
        const id = bubble.dataset.messageId;

        fetch(chatMessageUrl(id) + '/hide', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
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

@endif

@endsection
