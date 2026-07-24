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

    {{-- Typing indicator --}}
    <div id="chat-typing-indicator" class="hidden shrink-0 px-5 pt-2 text-xs text-gray-400 dark:text-gray-500 italic">
        {{ $project->user->name }} is typing…
    </div>

    <form id="chat-thread-form" data-mark-read-url="{{ route('admin.chat.read', $project) }}" data-typing-url="{{ route('admin.chat.typing', $project) }}"
          method="POST" action="{{ route('admin.chat.store', $project) }}"
          onsubmit="return submitAdminChatMessage(this, event)"
          class="shrink-0 flex items-center gap-2 px-5 py-4 border-t border-gray-200 dark:border-gray-700">
        @csrf
        <textarea name="body" rows="1" placeholder="Message {{ $project->user->name }}…" required
                  class="flex-1 resize-none rounded-full border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500"></textarea>
        <button type="button" id="chat-mic-btn" title="Voice input" class="hidden relative shrink-0 w-10 h-10 rounded-full border border-gray-300 dark:border-gray-600 text-gray-400 hover:text-gold-dark hover:border-gold flex items-center justify-center transition-colors">
            <span id="chat-mic-ring" class="hidden absolute inset-0 rounded-full bg-red-400 opacity-75 animate-ping"></span>
            <svg id="chat-mic-icon" class="w-4 h-4 relative" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z"/>
            </svg>
            <span id="chat-mic-bars" class="hidden relative items-end justify-center gap-0.5 h-4 w-4">
                <span class="chat-mic-bar w-0.5 h-full bg-current rounded-full origin-bottom"></span>
                <span class="chat-mic-bar w-0.5 h-full bg-current rounded-full origin-bottom"></span>
                <span class="chat-mic-bar w-0.5 h-full bg-current rounded-full origin-bottom"></span>
                <span class="chat-mic-bar w-0.5 h-full bg-current rounded-full origin-bottom"></span>
            </span>
        </button>
        <button type="submit" title="Send" class="shrink-0 w-10 h-10 rounded-full bg-navy hover:bg-navy-light text-white flex items-center justify-center transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
        </button>
    </form>
</div>

{{-- Delete-for-everyone confirm modal — same backdrop-fade/scale-in pattern as the "Reset client password?" modal on the project page --}}
<div id="chat-delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div id="chat-delete-backdrop" class="absolute inset-0 bg-navy-dark/60 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>

    <div id="chat-delete-panel" class="relative w-full max-w-sm transform scale-95 opacity-0 transition-all duration-200">
        <div class="bg-white dark:bg-navy rounded-2xl shadow-2xl p-6">
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

    if (typeof markAdminChatRead === 'function') markAdminChatRead();
})();

/**
 * Voice-to-text via the browser's own SpeechRecognition API — no
 * third-party service, no API key, entirely client-side. Supported in
 * Chrome/Edge/Safari; the mic button stays hidden in browsers without it
 * (Firefox) rather than showing a button that wouldn't work.
 */
