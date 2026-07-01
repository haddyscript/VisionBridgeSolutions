@extends('layouts.portal')

@section('title', 'Agreement Summary – Client Portal')
@section('page-title', 'Agreement Summary')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    Please review your agreement details below. Once you proceed, you will read and sign the Master Agreement.
</p>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">

    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
        <p class="text-xs font-bold uppercase tracking-widest text-navy dark:text-white">Client Agreement Summary</p>
    </div>

    <div class="divide-y divide-gray-100 dark:divide-gray-700">

        <div class="flex items-start justify-between gap-4 px-6 py-4">
            <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 shrink-0 w-48">Project Name</span>
            <span class="text-sm text-navy dark:text-white font-medium text-right">{{ $project->name }}</span>
        </div>

        @if ($plan)
            <div class="flex items-start justify-between gap-4 px-6 py-4">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 shrink-0 w-48">Website Care Plan Selected</span>
                <span class="text-sm text-navy dark:text-white font-medium text-right">{{ $plan->name }}</span>
            </div>

            <div class="flex items-start justify-between gap-4 px-6 py-4">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 shrink-0 w-48">Monthly Subscription</span>
                <span class="text-sm text-navy dark:text-white font-medium text-right">
                    ${{ number_format($plan->price, 2) }} / {{ $plan->interval ?? 'month' }}
                </span>
            </div>
        @endif

        @if ($template)
            <div class="flex items-start justify-between gap-4 px-6 py-4">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 shrink-0 w-48">Agreement</span>
                <span class="text-sm text-navy dark:text-white font-medium text-right">
                    {{ $template->title }}
                    <span class="text-xs text-gray-400 dark:text-gray-500 ml-1">v{{ $template->version }}</span>
                </span>
            </div>
        @endif

        <div class="flex items-start justify-between gap-4 px-6 py-4">
            <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 shrink-0 w-48">Agreement Effective Date</span>
            <span class="text-sm text-navy dark:text-white font-medium text-right">{{ now()->format('F j, Y') }}</span>
        </div>

        <div class="flex items-start justify-between gap-4 px-6 py-4">
            <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 shrink-0 w-48">Primary VisionBridge Contact</span>
            <span class="text-sm text-navy dark:text-white font-medium text-right">support@visionbridgesolutions.com</span>
        </div>

    </div>
</div>

<div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/40 rounded-xl px-5 py-4 mb-6">
    <p class="text-sm text-amber-800 dark:text-amber-300 font-semibold mb-1">Billing Authorization Notice</p>
    <p class="text-sm text-amber-700 dark:text-amber-400">
        By signing the Master Agreement on the next step, you authorize VisionBridge Solutions to process
        the recurring monthly subscription fee for the Website Care Plan selected above. Recurring billing
        continues until properly canceled in accordance with the terms of the Agreement.
    </p>
</div>

<form method="POST" action="{{ route('portal.agreement.summary.confirm') }}">
    @csrf
    <button type="submit" class="w-full bg-gold hover:bg-gold-dark text-navy font-bold text-base py-3.5 rounded-lg transition-colors shadow">
        Proceed to Read &amp; Sign Agreement →
    </button>
</form>

@endsection
