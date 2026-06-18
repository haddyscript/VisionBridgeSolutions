@extends('layouts.portal')

@section('title', 'Payments – Client Portal')
@section('page-title', 'Payments')

@section('content')

@if (request('checkout') === 'success')
    <div class="mb-6 flex items-center gap-3 rounded-xl px-5 py-4" style="background:rgba(42,157,143,0.08);border:1px solid rgba(42,157,143,0.25);">
        <span class="w-9 h-9 rounded-full bg-teal/15 flex items-center justify-center shrink-0">
            <svg class="w-[1.125rem] h-[1.125rem] text-teal-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
        </span>
        <p class="text-sm text-teal-dark font-medium">Payment received! It may take a moment to reflect below.</p>
    </div>
@elseif (request('checkout') === 'cancel')
    <div class="mb-6 flex items-center gap-3 rounded-xl px-5 py-4 bg-gray-50 border border-gray-200">
        <span class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center shrink-0">
            <svg class="w-[1.125rem] h-[1.125rem] text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </span>
        <p class="text-sm text-gray-600 font-medium">Checkout was canceled. No charge was made.</p>
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
        $statusDots = [
            'pending' => 'bg-gold',
            'paid' => 'bg-teal',
            'active' => 'bg-teal',
            'past_due' => 'bg-red-500',
            'failed' => 'bg-red-500',
            'canceled' => 'bg-gray-400',
        ];
        $totalDue = $payments->where('status', 'pending')->sum('amount');
        $totalPaid = $payments->where('status', 'paid')->sum('amount');
    @endphp

    {{-- Billing Overview hero --}}
    <div class="relative overflow-hidden rounded-2xl p-8 mb-8" style="background:linear-gradient(135deg,#111D33,#1B2A4A 60%,#1B2A4A);">
        <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full" style="background:radial-gradient(circle,rgba(201,168,76,0.18) 0%,transparent 70%);"></div>
        <div class="absolute -bottom-20 -left-10 w-56 h-56 rounded-full" style="background:radial-gradient(circle,rgba(42,157,143,0.14) 0%,transparent 70%);"></div>

        <div class="relative">
            <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-2">Billing Overview</p>
            <h2 class="font-display text-2xl font-bold text-white mb-6">Your account at a glance</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                    <p class="text-xs font-medium uppercase tracking-wide text-white/40 mb-1.5">Amount Due</p>
                    <p class="font-display text-2xl font-bold text-white">${{ number_format($totalDue / 100, 2) }}</p>
                </div>
                <div class="rounded-xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                    <p class="text-xs font-medium uppercase tracking-wide text-white/40 mb-1.5">Total Paid</p>
                    <p class="font-display text-2xl font-bold text-white">${{ number_format($totalPaid / 100, 2) }}</p>
                </div>
                <div class="rounded-xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                    <p class="text-xs font-medium uppercase tracking-wide text-white/40 mb-1.5">Maintenance Plan</p>
                    @if ($subscription && ! $subscription->isCanceled())
                        <p class="font-display text-2xl font-bold text-white">{{ $subscription->isActive() ? 'Active' : ucfirst($subscription->status) }}</p>
                    @else
                        <p class="font-display text-2xl font-bold text-white/40">None</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Maintenance Plan --}}
    @if ($subscription && ! $subscription->isCanceled())
        <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6 hover:shadow-md transition-shadow">
            <div class="flex flex-wrap items-center justify-between gap-5">
                <div class="flex items-center gap-4">
                    <span class="w-12 h-12 rounded-xl bg-teal/10 flex items-center justify-center shrink-0">
                        <svg class="w-[1.375rem] h-[1.375rem] text-teal-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </span>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-0.5">Maintenance Plan</p>
                        <p class="font-display text-lg font-bold text-navy">{{ $subscription->description }}</p>
                        <p class="text-sm text-gray-500 mt-0.5">
                            {{ $subscription->formattedAmount() }}
                            @if ($subscription->current_period_end)
                                &middot; renews {{ $subscription->current_period_end->format('M j, Y') }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full {{ $statusColors[$subscription->status] ?? 'bg-gray-100 text-gray-500' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$subscription->status] ?? 'bg-gray-400' }}"></span>
                        {{ $subscription->status === 'past_due' ? 'Past Due' : ucfirst($subscription->status) }}
                    </span>
                    @if ($subscription->isPending())
                        <form method="POST" action="{{ route('portal.subscriptions.checkout', $subscription) }}">
                            @csrf
                            <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                                Start Plan
                            </button>
                        </form>
                    @else
                        <a href="{{ route('portal.billing-portal') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy bg-gray-100 hover:bg-gray-200 px-4 py-2.5 rounded-lg transition-colors">
                            Manage Billing
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- One-Time Payments --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h3 class="font-display text-lg font-bold text-navy mb-5">Payment History</h3>

        @if ($payments->isEmpty())
            <div class="text-center py-10">
                <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h5M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/></svg>
                </div>
                <p class="text-sm text-gray-400">No payment requests yet. Your VisionBridge representative will let you know when one is ready.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($payments as $payment)
                    <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-gray-100 px-5 py-4 hover:border-gold/30 hover:shadow-sm transition-all">
                        <div class="flex items-center gap-4">
                            <span class="w-10 h-10 rounded-lg bg-navy/5 flex items-center justify-center shrink-0">
                                <svg class="w-[1.125rem] h-[1.125rem] text-navy/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 0v6m0-6L4 21"/></svg>
                            </span>
                            <div>
                                <p class="font-medium text-navy">{{ $payment->description }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $payment->created_at->format('M j, Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-display text-lg font-bold text-navy">{{ $payment->formattedAmount() }}</span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full {{ $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$payment->status] ?? 'bg-gray-400' }}"></span>
                                {{ ucfirst($payment->status) }}
                            </span>
                            @if ($payment->isPending())
                                <form method="POST" action="{{ route('portal.payments.checkout', $payment) }}">
                                    @csrf
                                    <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
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
