@extends('layouts.admin')

@section('title', 'Maintenance Plans – Admin')
@section('page-title', 'Maintenance Plans')

@section('content')

@php
    $statusColors = [
        'pending' => 'bg-gold/15 text-gold-dark',
        'active' => 'bg-teal/15 text-teal-dark',
        'past_due' => 'bg-red-50 text-red-500',
        'canceled' => 'bg-gray-100 text-gray-500',
    ];
    $statusLabels = [
        'pending' => 'Pending',
        'active' => 'Active',
        'past_due' => 'Past Due',
        'canceled' => 'Canceled',
    ];
@endphp

@if ($subscriptions->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
        <p class="text-gray-500">No maintenance plans yet.</p>
    </div>
@else
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Description</th>
                    <th class="px-5 py-3">Amount</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Renews</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($subscriptions as $subscription)
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy">{{ $subscription->project->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $subscription->project->name }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700">{{ $subscription->description }}</td>
                        <td class="px-5 py-3.5 text-gray-700">{{ $subscription->formattedAmount() }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$subscription->status] ?? 'bg-gray-100 text-gray-500' }}">
                                {{ $statusLabels[$subscription->status] ?? $subscription->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700">{{ $subscription->current_period_end?->format('M j, Y') ?? '—' }}</td>
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
