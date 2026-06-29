@extends('layouts.portal')

@section('title', 'Pay Invoice – Client Portal')
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
                        <span id="submit-button-text">Pay {{ $payment->formattedAmount() }}</span>
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
                <span class="text-gray-500 dark:text-gray-400">{{ $payment->description }}</span>
                <span class="font-semibold text-navy dark:text-white shrink-0">{{ $payment->formattedAmount() }}</span>
            </div>
            <div class="flex items-center justify-between gap-4 pt-4">
                <span class="font-display font-bold text-navy dark:text-white">Total</span>
                <span class="font-display text-lg font-bold text-navy dark:text-white">{{ $payment->formattedAmount() }} <span class="text-xs font-sans font-normal text-gray-400">{{ strtoupper($payment->currency) }}</span></span>
            </div>
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

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (submitting) return;
        submitting = true;

        submitButton.disabled = true;
        submitButtonText.textContent = 'Processing…';
        errorBox.classList.add('hidden');

        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: '{{ route('portal.payments.index') }}?checkout=success',
            },
            redirect: 'if_required',
        });

        if (error) {
            // A second confirm racing in (e.g. Link's own auto-checkout) can
            // report "already succeeded" — that's a success for us too.
            if (error.code === 'payment_intent_unexpected_state' && error.payment_intent?.status === 'succeeded') {
                window.location.href = '{{ route('portal.payments.index') }}?checkout=success';
                return;
            }

            errorBox.textContent = error.message || 'Something went wrong confirming your card. Please try again.';
            errorBox.classList.remove('hidden');
            submitButton.disabled = false;
            submitButtonText.textContent = originalButtonText;
            submitting = false;
            return;
        }

        window.location.href = '{{ route('portal.payments.index') }}?checkout=success';
    });
})();
</script>

@endsection
