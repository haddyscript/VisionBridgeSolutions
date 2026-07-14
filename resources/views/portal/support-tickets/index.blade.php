@extends('layouts.portal')

@section('title', 'Support – Client Portal')
@section('page-title', 'Support')

@section('content')

@php
    $statusColors = [
        'open' => 'bg-gold/15 text-gold-dark',
        'in_progress' => 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-300',
        'resolved' => 'bg-teal/10 text-teal-dark',
    ];
@endphp

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    For anything that isn't a website revision or content update — billing questions, account help, or anything else — open a ticket here and our team will get back to you.
</p>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-4">New Ticket</h3>
    <form method="POST" action="{{ route('portal.support-tickets.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Subject</label>
            <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="255"
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
            @error('subject') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Message</label>
            <textarea name="message" rows="4" required maxlength="5000" placeholder="Tell us what's going on..."
                      class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">{{ old('message') }}</textarea>
            @error('message') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
            Submit Ticket
        </button>
    </form>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
    @forelse ($tickets as $ticket)
        <a href="{{ route('portal.support-tickets.show', $ticket) }}" class="flex items-center justify-between gap-4 px-6 py-4 hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition-colors">
            <div class="min-w-0">
                <p class="text-sm font-semibold text-navy dark:text-white truncate">{{ $ticket->subject }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Opened {{ $ticket->created_at->format('M j, Y') }}</p>
            </div>
            <span class="shrink-0 text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-500' }}">
                {{ \App\Models\SupportTicket::STATUSES[$ticket->status] ?? $ticket->status }}
            </span>
        </a>
    @empty
        <p class="text-sm text-gray-400 dark:text-gray-500 px-6 py-8 text-center">No support tickets yet.</p>
    @endforelse
</div>

@endsection
