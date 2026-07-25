@extends('layouts.portal')

@section('title', 'Chat – Client Portal')
@section('page-title', 'Chat')

@section('content')

@if (! $project)

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No project has been set up for your account yet. Please contact your VisionBridge representative.</p>
    </div>

@else

    <style>
        @keyframes chatBubbleIn {
            from { opacity: 0; transform: translateY(8px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .chat-bubble-enter { animation: chatBubbleIn 220ms cubic-bezier(0.2, 0.8, 0.2, 1); }
        .chat-typing-dot { display: inline-block; animation: chatTypingBounce 1.2s ease-in-out infinite; }
        .chat-typing-dot:nth-child(2) { animation-delay: 0.15s; }
        .chat-typing-dot:nth-child(3) { animation-delay: 0.3s; }
        @keyframes chatTypingBounce {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.35; }
            30% { transform: translateY(-2px); opacity: 1; }
        }
        .chat-bubble-card { transition: transform 200ms ease, box-shadow 200ms ease, background-color 200ms ease; }
        .chat-bubble-card.chat-bubble-hoverable:hover { transform: translateY(-1px); }
    </style>

    <div id="chat-thread" data-project-id="{{ $project->id }}" data-message-base-url="{{ url('/portal/chat-messages') }}"
         class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700/60 shadow-sm overflow-hidden flex flex-col h-[calc(100vh-180px)] min-h-[28rem] transition-colors duration-200">

        {{-- Header --}}
        @php
            // Reuses the exact same status labels/colors the portal's own top
            // bar already shows for this project (layouts/portal.blade.php),
            // so "project phase" here is real data, not invented — just
            // surfaced again in this card for Intercom-style self-contained
            // context. "Last active" is derived from the most recent
            // team-authored message already loaded into $messages, not a new
            // query. "Active" (conversation status) and "Support Team" (role)
            // are honest static labels, not fabricated metrics — this app has
            // no per-admin identity or open/closed state on chat threads to
            // draw a more specific claim from.
            $chatPhaseLabels = [
                'onboarding' => 'Onboarding',
                'in_progress' => 'In Development',
                'review' => 'Under Review',
                'launched' => 'Launched',
                'maintenance' => 'Care Plan',
            ];
            $chatPhaseColors = [
                'onboarding' => 'bg-gold/10 text-gold-dark',
                'in_progress' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-300',
                'review' => 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-300',
                'launched' => 'bg-teal/10 text-teal-dark',
                'maintenance' => 'bg-teal/10 text-teal-dark',
            ];
            $chatPhaseLabel = $chatPhaseLabels[$project->status] ?? ucfirst($project->status);
            $chatPhaseColor = $chatPhaseColors[$project->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400';
            $lastTeamMessage = $messages->where('user_id', '!=', $project->user_id)->last();
        @endphp
        <div class="shrink-0 border-b border-gray-100 dark:border-gray-700/60">
            <div class="flex items-center gap-3.5 px-6 sm:px-8 pt-5 pb-3">
                <span class="relative w-14 h-14 rounded-full bg-navy flex items-center justify-center shrink-0 shadow-sm">
                    <span class="text-gold text-base font-bold tracking-tight">VB</span>
                    <span class="absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full bg-teal border-2 border-white dark:border-gray-800"></span>
                </span>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <p class="text-base font-semibold text-navy dark:text-white tracking-tight">VisionBridge Team</p>
                        <span class="inline-flex items-center gap-1 text-[0.65rem] font-semibold text-teal-dark dark:text-teal-light shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal"></span> Online
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Support Team</p>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1.5 text-[0.65rem] font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-teal/10 text-teal-dark">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal"></span> Active
                </span>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-1 px-6 sm:px-8 pb-3 text-xs text-gray-400 dark:text-gray-500">
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Usually replies within a few hours
                </span>
                <span>Last active {{ $lastTeamMessage?->created_at->diffForHumans() ?? 'not yet in this conversation' }}</span>
            </div>

            <div class="flex flex-wrap items-center gap-2 px-6 sm:px-8 pb-4">
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50 px-2.5 py-1 rounded-full border border-gray-100 dark:border-gray-700">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    <span class="truncate max-w-[10rem]">{{ $project->name }}</span>
                </span>
                <span class="inline-flex items-center text-[0.65rem] font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $chatPhaseColor }}">
                    {{ $chatPhaseLabel }}
                </span>
            </div>
        </div>

        {{-- Messages --}}
        @php
            // Whole-body-is-a-link detection — lets a plain pasted URL render as
            // an inline image or a file card without any attachment/upload
            // infrastructure behind it. Deliberately narrow (the entire
            // trimmed body must be nothing but an http(s) URL) so ordinary
            // sentences that merely contain a link are left as plain text.
            $chatMediaExt = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            $chatFileExt = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'csv', 'txt'];
            $chatLinkKind = function (?string $body) use ($chatMediaExt, $chatFileExt) {
                $trimmed = trim((string) $body);
                if ($trimmed === '' || preg_match('/\s/', $trimmed) || ! preg_match('#^https?://#i', $trimmed)) {
                    return null;
                }
                $ext = strtolower(pathinfo(parse_url($trimmed, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
                if (in_array($ext, $chatMediaExt, true)) return 'image';
                if (in_array($ext, $chatFileExt, true)) return 'file';
                return null;
            };
            // First team message the client hasn't opened Chat to see yet, as of
            // this page load (before the "mark read" call below fires) — draws
            // an iMessage-style "New" divider at the right spot using data that
            // already exists (read_at), not a fabricated indicator.
            $chatFirstUnreadId = $messages->first(function ($m) use ($project) {
                return $m->user_id !== $project->user_id && ! $m->read_at && ! $m->isDeleted();
            })?->id;
        @endphp
        <div id="chat-thread-messages" class="chat-thread-scroll flex-1 overflow-y-auto px-6 sm:px-8 py-7 bg-gray-50/70 dark:bg-gray-900/40">
            @forelse ($messages as $chatMessage)
                @php
                    $isOwn = $chatMessage->user_id === $project->user_id;
                    $isSystem = $chatMessage->user_id === null;
                    $prevMessage = $messages->get($loop->index - 1);
                    $isGrouped = $prevMessage && $prevMessage->user_id === $chatMessage->user_id;
                    $chatLink = $chatMessage->isDeleted() ? null : $chatLinkKind($chatMessage->body);
                @endphp

                @if ($chatMessage->id === $chatFirstUnreadId)
                    <div class="flex items-center gap-3 {{ $loop->first ? '' : 'mt-5' }} mb-2">
                        <div class="flex-1 h-px bg-gold/30"></div>
                        <span class="text-[0.6rem] font-semibold uppercase tracking-widest text-gold-dark">New</span>
                        <div class="flex-1 h-px bg-gold/30"></div>
                    </div>
                @endif

                @if ($isSystem)
                    {{-- Forward-compatible styling only — no code path in this app currently creates a message with a null user_id, so this never renders today. --}}
                    <div class="chat-bubble flex justify-center {{ $loop->first ? '' : 'mt-3' }}"
                         data-message-id="{{ $chatMessage->id }}" data-own="0" data-deleted="0" data-sender="system">
                        <span class="inline-flex items-center gap-1.5 text-[0.7rem] font-medium text-gray-400 dark:text-gray-500 bg-gray-100/80 dark:bg-gray-800/60 px-3.5 py-1.5 rounded-full">
                            {{ $chatMessage->body }}
                            <span class="chat-bubble-timestamp text-gray-300 dark:text-gray-600">· {{ $chatMessage->created_at->diffForHumans() }}</span>
                        </span>
                    </div>
                @else
                    <div class="chat-bubble group flex items-end {{ $isOwn ? 'justify-end' : '' }} gap-2.5 max-w-[75%] lg:max-w-[560px] {{ $isOwn ? 'ml-auto' : '' }} {{ $isGrouped ? 'mt-1' : ($loop->first ? '' : 'mt-5') }}"
                         data-message-id="{{ $chatMessage->id }}" data-own="{{ $isOwn ? '1' : '0' }}" data-deleted="{{ $chatMessage->isDeleted() ? '1' : '0' }}"
                         data-sender="{{ $isOwn ? 'own' : 'team' }}" data-raw-body="{{ $chatMessage->body }}">
                        @if (! $isOwn && ! $isGrouped)
                            <span class="w-9 h-9 rounded-full bg-navy text-gold text-xs font-bold flex items-center justify-center shrink-0 shadow-sm">VB</span>
                        @elseif (! $isOwn)
                            <span class="w-9 shrink-0"></span>
                        @endif
                        <div class="chat-bubble-card relative rounded-[1.375rem] {{ $isOwn ? 'rounded-br-md bg-gold' : 'rounded-bl-md bg-white dark:bg-gray-800/90 border border-gray-100 dark:border-gray-700/60' }} {{ $chatMessage->isDeleted() ? 'border border-dashed border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30 shadow-none' : 'shadow-sm chat-bubble-hoverable hover:shadow-md' }} px-5 py-3.5">
                            @if (! $isOwn && ! $isGrouped)
                                <p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold-dark mb-1.5">VisionBridge Team</p>
                            @endif

                            @if ($chatMessage->isDeleted())
                                <p class="chat-bubble-body flex items-center gap-1.5 text-sm italic text-gray-400 dark:text-gray-500">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/></svg>
                                    This message was deleted
                                </p>
                            @elseif ($chatLink === 'image')
                                <a href="{{ $chatMessage->body }}" target="_blank" rel="noopener noreferrer" class="chat-bubble-body block rounded-xl overflow-hidden mb-1">
                                    <img src="{{ $chatMessage->body }}" alt="Shared image" loading="lazy" class="max-w-full max-h-72 w-auto object-cover">
                                </a>
                            @elseif ($chatLink === 'file')
                                @php $chatFileName = basename(parse_url($chatMessage->body, PHP_URL_PATH) ?: '') ?: 'Shared file'; @endphp
                                <a href="{{ $chatMessage->body }}" target="_blank" rel="noopener noreferrer" class="chat-bubble-body flex items-center gap-3 rounded-xl {{ $isOwn ? 'bg-white/30 hover:bg-white/40' : 'bg-gray-50 dark:bg-gray-900/40 hover:bg-gray-100 dark:hover:bg-gray-900/70' }} px-3.5 py-3 transition-colors duration-200">
                                    <span class="w-9 h-9 rounded-lg {{ $isOwn ? 'bg-navy-dark/10 text-navy-dark' : 'bg-gold/15 text-gold-dark' }} flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block text-xs font-semibold truncate {{ $isOwn ? 'text-navy-dark' : 'text-gray-700 dark:text-gray-200' }}">{{ $chatFileName }}</span>
                                        <span class="block text-[0.6rem] uppercase tracking-wide {{ $isOwn ? 'text-navy-dark/50' : 'text-gray-400' }}">Open file</span>
                                    </span>
                                </a>
                            @else
                                <p class="chat-bubble-body text-sm leading-relaxed {{ $isOwn ? 'text-navy-dark font-medium' : 'text-gray-700 dark:text-gray-200' }} whitespace-pre-line">{{ $chatMessage->body }}</p>
                            @endif

                            <p class="chat-bubble-time flex items-center gap-1 text-[0.65rem] {{ $isOwn ? 'text-navy-dark/50 justify-end' : 'text-gray-400 dark:text-gray-500' }} mt-1.5">
                                <span class="chat-bubble-timestamp">{{ $chatMessage->created_at->diffForHumans() }}</span>
                                <span class="chat-bubble-edited {{ $chatMessage->isEdited() ? '' : 'hidden' }}"> · edited {{ $chatMessage->edited_at?->diffForHumans() }}</span>
                                @if ($isOwn && ! $chatMessage->isDeleted())
                                    <span class="chat-bubble-ticks inline-flex shrink-0">
                                        @if ($chatMessage->read_at)
                                            <svg class="w-3.5 h-3.5 text-teal-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><title>Read</title><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2 13l4 4L15 8M9 13l4 4L22 8"/></svg>
                                        @else
                                            <svg class="w-3.5 h-3.5 text-navy-dark/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><title>Sent</title><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </span>
                                @endif
                            </p>

                            @if (! $chatMessage->isDeleted())
                                <button type="button" class="chat-bubble-menu-btn absolute -top-2 {{ $isOwn ? '-left-2' : '-right-2' }} opacity-0 group-hover:opacity-100 focus:opacity-100 w-6 h-6 rounded-full bg-white dark:bg-gray-700 shadow border border-gray-100 dark:border-gray-600 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gold/40">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 100-4 2 2 0 000 4zM10 12a2 2 0 100-4 2 2 0 000 4zM10 18a2 2 0 100-4 2 2 0 000 4z"/></svg>
                                </button>
                                <div class="chat-bubble-menu hidden absolute z-20 {{ $isOwn ? 'left-0' : 'right-0' }} top-7 w-44 bg-white dark:bg-navy border border-gray-100 dark:border-gray-700 rounded-2xl shadow-lg py-1.5">
                                    @if ($isOwn)
                                        <button type="button" class="chat-action-edit w-full text-left px-3.5 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gold/10 hover:text-gold-dark transition-colors duration-200 rounded-lg mx-1 w-[calc(100%-0.5rem)]">Edit</button>
                                        <button type="button" class="chat-action-delete-everyone w-full text-left px-3.5 py-2 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors duration-200 rounded-lg mx-1 w-[calc(100%-0.5rem)]">Delete for everyone</button>
                                    @endif
                                    <button type="button" class="chat-action-delete-me w-full text-left px-3.5 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gold/10 hover:text-gold-dark transition-colors duration-200 rounded-lg mx-1 w-[calc(100%-0.5rem)]">Delete for me</button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @empty
                <div id="chat-thread-empty" class="h-full flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 rounded-full bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No messages yet — say hello, we're here to help.</p>
                </div>
            @endforelse
        </div>

        {{-- Typing indicator --}}
        <div id="chat-typing-indicator" class="hidden shrink-0 px-6 sm:px-8 pt-3 flex items-center gap-1.5 text-xs text-gray-400 dark:text-gray-500 italic">
            <span>VisionBridge Team is typing</span>
            <span class="inline-flex items-center gap-0.5">
                <span class="chat-typing-dot w-1 h-1 rounded-full bg-current"></span>
                <span class="chat-typing-dot w-1 h-1 rounded-full bg-current"></span>
                <span class="chat-typing-dot w-1 h-1 rounded-full bg-current"></span>
            </span>
        </div>

        {{-- Composer --}}
        <form id="chat-thread-form" data-mark-read-url="{{ route('portal.chat.read', $project) }}" data-typing-url="{{ route('portal.chat.typing', $project) }}" data-no-loading-overlay
              method="POST" action="{{ route('portal.chat.store', $project) }}"
              onsubmit="return submitPortalChatMessage(this, event)"
              class="shrink-0 flex items-center gap-3 px-6 sm:px-8 py-5 border-t border-gray-100 dark:border-gray-700/60 bg-white dark:bg-gray-800">
            @csrf
            <textarea name="body" rows="1" placeholder="Type a message…" required
                      class="flex-1 resize-none rounded-full border border-gray-200 dark:border-gray-600 px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold/40 focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500 shadow-sm transition-all duration-200"></textarea>
            <button type="button" id="chat-mic-btn" title="Voice input" class="hidden relative shrink-0 w-12 h-12 rounded-full border border-gray-200 dark:border-gray-600 text-gray-400 hover:text-gold-dark hover:border-gold flex items-center justify-center transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gold/40">
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
            <button type="submit" title="Send" class="shrink-0 w-12 h-12 rounded-full bg-gold hover:bg-gold-dark text-navy-dark flex items-center justify-center transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gold/40 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
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
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-7">
                <div class="w-11 h-11 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/></svg>
                </div>
                <h2 class="font-display text-lg font-bold text-navy dark:text-white mb-2">Delete this message?</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">It'll be removed for everyone in this conversation. This can't be undone.</p>
                <div class="flex justify-end gap-2.5">
                    <button type="button" id="chat-delete-cancel" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="button" id="chat-delete-confirm" class="px-4 py-2.5 rounded-xl text-sm font-semibold bg-red-500 hover:bg-red-600 text-white transition-colors duration-200">
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

    // Named distinctly from the layout's own top-level `const csrfToken` (layouts/portal.blade.php)
    // — classic <script> tags share one global scope, so a same-named function
    // declaration here previously collided with it and threw a page-wide
    // SyntaxError that silently broke every chat action on this page.
    function chatCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    }

    function chatMessageUrl(id) {
        return document.getElementById('chat-thread').dataset.messageBaseUrl + '/' + id;
    }

    /**
     * Whole-body-is-a-link detection, mirrored from the Blade template's
     * $chatLinkKind closure — kept in sync by hand since Blade (initial
     * render) and this script (live-appended/edited bubbles) are separate
     * languages with no shared source of truth.
     */
    function detectPortalChatLink(body) {
        const trimmed = (body || '').trim();
        if (!trimmed || /\s/.test(trimmed) || !/^https?:\/\//i.test(trimmed)) return null;

        let path = '';
        try { path = new URL(trimmed).pathname; } catch (e) { return null; }
        const ext = (path.split('.').pop() || '').toLowerCase();
        const mediaExt = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        const fileExt = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'csv', 'txt'];
        if (mediaExt.includes(ext)) return 'image';
        if (fileExt.includes(ext)) return 'file';
        return null;
    }

    /** Builds the actual .chat-bubble-body element for a message — plain text, or an image/file preview when the body is nothing but a link. */
    function buildPortalChatBodyEl(body, isOwn) {
        const kind = detectPortalChatLink(body);

        if (kind === 'image') {
            const a = document.createElement('a');
            a.href = body;
            a.target = '_blank';
            a.rel = 'noopener noreferrer';
            a.className = 'chat-bubble-body block rounded-xl overflow-hidden mb-1';
            const img = document.createElement('img');
            img.src = body;
            img.alt = 'Shared image';
            img.loading = 'lazy';
            img.className = 'max-w-full max-h-72 w-auto object-cover';
            a.appendChild(img);
            return a;
        }

        if (kind === 'file') {
            let fileName = 'Shared file';
            try { fileName = decodeURIComponent(new URL(body).pathname.split('/').pop()) || fileName; } catch (e) {}

            const a = document.createElement('a');
            a.href = body;
            a.target = '_blank';
            a.rel = 'noopener noreferrer';
            a.className = 'chat-bubble-body flex items-center gap-3 rounded-xl ' + (isOwn ? 'bg-white/30 hover:bg-white/40' : 'bg-gray-50 dark:bg-gray-900/40 hover:bg-gray-100 dark:hover:bg-gray-900/70') + ' px-3.5 py-3 transition-colors duration-200';
            a.innerHTML = '<span class="w-9 h-9 rounded-lg ' + (isOwn ? 'bg-navy-dark/10 text-navy-dark' : 'bg-gold/15 text-gold-dark') + ' flex items-center justify-center shrink-0">' +
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' +
                '</span><span class="min-w-0 flex-1"></span>';
            const nameWrap = a.querySelector('span.min-w-0');
            const nameEl = document.createElement('span');
            nameEl.className = 'block text-xs font-semibold truncate ' + (isOwn ? 'text-navy-dark' : 'text-gray-700 dark:text-gray-200');
            nameEl.textContent = fileName;
            const subEl = document.createElement('span');
            subEl.className = 'block text-[0.6rem] uppercase tracking-wide ' + (isOwn ? 'text-navy-dark/50' : 'text-gray-400');
            subEl.textContent = 'Open file';
            nameWrap.appendChild(nameEl);
            nameWrap.appendChild(subEl);
            return a;
        }

        const p = document.createElement('p');
        p.className = 'chat-bubble-body text-sm leading-relaxed ' + (isOwn ? 'text-navy-dark font-medium' : 'text-gray-700 dark:text-gray-200') + ' whitespace-pre-line';
        p.textContent = body;
        return p;
    }

    /** Swaps a bubble's body element for the right rendering (text vs. image/file) after a send or a live edit, and keeps data-raw-body in sync for the edit composer. */
    function updatePortalChatBubbleBody(bubble, body) {
        bubble.dataset.rawBody = body;
        const bodyEl = bubble.querySelector('.chat-bubble-body');
        if (!bodyEl) return;
        bodyEl.replaceWith(buildPortalChatBodyEl(body, bubble.dataset.own === '1'));
    }

    function buildPortalBubbleHtml(data, grouped) {
        const isOwn = !!data.isFromClient;
        const marginClass = grouped ? 'mt-1' : 'mt-5';
        let html = '<div class="chat-bubble chat-bubble-enter group flex items-end ' + (isOwn ? 'justify-end' : '') + ' gap-2.5 max-w-[75%] lg:max-w-[560px] ' + (isOwn ? 'ml-auto' : '') + ' ' + marginClass + '" data-message-id="' + data.id + '" data-own="' + (isOwn ? '1' : '0') + '" data-deleted="0">';
        if (!isOwn) {
            html += grouped
                ? '<span class="w-9 shrink-0"></span>'
                : '<span class="w-9 h-9 rounded-full bg-navy text-gold text-xs font-bold flex items-center justify-center shrink-0 shadow-sm">VB</span>';
        }
        html += '<div class="chat-bubble-card relative rounded-[1.375rem] ' + (isOwn ? 'rounded-br-md bg-gold' : 'rounded-bl-md bg-white dark:bg-gray-800/90 border border-gray-100 dark:border-gray-700/60') + ' shadow-sm chat-bubble-hoverable hover:shadow-md px-5 py-3.5">';
        if (!isOwn && !grouped) {
            html += '<p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold-dark mb-1.5">VisionBridge Team</p>';
        }
        html += '<p class="chat-bubble-body text-sm leading-relaxed ' + (isOwn ? 'text-navy-dark font-medium' : 'text-gray-700 dark:text-gray-200') + ' whitespace-pre-line"></p>';
        html += '<p class="chat-bubble-time flex items-center gap-1 text-[0.65rem] ' + (isOwn ? 'text-navy-dark/50 justify-end' : 'text-gray-400 dark:text-gray-500') + ' mt-1.5">' +
            '<span class="chat-bubble-timestamp"></span>' +
            '<span class="chat-bubble-edited hidden"></span>' +
            (isOwn ? '<span class="chat-bubble-ticks inline-flex shrink-0"><svg class="w-3.5 h-3.5 text-navy-dark/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><title>Sent</title><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></span>' : '') +
        '</p>';
        html += '<button type="button" class="chat-bubble-menu-btn absolute -top-2 ' + (isOwn ? '-left-2' : '-right-2') + ' opacity-0 group-hover:opacity-100 focus:opacity-100 w-6 h-6 rounded-full bg-white dark:bg-gray-700 shadow border border-gray-100 dark:border-gray-600 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gold/40">' +
            '<svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 100-4 2 2 0 000 4zM10 12a2 2 0 100-4 2 2 0 000 4zM10 18a2 2 0 100-4 2 2 0 000 4z"/></svg>' +
        '</button>';
        html += '<div class="chat-bubble-menu hidden absolute z-20 ' + (isOwn ? 'left-0' : 'right-0') + ' top-7 w-44 bg-white dark:bg-navy border border-gray-100 dark:border-gray-700 rounded-2xl shadow-lg py-1.5">';
        if (isOwn) {
            html += '<button type="button" class="chat-action-edit w-[calc(100%-0.5rem)] mx-1 text-left px-3.5 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gold/10 hover:text-gold-dark transition-colors duration-200 rounded-lg">Edit</button>';
            html += '<button type="button" class="chat-action-delete-everyone w-[calc(100%-0.5rem)] mx-1 text-left px-3.5 py-2 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors duration-200 rounded-lg">Delete for everyone</button>';
        }
        html += '<button type="button" class="chat-action-delete-me w-[calc(100%-0.5rem)] mx-1 text-left px-3.5 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gold/10 hover:text-gold-dark transition-colors duration-200 rounded-lg">Delete for me</button>';
        html += '</div></div></div>';
        return html;
    }

    function appendPortalChatBubble(data) {
        const container = document.getElementById('chat-thread-messages');
        if (!container) return;
        if (container.querySelector('[data-message-id="' + data.id + '"]')) return; // already rendered — avoid double-append (own send + Pusher echo)

        const empty = document.getElementById('chat-thread-empty');
        if (empty) empty.remove();

        const isOwn = !!data.isFromClient;
        const sender = isOwn ? 'own' : 'team';
        const prevBubble = container.lastElementChild && container.lastElementChild.classList.contains('chat-bubble') ? container.lastElementChild : null;
        const grouped = !!prevBubble && prevBubble.dataset.sender === sender;

        const wrapper = document.createElement('div');
        wrapper.innerHTML = buildPortalBubbleHtml(data, grouped);
        const bubble = wrapper.firstElementChild;
        bubble.dataset.sender = sender;
        bubble.dataset.rawBody = data.body;
        bubble.querySelector('.chat-bubble-body').replaceWith(buildPortalChatBodyEl(data.body, isOwn));
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
                'X-CSRF-TOKEN': chatCsrfToken(),
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

    /**
     * Typing indicator — sends at most one "I'm typing" ping every ~2s while
     * the textarea has input (not one per keystroke), and shows the
     * incoming indicator for a few seconds after the last ping received,
     * auto-expiring rather than waiting on an explicit "stopped typing"
     * signal that a closed tab or dropped connection would never send.
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
                headers: { 'X-CSRF-TOKEN': chatCsrfToken() },
            });
        });
    })();

    let portalTypingHideTimer = null;

    function showPortalTypingIndicator() {
        const indicator = document.getElementById('chat-typing-indicator');
        if (!indicator) return;

        indicator.classList.remove('hidden');
        clearTimeout(portalTypingHideTimer);
        portalTypingHideTimer = setTimeout(function () {
            indicator.classList.add('hidden');
        }, 3500);
    }

    /** Applies a live edit or delete pushed over Pusher — same DOM update the local action handlers use. */
    function applyPortalChatUpdate(data) {
        const bubble = document.querySelector('.chat-bubble[data-message-id="' + data.id + '"]');
        if (!bubble) return;

        updatePortalChatBubbleBody(bubble, data.body);
        const edited = bubble.querySelector('.chat-bubble-edited');
        if (edited) {
            edited.textContent = ' · edited ' + data.editedAt;
            edited.classList.remove('hidden');
        }
    }

    function applyPortalChatDeleted(data) {
        const bubble = document.querySelector('.chat-bubble[data-message-id="' + data.id + '"]');
        if (!bubble) return;

        const isOwn = bubble.dataset.own === '1';
        bubble.dataset.deleted = '1';

        const card = bubble.querySelector('.chat-bubble-card');
        if (card) {
            card.className = 'chat-bubble-card relative rounded-[1.375rem] ' + (isOwn ? 'rounded-br-md' : 'rounded-bl-md') + ' border border-dashed border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30 shadow-none px-5 py-3.5';
        }

        const body = bubble.querySelector('.chat-bubble-body');
        if (body) {
            body.outerHTML = '<p class="chat-bubble-body flex items-center gap-1.5 text-sm italic text-gray-400 dark:text-gray-500">' +
                '<svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/></svg>' +
                'This message was deleted</p>';
        }

        bubble.querySelector('.chat-bubble-ticks')?.remove();
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

        document.addEventListener('keydown', function (e) {
            const modal = document.getElementById('chat-delete-modal');
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) closeChatDeleteModal();
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
        const originalText = bubble.dataset.rawBody || '';
        const isOwn = bubble.dataset.own === '1';
        const card = bubble.querySelector('.chat-bubble-card');

        const form = document.createElement('div');
        form.className = 'chat-bubble-edit-form';
        form.innerHTML =
            '<textarea rows="4" class="w-full resize-y rounded-xl border-0 px-2.5 py-2 text-sm ' + (isOwn ? 'bg-white/60 text-navy-dark placeholder-navy-dark/50' : 'bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-200') + ' focus:outline-none focus:ring-2 focus:ring-gold/50 transition-all duration-200"></textarea>' +
            '<div class="flex items-center justify-end gap-2 mt-2">' +
                '<button type="button" class="chat-edit-cancel text-xs font-semibold ' + (isOwn ? 'text-navy-dark/70' : 'text-gray-500 dark:text-gray-400') + ' hover:underline transition-colors duration-200">Cancel</button>' +
                '<button type="button" class="chat-edit-save text-xs font-semibold ' + (isOwn ? 'text-navy-dark' : 'text-gold-dark') + ' hover:underline transition-colors duration-200">Save</button>' +
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
                    'X-CSRF-TOKEN': chatCsrfToken(),
                },
                body: new URLSearchParams({ body: newBody }),
            })
                .then(function (response) {
                    if (!response.ok) throw new Error('Request failed');
                    return response.json();
                })
                .then(function (data) {
                    updatePortalChatBubbleBody(bubble, data.body);
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
                'X-CSRF-TOKEN': chatCsrfToken(),
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
                'X-CSRF-TOKEN': chatCsrfToken(),
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
