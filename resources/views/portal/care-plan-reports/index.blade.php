@extends('layouts.portal')

@section('title', 'Monthly Reports – Client Portal')
@section('page-title', 'Monthly Reports')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    Each month, we send you a short report of everything we took care of on your website as part of your Care Plan — updates, backups, security checks, and any changes you asked for.
</p>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
    @forelse ($reports as $report)
        @php $workCount = count($report->work_items ?? []); $careCount = count($report->maintenance ?? []); @endphp
        <a href="{{ route('portal.care-plan-reports.show', $report) }}" class="flex items-center justify-between gap-4 px-6 py-4 hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition-colors">
            <div class="flex items-center gap-4 min-w-0">
                <div class="w-11 h-11 shrink-0 rounded-lg bg-gold/10 flex flex-col items-center justify-center">
                    <span class="text-[10px] font-bold uppercase text-gold-dark leading-none">{{ $report->month->format('M') }}</span>
                    <span class="text-[10px] text-gray-500 dark:text-gray-400 leading-none mt-0.5">{{ $report->month->format('Y') }}</span>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-navy dark:text-white">{{ $report->monthLabel() }} Report</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                        {{ $careCount }} care task{{ $careCount === 1 ? '' : 's' }}@if ($workCount) · {{ $workCount }} update{{ $workCount === 1 ? '' : 's' }} completed @endif
                    </p>
                </div>
            </div>
            <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    @empty
        <p class="text-sm text-gray-400 dark:text-gray-500 px-6 py-10 text-center">
            Your first monthly report will appear here early next month.
        </p>
    @endforelse
</div>

@endsection
