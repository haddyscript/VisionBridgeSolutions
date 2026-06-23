@extends('layouts.admin')

@section('title', $consultation->name.' – Admin')
@section('page-title', $consultation->name)

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

<a href="{{ route('admin.consultations.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-navy mb-6">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Consultations
</a>

<div class="grid lg:grid-cols-3 gap-6">

    {{-- Sidebar: contact + status --}}
    <div class="lg:col-span-1 space-y-6 order-1 lg:order-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-12 h-12 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center font-display font-bold text-lg shrink-0">
                    {{ strtoupper(substr($consultation->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-navy dark:text-white truncate">{{ $consultation->name }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 truncate">Consultation Request</p>
                </div>
            </div>

            <div class="space-y-2 mb-5">
                <a href="mailto:{{ $consultation->email }}" class="flex items-center gap-2.5 text-sm text-gray-600 dark:text-gray-300 hover:text-gold-dark">
                    <svg class="w-4 h-4 shrink-0 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="truncate">{{ $consultation->email }}</span>
                </a>
                @if ($consultation->phone)
                    <a href="tel:{{ $consultation->phone }}" class="flex items-center gap-2.5 text-sm text-gray-600 dark:text-gray-300 hover:text-gold-dark">
                        <svg class="w-4 h-4 shrink-0 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.517l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>{{ $consultation->phone }}</span>
                    </a>
                @endif
            </div>

            <form method="POST" action="{{ route('admin.consultations.update', $consultation) }}" class="pt-5 border-t border-gray-100 dark:border-gray-700/60 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Status</label>
                    <select name="status"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                        @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}" {{ $consultation->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Preferred Date/Time</label>
                    <input type="datetime-local" name="preferred_at"
                           value="{{ old('preferred_at', $consultation->preferred_at?->format('Y-m-d\TH:i')) }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Admin Notes</label>
                    <textarea name="admin_notes" rows="4" placeholder="Internal notes about this booking..."
                              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">{{ old('admin_notes', $consultation->admin_notes) }}</textarea>
                </div>

                <button type="submit" class="w-full bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all">
                    Save Changes
                </button>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-gray-400 dark:text-gray-500">Status</span>
                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$consultation->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }}">
                    {{ $statusLabels[$consultation->status] ?? $consultation->status }}
                </span>
            </div>
            @if ($consultation->preferred_at)
                <div class="flex items-center justify-between">
                    <span class="text-gray-400 dark:text-gray-500">Requested Time</span>
                    <span class="text-navy dark:text-white font-medium">{{ $consultation->preferred_at->format('M j, Y \a\t g:ia') }}</span>
                </div>
            @endif
            <div class="flex items-center justify-between">
                <span class="text-gray-400 dark:text-gray-500">Submitted</span>
                <span class="text-navy dark:text-white font-medium" title="{{ $consultation->created_at->format('M j, Y \a\t g:ia') }}">{{ $consultation->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>

    {{-- Main content --}}
    <div class="lg:col-span-2 space-y-6 order-2 lg:order-1">
        @if ($consultation->message)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-navy dark:text-white mb-1.5">Message</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $consultation->message }}</p>
            </div>
        @endif
    </div>
</div>

@endsection
