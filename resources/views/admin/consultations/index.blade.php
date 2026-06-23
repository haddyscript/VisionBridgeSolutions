@extends('layouts.admin')

@section('title', 'Consultations – Admin')
@section('page-title', 'Consultations')

@section('content')

@php
    $statusLabels = [
        'new' => 'New',
        'confirmed' => 'Confirmed',
        'rescheduled' => 'Rescheduled',
        'cancelled' => 'Cancelled',
    ];
    $statusColors = [
        'new' => 'bg-gold/15 text-gold-dark',
        'confirmed' => 'bg-emerald-100 text-emerald-700',
        'rescheduled' => 'bg-teal/15 text-teal-dark',
        'cancelled' => 'bg-red-100 text-red-600',
    ];
@endphp

<form method="GET" class="flex items-center justify-end gap-2.5 mb-5">
    <label class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Sort by</label>
    <div class="relative">
        <select name="sort" onchange="this.form.submit()"
                class="appearance-none rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm pl-3 pr-9 py-2 text-sm font-semibold text-navy dark:text-white focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold cursor-pointer hover:border-gold/50 transition-colors">
            @foreach (\App\Http\Controllers\Admin\ConsultationController::SORTS as $value => $label)
                <option value="{{ $value }}" {{ $sort === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
</form>

@if ($consultations->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No consultation bookings yet.</p>
    </div>
@else
    <div class="space-y-4">
        @foreach ($consultations as $consultation)
            <div class="bg-white dark:bg-gray-800 rounded-xl border p-6 {{ $consultation->isRead() ? 'border-gray-200 dark:border-gray-700' : 'border-gold/40 shadow-sm' }}" style="{{ $consultation->isRead() ? '' : 'background:linear-gradient(to right, rgba(201,168,76,0.05), #ffffff 12%);' }}">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-3">
                    <div class="flex items-center gap-2.5">
                        @if (! $consultation->isRead())
                            <span class="w-2 h-2 rounded-full bg-gold shrink-0" title="Unread"></span>
                        @endif
                        <div>
                            <a href="{{ route('admin.consultations.show', $consultation) }}" class="font-semibold text-navy dark:text-white hover:text-gold-dark {{ $consultation->isRead() ? '' : 'font-bold' }}">
                                {{ $consultation->name }}
                                @if (! $consultation->isRead())
                                    <span class="ml-1.5 text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gold/15 text-gold-dark">New</span>
                                @endif
                            </a>
                            <br>
                            <a href="mailto:{{ $consultation->email }}" class="text-sm text-gold-dark hover:underline">{{ $consultation->email }}</a>
                            @if ($consultation->phone)
                                <span class="text-sm text-gray-400 dark:text-gray-500"> &middot; {{ $consultation->phone }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$consultation->status] ?? 'bg-gray-100 text-gray-600' }} mb-1">
                            {{ $statusLabels[$consultation->status] ?? $consultation->status }}
                        </span>
                        @if ($consultation->preferred_at)
                            <p class="text-xs text-navy dark:text-white font-medium">{{ $consultation->preferred_at->format('M j, Y \a\t g:ia') }}</p>
                        @endif
                        <p class="text-xs text-gray-400 dark:text-gray-500">Submitted {{ $consultation->created_at->format('M j, Y \a\t g:ia') }}</p>
                    </div>
                </div>

                @if ($consultation->message)
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line border-t border-gray-100 dark:border-gray-700/60 pt-3 mb-3">{{ $consultation->message }}</p>
                @endif

                <form method="POST" action="{{ route('admin.consultations.toggle-read', $consultation) }}" class="flex justify-end">
                    @csrf
                    @method('PATCH')
                    @if ($consultation->isRead())
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
@endif

@endsection
