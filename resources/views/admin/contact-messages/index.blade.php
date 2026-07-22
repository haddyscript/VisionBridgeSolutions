@extends('layouts.admin')

@section('title', 'Contact Messages – Admin')
@section('page-title', 'Contact Messages')

@section('content')

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
    <div class="space-y-4">
        @foreach ($messages as $message)
            <div class="bg-white dark:bg-navy rounded-xl border p-6 {{ $message->isRead() ? 'border-gray-200 dark:border-gray-700' : 'border-gold/40 shadow-sm' }}" style="{{ $message->isRead() ? '' : 'background:linear-gradient(to right, rgba(201,168,76,0.05), #ffffff 12%);' }}">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-3">
                    <div class="flex items-center gap-2.5">
                        @if (! $message->isRead())
                            <span class="w-2 h-2 rounded-full bg-gold shrink-0" title="Unread"></span>
                        @endif
                        <div>
                            <p class="font-semibold text-navy dark:text-white {{ $message->isRead() ? '' : 'font-bold' }}">
                                {{ $message->first_name }} {{ $message->last_name }}
                                @if (! $message->isRead())
                                    <span class="ml-1.5 text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gold/15 text-gold-dark">New</span>
                                @endif
                            </p>
                            <a href="mailto:{{ $message->email }}" class="text-sm text-gold-dark hover:underline">{{ $message->email }}</a>
                            @if ($message->organization)
                                <span class="text-sm text-gray-500 dark:text-gray-400"> &middot; {{ $message->organization }}</span>
                            @endif
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

                @if ($message->message)
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line border-t border-gray-100 dark:border-gray-700/60 pt-3 mb-3">{{ $message->message }}</p>
                @endif

                <form method="POST" action="{{ route('admin.contact-messages.toggle-read', $message) }}" class="flex justify-end">
                    @csrf
                    @method('PATCH')
                    @if ($message->isRead())
                        <button type="submit" class="text-xs font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 px-3 py-1.5 rounded-full transition-colors">
                            Mark as Unread
                        </button>
                    @else
                        <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gold-dark bg-gold/10 border border-gold/30 px-3 py-1.5 rounded-full hover:bg-gold/15 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            Mark as Read
                        </button>
                    @endif
                </form>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $messages->links() }}
    </div>
@endif

@endsection
