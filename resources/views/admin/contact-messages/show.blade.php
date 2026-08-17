@extends('layouts.admin')

@section('title', $message->first_name.' '.$message->last_name.' – Admin')
@section('page-title', $message->first_name.' '.$message->last_name)

@section('content')

<div class="flex items-center justify-between mb-6">
    <a href="{{ route('admin.contact-messages.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white">
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

    {{-- Sidebar: contact details --}}
    <div class="lg:col-span-1 space-y-6 order-1 lg:order-2">
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-12 h-12 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center font-display font-bold text-lg shrink-0">
                    {{ strtoupper(substr($message->first_name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-navy dark:text-white truncate">{{ $message->first_name }} {{ $message->last_name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Contact Form Message</p>
                </div>
            </div>

            <div class="space-y-2">
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
        </div>

        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-gray-500 dark:text-gray-400">Status</span>
                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $message->isRead() ? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' : 'bg-gold/15 text-gold-dark' }}">
                    {{ $message->isRead() ? 'Read' : 'New' }}
                </span>
            </div>
            <div class="flex items-center justify-between gap-3">
                <span class="text-gray-500 dark:text-gray-400 shrink-0">Label</span>
                <form method="POST" action="{{ route('admin.contact-messages.update-label', $message) }}">
                    @csrf
                    @method('PATCH')
                    <select name="label" onchange="this.form.submit()"
                            class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-navy-dark px-2.5 py-1 text-xs font-semibold text-navy dark:text-white focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold cursor-pointer">
                        <option value="" {{ $message->label ? '' : 'selected' }}>No Label</option>
                        @foreach (\App\Models\ContactMessage::LABELS as $value => $label)
                            <option value="{{ $value }}" {{ $message->label === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            @if ($message->service)
                <div class="flex items-center justify-between gap-3">
                    <span class="text-gray-500 dark:text-gray-400 shrink-0">Service</span>
                    <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-gold/15 text-gold-dark text-right">
                        {{ $message->service }}
                    </span>
                </div>
            @endif
            <div class="flex items-center justify-between">
                <span class="text-gray-500 dark:text-gray-400">Submitted</span>
                <span class="text-navy dark:text-white font-medium" title="{{ $message->created_at->format('M j, Y \a\t g:ia') }}">{{ $message->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>

    {{-- Main content --}}
    <div class="lg:col-span-2 space-y-6 order-2 lg:order-1">
        @if ($message->message)
            <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-navy dark:text-white mb-1.5">Message</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $message->message }}</p>
            </div>
        @endif
    </div>
</div>

@endsection
