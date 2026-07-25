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
        .chat-bubble-card { transition: transform 200ms ease-out, box-shadow 200ms ease-out, background-color 200ms ease-out; }
        .chat-bubble-card.chat-bubble-hoverable:hover { transform: translateY(-1px); }
        #chat-typing-indicator { transition: opacity 200ms ease-out, transform 200ms ease-out; }
        #chat-scroll-to-bottom-btn { transition: opacity 200ms ease-out, transform 200ms ease-out, box-shadow 200ms ease-out; }
        {{-- Phase 9 note: previously this used rotate() and an overshooting
             cubic-bezier at 380ms — outside the "opacity/translateY/scale
             only", "ease-out", "150-250ms" rules this pass set, so it's
             tightened here rather than left as an exception. --}}
        @keyframes chatSendLaunch {
            0% { transform: scale(1); }
            40% { transform: scale(0.86); }
            100% { transform: scale(1); }
        }
        #chat-send-btn.chat-send-launch { animation: chatSendLaunch 220ms ease-out; }

        {{-- Shared entrance for every popover/dropdown/reaction-picker on
             this page — one keyframe, one duration, reused everywhere
             rather than a bespoke animation per widget. --}}
        @keyframes chatPopoverIn {
            from { opacity: 0; transform: translateY(6px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        #chat-emoji-picker:not(.hidden), #chat-templates-picker:not(.hidden), #chat-attachment-picker:not(.hidden),
        #chat-mention-picker:not(.hidden), .chat-bubble-menu:not(.hidden), .chat-reaction-picker:not(.hidden) {
            animation: chatPopoverIn 180ms ease-out;
        }
        .chat-reaction-pill { animation: chatPopoverIn 180ms ease-out; }

        {{-- Button press feedback — every button inside the thread or its
             modal gets a quick, subtle compress on press, distinct from
             (and layered on top of) whatever hover treatment it already
             has. Scale-only, no color/shadow change here, so it never
             fights with a button's own hover state. --}}
        #chat-thread button:active, #chat-delete-modal button:active {
            transform: scale(0.96);
            transition: transform 150ms ease-out;
        }
    </style>

    <div id="chat-thread" data-project-id="{{ $project->id }}" data-message-base-url="{{ url('/portal/chat-messages') }}"
         class="relative bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700/60 shadow-sm overflow-hidden flex flex-col h-[calc(100vh-180px)] min-h-[28rem] transition-colors duration-200">

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
            // Reusable date-separator label — mirrored by chatDateLabel() in the
            // script below for live-appended messages, same "Today"/"Yesterday"
            // rule on both sides.
            $chatDateLabel = function ($date) {
                if ($date->isToday()) return 'Today';
                if ($date->isYesterday()) return 'Yesterday';
                return $date->format('F j, Y');
            };
        @endphp
        {{-- Wraps the scroll area and its floating "scroll to newest" button
             together so the button positions relative to the message list's
             own bottom edge (right above the composer), not the whole card. --}}
        <div class="relative flex-1 min-h-0 flex flex-col">
        <div id="chat-thread-messages" class="chat-thread-scroll flex-1 overflow-y-auto px-6 sm:px-8 py-7 bg-gray-50/70 dark:bg-gray-900/40">
            @forelse ($messages as $chatMessage)
                @php
                    $isOwn = $chatMessage->user_id === $project->user_id;
                    $isSystem = $chatMessage->user_id === null;
                    $prevMessage = $messages->get($loop->index - 1);
                    $chatLink = $chatMessage->isDeleted() ? null : $chatLinkKind($chatMessage->body);
                    $chatShowDateSeparator = ! $prevMessage || ! $prevMessage->created_at->isSameDay($chatMessage->created_at);
                    // Never grouped across a date separator — matches the
                    // JS-append path, where a freshly-inserted date
                    // separator becomes the new "last element", naturally
                    // resetting grouping for whatever message follows it.
                    $isGrouped = $prevMessage && $prevMessage->user_id === $chatMessage->user_id && ! $chatShowDateSeparator;
                @endphp

                @if ($chatShowDateSeparator)
                    {{-- position:sticky (no JS) makes this float/pin at the top
                         of the scroll container while its day's messages are in
                         view, then get pushed out by the next day's separator —
                         the same trick WhatsApp/Telegram use for a "floating
                         date" header. --}}
                    <div class="chat-date-separator sticky top-0 z-10 -mx-6 sm:-mx-8 flex justify-center py-2 {{ $loop->first ? '' : 'mt-5' }} pointer-events-none" data-date="{{ $chatMessage->created_at->format('Y-m-d') }}">
                        <span class="pointer-events-auto inline-flex items-center text-[0.65rem] font-semibold uppercase tracking-wide px-3 py-1 rounded-full bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm text-gray-500 dark:text-gray-400 shadow-sm border border-gray-100 dark:border-gray-700">
                            {{ $chatDateLabel($chatMessage->created_at) }}
                        </span>
                    </div>
                @endif

                @if ($chatMessage->id === $chatFirstUnreadId)
                    <div id="chat-unread-divider" class="sticky top-9 z-[9] flex items-center gap-3 {{ $chatShowDateSeparator ? '' : ($loop->first ? '' : 'mt-5') }} mb-2 py-1 bg-gray-50/95 dark:bg-gray-900/80 backdrop-blur-sm transition-opacity duration-300">
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

                            {{-- Reaction pill — client-side/session-only,
                                 see the react button's own comment below. --}}
                            <div class="chat-reaction-display hidden mt-1 {{ $isOwn ? 'justify-end' : '' }}">
                                <span class="chat-reaction-pill inline-flex items-center gap-1 text-xs bg-gold/10 dark:bg-gold/15 text-gold-dark px-2 py-0.5 rounded-full border border-gold/20"></span>
                            </div>

                            @if (! $chatMessage->isDeleted())
                                {{-- Reactions here are deliberately not persisted or
                                     broadcast — there's no chat_message_reactions
                                     table or event (that's a real backend change,
                                     out of scope this pass). Picking one just shows
                                     a pill locally for this page view; it's gone on
                                     reload and no one else sees it. --}}
                                <button type="button" class="chat-bubble-react-btn absolute -top-2 {{ $isOwn ? '-left-9' : '-right-9' }} opacity-0 group-hover:opacity-100 focus:opacity-100 w-6 h-6 rounded-full bg-white dark:bg-gray-700 shadow border border-gray-100 dark:border-gray-600 flex items-center justify-center text-gray-400 hover:text-gold-dark transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gold/40" title="React">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                                <div class="chat-reaction-picker hidden absolute z-20 {{ $isOwn ? 'right-8' : 'left-8' }} -top-11 items-center gap-0.5 bg-white dark:bg-navy border border-gray-100 dark:border-gray-700 rounded-full shadow-lg px-1.5 py-1">
                                    @foreach (['👍', '❤️', '😂', '😮', '😢', '🙏'] as $chatReactionEmoji)
                                        <button type="button" class="chat-reaction-option w-7 h-7 rounded-full flex items-center justify-center text-base hover:bg-gold/10 hover:scale-110 transition-all duration-150">{{ $chatReactionEmoji }}</button>
                                    @endforeach
                                </div>

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

        {{-- Scroll-to-newest affordance — hidden while already near the
             bottom, shown once the client has scrolled up into history so
             they're never yanked back down against their will; badges a
             count when new messages arrive while scrolled away. --}}
        <div id="chat-scroll-to-bottom" class="hidden absolute bottom-4 right-6 sm:right-8 z-20">
            <button type="button" id="chat-scroll-to-bottom-btn" class="flex items-center gap-1.5 pl-3 pr-3.5 py-2 rounded-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-lg hover:shadow-xl text-xs font-semibold text-gray-600 dark:text-gray-300">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                <span id="chat-scroll-to-bottom-count" class="hidden inline-flex items-center justify-center min-w-[1.1rem] h-[1.1rem] px-1 rounded-full bg-gold text-navy-dark text-[0.6rem] font-bold leading-none"></span>
            </button>
        </div>
        </div>

        {{-- Typing indicator --}}
        <div id="chat-typing-indicator" class="hidden opacity-0 -translate-y-1 shrink-0 px-6 sm:px-8 pt-3 flex items-center gap-1.5 text-xs text-gray-400 dark:text-gray-500 italic">
            <span>VisionBridge Team is typing</span>
            <span class="inline-flex items-center gap-0.5">
                <span class="chat-typing-dot w-1 h-1 rounded-full bg-current"></span>
                <span class="chat-typing-dot w-1 h-1 rounded-full bg-current"></span>
                <span class="chat-typing-dot w-1 h-1 rounded-full bg-current"></span>
            </span>
        </div>

        {{-- Composer — Phase 6: redesigned as a Notion AI / Intercom-style
             self-contained card (auto-expanding textarea on top, a tool
             row underneath) instead of a single pill + two circular
             buttons. No backend changes: the emoji/template/mention
             affordances only ever insert plain text into the same
             textarea that already posts to Portal\ChatController::store();
             "attachment" has no real upload behind it (out of scope, same
             as the original chat build) — it inserts a link that Phase 3's
             existing image/file link-preview rendering then picks up. --}}
        @php
            $chatEmojiSet = ['👍','👎','🙏','👏','🙌','💪','🤝','🎉','😀','😄','😁','😊','🙂','😉','😍','😘','😎','🤔','😅','😂','🤣','😢','😭','😮','😲','😴','🙄','👀','💡','🔥','✨','💯','✅','❌','⚠️','❤️','💙','💛','💚','🧡','📌','📎','📅','⏰','💬','📞','🚀','🙋'];
            $chatQuickReplies = [
                'Hi! Just checking in on the status of our project.',
                'Thanks so much — this looks great!',
                'Could you clarify the next steps?',
                "We'd like to schedule a call to discuss this further.",
                "Sorry for the delay — I'll get back to you shortly.",
            ];
        @endphp
        <div class="shrink-0 px-4 sm:px-6 py-4 border-t border-gray-100 dark:border-gray-700/60 bg-white dark:bg-gray-800">
            <form id="chat-thread-form" data-mark-read-url="{{ route('portal.chat.read', $project) }}" data-typing-url="{{ route('portal.chat.typing', $project) }}" data-no-loading-overlay
                  method="POST" action="{{ route('portal.chat.store', $project) }}"
                  onsubmit="return submitPortalChatMessage(this, event)"
                  class="chat-composer relative rounded-[1.75rem] border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 shadow-sm transition-all duration-200 focus-within:border-gold focus-within:ring-4 focus-within:ring-gold/10 focus-within:bg-white dark:focus-within:bg-gray-800">
                @csrf

                {{-- Emoji picker --}}
                <div id="chat-emoji-picker" class="hidden absolute bottom-full left-2 mb-2 w-72 max-h-56 overflow-y-auto bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xl p-2.5 z-30">
                    <div class="grid grid-cols-8 gap-0.5">
                        @foreach ($chatEmojiSet as $chatEmoji)
                            <button type="button" class="chat-emoji-option w-7 h-7 rounded-lg flex items-center justify-center text-lg hover:bg-gold/10 transition-colors duration-150">{{ $chatEmoji }}</button>
                        @endforeach
                    </div>
                </div>

                {{-- Quick-reply templates --}}
                <div id="chat-templates-picker" class="hidden absolute bottom-full left-2 mb-2 w-80 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xl p-1.5 z-30">
                    @foreach ($chatQuickReplies as $chatQuickReply)
                        <button type="button" class="chat-template-option block w-full text-left px-3 py-2 text-xs text-gray-600 dark:text-gray-300 hover:bg-gold/10 hover:text-gold-dark rounded-xl transition-colors duration-150">{{ $chatQuickReply }}</button>
                    @endforeach
                </div>

                {{-- Attachment — no file-upload backend exists for chat, so
                     this inserts a link rather than pretending to upload a
                     file; Phase 3's existing whole-body-is-a-link rendering
                     turns it into an inline image/file preview once sent. --}}
                <div id="chat-attachment-picker" class="hidden absolute bottom-full left-2 mb-2 w-80 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xl p-4 z-30">
                    <p class="text-xs font-semibold text-navy dark:text-white mb-2">Share a link</p>
                    <div class="flex items-center gap-2">
                        <input type="url" id="chat-attachment-url" placeholder="Paste an image or file link…" class="flex-1 min-w-0 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-gold/40 focus:border-gold">
                        <button type="button" id="chat-attachment-insert" class="shrink-0 px-3 py-2 rounded-lg bg-gold hover:bg-gold-dark text-navy-dark text-xs font-semibold transition-colors duration-200">Insert</button>
                    </div>
                    <p class="text-[0.65rem] text-gray-400 dark:text-gray-500 mt-2 leading-relaxed">File uploads aren't available yet — paste a link and we'll preview it in the conversation.</p>
                </div>

                {{-- Mentions — this conversation only ever has one
                     addressable party from the client's side (there's no
                     per-admin identity anywhere else in chat either, see
                     Phase 2/3), so this is a plain-text insertion helper,
                     not a real @-tagging/notification system. --}}
                <div id="chat-mention-picker" class="hidden absolute bottom-full left-2 mb-2 w-56 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xl py-1.5 z-30">
                    <button type="button" class="chat-mention-option flex items-center gap-2 w-full text-left px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gold/10 hover:text-gold-dark rounded-xl transition-colors duration-150">
                        <span class="w-5 h-5 rounded-full bg-navy text-gold text-[0.55rem] font-bold flex items-center justify-center shrink-0">VB</span>
                        VisionBridge Team
                    </button>
                </div>

                <textarea name="body" id="chat-composer-textarea" rows="1" maxlength="5000" placeholder="Message VisionBridge Team…" required
                          class="w-full resize-none bg-transparent px-5 pt-4 pb-1.5 text-sm text-navy-dark dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none transition-all duration-150"
                          style="max-height: 9.5rem;"></textarea>

                <div class="flex items-end justify-between gap-2 px-2.5 sm:px-3.5 pb-2.5">
                    <div class="flex items-center gap-0.5">
                        <button type="button" id="chat-emoji-btn" title="Emoji" class="w-9 h-9 rounded-full flex items-center justify-center text-gray-400 hover:text-gold-dark hover:bg-gold/10 hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gold/30">
                            <svg class="w-[1.125rem] h-[1.125rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>
                        <button type="button" id="chat-attachment-btn" title="Share a link" class="w-9 h-9 rounded-full flex items-center justify-center text-gray-400 hover:text-gold-dark hover:bg-gold/10 hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gold/30">
                            <svg class="w-[1.125rem] h-[1.125rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        </button>
                        <button type="button" id="chat-templates-btn" title="Quick replies" class="w-9 h-9 rounded-full flex items-center justify-center text-gray-400 hover:text-gold-dark hover:bg-gold/10 hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gold/30">
                            <svg class="w-[1.125rem] h-[1.125rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </button>
                        <button type="button" id="chat-mic-btn" title="Voice input" class="hidden relative w-9 h-9 rounded-full text-gray-400 hover:text-gold-dark hover:bg-gold/10 hover:scale-105 flex items-center justify-center transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gold/30">
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
                    </div>

                    <div class="flex items-center gap-3 pb-1">
                        <span id="chat-char-counter" class="text-[0.65rem] font-medium text-gray-300 dark:text-gray-600 tabular-nums select-none transition-colors duration-200">0/5000</span>
                        <button type="submit" id="chat-send-btn" title="Send" class="shrink-0 w-10 h-10 rounded-full bg-gold hover:bg-gold-dark text-navy-dark flex items-center justify-center transition-all duration-200 shadow-sm hover:shadow-md hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gold/40 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
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
     * Scroll intelligence for the thread: never yanks the client back to the
     * bottom while they're reading up through history, but auto-follows
     * (smoothly, not an instant jump) when they're already at/near the
     * bottom and a new message arrives. Exposes window.chatScrollToNewMessage
     * so appendPortalChatBubble (below) can decide per-message rather than
     * always forcing scrollTop like it used to.
     */
    (function () {
        const container = document.getElementById('chat-thread-messages');
        const jumpWrap = document.getElementById('chat-scroll-to-bottom');
        const jumpBtn = document.getElementById('chat-scroll-to-bottom-btn');
        const jumpCount = document.getElementById('chat-scroll-to-bottom-count');
        if (!container || !jumpWrap || !jumpBtn) return;

        const NEAR_BOTTOM_THRESHOLD = 120;
        let unseenCount = 0;

        function isNearBottom() {
            return container.scrollHeight - container.scrollTop - container.clientHeight < NEAR_BOTTOM_THRESHOLD;
        }

        function clearUnseen() {
            unseenCount = 0;
            jumpCount.classList.add('hidden');
            jumpCount.textContent = '';
        }

        function refreshJumpAffordance() {
            const nearBottom = isNearBottom();
            jumpWrap.classList.toggle('hidden', nearBottom);
            if (nearBottom) {
                clearUnseen();
                // The unread marker only matters until the client has
                // actually scrolled down through it — once they're caught
                // up to the bottom it just fades away rather than sitting
                // there stale for the rest of the page view.
                const unreadDivider = document.getElementById('chat-unread-divider');
                if (unreadDivider) {
                    unreadDivider.classList.add('opacity-0');
                    setTimeout(function () { unreadDivider.remove(); }, 300);
                }
            }
        }

        container.addEventListener('scroll', refreshJumpAffordance);
        refreshJumpAffordance();

        jumpBtn.addEventListener('click', function () {
            container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
        });

        window.chatScrollToNewMessage = function (isOwnMessage) {
            if (isOwnMessage || isNearBottom()) {
                container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
                refreshJumpAffordance();
                return;
            }
            unseenCount++;
            jumpCount.textContent = unseenCount > 9 ? '9+' : String(unseenCount);
            jumpCount.classList.remove('hidden');
            jumpWrap.classList.remove('hidden');
        };
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

    /**
     * Phase 6 composer — auto-expand, character counter, Enter-to-send /
     * Shift+Enter-newline, and the emoji/attachment/templates/mention
     * popovers. All plain client-side text insertion into the same
     * textarea the existing send flow already posts — nothing here talks
     * to a new endpoint.
     */
    (function () {
        const textarea = document.getElementById('chat-composer-textarea');
        const counter = document.getElementById('chat-char-counter');
        const form = document.getElementById('chat-thread-form');
        if (!textarea || !form) return;

        const MAX_LENGTH = 5000;
        const MAX_TEXTAREA_HEIGHT = 152; // px — matches the inline max-height set on the textarea

        function autoExpand() {
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, MAX_TEXTAREA_HEIGHT) + 'px';
        }

        function updateCounter() {
            const len = textarea.value.length;
            if (!counter) return;
            counter.textContent = len + '/' + MAX_LENGTH;
            counter.classList.toggle('text-red-500', len >= MAX_LENGTH);
            counter.classList.toggle('dark:text-red-400', len >= MAX_LENGTH);
            counter.classList.toggle('text-amber-500', len >= MAX_LENGTH * 0.9 && len < MAX_LENGTH);
            counter.classList.toggle('text-gray-300', len < MAX_LENGTH * 0.9);
            counter.classList.toggle('dark:text-gray-600', len < MAX_LENGTH * 0.9);
        }

        window.chatResetComposerTextarea = function () {
            textarea.style.height = 'auto';
            updateCounter();
        };

        textarea.addEventListener('input', function () {
            autoExpand();
            updateCounter();
            maybeShowMentionPicker();
        });
        autoExpand();
        updateCounter();

        // Enter sends, Shift+Enter inserts a newline — textareas don't
        // submit on Enter natively, so without this the only way to send
        // was ever clicking the button.
        textarea.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey && !e.isComposing) {
                e.preventDefault();
                if (!closeChatComposerPopovers()) {
                    form.requestSubmit ? form.requestSubmit() : submitPortalChatMessage(form, { preventDefault: function () {} });
                }
                return;
            }
            if (e.key === 'Escape') {
                closeChatComposerPopovers();
            }
        });

        // --- Popovers (emoji / templates / attachment / mention) ---
        const popoverIds = ['chat-emoji-picker', 'chat-templates-picker', 'chat-attachment-picker', 'chat-mention-picker'];

        function closeChatComposerPopovers() {
            let wasOpen = false;
            popoverIds.forEach(function (id) {
                const el = document.getElementById(id);
                if (el && !el.classList.contains('hidden')) {
                    el.classList.add('hidden');
                    wasOpen = true;
                }
            });
            return wasOpen;
        }

        function toggleChatComposerPopover(id) {
            const target = document.getElementById(id);
            if (!target) return;
            const alreadyOpen = !target.classList.contains('hidden');
            closeChatComposerPopovers();
            if (!alreadyOpen) target.classList.remove('hidden');
        }

        function insertAtCursor(text) {
            const start = textarea.selectionStart ?? textarea.value.length;
            const end = textarea.selectionEnd ?? textarea.value.length;
            textarea.value = textarea.value.slice(0, start) + text + textarea.value.slice(end);
            const cursor = start + text.length;
            textarea.setSelectionRange(cursor, cursor);
            textarea.focus();
            autoExpand();
            updateCounter();
        }

        document.getElementById('chat-emoji-btn')?.addEventListener('click', function () {
            toggleChatComposerPopover('chat-emoji-picker');
        });
        document.getElementById('chat-templates-btn')?.addEventListener('click', function () {
            toggleChatComposerPopover('chat-templates-picker');
        });
        document.getElementById('chat-attachment-btn')?.addEventListener('click', function () {
            toggleChatComposerPopover('chat-attachment-picker');
            document.getElementById('chat-attachment-url')?.focus();
        });

        document.getElementById('chat-emoji-picker')?.addEventListener('click', function (e) {
            const btn = e.target.closest('.chat-emoji-option');
            if (!btn) return;
            insertAtCursor(btn.textContent);
        });

        document.getElementById('chat-templates-picker')?.addEventListener('click', function (e) {
            const btn = e.target.closest('.chat-template-option');
            if (!btn) return;
            textarea.value = btn.textContent.trim();
            textarea.setSelectionRange(textarea.value.length, textarea.value.length);
            textarea.focus();
            autoExpand();
            updateCounter();
            closeChatComposerPopovers();
        });

        document.getElementById('chat-attachment-insert')?.addEventListener('click', function () {
            const urlInput = document.getElementById('chat-attachment-url');
            const url = urlInput?.value.trim();
            if (!url) return;
            insertAtCursor((textarea.value && !textarea.value.endsWith(' ') && !textarea.value.endsWith('\n') ? ' ' : '') + url);
            urlInput.value = '';
            closeChatComposerPopovers();
        });

        document.getElementById('chat-mention-picker')?.addEventListener('click', function (e) {
            const btn = e.target.closest('.chat-mention-option');
            if (!btn) return;
            // Replaces the trailing "@" that triggered the picker with the mention text.
            textarea.value = textarea.value.replace(/@$/, '@VisionBridge Team ');
            textarea.setSelectionRange(textarea.value.length, textarea.value.length);
            textarea.focus();
            autoExpand();
            updateCounter();
            closeChatComposerPopovers();
        });

        /** Typing "@" at the start of the message or right after whitespace opens the mention picker — plain-text insertion only, see the markup comment above. */
        function maybeShowMentionPicker() {
            const value = textarea.value;
            const triggered = /(^|\s)@$/.test(value);
            const picker = document.getElementById('chat-mention-picker');
            if (!picker) return;
            if (triggered) {
                closeChatComposerPopovers();
                picker.classList.remove('hidden');
            } else if (!picker.classList.contains('hidden')) {
                picker.classList.add('hidden');
            }
        }

        document.addEventListener('click', function (e) {
            if (e.target.closest('#chat-emoji-btn, #chat-templates-btn, #chat-attachment-btn, #chat-emoji-picker, #chat-templates-picker, #chat-attachment-picker, #chat-mention-picker, #chat-composer-textarea')) {
                return;
            }
            closeChatComposerPopovers();
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
            (isOwn ? '<span class="chat-bubble-ticks inline-flex shrink-0"></span>' : '') +
        '</p>';
        html += '<div class="chat-reaction-display hidden mt-1 ' + (isOwn ? 'justify-end' : '') + '"><span class="chat-reaction-pill inline-flex items-center gap-1 text-xs bg-gold/10 dark:bg-gold/15 text-gold-dark px-2 py-0.5 rounded-full border border-gold/20"></span></div>';
        html += '<button type="button" class="chat-bubble-react-btn absolute -top-2 ' + (isOwn ? '-left-9' : '-right-9') + ' opacity-0 group-hover:opacity-100 focus:opacity-100 w-6 h-6 rounded-full bg-white dark:bg-gray-700 shadow border border-gray-100 dark:border-gray-600 flex items-center justify-center text-gray-400 hover:text-gold-dark transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gold/40" title="React">' +
            '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' +
        '</button>';
        html += '<div class="chat-reaction-picker hidden absolute z-20 ' + (isOwn ? 'right-8' : 'left-8') + ' -top-11 items-center gap-0.5 bg-white dark:bg-navy border border-gray-100 dark:border-gray-700 rounded-full shadow-lg px-1.5 py-1">';
        ['👍', '❤️', '😂', '😮', '😢', '🙏'].forEach(function (emoji) {
            html += '<button type="button" class="chat-reaction-option w-7 h-7 rounded-full flex items-center justify-center text-base hover:bg-gold/10 hover:scale-110 transition-all duration-150">' + emoji + '</button>';
        });
        html += '</div>';
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

    /**
     * "Today"/"Yesterday"/full-date label, mirrored from the Blade
     * template's $chatDateLabel closure — same rule on both sides, kept in
     * sync by hand since Blade and this script share no runtime.
     */
    function chatDateLabel(date) {
        const now = new Date();
        const startOfDay = function (d) { return new Date(d.getFullYear(), d.getMonth(), d.getDate()).getTime(); };
        const diffDays = Math.round((startOfDay(now) - startOfDay(date)) / 86400000);
        if (diffDays === 0) return 'Today';
        if (diffDays === 1) return 'Yesterday';
        return date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
    }

    function buildDateSeparatorHtml(date) {
        const iso = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + String(date.getDate()).padStart(2, '0');
        return '<div class="chat-date-separator sticky top-0 z-10 -mx-6 sm:-mx-8 flex justify-center py-2 mt-5 pointer-events-none" data-date="' + iso + '">' +
            '<span class="pointer-events-auto inline-flex items-center text-[0.65rem] font-semibold uppercase tracking-wide px-3 py-1 rounded-full bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm text-gray-500 dark:text-gray-400 shadow-sm border border-gray-100 dark:border-gray-700">' +
                chatDateLabel(date) +
            '</span>' +
        '</div>';
    }

    /** Inserts a new floating date separator if the last one on the page isn't for today — covers a tab left open across midnight. */
    function insertDateSeparatorIfNeeded(container) {
        const separators = container.querySelectorAll('.chat-date-separator');
        const last = separators.length ? separators[separators.length - 1] : null;
        const now = new Date();
        const todayIso = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
        if (last && last.dataset.date === todayIso) return;

        const wrapper = document.createElement('div');
        wrapper.innerHTML = buildDateSeparatorHtml(now);
        container.appendChild(wrapper.firstElementChild);
    }

    /** Single source of truth for an outgoing bubble's tick/status icon — sending (optimistic), sent, read, or failed-with-retry. */
    function setChatTickState(bubble, state) {
        const ticks = bubble.querySelector('.chat-bubble-ticks');
        const menuBtn = bubble.querySelector('.chat-bubble-menu-btn');
        if (!ticks) return;

        // A still-sending or failed message has no real message id yet (or
        // never got one) — editing/deleting it would PATCH/DELETE a
        // nonsense URL, so the menu stays hidden until it's actually sent.
        menuBtn?.classList.toggle('hidden', state === 'sending' || state === 'failed');

        if (state === 'sending') {
            ticks.innerHTML = '<svg class="w-3.5 h-3.5 text-navy-dark/30 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><title>Sending…</title><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        } else if (state === 'read') {
            ticks.innerHTML = '<svg class="w-3.5 h-3.5 text-teal-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><title>Read</title><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2 13l4 4L15 8M9 13l4 4L22 8"/></svg>';
        } else if (state === 'failed') {
            ticks.innerHTML = '<button type="button" class="chat-retry-send text-red-500 text-[0.65rem] font-semibold underline">Retry</button>';
        } else {
            ticks.innerHTML = '<svg class="w-3.5 h-3.5 text-navy-dark/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><title>Sent</title><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
        }
    }

    function appendPortalChatBubble(data) {
        const container = document.getElementById('chat-thread-messages');
        if (!container) return;
        if (container.querySelector('[data-message-id="' + data.id + '"]')) return; // already rendered — avoid double-append (own send + Pusher echo)

        // A Pusher echo of a message THIS tab just sent optimistically can
        // arrive before the fetch response that renames the pending bubble
        // from its temporary "pending-…" id to the real one — the check
        // above alone wouldn't catch that race, since the pending bubble
        // doesn't have the real id yet. Reconcile by raw body text instead
        // of rendering what would look like a second, separate copy of the
        // same message (it only ever affects "sending…" bubbles, so a
        // second *different* message with identical text sent moments
        // apart is never mistaken for this one).
        if (data.isFromClient) {
            const pending = Array.from(container.querySelectorAll('.chat-bubble[data-own="1"]')).find(function (el) {
                return el.dataset.messageId && el.dataset.messageId.indexOf('pending-') === 0 && el.dataset.rawBody === data.body;
            });
            if (pending) {
                resolvePendingChatBubble(pending.dataset.messageId, data);
                return;
            }
        }

        const empty = document.getElementById('chat-thread-empty');
        if (empty) empty.remove();

        insertDateSeparatorIfNeeded(container);

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
        if (isOwn) setChatTickState(bubble, data.status || 'sent');

        container.appendChild(bubble);

        if (typeof window.chatScrollToNewMessage === 'function') {
            window.chatScrollToNewMessage(isOwn);
        } else {
            container.scrollTop = container.scrollHeight;
        }
    }

    /**
     * Sends a message optimistically: the bubble appears immediately in a
     * "Sending…" state (clock icon, no menu) rather than waiting on the
     * network round-trip, then gets upgraded in place to "Sent" on success
     * or "Failed — Retry" on failure. This app's broadcasting is
     * synchronous (ShouldBroadcastNow, no queue worker — see
     * ChatController::broadcastSafely()) so the round-trip is normally
     * fast, but a slow/dropped connection previously gave zero feedback
     * until either an alert() fired or the message silently appeared.
     */
    function sendChatMessage(bodyText) {
        const form = document.getElementById('chat-thread-form');
        if (!form || !bodyText.trim()) return;

        const tempId = 'pending-' + Date.now() + '-' + Math.random().toString(36).slice(2);
        appendPortalChatBubble({ id: tempId, body: bodyText, sentAt: 'Sending…', isFromClient: true, status: 'sending' });

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': chatCsrfToken(),
            },
            body: new URLSearchParams({ body: bodyText }),
        })
            .then(function (response) {
                if (!response.ok) throw new Error('Request failed');
                return response.json();
            })
            .then(function (data) {
                resolvePendingChatBubble(tempId, data);
            })
            .catch(function () {
                failPendingChatBubble(tempId);
            });
    }

    function resolvePendingChatBubble(tempId, data) {
        const bubble = document.querySelector('.chat-bubble[data-message-id="' + tempId + '"]');
        if (!bubble) return;

        bubble.dataset.messageId = data.id;
        const timestamp = bubble.querySelector('.chat-bubble-timestamp');
        if (timestamp) timestamp.textContent = data.sentAt;
        setChatTickState(bubble, 'sent');
    }

    function failPendingChatBubble(tempId) {
        const bubble = document.querySelector('.chat-bubble[data-message-id="' + tempId + '"]');
        if (!bubble) return;

        const timestamp = bubble.querySelector('.chat-bubble-timestamp');
        if (timestamp) timestamp.textContent = 'Not delivered';
        setChatTickState(bubble, 'failed');
    }

    /** Re-sends a failed message's original text as a brand-new send attempt, replacing the failed bubble. */
    function retrySendChatMessage(bubble) {
        const bodyText = bubble.dataset.rawBody || '';
        if (!bodyText) return;
        bubble.remove();
        sendChatMessage(bodyText);
    }

    function submitPortalChatMessage(form, event) {
        event.preventDefault();

        const textarea = form.querySelector('textarea[name="body"]');
        const bodyText = textarea.value.trim();
        if (!bodyText) return false;

        const sendBtn = document.getElementById('chat-send-btn');
        if (sendBtn) {
            sendBtn.classList.remove('chat-send-launch');
            // Reflow before re-adding the class so the animation replays on every send, not just the first.
            void sendBtn.offsetWidth;
            sendBtn.classList.add('chat-send-launch');
        }

        textarea.value = '';
        if (typeof window.chatResetComposerTextarea === 'function') window.chatResetComposerTextarea();
        sendChatMessage(bodyText);

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

        clearTimeout(portalTypingHideTimer);
        if (indicator.classList.contains('hidden')) {
            indicator.classList.remove('hidden');
            // Force the "hidden" state to apply before removing it a frame
            // later, so the browser actually animates the fade+rise instead
            // of skipping straight to the end state.
            requestAnimationFrame(function () {
                indicator.classList.remove('opacity-0', '-translate-y-1');
            });
        }
        portalTypingHideTimer = setTimeout(hidePortalTypingIndicator, 3500);
    }

    function hidePortalTypingIndicator() {
        const indicator = document.getElementById('chat-typing-indicator');
        if (!indicator) return;

        indicator.classList.add('opacity-0', '-translate-y-1');
        setTimeout(function () { indicator.classList.add('hidden'); }, 200);
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
        bubble.querySelector('.chat-bubble-react-btn')?.remove();
        bubble.querySelector('.chat-reaction-picker')?.remove();
        bubble.querySelector('.chat-reaction-display')?.remove();
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

        // A reaction picker's root needs to actually be a flex row (to lay
        // its emoji buttons out side by side), unlike a plain dropdown menu
        // — so unlike .chat-bubble-menu, closing/opening it also has to
        // toggle a 'flex' class, not just 'hidden'. Mirrors the exact
        // hidden/flex pairing this file already uses for #chat-delete-modal.
        function closeAllChatBubblePopovers() {
            container.querySelectorAll('.chat-bubble-menu').forEach(function (m) { m.classList.add('hidden'); });
            container.querySelectorAll('.chat-reaction-picker').forEach(function (m) {
                m.classList.add('hidden');
                m.classList.remove('flex');
            });
        }

        // Menu buttons/dropdowns are built per-bubble (server-rendered and
        // JS-appended alike), so delegation on the scroll container catches
        // every one without needing to re-bind after each append.
        container.addEventListener('click', function (e) {
            const retryBtn = e.target.closest('.chat-retry-send');
            if (retryBtn) {
                retrySendChatMessage(retryBtn.closest('.chat-bubble'));
                return;
            }

            const menuBtn = e.target.closest('.chat-bubble-menu-btn');
            if (menuBtn) {
                const menu = menuBtn.nextElementSibling;
                const alreadyOpen = !menu.classList.contains('hidden');
                closeAllChatBubblePopovers();
                if (!alreadyOpen) menu.classList.remove('hidden');
                return;
            }

            const reactBtn = e.target.closest('.chat-bubble-react-btn');
            if (reactBtn) {
                const picker = reactBtn.nextElementSibling;
                const alreadyOpen = !picker.classList.contains('hidden');
                closeAllChatBubblePopovers();
                if (!alreadyOpen) {
                    picker.classList.remove('hidden');
                    picker.classList.add('flex');
                }
                return;
            }

            const reactionOption = e.target.closest('.chat-reaction-option');
            if (reactionOption) {
                const picker = reactionOption.closest('.chat-reaction-picker');
                picker.classList.add('hidden');
                picker.classList.remove('flex');
                setChatBubbleReaction(reactionOption.closest('.chat-bubble'), reactionOption.textContent.trim());
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

            if (!e.target.closest('.chat-bubble-menu') && !e.target.closest('.chat-reaction-picker')) {
                closeAllChatBubblePopovers();
            }
        });
    })();

    /**
     * Sets (or, clicking the same emoji again, clears) this bubble's
     * reaction pill. Deliberately client-side/session-only — see the
     * react button's markup comment for why nothing here is persisted or
     * broadcast to the other side of the conversation.
     */
    function setChatBubbleReaction(bubble, emoji) {
        const display = bubble.querySelector('.chat-reaction-display');
        const pill = bubble.querySelector('.chat-reaction-pill');
        if (!display || !pill) return;

        if (bubble.dataset.reaction === emoji) {
            bubble.dataset.reaction = '';
            pill.textContent = '';
            display.classList.add('hidden');
            display.classList.remove('flex');
            return;
        }

        bubble.dataset.reaction = emoji;
        pill.textContent = emoji;
        display.classList.remove('hidden');
        display.classList.add('flex');
    }

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
