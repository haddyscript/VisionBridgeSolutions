{{-- Persistent AI Client Portal assistant — floating bubble on every portal page.
     See specs/AI_ASSISTANT_KNOWLEDGE_BASE.md for the decisions behind this UI. --}}
<button type="button" id="assistant-bubble-toggle" title="Ask the assistant"
        class="fixed bottom-5 right-5 z-40 w-14 h-14 rounded-full bg-gold hover:bg-gold-dark text-navy-dark shadow-xl flex items-center justify-center transition-transform hover:scale-105">
    <svg id="assistant-bubble-icon-chat" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
    </svg>
    <svg id="assistant-bubble-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
    </svg>
</button>

<div id="assistant-panel" class="hidden fixed bottom-24 right-5 z-40 w-[22rem] max-w-[calc(100vw-2.5rem)] h-[32rem] max-h-[calc(100vh-8rem)] bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
    <div class="bg-navy-dark px-4 py-3.5 flex items-center gap-3 shrink-0">
        <div class="w-8 h-8 rounded-full bg-gold/20 text-gold flex items-center justify-center shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
        </div>
        <div class="min-w-0">
            <p class="text-sm font-bold text-white">VisionBridge Assistant</p>
            <p class="text-xs text-white/50">Usually replies instantly</p>
        </div>
    </div>

    <div id="assistant-messages" class="flex-1 overflow-y-auto gold-scrollbar px-4 py-4 space-y-3">
        <div class="flex justify-start">
            <div class="max-w-[85%] rounded-2xl rounded-bl-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm px-3.5 py-2.5">
                Hi! I'm the VisionBridge assistant. Ask me about your project, Care Plan, billing, or how to use the portal — I'm happy to help.
            </div>
        </div>
    </div>

    <div id="assistant-escalated-banner" class="hidden px-4 py-2 text-xs text-gold-dark bg-gold/10 border-t border-gold/20 shrink-0">
        This conversation has been shared with our team — someone will follow up by email if needed.
    </div>

    <form id="assistant-form" data-no-loading-overlay class="shrink-0 border-t border-gray-100 dark:border-gray-700 p-3 flex items-end gap-2">
        @csrf
        <textarea id="assistant-input" rows="1" maxlength="2000" placeholder="Type your question…"
                  class="flex-1 resize-none rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold"></textarea>
        <button type="submit" id="assistant-send" class="shrink-0 w-9 h-9 rounded-lg bg-gold hover:bg-gold-dark text-navy-dark flex items-center justify-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
        </button>
    </form>
</div>

<script>
    (function () {
        const toggle = document.getElementById('assistant-bubble-toggle');
        const iconChat = document.getElementById('assistant-bubble-icon-chat');
        const iconClose = document.getElementById('assistant-bubble-icon-close');
        const panel = document.getElementById('assistant-panel');
        const messagesEl = document.getElementById('assistant-messages');
        const escalatedBanner = document.getElementById('assistant-escalated-banner');
        const form = document.getElementById('assistant-form');
        const input = document.getElementById('assistant-input');
        const sendBtn = document.getElementById('assistant-send');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        let historyLoaded = false;

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function appendMessage(role, content) {
            const wrapper = document.createElement('div');
            wrapper.className = 'flex ' + (role === 'user' ? 'justify-end' : 'justify-start');

            const bubble = document.createElement('div');
            bubble.className = role === 'user'
                ? 'max-w-[85%] rounded-2xl rounded-br-sm bg-gold text-navy-dark text-sm px-3.5 py-2.5 whitespace-pre-wrap break-words'
                : 'max-w-[85%] rounded-2xl rounded-bl-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm px-3.5 py-2.5 whitespace-pre-wrap break-words';
            bubble.innerHTML = escapeHtml(content).replace(/\n/g, '<br>');

            wrapper.appendChild(bubble);
            messagesEl.appendChild(wrapper);
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        function typeOutMessage(content) {
            const wrapper = document.createElement('div');
            wrapper.className = 'flex justify-start';

            const bubble = document.createElement('div');
            bubble.className = 'max-w-[85%] rounded-2xl rounded-bl-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm px-3.5 py-2.5 whitespace-pre-wrap break-words';

            wrapper.appendChild(bubble);
            messagesEl.appendChild(wrapper);

            let shown = 0;
            // A few characters per tick reads as natural typing without
            // dragging out long replies too much.
            const CHARS_PER_TICK = 3;
            const TICK_MS = 20;

            const interval = setInterval(function () {
                shown = Math.min(content.length, shown + CHARS_PER_TICK);
                bubble.innerHTML = escapeHtml(content.slice(0, shown)).replace(/\n/g, '<br>');
                messagesEl.scrollTop = messagesEl.scrollHeight;

                if (shown >= content.length) {
                    clearInterval(interval);
                }
            }, TICK_MS);
        }

        function appendTyping() {
            const wrapper = document.createElement('div');
            wrapper.id = 'assistant-typing';
            wrapper.className = 'flex justify-start';
            wrapper.innerHTML = '<div class="rounded-2xl rounded-bl-sm bg-gray-100 dark:bg-gray-700 text-gray-400 text-sm px-3.5 py-2.5">Typing…</div>';
            messagesEl.appendChild(wrapper);
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        function removeTyping() {
            document.getElementById('assistant-typing')?.remove();
        }

        function loadHistory() {
            if (historyLoaded) return;
            historyLoaded = true;

            fetch('{{ route('portal.assistant.show') }}', {
                headers: { 'Accept': 'application/json' },
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    data.messages.forEach(function (message) {
                        appendMessage(message.role, message.content);
                    });
                    escalatedBanner.classList.toggle('hidden', !data.escalated);
                });
        }

        toggle.addEventListener('click', function () {
            const isHidden = panel.classList.contains('hidden');
            panel.classList.toggle('hidden');
            iconChat.classList.toggle('hidden', isHidden);
            iconClose.classList.toggle('hidden', !isHidden);

            if (isHidden) {
                loadHistory();
                setTimeout(function () { input.focus(); }, 50);
            }
        });

        input.addEventListener('input', function () {
            input.style.height = 'auto';
            input.style.height = Math.min(input.scrollHeight, 96) + 'px';
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.requestSubmit();
            }
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const question = input.value.trim();
            if (!question) return;

            appendMessage('user', question);
            input.value = '';
            input.style.height = 'auto';
            sendBtn.disabled = true;
            appendTyping();

            fetch('{{ route('portal.assistant.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ message: question }),
            })
                .then(function (response) {
                    if (!response.ok) {
                        return response.json().then(function (data) {
                            throw new Error(data.message || 'Something went wrong. Please try again.');
                        });
                    }
                    return response.json();
                })
                .then(function (data) {
                    removeTyping();
                    typeOutMessage(data.reply);
                    escalatedBanner.classList.toggle('hidden', !data.escalated);
                })
                .catch(function (error) {
                    removeTyping();
                    typeOutMessage(error.message || "Sorry, I couldn't process that. Please try again or contact support@visionbridgesolutions.com.");
                })
                .finally(function () {
                    sendBtn.disabled = false;
                });
        });
    })();
</script>
