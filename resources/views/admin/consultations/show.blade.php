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

<div class="flex items-center justify-between mb-6">
    <a href="{{ route('admin.consultations.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-navy">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Consultations
    </a>

    <form method="POST" action="{{ route('admin.consultations.destroy', $consultation) }}" onsubmit="return confirm('Delete this consultation request? This cannot be undone.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-medium text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 px-3 py-1.5 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete
        </button>
    </form>
</div>

<div class="grid lg:grid-cols-3 gap-6">

    {{-- Sidebar: contact + status --}}
    <div class="lg:col-span-1 space-y-6 order-1 lg:order-2">
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-12 h-12 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center font-display font-bold text-lg shrink-0">
                    {{ strtoupper(substr($consultation->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-navy dark:text-white truncate">{{ $consultation->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Consultation Request</p>
                </div>
            </div>

            <div class="space-y-2 mb-5">
                <a href="mailto:{{ $consultation->email }}" class="flex items-center gap-2.5 text-sm text-gray-600 dark:text-gray-300 hover:text-gold-dark">
                    <svg class="w-4 h-4 shrink-0 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="truncate">{{ $consultation->email }}</span>
                </a>
                @if ($consultation->phone)
                    <a href="tel:{{ $consultation->phone }}" class="flex items-center gap-2.5 text-sm text-gray-600 dark:text-gray-300 hover:text-gold-dark">
                        <svg class="w-4 h-4 shrink-0 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.517l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>
                            @if ($consultation->countryFlag()) {{ $consultation->countryFlag() }} @endif
                            {{ $consultation->phone }}
                            @if ($consultation->country)
                                <span class="text-gray-500 dark:text-gray-400">({{ $consultation->country }})</span>
                            @endif
                        </span>
                    </a>
                @endif
            </div>

            @php
                $statusDotColors = [
                    'new' => '#C9A84C',
                    'confirmed' => '#10B981',
                    'rescheduled' => '#2A9D8F',
                    'cancelled' => '#EF4444',
                ];
            @endphp

            <form method="POST" action="{{ route('admin.consultations.update', $consultation) }}" id="consultation-update-form" class="pt-5 border-t border-gray-100 dark:border-gray-700/60 space-y-5">
                @csrf
                @method('PATCH')
                <input type="hidden" name="preferred_at" id="preferred_at_hidden">

                {{-- Status — Google Calendar style colored dot + select --}}
                <div class="flex items-center gap-3">
                    <span id="status-dot" class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $statusDotColors[$consultation->status] ?? '#9CA3AF' }};"></span>
                    <select name="status" id="status-select"
                            class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white"
                            data-colors='@json($statusDotColors)'>
                        @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}" {{ $consultation->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Date & time — clock icon row --}}
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 mt-2 shrink-0 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="flex-1 grid grid-cols-2 gap-2">
                        <input type="date" id="preferred_date"
                               value="{{ old('preferred_date', $consultation->preferred_at?->format('Y-m-d')) }}"
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                        <input type="time" id="preferred_time"
                               value="{{ old('preferred_time', $consultation->preferred_at?->format('H:i')) }}"
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                    </div>
                </div>

                {{-- Meeting link — video camera icon row, like Calendar's "Add conferencing" --}}
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 mt-2 shrink-0 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <div class="flex-1">
                        <input type="url" name="meeting_link" placeholder="Add Zoom or Google Meet link"
                               value="{{ old('meeting_link', $consultation->meeting_link) }}"
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500">
                        @if ($consultation->meeting_link)
                            <a href="{{ $consultation->meeting_link }}" target="_blank" class="inline-flex items-center gap-1.5 mt-1.5 text-xs font-semibold text-teal-dark hover:underline">
                                Join meeting
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Notes — description icon row --}}
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 mt-2 shrink-0 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    <textarea name="admin_notes" rows="4" placeholder="Internal notes about this booking..."
                              class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500">{{ old('admin_notes', $consultation->admin_notes) }}</textarea>
                </div>

                <button type="submit" class="w-full bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all">
                    Save Changes
                </button>
            </form>

            <script>
            (function () {
                const dot = document.getElementById('status-dot');
                const select = document.getElementById('status-select');
                const colors = JSON.parse(select.dataset.colors);

                select.addEventListener('change', () => {
                    dot.style.backgroundColor = colors[select.value] || '#9CA3AF';
                });

                document.getElementById('consultation-update-form').addEventListener('submit', (e) => {
                    const date = document.getElementById('preferred_date').value;
                    const time = document.getElementById('preferred_time').value;
                    document.getElementById('preferred_at_hidden').value = (date && time) ? (date + 'T' + time) : '';
                });
            })();
            </script>

            @php
                $notifyLabels = [
                    'confirmed' => 'Send Confirmation Email',
                    'rescheduled' => 'Send Reschedule Notice',
                    'cancelled' => 'Send Cancellation Email',
                ];
                $notifyReady = match ($consultation->status) {
                    'confirmed' => (bool) $consultation->meeting_link,
                    'rescheduled' => (bool) $consultation->preferred_at,
                    'cancelled' => true,
                    default => false,
                };
                $notifyHint = match (true) {
                    $consultation->status === 'new' => 'Set status to Confirmed, Rescheduled, or Cancelled to notify the client.',
                    $consultation->status === 'confirmed' && ! $notifyReady => 'Add and save a meeting link first.',
                    $consultation->status === 'rescheduled' && ! $notifyReady => 'Set and save the new date/time first.',
                    default => null,
                };
            @endphp

            <form method="POST" action="{{ route('admin.consultations.notify', $consultation) }}" class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700/60">
                @csrf
                <button type="submit"
                        @if (! $notifyReady) disabled @endif
                        class="w-full inline-flex items-center justify-center gap-2 text-sm font-semibold px-5 py-2.5 rounded-lg transition-all {{ $notifyReady ? 'bg-teal hover:bg-teal-dark text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-400 cursor-not-allowed' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $notifyLabels[$consultation->status] ?? 'Notify Client' }}
                </button>
                @if ($notifyHint)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">{{ $notifyHint }}</p>
                @elseif ($consultation->confirmation_sent_at)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">Last sent {{ $consultation->confirmation_sent_at->diffForHumans() }}.</p>
                @endif
            </form>
        </div>

        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-gray-500 dark:text-gray-400">Status</span>
                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$consultation->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }}">
                    {{ $statusLabels[$consultation->status] ?? $consultation->status }}
                </span>
            </div>
            @if ($consultation->preferred_at)
                <div class="flex items-center justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Requested Time</span>
                    <span class="text-navy dark:text-white font-medium">
                        {{ $consultation->preferred_at->format('M j, Y \a\t g:ia') }}
                        @if ($consultation->timezone)
                            <span class="text-xs text-gray-500 dark:text-gray-400">({{ $consultation->timezone }})</span>
                        @endif
                    </span>
                </div>
            @endif
            <div class="flex items-center justify-between">
                <span class="text-gray-500 dark:text-gray-400">Submitted</span>
                <span class="text-navy dark:text-white font-medium" title="{{ $consultation->created_at->format('M j, Y \a\t g:ia') }}">{{ $consultation->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>

    {{-- Main content --}}
    <div class="lg:col-span-2 space-y-6 order-2 lg:order-1">
        @if ($consultation->message)
            <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-navy dark:text-white mb-1.5">Message</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $consultation->message }}</p>
            </div>
        @endif
    </div>
</div>

@endsection
