@extends('layouts.admin')

@section('title', 'Website Check Leads – Admin')
@section('page-title', 'Website Check Leads')

@section('content')

@php
    $card = 'bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700';
    $statusColors = [
        'new' => 'bg-gold/15 text-gold-dark',
        'contacted' => 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-300',
        'consultation_booked' => 'bg-sky-50 dark:bg-sky-500/10 text-sky-600 dark:text-sky-300',
        'won' => 'bg-teal/10 text-teal-dark dark:text-teal-light',
        'not_interested' => 'bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400',
    ];
@endphp

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    People who ran the <a href="{{ route('website-check') }}" target="_blank" class="text-gold-dark hover:text-gold font-medium">Free Website Check</a> on our site and left their details for the full report. Their site's biggest problems make an easy conversation opener.
</p>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="{{ $card }} p-5">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Checks Run This Month</p>
        <p class="text-2xl font-bold text-navy dark:text-white mt-1">{{ number_format($stats['checks']) }}</p>
    </div>
    <div class="{{ $card }} p-5">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Leads This Month</p>
        <p class="text-2xl font-bold text-navy dark:text-white mt-1">{{ number_format($stats['leads']) }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $stats['rate'] }}% of checks left their details</p>
    </div>
    <div class="{{ $card }} p-5">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Not Contacted Yet</p>
        <p class="text-2xl font-bold {{ $stats['new'] ? 'text-gold-dark' : 'text-navy dark:text-white' }} mt-1">{{ number_format($stats['new']) }}</p>
    </div>
    <div class="{{ $card }} p-5">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Became Clients</p>
        <p class="text-2xl font-bold text-teal mt-1">{{ number_format($stats['won']) }}</p>
    </div>
</div>

<div class="{{ $card }} overflow-hidden">
    <form method="GET" class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex flex-wrap items-center gap-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search name, email, organization, website…"
               class="flex-1 min-w-[12rem] rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500">
        <select name="status" class="rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold dark:bg-navy-dark dark:text-white">
            <option value="">All statuses</option>
            @foreach (\App\Models\WebsiteCheck::STATUSES as $key => $label)
                <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-4 py-2 rounded-lg transition-colors">Filter</button>
        @if ($search !== '' || $status)
            <a href="{{ route('admin.website-checks.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white px-2">Clear</a>
        @endif
    </form>

    <div class="divide-y divide-gray-100 dark:divide-gray-700">
        @forelse ($leads as $lead)
            @php $grade = $lead->grade(); @endphp
            <a href="{{ route('admin.website-checks.show', $lead) }}" class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50/60 dark:hover:bg-white/5 transition-colors">
                <div class="w-12 h-12 shrink-0 rounded-full flex items-center justify-center text-sm font-bold" style="color: {{ $grade['color'] }}; background: {{ $grade['color'] }}1A;">
                    {{ $lead->score }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-navy dark:text-white truncate">
                        {{ $lead->name }}@if ($lead->organization) <span class="font-normal text-gray-500 dark:text-gray-400">· {{ $lead->organization }}</span>@endif
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $lead->host() }} · {{ $lead->email }}</p>
                </div>
                <div class="hidden sm:block text-right shrink-0">
                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $lead->created_at->format('M j, Y') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ count($lead->issues()) }} issue{{ count($lead->issues()) === 1 ? '' : 's' }}</p>
                </div>
                <span class="shrink-0 text-[11px] font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$lead->status] ?? '' }}">
                    {{ \App\Models\WebsiteCheck::STATUSES[$lead->status] ?? $lead->status }}
                </span>
            </a>
        @empty
            <p class="px-5 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                {{ $search !== '' || $status ? 'No leads match your filter.' : 'No leads yet — share the Free Website Check link to get started.' }}
            </p>
        @endforelse
    </div>

    @if ($leads->hasPages())
        <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700">{{ $leads->links() }}</div>
    @endif
</div>

@endsection
