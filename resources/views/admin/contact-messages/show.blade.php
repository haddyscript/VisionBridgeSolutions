@extends('layouts.admin')

@php
    $displayName = $message->displayName();

    $labelColors = [
        'spam' => ['dot' => 'bg-red-400', 'text' => 'text-red-500'],
        'not_helpful' => ['dot' => 'bg-gray-400', 'text' => 'text-gray-500 dark:text-gray-400'],
        'follow_up' => ['dot' => 'bg-blue-400', 'text' => 'text-blue-500'],
    ];

    // Turn bare URLs in the message into real links — this inbox regularly
    // gets outreach/spam messages packed with Telegram/WhatsApp links, and
    // as plain text they're neither clickable nor easy to scan for.
    $linkPattern = '/((https?:\/\/|www\.)[^\s<]+)/i';
    $linkCount = $message->message ? preg_match_all($linkPattern, $message->message) : 0;
    $linkedMessage = $message->message
        ? preg_replace_callback($linkPattern, function ($matches) {
            $url = rtrim($matches[0], '.,)');
            $href = str_starts_with($url, 'http') ? $url : 'https://'.$url;
            return '<a href="'.e($href).'" target="_blank" rel="noopener noreferrer" class="text-gold-dark underline decoration-gold/40 hover:decoration-gold-dark break-all">'.e($url).'</a>';
        }, e($message->message))
        : null;
@endphp

@section('title', $displayName.' – Admin')
@section('page-title', $displayName)

@section('content')

<div class="flex items-center justify-between mb-6">
    <a href="{{ route('admin.contact-messages.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Contact Messages
    </a>

    <div class="flex items-center gap-2">
        <form method="POST" action="{{ route('admin.contact-messages.toggle-read', $message) }}">
            @csrf
            @method('PATCH')
            @if ($message->isRead())
                <button type="submit" class="text-xs font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 px-3 py-1.5 rounded-full transition-colors">
                    Mark as Unread
                </button>
            @else
                <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gold-dark bg-gold/10 border border-gold/30 px-3 py-1.5 rounded-full hover:bg-gold/15 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    Mark as Read
                </button>
            @endif
        </form>

        <form method="POST" action="{{ route('admin.contact-messages.destroy', $message) }}" onsubmit="return confirm('Delete this message? This cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-medium text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 px-3 py-1.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete
            </button>
        </form>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">

    {{-- Sidebar: contact + status --}}
    <div class="lg:col-span-1 space-y-6 order-1 lg:order-2">
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-12 h-12 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center font-display font-bold text-lg shrink-0">
                    {{ strtoupper(substr($displayName, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-navy dark:text-white truncate">{{ $displayName }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Contact Form Message</p>
                </div>
            </div>

            <div class="space-y-2 mb-5">
                <a href="mailto:{{ $message->email }}" class="flex items-center gap-2.5 text-sm text-gray-600 dark:text-gray-300 hover:text-gold-dark">
                    <svg class="w-4 h-4 shrink-0 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="truncate">{{ $message->email }}</span>
                </a>
                @if ($message->organization)
                    <p class="flex items-center gap-2.5 text-sm text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4 shrink-0 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                        <span class="truncate">{{ $message->organization }}</span>
                    </p>
                @endif
            </div>

            <a href="mailto:{{ $message->email }}" class="flex items-center justify-center gap-2 w-full text-sm font-semibold text-navy-dark bg-gold hover:bg-gold-dark px-4 py-2.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Reply via Email
            </a>
        </div>

        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6 text-sm">
            <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1.5">Label</label>
            <form method="POST" action="{{ route('admin.contact-messages.update-label', $message) }}">
                @csrf
                @method('PATCH')
                @include('admin._dropdown', [
                    'name' => 'label',
                    'domId' => 'message-label',
                    'options' => collect(\App\Models\ContactMessage::LABELS)->map(fn ($label, $value) => [
                        'value' => $value,
                        'label' => $label,
                        'dot' => $labelColors[$value]['dot'] ?? 'bg-gray-400',
                    ])->values()->all(),
                    'selected' => $message->label,
                    'placeholder' => 'No Label',
                    'autoSubmit' => true,
                ])
            </form>

            <div class="space-y-4 mt-5 pt-5 border-t border-gray-100 dark:border-gray-700/60">
                <div class="flex items-center justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Status</span>
                    <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $message->isRead() ? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' : 'bg-gold/15 text-gold-dark' }}">
                        {{ $message->isRead() ? 'Read' : 'New' }}
                    </span>
                </div>

                @if ($message->service)
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 block mb-1">Service</span>
                        <p class="text-navy dark:text-white font-medium leading-snug">{{ $message->service }}</p>
                    </div>
                @endif

                <div class="flex items-center justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Submitted</span>
                    <span class="text-navy dark:text-white font-medium" title="{{ $message->created_at->format('M j, Y \a\t g:ia') }}">{{ $message->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main content --}}
    <div class="lg:col-span-2 space-y-6 order-2 lg:order-1">
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-navy dark:text-white">Message</h3>
                @if ($linkCount > 0)
                    <span class="inline-flex items-center gap-1 text-[0.65rem] font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400">
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 010 5.656l-3 3a4 4 0 01-5.656-5.656l1.5-1.5M10.172 13.828a4 4 0 010-5.656l3-3a4 4 0 015.656 5.656l-1.5 1.5"/></svg>
                        {{ $linkCount }} {{ \Illuminate\Support\Str::plural('link', $linkCount) }}
                    </span>
                @endif
            </div>
            @if ($linkedMessage)
                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed">{!! $linkedMessage !!}</p>
            @else
                <p class="text-sm text-gray-400 dark:text-gray-500 italic">No message was included with this submission.</p>
            @endif
        </div>
    </div>
</div>

@endsection
