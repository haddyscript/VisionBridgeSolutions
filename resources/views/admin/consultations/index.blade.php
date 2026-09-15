@extends('layouts.admin')

@section('title', 'Consultations – Admin')
@section('page-title', 'Consultations')

@section('content')

@php
    $statusLabels = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'confirmed' => 'Confirmed',
        'rescheduled' => 'Reschedule',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'follow_up' => 'Follow-Up',
        'proceed' => 'Proceed',
        'no_show' => 'No Show',
        'cancelled' => 'Cancelled',
        'declined' => 'Declined',
    ];
    $statusColors = [
        'new' => 'bg-gold/15 text-gold-dark',
        'contacted' => 'bg-amber-100 dark:bg-amber-500/15 text-amber-700 dark:text-amber-400',
        'confirmed' => 'bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-400',
        'rescheduled' => 'bg-teal/15 text-teal-dark',
        'in_progress' => 'bg-indigo-100 dark:bg-indigo-500/15 text-indigo-700 dark:text-indigo-400',
        'completed' => 'bg-green-100 dark:bg-green-500/15 text-green-700 dark:text-green-400',
        'follow_up' => 'bg-cyan-100 dark:bg-cyan-500/15 text-cyan-700 dark:text-cyan-400',
        'proceed' => 'bg-blue-100 dark:bg-blue-500/15 text-blue-700 dark:text-blue-400',
        'no_show' => 'bg-orange-100 dark:bg-orange-500/15 text-orange-700 dark:text-orange-400',
        'cancelled' => 'bg-red-100 dark:bg-red-500/15 text-red-600 dark:text-red-400',
        'declined' => 'bg-pink-100 dark:bg-pink-500/15 text-pink-700 dark:text-pink-400',
    ];
@endphp

<form method="GET" class="flex items-center justify-end gap-2.5 mb-5">
    <label class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Sort by</label>
    <div class="relative">
        <select name="sort" onchange="this.form.submit()"
                class="appearance-none rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-navy shadow-sm pl-3 pr-9 py-2 text-sm font-semibold text-navy dark:text-white focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold cursor-pointer hover:border-gold/50 transition-colors">
            @foreach (\App\Http\Controllers\Admin\ConsultationController::SORTS as $value => $label)
                <option value="{{ $value }}" {{ $sort === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
</form>

@if ($consultations->isEmpty())
    <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No consultation bookings yet.</p>
    </div>
@else
    <div class="space-y-2.5">
        @foreach ($consultations as $consultation)
            <div onclick="window.location='{{ route('admin.consultations.show', $consultation) }}'"
                 class="{{ $consultation->isRead() ? 'bg-white dark:bg-navy' : 'bg-[linear-gradient(to_right,rgba(201,168,76,0.08),#ffffff_12%)] dark:bg-[linear-gradient(to_right,rgba(201,168,76,0.14),#1B2A4A_12%)]' }} rounded-xl border p-3.5 {{ $consultation->isRead() ? 'border-gray-200 dark:border-gray-700' : 'border-gold/40 shadow-sm' }} cursor-pointer hover:shadow-md hover:-translate-y-0.5 hover:border-gold/40 transition-all duration-200">
                <div class="flex flex-wrap items-start justify-between gap-3 mb-1.5">
                    <div class="flex items-start gap-2.5">
                        @if (! $consultation->isRead())
                            <span class="w-2 h-2 rounded-full bg-gold shrink-0 mt-1.5" title="Unread"></span>
                        @endif
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('admin.consultations.show', $consultation) }}" class="font-semibold text-navy dark:text-white hover:text-gold-dark {{ $consultation->isRead() ? '' : 'font-bold' }}">
                                    {{ $consultation->name }}
                                </a>
                                <span class="inline-flex items-center text-[0.65rem] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $statusColors[$consultation->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusLabels[$consultation->status] ?? $consultation->status }}
                                </span>
                            </div>
                            {{-- Contact metadata — tight, icon-led --}}
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-0.5">
                                <a href="mailto:{{ $consultation->email }}" onclick="event.stopPropagation()" class="inline-flex items-center gap-1.5 text-sm text-gold-dark hover:underline">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    {{ $consultation->email }}
                                </a>
                                @if ($consultation->phone)
                                    <span class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.517l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        @if ($consultation->countryFlag()) {{ $consultation->countryFlag() }} @endif {{ $consultation->phone }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        @if ($consultation->preferred_at)
                            <p class="flex items-center justify-end gap-1.5 text-sm font-bold text-navy dark:text-white">
                                <svg class="w-4 h-4 shrink-0 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $consultation->preferred_at->format('M j, Y \a\t g:ia') }}
                            </p>
                        @endif
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Submitted {{ $consultation->created_at->format('M j, Y \a\t g:ia') }}</p>
                    </div>
                </div>

                {{-- Message toggle + read/unread action now share one row
                     (previously stacked as two separate rows) to cut an
                     extra row of height off every card. --}}
                <div class="flex items-center gap-3 mt-1.5 {{ $consultation->message ? 'pt-1.5 border-t border-gray-100 dark:border-gray-700/60' : '' }}">
                    @if ($consultation->message)
                        <button type="button" onclick="event.stopPropagation(); toggleConsultationMessage(this)" data-target="consultation-message-{{ $consultation->id }}"
                                class="consultation-message-toggle flex items-center gap-1.5 text-xs font-semibold text-navy dark:text-white hover:text-gold-dark text-left">
                            <svg class="w-3.5 h-3.5 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <span class="toggle-label">Show message</span>
                        </button>
                    @endif

                    <form method="POST" action="{{ route('admin.consultations.toggle-read', $consultation) }}" onclick="event.stopPropagation()" class="ml-auto">
                        @csrf
                        @method('PATCH')
                        @if ($consultation->isRead())
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

                {{-- Message accordion — collapsed by default, smooth height reveal --}}
                @if ($consultation->message)
                    <div id="consultation-message-{{ $consultation->id }}" class="overflow-hidden transition-all duration-300 ease-in-out" style="max-height: 0px;">
                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line pt-2 pb-1">{{ $consultation->message }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $consultations->links() }}
    </div>
@endif

<script>
    function toggleConsultationMessage(btn) {
        const panel = document.getElementById(btn.dataset.target);
        if (!panel) return;

        const isOpen = panel.style.maxHeight && panel.style.maxHeight !== '0px';
        panel.style.maxHeight = isOpen ? '0px' : panel.scrollHeight + 'px';
        btn.querySelector('svg').classList.toggle('rotate-90', !isOpen);
        btn.querySelector('.toggle-label').textContent = isOpen ? 'Show message' : 'Hide message';
    }
</script>

@endsection
