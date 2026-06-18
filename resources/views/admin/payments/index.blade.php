@extends('layouts.admin')

@section('title', 'Payments – Admin')
@section('page-title', 'Payments')

@section('content')

@php
    $statusColors = [
        'pending' => 'bg-gold/15 text-gold-dark',
        'paid' => 'bg-teal/15 text-teal-dark',
        'failed' => 'bg-red-50 text-red-500',
        'canceled' => 'bg-gray-100 text-gray-500',
    ];
@endphp

@if ($payments->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
        <p class="text-gray-500">No payment requests yet.</p>
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
                    <th class="px-5 py-3">Date</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($payments as $payment)
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy">{{ $payment->project->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $payment->project->name }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700">{{ $payment->description }}</td>
                        <td class="px-5 py-3.5 text-gray-700">{{ $payment->formattedAmount() }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-500' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700">{{ $payment->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.projects.show', $payment->project) }}" class="text-gold-dark font-semibold hover:underline">View Project</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
