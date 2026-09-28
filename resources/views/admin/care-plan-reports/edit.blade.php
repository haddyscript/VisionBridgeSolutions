@extends('layouts.admin')

@section('title', 'Care Plan Report – Admin')
@section('page-title', 'Care Plan Report')

@section('content')

@php
    $card = 'bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700';
    $input = 'rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500';
    $items = old('work_items', $report->work_items ?? []);
    $done = old('maintenance', $report->maintenance ?? []);
@endphp

<a href="{{ route('admin.care-plan-reports.index', ['month' => $report->month->format('Y-m')]) }}" class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white mb-4">← All {{ $report->monthLabel() }} reports</a>

@if (session('success'))
    <div class="mb-4 rounded-lg bg-teal/10 text-teal-dark dark:text-teal-light text-sm px-4 py-3">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-sm px-4 py-3">{{ $errors->first() }}</div>
@endif

<div class="{{ $card }} p-5 mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <p class="text-lg font-bold text-navy dark:text-white">{{ $report->project->user->name }} — {{ $report->monthLabel() }}</p>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ $report->project->name }}
            @if ($report->subscription?->maintenancePlan)
                · {{ $report->subscription->maintenancePlan->name }} plan
            @endif
            @if ($report->subscription?->domain)
                · {{ $report->subscription->domain }}
            @endif
        </p>
    </div>
    @if ($report->isSent())
        <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-teal/10 text-teal-dark dark:text-teal-light">Sent {{ $report->sent_at->format('M j, Y g:i a') }}{{ $report->sentBy ? ' by '.$report->sentBy->name : '' }}</span>
    @else
        <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-gold/15 text-gold-dark">Draft — not visible to the client</span>
    @endif
</div>

<form method="POST" action="{{ route('admin.care-plan-reports.update', $report) }}" class="space-y-6">
    @csrf
    @method('PATCH')

    <div class="{{ $card }} p-5">
        <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
            <div>
                <h3 class="text-sm font-bold text-navy dark:text-white">Work Completed This Month</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pre-filled from completed revisions/content updates and resolved support tickets. Reword anything that's too technical, remove what shouldn't show, or add work that wasn't logged.</p>
            </div>
            <button type="submit" name="action" value="refresh" onclick="return confirm('Replace this list with a fresh pull of this month\'s completed work? Unsaved edits to the list will be lost.')"
                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5">↻ Refresh from activity</button>
        </div>

        <div id="work-items" class="space-y-2">
            @foreach ($items as $i => $item)
                <div class="work-item flex flex-col sm:flex-row gap-2">
                    <input type="text" name="work_items[{{ $i }}][title]" value="{{ $item['title'] ?? '' }}" maxlength="200" placeholder="What was done" class="{{ $input }} flex-1">
                    <select name="work_items[{{ $i }}][type]" class="{{ $input }}">
                        @foreach (\App\Models\CarePlanReport::WORK_TYPES as $key => $label)
                            <option value="{{ $key }}" @selected(($item['type'] ?? 'other') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="work_items[{{ $i }}][date]" value="{{ $item['date'] ?? '' }}" class="{{ $input }}">
                    <button type="button" class="remove-item text-sm text-gray-400 hover:text-red-500 px-2" aria-label="Remove">✕</button>
                </div>
            @endforeach
        </div>
        <p id="no-items" class="text-sm text-gray-400 dark:text-gray-500 {{ count($items) ? 'hidden' : '' }}">No logged work this month — add anything the team did, or leave it empty (the report will focus on routine care).</p>
        <button type="button" id="add-item" class="mt-3 text-sm font-semibold text-gold-dark hover:text-gold">+ Add work item</button>
    </div>

    <div class="{{ $card }} p-5">
        <h3 class="text-sm font-bold text-navy dark:text-white">Routine Care Done</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">Tick only what was actually done this month. Carried over from last month's report as a starting point.</p>
        <div class="grid sm:grid-cols-2 gap-2">
            @foreach (\App\Models\CarePlanReport::MAINTENANCE_TASKS as $key => $label)
                <label class="flex items-center gap-2.5 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                    <input type="checkbox" name="maintenance[]" value="{{ $key }}" @checked(in_array($key, $done, true)) class="rounded border-gray-300 text-gold focus:ring-gold">
                    {{ $label }}
                </label>
            @endforeach
        </div>
    </div>

    <div class="{{ $card }} p-5">
        <label for="notes" class="text-sm font-bold text-navy dark:text-white">Note from the Team</label>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-3">Optional, shown at the top of the report. E.g. a highlight of the month, a tip, or a suggestion for next month.</p>
        <textarea id="notes" name="notes" rows="4" maxlength="5000" placeholder="Great month! Your new Easter events page is live…" class="{{ $input }} w-full">{{ old('notes', $report->notes) }}</textarea>
    </div>

    <div class="flex flex-wrap justify-end gap-3">
        <button type="submit" name="action" value="save" class="border border-gray-300 dark:border-gray-600 text-navy dark:text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5">
            {{ $report->isSent() ? 'Save Changes' : 'Save Draft' }}
        </button>
        @unless ($report->isSent())
            <button type="submit" name="action" value="send" onclick="return confirm('Send this report to the client now? They\'ll get an email and a portal notification.')"
                    class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">Save &amp; Send to Client</button>
        @endunless
    </div>
</form>

<template id="work-item-template">
    <div class="work-item flex flex-col sm:flex-row gap-2">
        <input type="text" name="work_items[__I__][title]" maxlength="200" placeholder="What was done" class="{{ $input }} flex-1">
        <select name="work_items[__I__][type]" class="{{ $input }}">
            @foreach (\App\Models\CarePlanReport::WORK_TYPES as $key => $label)
                <option value="{{ $key }}" @selected($key === 'other')>{{ $label }}</option>
            @endforeach
        </select>
        <input type="date" name="work_items[__I__][date]" class="{{ $input }}">
        <button type="button" class="remove-item text-sm text-gray-400 hover:text-red-500 px-2" aria-label="Remove">✕</button>
    </div>
</template>

<script>
(function () {
    const list = document.getElementById('work-items');
    const empty = document.getElementById('no-items');
    let next = {{ count($items) ? max(array_keys($items)) + 1 : 0 }};

    const sync = () => empty.classList.toggle('hidden', list.children.length > 0);

    document.getElementById('add-item').addEventListener('click', () => {
        const html = document.getElementById('work-item-template').innerHTML.replaceAll('__I__', next++);
        list.insertAdjacentHTML('beforeend', html);
        list.lastElementChild.querySelector('input').focus();
        sync();
    });

    list.addEventListener('click', (e) => {
        if (e.target.closest('.remove-item')) {
            e.target.closest('.work-item').remove();
            sync();
        }
    });
})();
</script>

@endsection
