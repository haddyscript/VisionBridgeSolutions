@extends('layouts.portal')

@section('title', 'Manage Billing – Client Portal')
@section('page-title', 'Manage Billing')

@section('content')

<div class="max-w-5xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <div class="lg:col-span-2 lg:border-r lg:border-gray-200 dark:lg:border-gray-700 lg:pr-10">
            <div class="bg-red-50/60 dark:bg-red-500/5 rounded-2xl border-2 border-red-200 dark:border-red-500/30 p-6 mb-8">
                <div class="flex items-start gap-3 mb-4">
                    <span class="w-9 h-9 rounded-full bg-red-100 dark:bg-red-500/15 flex items-center justify-center shrink-0">
                        <svg class="w-4.5 h-4.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </span>
                    <div>
                        <h3 class="font-bold text-red-700 dark:text-red-400">Cancel Plan</h3>
                        <p class="text-sm text-red-600/80 dark:text-red-400/70 mt-0.5">This stops future billing immediately. You can start a new plan anytime from the Payments page.</p>
                    </div>
                </div>
                <form id="cancel-form" method="POST" action="{{ route('portal.subscriptions.cancel', $subscription) }}" data-confirm="Cancel this care plan? This stops future billing immediately.">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold text-sm px-5 py-2.5 rounded-lg transition-colors shadow">
                        Cancel Care Plan
                    </button>
                </form>
            </div>

            @if ($card)
                <h3 class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-3">Card on File</h3>
                <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100 dark:border-gray-700">
                    <span class="w-10 h-7 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xs font-bold uppercase text-gray-500 dark:text-gray-300">{{ $card->brand }}</span>
                    <span class="text-sm text-gray-700 dark:text-gray-200">Ending in {{ $card->last4 }} &middot; Expires {{ str_pad($card->exp_month, 2, '0', STR_PAD_LEFT) }}/{{ $card->exp_year }}</span>
                </div>
            @endif

            <h3 class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4">{{ $card ? 'Update Card' : 'Add a Card' }}</h3>

            <div id="checkout-error" class="hidden mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3"></div>

            <form id="payment-form">
                <div id="payment-element" class="mb-6"></div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('portal.payments.index') }}" class="text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white px-5 py-2.5 rounded-lg transition-colors">
                        Back
                    </a>
                    <button id="submit-button" type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark font-bold text-sm px-7 py-2.5 rounded-lg transition-colors shadow disabled:opacity-60 disabled:cursor-not-allowed">
                        <span id="submit-button-text">{{ $subscription->isPastDue() ? 'Save Card & Pay Now' : 'Save Card' }}</span>
                    </button>
                </div>
            </form>

            <p class="text-xs text-gray-400 dark:text-gray-500 mt-6">
                Payments are processed securely by Stripe. Your card details never touch our servers.
            </p>
        </div>

        <div class="lg:col-span-1">
            <h3 class="font-display text-xl font-bold text-navy dark:text-white mb-5">Summary</h3>
            <div class="flex items-center justify-between gap-4 py-3 border-b border-gray-100 dark:border-gray-700 text-sm">
                <span class="text-gray-500 dark:text-gray-400">{{ $subscription->description }}</span>
                <span class="font-semibold text-navy dark:text-white shrink-0">{{ $subscription->formattedAmount() }}</span>
            </div>
            @if ($subscription->current_period_end)
                <div class="flex items-center justify-between gap-4 pt-4 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Next Renewal</span>
                    <span class="font-semibold text-navy dark:text-white">{{ $subscription->current_period_end->format('M j, Y') }}</span>
                </div>
            @endif
        </div>

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
    const originalButtonText = submitButtonText.textContent;
    let submitting = false;

    function resetButton() {
        submitting = false;
        submitButton.disabled = false;
        submitButtonText.textContent = originalButtonText;
    }

    function showError(message) {
        errorBox.textContent = message || 'Something went wrong. Please try again.';
        errorBox.classList.remove('hidden');
        resetButton();
    }

    async function finishSetup(setupIntentId) {
        submitButtonText.textContent = 'Saving…';

        try {
            const response = await fetch('{{ route('portal.subscriptions.update-payment-method', $subscription) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                },
                body: JSON.stringify({ setup_intent: setupIntentId }),
            });

            const data = await response.json();

            if (!response.ok) {
                showError(data.error);
                return;
            }

            window.location.href = data.redirect;
        } catch (err) {
            showError('Could not save your card. Please try again.');
        }
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (submitting) return;
        submitting = true;

        submitButton.disabled = true;
        submitButtonText.textContent = 'Saving card…';
        errorBox.classList.add('hidden');

        const { error, setupIntent } = await stripe.confirmSetup({
            elements,
            confirmParams: { return_url: window.location.href },
            redirect: 'if_required',
        });

        if (error) {
            if (error.code === 'setup_intent_unexpected_state' && error.setup_intent?.status === 'succeeded') {
                await finishSetup(error.setup_intent.id);
                return;
            }

            showError(error.message);
            return;
        }

        await finishSetup(setupIntent.id);
    });
})();
</script>

@endsection
