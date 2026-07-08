@extends('layouts.portal')

@section('title', 'Start Maintenance Plan – Client Portal')
@section('page-title', 'Checkout')

@section('content')

<div class="max-w-5xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <div class="lg:col-span-2 lg:border-r lg:border-gray-200 dark:lg:border-gray-700 lg:pr-10">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4">Payment Method</h3>

            <div id="checkout-error" class="hidden mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3"></div>

            <form id="payment-form">
                <div id="payment-element" class="mb-6"></div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('portal.payments.index') }}" class="text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white px-5 py-2.5 rounded-lg transition-colors">
                        Back
                    </a>
                    <button id="submit-button" type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark font-bold text-sm px-7 py-2.5 rounded-lg transition-colors shadow disabled:opacity-60 disabled:cursor-not-allowed">
                        <span id="submit-button-text">Start Plan — {{ $subscription->formattedAmount() }}</span>
                    </button>
                </div>
            </form>

            <p class="text-xs text-gray-400 dark:text-gray-500 mt-6">
                Payments are processed securely by Stripe. Your card details never touch our servers.
            </p>
        </div>

        <div class="lg:col-span-1">
            <h3 class="font-display text-xl font-bold text-navy dark:text-white mb-2">Summary</h3>
            <p class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-gold-dark mb-5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                {{ ucfirst($subscription->interval) }}ly Subscription
            </p>
            <div class="flex items-center justify-between gap-4 py-3 border-b border-gray-100 dark:border-gray-700 text-sm">
                <span class="text-gray-500 dark:text-gray-400">{{ $subscription->description }}</span>
                <span class="font-semibold text-navy dark:text-white shrink-0">{{ $subscription->formattedAmount() }}</span>
            </div>
            <div class="flex items-center justify-between gap-4 pt-4">
                <span class="font-display font-bold text-navy dark:text-white">Total Today</span>
                <span class="font-display text-lg font-bold text-navy dark:text-white">{{ $subscription->formattedAmount() }}</span>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-3">Billed automatically every {{ $subscription->interval }} until canceled.</p>
        </div>

    </div>
</div>

{{-- Full-page processing overlay — this flow has two async legs
     (confirmSetup, then the confirm() call that actually creates and
     charges the subscription), so it can take noticeably longer than a
     one-time payment. --}}
<div id="processing-overlay" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-white/95 dark:bg-navy-dark/95 backdrop-blur-sm">
    <div class="text-center px-6">
        <svg class="w-12 h-12 mx-auto mb-5 text-gold animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        <p id="processing-overlay-text" class="font-display text-lg font-bold text-navy dark:text-white mb-1.5">Setting up your plan&hellip;</p>
        <p class="text-sm text-gray-500 dark:text-gray-400">Please don't close or refresh this window.</p>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
(function () {
    const stripe = Stripe('{{ $stripeKey }}');
    const elements = stripe.elements({
        clientSecret: '{{ $clientSecret }}',
        appearance: {
            theme: 'stripe',
            variables: {
                colorPrimary: '#C9A84C',
                colorBackground: '#ffffff',
                colorText: '#1B2A4A',
                colorDanger: '#dc2626',
                fontFamily: 'Inter, sans-serif',
                borderRadius: '8px',
                spacingUnit: '4px',
            },
            rules: {
                '.Input': {
                    border: '1px solid #d1d5db',
                    boxShadow: 'none',
                    padding: '10px 12px',
                },
                '.Input:focus': {
                    border: '1px solid #C9A84C',
                    boxShadow: '0 0 0 1px #C9A84C',
                },
                '.Label': {
                    fontWeight: '500',
                    color: '#374151',
                    marginBottom: '6px',
                },
            },
        },
    });
    const paymentElement = elements.create('payment');
    paymentElement.mount('#payment-element');

    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const submitButtonText = document.getElementById('submit-button-text');
    const errorBox = document.getElementById('checkout-error');
    const overlay = document.getElementById('processing-overlay');
    const overlayText = document.getElementById('processing-overlay-text');
    const originalButtonText = submitButtonText.textContent;
    const setupIntentId = '{{ $clientSecret }}'.split('_secret_')[0];
    let submitting = false;

    function showOverlay(message) {
        overlayText.textContent = message;
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
    }

    function hideOverlay() {
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
    }

    function resetButton() {
        submitting = false;
        submitButton.disabled = false;
        submitButtonText.textContent = originalButtonText;
        hideOverlay();
    }

    function showError(message) {
        errorBox.textContent = message || 'Something went wrong. Please try again.';
        errorBox.classList.remove('hidden');
        resetButton();
    }

    async function finishSetup() {
        submitButtonText.textContent = 'Starting plan…';
        showOverlay('Starting your plan…');

        try {
            const response = await fetch('{{ route('portal.subscriptions.confirm', $subscription) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    setup_intent: setupIntentId,
                    timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                showError(data.error);
                return;
            }

            window.location.href = data.redirect;
        } catch (err) {
            showError('Could not finish setting up this plan. Please try again.');
        }
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (submitting) return;
        submitting = true;

        submitButton.disabled = true;
        submitButtonText.textContent = 'Saving card…';
        errorBox.classList.add('hidden');
        showOverlay('Saving your card…');

        const { error } = await stripe.confirmSetup({
            elements,
            confirmParams: {
                return_url: window.location.href,
            },
            redirect: 'if_required',
        });

        if (error) {
            // Something else (e.g. Stripe Link) already confirmed this same
            // SetupIntent successfully — that's a success for us too.
            if (error.code === 'setup_intent_unexpected_state' && error.setup_intent?.status === 'succeeded') {
                await finishSetup();
                return;
            }

            showError(error.message);
            return;
        }

        await finishSetup();
    });
})();
</script>

@endsection
