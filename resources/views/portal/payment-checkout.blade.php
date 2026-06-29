@extends('layouts.portal')

@section('title', 'Pay Invoice – Client Portal')
@section('page-title', 'Pay Invoice')

@section('content')

<a href="{{ route('portal.payments.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white mb-5">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Back to Payments
</a>

<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden grid grid-cols-1 lg:grid-cols-2">
        <div class="px-8 py-10 lg:py-12 flex flex-col" style="background:linear-gradient(135deg,#111D33,#1B2A4A);">
            <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-2">Payment</p>
            <h2 class="font-display text-2xl font-bold text-white mb-3">{{ $payment->description }}</h2>
            <p class="font-display text-3xl font-bold text-white">{{ $payment->formattedAmount() }}</p>
            <p class="text-white/50 text-sm mt-1">{{ strtoupper($payment->currency) }}</p>

            <div class="mt-auto pt-10 hidden lg:block">
                <p class="text-white/40 text-xs leading-relaxed">
                    Payments are processed securely by Stripe. Your card details never touch our servers.
                </p>
            </div>
        </div>

        <div class="p-8 lg:py-12">
            <div id="checkout-error" class="hidden mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3"></div>

            <form id="payment-form">
                <div id="payment-element" class="mb-5"></div>

                <button id="submit-button" type="submit" class="w-full bg-gold hover:bg-gold-dark text-navy-dark font-bold text-base py-3.5 rounded-lg transition-colors shadow disabled:opacity-60 disabled:cursor-not-allowed">
                    <span id="submit-button-text">Pay {{ $payment->formattedAmount() }}</span>
                </button>
            </form>

            <p class="text-xs text-gray-400 dark:text-gray-500 text-center mt-4 lg:hidden">
                Payments are processed securely by Stripe. Your card details never touch our servers.
            </p>
        </div>
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
