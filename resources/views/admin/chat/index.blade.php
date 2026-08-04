@extends('layouts.admin')

@section('title', 'Chat – Admin')
@section('page-title', 'Chat')

@section('content')

<div class="flex h-[calc(100vh-120px)] min-h-[28rem] bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

    {{-- Conversation list --}}
    <div class="w-full sm:w-80 shrink-0 border-r border-gray-200 dark:border-gray-700 flex-col {{ $activeProject ? 'hidden sm:flex' : 'flex' }}">
        <div class="shrink-0 p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5A6.5 6.5 0 114 10.5a6.5 6.5 0 0113 0z"/></svg>
                <input type="text" id="chat-search" placeholder="Search clients or projects…"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500">
            </div>
        </div>

        <div id="chat-conversation-list" class="flex-1 overflow-y-auto">
            @forelse ($projects as $proj)
                @php
                    $unread = $proj->unreadClientChatMessagesCount();
                    $isActive = $activeProject && $activeProject->id === $proj->id;
                @endphp
                <a href="{{ route('admin.chat.show', $proj) }}"
                   data-search="{{ strtolower($proj->user->name.' '.$proj->name) }}"
                   data-project-id="{{ $proj->id }}"
                   class="conversation-row flex items-center gap-3 px-4 py-3 border-b border-gray-100 dark:border-gray-700/60 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors {{ $isActive ? 'bg-gold/10 dark:bg-gold/10' : '' }}">
                    <span class="w-10 h-10 rounded-full bg-navy text-gold text-sm font-bold flex items-center justify-center shrink-0">
                        {{ strtoupper(substr($proj->user->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <p class="conversation-name text-sm {{ $unread > 0 ? 'font-bold' : 'font-semibold' }} text-navy dark:text-white truncate">{{ $proj->user->name }}</p>
                            <span class="conversation-time text-xs text-gray-400 dark:text-gray-500 shrink-0">
                                {{ $proj->chat_messages_max_created_at ? \Illuminate\Support\Carbon::parse($proj->chat_messages_max_created_at)->diffForHumans(null, true) : '' }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $proj->name }}</p>
                    </div>
                    <span class="conversation-unread-badge shrink-0 min-w-[1.25rem] h-5 px-1.5 rounded-full bg-teal text-white text-xs font-semibold flex items-center justify-center {{ $unread > 0 ? '' : 'hidden' }}">{{ $unread }}</span>
                </a>
            @empty
                <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-10">No clients yet.</p>
            @endforelse
            <p id="chat-search-empty" class="hidden text-sm text-gray-400 dark:text-gray-500 text-center py-10">No conversations match "<span id="chat-search-empty-term"></span>".</p>
        </div>
    </div>

    {{-- Active conversation --}}
    <div class="flex-1 min-w-0 flex-col {{ $activeProject ? 'flex' : 'hidden sm:flex' }}">
        @if ($activeProject)
            @include('admin.chat._thread', ['project' => $activeProject])
        @else
            <div class="h-full flex flex-col items-center justify-center text-center p-10">
                <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Select a conversation to start chatting.</p>
            </div>
        @endif
    </div>
</div>

<script>
(function () {
    const search = document.getElementById('chat-search');
    if (!search) return;

    const rows = document.querySelectorAll('.conversation-row');
    const emptyState = document.getElementById('chat-search-empty');
    const emptyTerm = document.getElementById('chat-search-empty-term');

    search.addEventListener('input', function () {
        const term = search.value.trim().toLowerCase();
        let visibleCount = 0;

        rows.forEach(function (row) {
            const show = !term || row.dataset.search.includes(term);
            row.classList.toggle('hidden', !show);
            if (show) visibleCount++;
        });

        if (emptyState) {
            emptyState.classList.toggle('hidden', visibleCount > 0 || !term);
            if (emptyTerm) emptyTerm.textContent = search.value.trim();
        }
    });
})();

/**
 * Live-updates a client's row when a message comes in for a project that
 * isn't the one currently open (see private-admin.chat-inbox in
 * layouts/admin.blade.php) — bumps it to the top, refreshes the activity
 * time, and increments the unread badge unless we're already viewing that
 * project's thread. A brand-new client with no existing row is a no-op here
 * (nothing to update) — that case still needs a reload.
 */
function bumpAdminChatInboxRow(data) {
    const list = document.getElementById('chat-conversation-list');
    if (!list) return;

    const row = list.querySelector('.conversation-row[data-project-id="' + data.projectId + '"]');
    if (!row) return;

    const timeEl = row.querySelector('.conversation-time');
    if (timeEl) timeEl.textContent = data.sentAt;

    const activeProjectId = document.getElementById('chat-thread')?.dataset.projectId;
    const isViewingThisProject = !!activeProjectId && String(activeProjectId) === String(data.projectId);

    if (data.isFromClient && !isViewingThisProject) {
        const badge = row.querySelector('.conversation-unread-badge');
        if (badge) {
            const current = parseInt(badge.textContent, 10) || 0;
            badge.textContent = current + 1;
            badge.classList.remove('hidden');
        }
        row.querySelector('.conversation-name')?.classList.add('font-bold');
    }

    list.prepend(row);
}
</script>

@endsection
