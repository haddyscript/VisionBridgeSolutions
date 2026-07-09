{{--
    $subscription: the client's Subscription (must be non-null before including this)

    Self-contained (defines its own status color maps) so it can be rendered
    both as part of the full Payments page and standalone as an AJAX fragment
    from Portal\SubscriptionController::refresh().
--}}
@php
    $statusColors = [
        'pending' => 'bg-gold/15 text-gold-dark',
        'paid' => 'bg-teal/15 text-teal-dark',
        'active' => 'bg-teal/15 text-teal-dark',
        'past_due' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
        'failed' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
        'canceled' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
    ];
    $statusDots = [
        'pending' => 'bg-gold',
        'paid' => 'bg-teal',
        'active' => 'bg-teal',
        'past_due' => 'bg-red-500',
        'failed' => 'bg-red-500',
        'canceled' => 'bg-gray-400',
    ];
@endphp
<div id="maintenance-plan-card" class="subscription-card cursor-pointer bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 mb-6 hover:shadow-md hover:border-gold/40 transition-all"
     data-description="{{ $subscription->description }}"
     data-amount="{{ $subscription->formattedAmount() }}"
     data-status="{{ $subscription->status }}"
     data-status-label="{{ $subscription->status === 'past_due' ? 'Past Due' : ucfirst($subscription->status) }}"
     data-started="{{ $subscription->created_at->format('M j, Y') }}"
     data-period-end="{{ $subscription->current_period_end?->format('M j, Y') }}"
     data-cancel-at-period-end="{{ $subscription->cancel_at_period_end ? '1' : '0' }}"
     data-checkout-url="{{ $subscription->isPending() ? route('portal.subscriptions.checkout', $subscription) : '' }}"
     data-billing-portal-url="{{ ! $subscription->isPending() && ! $subscription->isCanceled() ? route('portal.billing.show') : '' }}">
    <div class="flex flex-wrap items-center justify-between gap-5">
        <div class="flex items-center gap-4">
            <span class="w-12 h-12 rounded-xl bg-teal/10 flex items-center justify-center shrink-0">
                <svg class="w-[1.375rem] h-[1.375rem] text-teal-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </span>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-0.5">Care Plan</p>
                <p class="font-display text-lg font-bold text-navy dark:text-white">{{ $subscription->description }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    {{ $subscription->formattedAmount() }}
                    @if ($subscription->isCanceled() && $subscription->canceled_at)
                        &middot; canceled {{ $subscription->canceled_at->format('M j, Y') }}
                    @elseif ($subscription->cancel_at_period_end && $subscription->current_period_end)
                        &middot; cancels {{ $subscription->current_period_end->format('M j, Y') }}
                    @elseif ($subscription->current_period_end)
                        &middot; renews {{ $subscription->current_period_end->format('M j, Y') }}
                    @endif
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if ($subscription->cancel_at_period_end && $subscription->isActive())
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                    Cancels {{ $subscription->current_period_end?->format('M j') }}
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full {{ $statusColors[$subscription->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$subscription->status] ?? 'bg-gray-400' }}"></span>
                    {{ $subscription->status === 'past_due' ? 'Past Due' : ucfirst($subscription->status) }}
                </span>
            @endif
            @if ($subscription->isCanceled())
                <form method="POST" action="{{ route('portal.subscriptions.restart', $subscription) }}" onclick="event.stopPropagation()">
                    @csrf
                    <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                        Start This Plan Again
                    </button>
                </form>
            @elseif ($subscription->isPending())
                @if ($subscription->stripe_checkout_session_id)
                    <form method="POST" action="{{ route('portal.subscriptions.refresh', $subscription) }}" onclick="event.stopPropagation()" data-ajax-target="maintenance-plan-card">
                        @csrf
                        <button type="submit" title="Already paid? Check the real status with Stripe" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 px-4 py-2.5 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Refresh Status
                        </button>
                    </form>
                @endif
                <form method="POST" action="{{ route('portal.subscriptions.checkout', $subscription) }}" onclick="event.stopPropagation()">
                    @csrf
                    <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                        Start Plan
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('portal.subscriptions.refresh', $subscription) }}" onclick="event.stopPropagation()" data-ajax-target="maintenance-plan-card">
                    @csrf
                    <button type="submit" title="Status looks out of date? Re-check it with Stripe" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 px-4 py-2.5 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Refresh Status
                    </button>
                </form>
                <a href="{{ route('portal.billing.show') }}" onclick="event.stopPropagation()" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 px-4 py-2.5 rounded-lg transition-colors">
                    Manage Billing
                </a>
            @endif
        </div>
    </div>
</div>
