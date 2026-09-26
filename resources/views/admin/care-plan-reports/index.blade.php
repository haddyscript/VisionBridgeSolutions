@extends('layouts.admin')

@section('title', 'Care Plan Reports – Admin')
@section('page-title', 'Care Plan Reports')

@section('content')

@php
    $card = 'bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700';
    $drafts = $reports->reject->isSent();
@endphp

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    Monthly "here's what we did on your website" reports for Care Plan clients. Drafts are created automatically on the 1st of each month for the month before, pre-filled with completed revisions and support tickets. Review each one, tick off the routine care done, add a note, and send — clients never see a draft.
</p>

@if (session('success'))
    <div class="mb-4 rounded-lg bg-teal/10 text-teal-dark dark:text-teal-light text-sm px-4 py-3">{{ session('success') }}</div>
@endif

<div class="{{ $card }} p-5 mb-6 flex flex-wrap items-end justify-between gap-4">
    <form method="GET" class="flex items-end gap-2">
        <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Month</label>
            <select name="month" onchange="this.form.submit()" class="rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold dark:bg-navy-dark dark:text-white">
                @forelse ($months as $m)
                    <option value="{{ $m }}" @selected($m === $selected)>{{ \Illuminate\Support\Carbon::createFromFormat('Y-m', $m)->format('F Y') }}</option>
                @empty
                    <option value="{{ $selected }}">{{ $monthDate->format('F Y') }}</option>
                @endforelse
            </select>
        </div>
    </form>

    <form method="POST" action="{{ route('admin.care-plan-reports.generate') }}" class="flex items-end gap-2">
        @csrf
        <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Create drafts for</label>
            <input type="month" name="month" value="{{ now()->subMonthNoOverflow()->format('Y-m') }}" max="{{ now()->format('Y-m') }}" required
                   class="rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold dark:bg-navy-dark dark:text-white">
        </div>
        <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-4 py-2 rounded-lg transition-colors">Create Drafts</button>
    </form>
</div>

<div class="{{ $card }} overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex flex-wrap items-center justify-between gap-2">
        <h3 class="text-sm font-bold text-navy dark:text-white">{{ $monthDate->format('F Y') }}</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            {{ $reports->count() - $drafts->count() }} sent · <span class="{{ $drafts->count() ? 'text-gold-dark font-semibold' : '' }}">{{ $drafts->count() }} waiting for review</span> · {{ $activePlanCount }} active Care Plan{{ $activePlanCount === 1 ? '' : 's' }}
        </p>
    </div>
    <div class="divide-y divide-gray-100 dark:divide-gray-700">
        @forelse ($reports as $report)
            <a href="{{ route('admin.care-plan-reports.edit', $report) }}" class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50/60 dark:hover:bg-white/5 transition-colors">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-navy dark:text-white truncate">{{ $report->project?->user?->name ?? 'Unknown client' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                        {{ $report->project?->name }}@if ($report->subscription?->maintenancePlan) · {{ $report->subscription->maintenancePlan->name }}@endif
                    </p>
                </div>
                <p class="hidden sm:block text-xs text-gray-500 dark:text-gray-400 shrink-0">
                    {{ count($report->work_items ?? []) }} work item{{ count($report->work_items ?? []) === 1 ? '' : 's' }} · {{ count($report->maintenance ?? []) }} care task{{ count($report->maintenance ?? []) === 1 ? '' : 's' }}
                </p>
                @if ($report->isSent())
                    <span class="shrink-0 text-[11px] font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-teal/10 text-teal-dark dark:text-teal-light">Sent {{ $report->sent_at->format('M j') }}</span>
                @else
                    <span class="shrink-0 text-[11px] font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-gold/15 text-gold-dark">Draft</span>
                @endif
            </a>
        @empty
            <p class="px-5 py-10 text-center text-sm text-gray-400 dark:text-gray-500">No reports for this month yet. Use "Create Drafts" above to start them now.</p>
        @endforelse
    </div>
</div>

@endsection
