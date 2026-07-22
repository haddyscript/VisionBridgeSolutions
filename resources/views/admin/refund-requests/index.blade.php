@extends('layouts.admin')

@section('title', 'Refund Requests – Admin')
@section('page-title', 'Refund Requests')

@section('content')

@php
    $statusColors = [
        'pending' => 'bg-gold/15 text-gold-dark',
        'approved' => 'bg-teal/15 text-teal-dark',
        'declined' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
    ];
@endphp

@if ($refundRequests->isEmpty())
    <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No refund requests yet.</p>
    </div>
@else
    <div class="space-y-4">
        @foreach ($refundRequests as $item)
            <details class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden" {{ $item->isPending() ? 'open' : '' }}>
                <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-6 py-4 [&::-webkit-details-marker]:hidden">
                    <div class="flex items-center gap-3">
                        <svg class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400 transition-transform duration-200 group-open:rotate-90 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <div>
                            <p class="font-semibold text-navy dark:text-white">{{ $item->payment->project->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->payment->description }} &middot; {{ $item->payment->formattedAmount() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $item->created_at->format('M j, Y') }}</span>
                        <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-500' }}">
                            {{ \App\Models\RefundRequest::STATUSES[$item->status] ?? $item->status }}
                        </span>
                    </div>
                </summary>

                <div class="border-t border-gray-200 dark:border-gray-700 p-6">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1.5">Client's Reason</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line mb-4">{{ $item->reason }}</p>

                    @if ($item->admin_notes)
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1.5">Admin Notes</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line mb-4">{{ $item->admin_notes }}</p>
                    @endif

                    @if ($item->isPending())
                        <div class="grid sm:grid-cols-2 gap-4 pt-2 border-t border-gray-100 dark:border-gray-700">
                            <form method="POST" action="{{ route('admin.refund-requests.update', $item) }}" class="pt-4"
                                onsubmit="return confirm('Refund {{ $item->payment->formattedAmount() }} (minus Stripe\'s fee) to {{ addslashes($item->payment->project->user->name) }}? This processes a real Stripe refund immediately and cannot be undone.')">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="w-full bg-teal hover:bg-teal-dark text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">
                                    Approve &amp; Refund
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.refund-requests.update', $item) }}" class="pt-4 space-y-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="declined">
                                <textarea name="admin_notes" rows="1" placeholder="Optional note to the client..."
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500"></textarea>
                                <button type="submit" onclick="return confirm('Decline this refund request?')" class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-navy dark:text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">
                                    Decline
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </details>
        @endforeach
    </div>
@endif

@endsection
