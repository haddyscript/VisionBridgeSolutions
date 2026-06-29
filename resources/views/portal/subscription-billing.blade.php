@extends('layouts.portal')

@section('title', 'Manage Billing – Client Portal')
@section('page-title', 'Manage Billing')

@section('content')

<a href="{{ route('portal.payments.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white mb-5">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Back to Payments
</a>

<div class="max-w-lg mx-auto space-y-6">

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-7 py-6" style="background:linear-gradient(135deg,#111D33,#1B2A4A);">
            <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-1">Maintenance Plan</p>
            <h2 class="font-display text-xl font-bold text-white">{{ $subscription->description }}</h2>
            <p class="text-white/60 text-sm mt-1">
                {{ $subscription->formattedAmount() }}
                @if ($subscription->current_period_end)
                    &middot; renews {{ $subscription->current_period_end->format('M j, Y') }}
                @endif
            </p>
        </div>

        @if ($card)
            <div class="px-7 py-5 flex items-center justify-between border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-7 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xs font-bold uppercase text-gray-500 dark:text-gray-300">{{ $card->brand }}</span>
                    <span class="text-sm text-gray-700 dark:text-gray-200">Ending in {{ $card->last4 }} &middot; Expires {{ str_pad($card->exp_month, 2, '0', STR_PAD_LEFT) }}/{{ $card->exp_year }}</span>
                </div>
            </div>
        @endif

        <div class="p-7">
            <div id="checkout-error" class="hidden mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3"></div>

            <form id="payment-form">
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">{{ $card ? 'Update Card' : 'Add a Card' }}</label>
                <div id="payment-element" class="mb-5"></div>

                <button id="submit-button" type="submit" class="w-full bg-gold hover:bg-gold-dark text-navy-dark font-bold text-base py-3.5 rounded-lg transition-colors shadow disabled:opacity-60 disabled:cursor-not-allowed">
                    <span id="submit-button-text">{{ $subscription->isPastDue() ? 'Save Card & Pay Now' : 'Save Card' }}</span>
                </button>
            </form>

            <p class="text-xs text-gray-400 dark:text-gray-500 text-center mt-4">
                Payments are processed securely by Stripe. Your card details never touch our servers.
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-7">
        <h3 class="font-semibold text-navy dark:text-white mb-1.5">Cancel Plan</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">This stops future billing immediately. You can start a new plan anytime from the Payments page.</p>
        <form id="cancel-form" method="POST" action="{{ route('portal.subscriptions.cancel', $subscription) }}" data-confirm="Cancel this maintenance plan? This stops future billing immediately.">
            @csrf
            <button type="submit" class="text-sm font-semibold text-red-500 hover:text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-500/10 px-4 py-2.5 rounded-lg transition-colors">
                Cancel Maintenance Plan
            </button>
        </form>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
(function () {
    const stripe = Stripe('{{ $stripeKey }}');
    const elements = stripe.elements({ clientSecret: '{{ $clientSecret }}' });
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
