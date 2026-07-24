@extends('layouts.portal')

@section('title', 'Chat – Client Portal')
@section('page-title', 'Chat')

@section('content')

@if (! $project)

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No project has been set up for your account yet. Please contact your VisionBridge representative.</p>
    </div>

@else

    <div id="chat-thread" data-project-id="{{ $project->id }}" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden flex flex-col h-[calc(100vh-180px)] min-h-[28rem]">

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
                @if ($chatMessage->user_id === $project->user_id)
                    {{-- Your message bubble --}}
                    <div class="flex items-end justify-end gap-2 max-w-[75%] ml-auto" data-message-id="{{ $chatMessage->id }}">
                        <div class="rounded-2xl rounded-br-md bg-gradient-to-br from-gold to-gold-dark px-4 py-2.5 shadow-sm">
                            <p class="text-sm text-navy-dark font-medium whitespace-pre-line">{{ $chatMessage->body }}</p>
                            <p class="chat-bubble-time text-[0.7rem] text-navy-dark/60 mt-1 text-right">{{ $chatMessage->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @else
                    {{-- VisionBridge bubble --}}
                    <div class="flex items-end gap-2 max-w-[75%]" data-message-id="{{ $chatMessage->id }}">
                        <span class="w-8 h-8 rounded-full bg-gradient-to-br from-navy to-navy-light text-gold text-xs font-bold flex items-center justify-center shrink-0 shadow-sm">VB</span>
                        <div class="rounded-2xl rounded-bl-md bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 px-4 py-2.5 shadow-sm">
                            <p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold-dark mb-1">VisionBridge Team</p>
                            <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">{{ $chatMessage->body }}</p>
                            <p class="chat-bubble-time text-[0.7rem] text-gray-400 dark:text-gray-500 mt-1">{{ $chatMessage->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endif
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
        <form id="chat-thread-form" data-mark-read-url="{{ route('portal.chat.read', $project) }}"
              method="POST" action="{{ route('portal.chat.store', $project) }}"
              onsubmit="return submitPortalChatMessage(this, event)"
              class="shrink-0 flex items-center gap-3 px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
            @csrf
            <textarea name="body" rows="1" placeholder="Type a message…" required
                      class="flex-1 resize-none rounded-full border border-gray-200 dark:border-gray-600 px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold/50 focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500 shadow-sm"></textarea>
            <button type="submit" title="Send" class="shrink-0 w-11 h-11 rounded-full bg-gradient-to-br from-gold to-gold-dark hover:shadow-md text-navy-dark flex items-center justify-center transition-all shadow-sm">
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

        fetch(document.getElementById('chat-thread-form').dataset.markReadUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            },
        });
    })();

    function appendPortalChatBubble(data) {
        const container = document.getElementById('chat-thread-messages');
        if (!container) return;
        if (container.querySelector('[data-message-id="' + data.id + '"]')) return; // already rendered — avoid double-append (own send + Pusher echo)

        const empty = document.getElementById('chat-thread-empty');
        if (empty) empty.remove();

        const bubble = document.createElement('div');
        bubble.dataset.messageId = data.id;

        if (data.isFromClient) {
            bubble.className = 'flex items-end justify-end gap-2 max-w-[75%] ml-auto';
            bubble.innerHTML =
                '<div class="rounded-2xl rounded-br-md bg-gradient-to-br from-gold to-gold-dark px-4 py-2.5 shadow-sm">' +
                    '<p class="text-sm text-navy-dark font-medium whitespace-pre-line"></p>' +
                    '<p class="chat-bubble-time text-[0.7rem] text-navy-dark/60 mt-1 text-right"></p>' +
                '</div>';
        } else {
            bubble.className = 'flex items-end gap-2 max-w-[75%]';
            bubble.innerHTML =
                '<span class="w-8 h-8 rounded-full bg-gradient-to-br from-navy to-navy-light text-gold text-xs font-bold flex items-center justify-center shrink-0 shadow-sm">VB</span>' +
                '<div class="rounded-2xl rounded-bl-md bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 px-4 py-2.5 shadow-sm">' +
                    '<p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold-dark mb-1">VisionBridge Team</p>' +
                    '<p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line"></p>' +
                    '<p class="chat-bubble-time text-[0.7rem] text-gray-400 dark:text-gray-500 mt-1"></p>' +
                '</div>';
        }

        bubble.querySelector('.text-sm').textContent = data.body;
        bubble.querySelector('.chat-bubble-time').textContent = data.sentAt;

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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
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
    </script>

@endif

@endsection
