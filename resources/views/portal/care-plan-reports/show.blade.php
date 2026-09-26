@extends('layouts.portal')

@section('title', $report->monthLabel().' Report – Client Portal')
@section('page-title', 'Monthly Report')

@section('content')

@php
    $card = 'bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700';
    $work = $report->work_items ?? [];
    $care = array_values(array_intersect_key(\App\Models\CarePlanReport::MAINTENANCE_TASKS, array_flip($report->maintenance ?? [])));
@endphp

<div class="flex flex-wrap items-center justify-between gap-3 mb-6 print:hidden">
    <a href="{{ route('portal.care-plan-reports.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white">← All reports</a>
    <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 text-sm font-semibold text-navy dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700/50">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        Print / Save as PDF
    </button>
</div>

<div class="space-y-6 max-w-4xl">
    <div class="{{ $card }} p-6 sm:p-8">
        <p class="text-xs font-bold uppercase tracking-widest text-gold-dark">Website Care Report</p>
        <h2 class="font-display text-2xl sm:text-3xl font-bold text-navy dark:text-white mt-2">{{ $report->monthLabel() }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            {{ $report->subscription?->maintenancePlan?->name ? $report->subscription->maintenancePlan->name.' Care Plan' : 'Care Plan' }}@if ($report->subscription?->domain) · {{ $report->subscription->domain }}@endif
        </p>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <div class="rounded-lg bg-teal/5 dark:bg-teal/10 p-4">
                <p class="text-2xl font-bold text-teal">{{ count($care) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">routine care task{{ count($care) === 1 ? '' : 's' }} done</p>
            </div>
            <div class="rounded-lg bg-gold/10 p-4">
                <p class="text-2xl font-bold text-gold-dark">{{ count($work) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">update{{ count($work) === 1 ? '' : 's' }} &amp; request{{ count($work) === 1 ? '' : 's' }} completed</p>
            </div>
        </div>

        @if ($report->notes)
            <div class="mt-6 rounded-lg border-l-4 border-gold bg-gray-50 dark:bg-gray-900/40 px-4 py-3">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">A note from our team</p>
                <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">{{ $report->notes }}</p>
            </div>
        @endif
    </div>

    @if ($care)
        <div class="{{ $card }} p-6">
            <h3 class="text-base font-bold text-navy dark:text-white mb-4">Routine Care We Took Care Of</h3>
            <ul class="grid sm:grid-cols-2 gap-3">
                @foreach ($care as $task)
                    <li class="flex items-start gap-2.5 text-sm text-gray-700 dark:text-gray-300">
                        <span class="w-5 h-5 shrink-0 rounded-full bg-teal/10 text-teal flex items-center justify-center text-xs font-bold mt-px">✓</span>
                        {{ $task }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="{{ $card }} p-6">
        <h3 class="text-base font-bold text-navy dark:text-white mb-4">Updates &amp; Requests Completed</h3>
        @forelse ($work as $item)
            <div class="flex items-start gap-3 py-2.5 {{ $loop->first ? '' : 'border-t border-gray-100 dark:border-gray-700' }}">
                <span class="shrink-0 mt-0.5 text-[10px] font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300">
                    {{ \App\Models\CarePlanReport::WORK_TYPES[$item['type'] ?? 'other'] ?? 'Work' }}
                </span>
                <p class="flex-1 text-sm text-gray-700 dark:text-gray-200">{{ $item['title'] }}</p>
                @if (! empty($item['date']))
                    <p class="shrink-0 text-xs text-gray-400 dark:text-gray-500">{{ \Illuminate\Support\Carbon::parse($item['date'])->format('M j') }}</p>
                @endif
            </div>
        @empty
            <p class="text-sm text-gray-500 dark:text-gray-400">No change requests this month — your site just got its regular care.</p>
        @endforelse
    </div>

    <div class="{{ $card }} p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden">
        <div>
            <p class="text-sm font-semibold text-navy dark:text-white">Need something changed on your website?</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Updates and content changes are part of your Care Plan.</p>
        </div>
        <a href="{{ route('portal.category', 'revision') }}" class="shrink-0 bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors text-center">Request an Update</a>
    </div>
</div>

@endsection
