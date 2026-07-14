@extends('layouts.portal')

@section('title', 'Support Ticket – Client Portal')
@section('page-title', 'Support Ticket')

@section('content')

@php
    $statusColors = [
        'open' => 'bg-gold/15 text-gold-dark',
        'in_progress' => 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-300',
        'resolved' => 'bg-teal/10 text-teal-dark',
    ];
@endphp

<a href="{{ route('portal.support-tickets.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white mb-5">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Back to Support
</a>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <div class="flex items-start justify-between gap-4 mb-1">
        <h3 class="font-display text-lg font-bold text-navy dark:text-white">{{ $ticket->subject }}</h3>
        <span class="shrink-0 text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-500' }}">
            {{ \App\Models\SupportTicket::STATUSES[$ticket->status] ?? $ticket->status }}
        </span>
    </div>
    <p class="text-xs text-gray-400 dark:text-gray-500">Opened {{ $ticket->created_at->format('M j, Y \a\t g:ia') }}</p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4">
    {{-- Original message --}}
    <div class="flex justify-end">
        <div class="max-w-[85%] bg-gold/10 rounded-2xl rounded-tr-sm px-4 py-3">
            <p class="text-sm text-navy dark:text-white whitespace-pre-line">{{ $ticket->message }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">{{ $ticket->created_at->format('M j, g:ia') }}</p>
        </div>
    </div>

    @foreach ($ticket->replies as $reply)
        @php $fromClient = $reply->user_id === $ticket->user_id; @endphp
        <div class="flex {{ $fromClient ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-[85%] {{ $fromClient ? 'bg-gold/10 rounded-tr-sm' : 'bg-gray-100 dark:bg-gray-700 rounded-tl-sm' }} rounded-2xl px-4 py-3">
                @unless ($fromClient)
                    <p class="text-xs font-semibold text-navy dark:text-white mb-1">{{ $reply->user->name }} · VisionBridge</p>
                @endunless
                <p class="text-sm text-navy dark:text-white whitespace-pre-line">{{ $reply->body }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">{{ $reply->created_at->format('M j, g:ia') }}</p>
            </div>
        </div>
    @endforeach

    <form method="POST" action="{{ route('portal.support-tickets.reply', $ticket) }}" class="pt-2 border-t border-gray-100 dark:border-gray-700">
        @csrf
        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 mt-4">Reply</label>
        <textarea name="body" rows="3" required maxlength="5000" placeholder="Type your reply..."
                  class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"></textarea>
        @error('body') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        <button type="submit" class="mt-3 bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
            Send Reply
        </button>
    </form>
</div>

@endsection
