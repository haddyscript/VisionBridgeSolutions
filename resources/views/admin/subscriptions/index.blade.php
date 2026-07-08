@extends('layouts.admin')

@section('title', 'Care Plans – Admin')
@section('page-title', 'Care Plans')

@section('content')

@php
    $statusColors = [
        'pending' => 'bg-gold/15 text-gold-dark',
        'active' => 'bg-teal/15 text-teal-dark',
        'past_due' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
        'canceled' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
    ];
    $statusLabels = [
        'pending' => 'Pending',
        'active' => 'Active',
        'past_due' => 'Past Due',
        'canceled' => 'Canceled',
    ];
@endphp

@if ($subscriptions->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No care plans yet.</p>
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Description</th>
                    <th class="px-5 py-3">Amount</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Renews</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($subscriptions as $subscription)
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white">{{ $subscription->project->user->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $subscription->project->name }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $subscription->description }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $subscription->formattedAmount() }}</td>
                        <td class="px-5 py-3.5">
                            @if ($subscription->cancel_at_period_end && $subscription->isActive())
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500">
                                    Cancels {{ $subscription->current_period_end?->format('M j') }}
                                </span>
                            @else
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$subscription->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                    {{ $statusLabels[$subscription->status] ?? $subscription->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $subscription->current_period_end?->format('M j, Y') ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.projects.show', $subscription->project) }}" class="text-gold-dark font-semibold hover:underline">View Project</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
