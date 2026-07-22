@extends('layouts.admin')

@section('title', 'Support Tickets – Admin')
@section('page-title', 'Support Tickets')

@section('content')

@php
    $statusColors = [
        'open' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-800 dark:text-amber-400 ring-1 ring-inset ring-amber-200 dark:ring-amber-500/20',
        'in_progress' => 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-300 ring-1 ring-inset ring-indigo-200 dark:ring-indigo-500/20',
        'resolved' => 'bg-teal/10 text-teal-dark ring-1 ring-inset ring-teal/20',
    ];
@endphp

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">General client support requests — billing questions, account help, anything outside a website revision.</p>

@if ($tickets->isEmpty())
    <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No support tickets yet.</p>
    </div>
@else
    <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-navy-dark text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Subject</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Opened</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($tickets as $ticket)
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30">
                        <td class="px-5 py-3.5 align-middle">
                            <p class="font-medium text-navy dark:text-white">{{ $ticket->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $ticket->project->name ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-3.5 align-middle font-semibold text-navy dark:text-white">{{ $ticket->subject }}</td>
                        <td class="px-5 py-3.5 align-middle">
                            <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$ticket->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                                {{ \App\Models\SupportTicket::STATUSES[$ticket->status] ?? $ticket->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 align-middle text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $ticket->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-3.5 align-middle text-right">
                            <a href="{{ route('admin.support-tickets.show', $ticket) }}"
                               class="inline-flex items-center gap-1.5 border border-gray-200 dark:border-gray-600 hover:border-gold hover:bg-gold/10 text-gray-600 dark:text-gray-300 hover:text-gold-dark text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $tickets->links() }}
    </div>
@endif

@endsection
