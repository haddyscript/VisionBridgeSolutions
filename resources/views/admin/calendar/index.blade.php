@extends('layouts.admin')

@section('title', 'Calendar – Admin')
@section('page-title', 'Calendar')

@section('content')

<div class="flex items-center justify-between mb-5">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.calendar', ['month' => $prevMonth]) }}" class="w-9 h-9 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-navy dark:hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="font-display text-lg font-bold text-navy dark:text-white">{{ $month->format('F Y') }}</h2>
        <a href="{{ route('admin.calendar', ['month' => $nextMonth]) }}" class="w-9 h-9 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-navy dark:hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    <div class="flex items-center gap-4 text-xs font-medium text-gray-500 dark:text-gray-400">
        <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-gold"></span> Consultation</span>
        <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-teal"></span> Milestone Due</span>
    </div>
</div>

@php
    $today = now()->format('Y-m-d');
    $firstOfMonth = $month->copy()->startOfMonth();
    $daysInMonth = $month->copy()->endOfMonth()->day;
    $startOffset = $firstOfMonth->dayOfWeek;
@endphp

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="grid grid-cols-7 border-b border-gray-200 dark:border-gray-700">
        @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
            <div class="px-2 py-2.5 text-center text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ $day }}</div>
        @endforeach
    </div>

    <div class="grid grid-cols-7">
        @for ($i = 0; $i < $startOffset; $i++)
            <div class="border-b border-r border-gray-100 dark:border-gray-700/60 min-h-[110px] bg-gray-50/40 dark:bg-gray-900/20"></div>
        @endfor

        @for ($day = 1; $day <= $daysInMonth; $day++)
            @php
                $dateKey = $month->copy()->day($day)->format('Y-m-d');
                $events = $eventsByDay[$dateKey] ?? [];
                $isToday = $dateKey === $today;
            @endphp
            <div class="border-b border-r border-gray-100 dark:border-gray-700/60 min-h-[110px] p-2 {{ $isToday ? 'bg-gold/5' : '' }}">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-semibold {{ $isToday ? 'bg-gold text-navy' : 'text-gray-500 dark:text-gray-400' }}">{{ $day }}</span>
                <div class="mt-1.5 space-y-1">
                    @foreach (array_slice($events, 0, 3) as $event)
                        <a href="{{ $event['url'] }}" class="block text-[0.68rem] font-medium px-1.5 py-1 rounded-md truncate {{ $event['type'] === 'consultation' ? 'bg-gold/15 text-gold-dark' : 'bg-teal/10 text-teal-dark' }}">
                            @if ($event['time']) {{ $event['time'] }} &middot; @endif {{ $event['title'] }}
                        </a>
                    @endforeach
                    @if (count($events) > 3)
                        <p class="text-[0.65rem] text-gray-400 dark:text-gray-500 px-1.5">+{{ count($events) - 3 }} more</p>
                    @endif
                </div>
            </div>
        @endfor
    </div>
</div>

@endsection
