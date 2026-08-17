@extends('layouts.admin')

@section('title', 'Contact Messages – Admin')
@section('page-title', 'Contact Messages')

@section('content')

@php
    $labelColors = [
        'spam' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
        'not_helpful' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
        'follow_up' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-500',
    ];
@endphp

<form method="GET" class="flex items-center justify-end gap-2.5 mb-5">
    <label class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Sort by</label>
    <div class="relative">
        <select name="sort" onchange="this.form.submit()"
                class="appearance-none rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-navy shadow-sm pl-3 pr-9 py-2 text-sm font-semibold text-navy dark:text-white focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold cursor-pointer hover:border-gold/50 transition-colors">
            @foreach (\App\Http\Controllers\Admin\ContactMessageController::SORTS as $value => $label)
                <option value="{{ $value }}" {{ $sort === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
</form>

@if ($messages->isEmpty())
    <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No messages from the "Get in Touch" form yet.</p>
    </div>
@else
    <div class="space-y-2.5">
        @foreach ($messages as $message)
            <div onclick="window.location='{{ route('admin.contact-messages.show', $message) }}'"
                 class="{{ $message->isRead() ? 'bg-white dark:bg-navy' : 'bg-[linear-gradient(to_right,rgba(201,168,76,0.05),#ffffff_12%)] dark:bg-[linear-gradient(to_right,rgba(201,168,76,0.12),#1B2A4A_12%)]' }} rounded-xl border p-3.5 {{ $message->isRead() ? 'border-gray-200 dark:border-gray-700' : 'border-gold/40 shadow-sm' }} cursor-pointer hover:shadow-md hover:-translate-y-0.5 hover:border-gold/40 transition-all duration-200">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="flex items-start gap-2.5">
                        @if (! $message->isRead())
                            <span class="w-2 h-2 rounded-full bg-gold shrink-0 mt-1.5" title="Unread"></span>
                        @endif
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('admin.contact-messages.show', $message) }}" class="font-semibold text-navy dark:text-white hover:text-gold-dark {{ $message->isRead() ? '' : 'font-bold' }}">
                                    {{ $message->first_name }} {{ $message->last_name }}
                                </a>
                                @if (! $message->isRead())
                                    <span class="text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gold/15 text-gold-dark">New</span>
                                @endif
                                @if ($message->label)
                                    <span class="text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $labelColors[$message->label] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                                        {{ \App\Models\ContactMessage::LABELS[$message->label] ?? $message->label }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-0.5">
                                <a href="mailto:{{ $message->email }}" onclick="event.stopPropagation()" class="text-sm text-gold-dark hover:underline">{{ $message->email }}</a>
                                @if ($message->organization)
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $message->organization }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        @if ($message->service)
                            <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-gold/15 text-gold-dark mb-1">
                                {{ $message->service }}
                            </span>
                        @endif
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->format('M j, Y \a\t g:ia') }}</p>
                    </div>
                </div>

                <div class="flex justify-end mt-2 pt-2 border-t border-gray-100 dark:border-gray-700/60">
                    <form method="POST" action="{{ route('admin.contact-messages.toggle-read', $message) }}" onclick="event.stopPropagation()">
                        @csrf
                        @method('PATCH')
                        @if ($message->isRead())
                            <button type="submit" class="text-xs font-semibold text-gray-500 dark:text-gray-400 bg-white dark:bg-navy border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-500 px-3 py-1 rounded-full transition-colors">
                                Mark as Unread
                            </button>
                        @else
                            <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white bg-white dark:bg-navy border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-500 px-3 py-1 rounded-full transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                Mark as Read
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $messages->links() }}
    </div>
@endif

@endsection