(function () {
    const micBtn = document.getElementById('chat-mic-btn');
    const micIcon = document.getElementById('chat-mic-icon');
    const micBars = document.getElementById('chat-mic-bars');
    const micRing = document.getElementById('chat-mic-ring');
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

    // Real-time volume-reactive bars — separate from SpeechRecognition
    // (which only exposes transcripts, not audio levels), so this opens
    // its own short-lived getUserMedia stream purely for visualization.
    // Since SpeechRecognition already required mic permission, this
    // reuses that same grant rather than prompting again.
    let audioCtx = null;
    let analyser = null;
    let micStream = null;
    let rafId = null;

    function startVisualizer() {
        if (!navigator.mediaDevices?.getUserMedia) return;

        navigator.mediaDevices.getUserMedia({ audio: true }).then(function (stream) {
            if (!listening) {
                stream.getTracks().forEach(function (t) { t.stop(); });
                return;
            }

            micStream = stream;
            audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
            analyser = audioCtx.createAnalyser();
            analyser.fftSize = 64;
            audioCtx.createMediaStreamSource(stream).connect(analyser);

            const data = new Uint8Array(analyser.frequencyBinCount);
            const bars = micBars.querySelectorAll('.chat-mic-bar');
            const segment = Math.max(1, Math.floor(data.length / bars.length));

            (function draw() {
                analyser.getByteFrequencyData(data);
                bars.forEach(function (bar, i) {
                    let sum = 0;
                    for (let j = i * segment; j < (i + 1) * segment; j++) sum += data[j];
                    const scale = Math.max(0.25, Math.min(1, (sum / segment) / 130));
                    bar.style.transform = 'scaleY(' + scale + ')';
                });
                rafId = requestAnimationFrame(draw);
            })();
        }).catch(function () {
            // Visualization is decorative — voice-to-text itself still works without it.
        });
    }

    function stopVisualizer() {
        if (rafId) cancelAnimationFrame(rafId);
        rafId = null;
        if (micStream) {
            micStream.getTracks().forEach(function (t) { t.stop(); });
            micStream = null;
        }
    }

    function setListeningUI(active) {
        listening = active;
        micIcon.classList.toggle('hidden', active);
        micBars.classList.toggle('hidden', !active);
        micBars.classList.toggle('flex', active);
        micRing.classList.toggle('hidden', !active);
        micBtn.classList.toggle('text-red-500', active);
        micBtn.classList.toggle('border-red-300', active);

        if (active) {
            startVisualizer();
        } else {
            stopVisualizer();
        }
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

let adminChatDeleteConfirmCallback = null;

function openAdminChatDeleteModal(onConfirm) {
    adminChatDeleteConfirmCallback = onConfirm;
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

function closeAdminChatDeleteModal() {
    const modal = document.getElementById('chat-delete-modal');
    const backdrop = document.getElementById('chat-delete-backdrop');
    const panel = document.getElementById('chat-delete-panel');

    backdrop.classList.add('opacity-0');
    panel.classList.add('scale-95', 'opacity-0');
    setTimeout(function () {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
    adminChatDeleteConfirmCallback = null;
}

(function () {
    document.getElementById('chat-delete-cancel')?.addEventListener('click', closeAdminChatDeleteModal);
    document.getElementById('chat-delete-backdrop')?.addEventListener('click', closeAdminChatDeleteModal);
    document.getElementById('chat-delete-confirm')?.addEventListener('click', function () {
        const callback = adminChatDeleteConfirmCallback;
        closeAdminChatDeleteModal();
        if (callback) callback();
    });
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

/**
 * Typing indicator — sends at most one "I'm typing" ping every ~2s while
 * the textarea has input (not one per keystroke), and shows the incoming
 * indicator for a few seconds after the last ping received, auto-expiring
 * rather than waiting on an explicit "stopped typing" signal that a closed
 * tab or dropped connection would never send.
 */
(function () {
    const form = document.getElementById('chat-thread-form');
    const textarea = form?.querySelector('textarea[name="body"]');
    if (!form || !textarea || !form.dataset.typingUrl) return;

    let lastSentAt = 0;

    textarea.addEventListener('input', function () {
        const now = Date.now();
        if (now - lastSentAt < 2000) return;
        lastSentAt = now;

        fetch(form.dataset.typingUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': adminCsrfToken() },
        });
    });
})();

let adminTypingHideTimer = null;

function showAdminTypingIndicator() {
    const indicator = document.getElementById('chat-typing-indicator');
    if (!indicator) return;

    indicator.classList.remove('hidden');
    clearTimeout(adminTypingHideTimer);
    adminTypingHideTimer = setTimeout(function () {
        indicator.classList.add('hidden');
    }, 3500);
}

function markAdminChatRead() {
    const form = document.getElementById('chat-thread-form');
    if (!form || !form.dataset.markReadUrl) return;

    fetch(form.dataset.markReadUrl, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': adminCsrfToken(),
        },
    })
        .then(function (response) { return response.ok ? response.json() : null; })
        .then(function (data) {
            if (!data || !data.count) return;

            // This project's own row in the conversation list — rendered
            // once at page load, otherwise stays stuck showing whatever was
            // unread when the page opened even though it's now been read.
            const projectId = document.getElementById('chat-thread')?.dataset.projectId;
            const row = projectId && document.querySelector('.conversation-row[data-project-id="' + projectId + '"]');
            if (row) {
                const rowBadge = row.querySelector('.conversation-unread-badge');
                if (rowBadge) {
                    rowBadge.textContent = '0';
                    rowBadge.classList.add('hidden');
                }
                row.querySelector('.conversation-name')?.classList.remove('font-bold');
            }

            // Same staleness problem for the "Chat" nav item's own badge.
            const navBadge = document.getElementById('admin-chat-nav-badge');
            if (navBadge) {
                const remaining = (parseInt(navBadge.textContent, 10) || 0) - data.count;
                if (remaining > 0) {
                    navBadge.textContent = remaining;
                } else {
                    navBadge.remove();
                }
            }
        });
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
            const bubbleToDelete = deleteEveryoneBtn.closest('.chat-bubble');
            openAdminChatDeleteModal(function () {
                deleteAdminBubbleForEveryone(bubbleToDelete);
            });
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

const CHAT_EDIT_WIDTH_CLASSES = ['w-72', 'sm:w-96', 'max-w-full'];

function startEditingAdminBubble(bubble) {
    const id = bubble.dataset.messageId;
    const bodyEl = bubble.querySelector('.chat-bubble-body');
    const originalText = bodyEl.textContent.trim();
    const isOwn = bubble.dataset.own === '1';
    const card = bubble.querySelector('.chat-bubble-card');

    const form = document.createElement('div');
    form.className = 'chat-bubble-edit-form';
    form.innerHTML =
        '<textarea rows="4" class="w-full resize-y rounded-lg border-0 px-2 py-1.5 text-sm ' + (isOwn ? 'bg-white/10 text-white placeholder-white/50' : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200') + ' focus:outline-none focus:ring-2 focus:ring-gold/50"></textarea>' +
        '<div class="flex items-center justify-end gap-2 mt-1.5">' +
            '<button type="button" class="chat-edit-cancel text-xs font-semibold ' + (isOwn ? 'text-white/70' : 'text-gray-500 dark:text-gray-400') + ' hover:underline">Cancel</button>' +
            '<button type="button" class="chat-edit-save text-xs font-semibold ' + (isOwn ? 'text-gold' : 'text-gold-dark') + ' hover:underline">Save</button>' +
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
                exitEditMode();
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
