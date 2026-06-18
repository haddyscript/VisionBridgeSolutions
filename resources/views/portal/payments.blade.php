@extends('layouts.portal')

@section('title', 'Payments – Client Portal')
@section('page-title', 'Payments')

@section('content')

@if (request('checkout') === 'success')
    <div class="mb-6 text-sm text-teal-dark bg-teal/10 border border-teal/30 rounded-lg px-4 py-3">
        Payment received! It may take a moment to reflect below.
    </div>
@elseif (request('checkout') === 'cancel')
    <div class="mb-6 text-sm text-gray-600 bg-gray-100 border border-gray-200 rounded-lg px-4 py-3">
        Checkout was canceled. No charge was made.
    </div>
@endif

@if (! $project)

    <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
        <p class="text-gray-500">No project has been set up for your account yet. Please contact your VisionBridge representative.</p>
    </div>

@else

    @php
        $statusColors = [
            'pending' => 'bg-gold/15 text-gold-dark',
            'paid' => 'bg-teal/15 text-teal-dark',
            'active' => 'bg-teal/15 text-teal-dark',
            'past_due' => 'bg-red-50 text-red-500',
            'failed' => 'bg-red-50 text-red-500',
            'canceled' => 'bg-gray-100 text-gray-500',
        ];
    @endphp

    {{-- Maintenance Plan --}}
    @if ($subscription && ! $subscription->isCanceled())
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold text-navy mb-4">Maintenance Plan</h3>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="font-medium text-navy">{{ $subscription->description }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $subscription->formattedAmount() }}
                        @if ($subscription->current_period_end)
                            &middot; renews {{ $subscription->current_period_end->format('M j, Y') }}
                        @endif
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$subscription->status] ?? 'bg-gray-100 text-gray-500' }}">
                        {{ $subscription->status === 'past_due' ? 'Past Due' : ucfirst($subscription->status) }}
                    </span>
                    @if ($subscription->isPending())
                        <form method="POST" action="{{ route('portal.subscriptions.checkout', $subscription) }}">
                            @csrf
                            <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                Start Plan
                            </button>
                        </form>
                    @else
                        <a href="{{ route('portal.billing-portal') }}" class="text-sm font-semibold text-gold-dark hover:underline">
                            Manage Billing
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- One-Time Payments --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="font-semibold text-navy mb-4">Payments</h3>

        @if ($payments->isEmpty())
            <p class="text-sm text-gray-400">No payment requests yet. Your VisionBridge representative will let you know when one is ready.</p>
        @else
            <div class="divide-y divide-gray-100 -mx-6">
                @foreach ($payments as $payment)
                    <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4">
                        <div>
                            <p class="font-medium text-navy">{{ $payment->description }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $payment->created_at->format('M j, Y') }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-semibold text-navy">{{ $payment->formattedAmount() }}</span>
                            <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-500' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                            @if ($payment->isPending())
                                <form method="POST" action="{{ route('portal.payments.checkout', $payment) }}">
                                    @csrf
                                    <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                        Pay Now
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endif

@endsection
