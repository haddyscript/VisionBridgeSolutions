@extends('layouts.portal')

@section('title', 'Project Deposit – Client Portal')
@section('page-title', 'Project Deposit')

@section('content')

@include('portal.partials.onboarding-progress', ['step' => 3, 'label' => 'Project Deposit'])

<div class="max-w-2xl">

@if (! $deposit)
    {{-- Waiting on a quote — nothing to pay yet --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8 sm:p-10 text-center">
        <div class="relative w-16 h-16 mx-auto mb-5">
            <div class="absolute inset-0 rounded-full bg-gold/10 animate-ping-slow"></div>
            <div class="relative w-16 h-16 rounded-full bg-gold/10 flex items-center justify-center">
                <svg class="w-8 h-8 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>
        <h2 class="font-display text-xl font-bold text-navy dark:text-white mb-2">We're preparing your custom quote</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-8">
            VisionBridge is reviewing your project and putting together a custom proposal. You don't need to do
            anything else right now — we'll take it from here.
        </p>

        <div class="grid grid-cols-3 gap-3 text-left max-w-md mx-auto">
            <div class="flex flex-col items-center text-center gap-2">
                <span class="w-8 h-8 rounded-full bg-teal/15 text-teal-dark flex items-center justify-center text-xs font-bold shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
                <p class="text-xs font-semibold text-navy dark:text-white leading-snug">We review your project</p>
            </div>
            <div class="flex flex-col items-center text-center gap-2">
                <span class="w-8 h-8 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center text-xs font-bold shrink-0 animate-pulse-slow">2</span>
                <p class="text-xs font-semibold text-navy dark:text-white leading-snug">You get an emailed quote</p>
            </div>
            <div class="flex flex-col items-center text-center gap-2">
                <span class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 flex items-center justify-center text-xs font-bold shrink-0">3</span>
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 leading-snug">Pay deposit &amp; continue</p>
            </div>
        </div>
    </div>

@elseif ($deposit->isPending())
    {{-- Quote ready — invoice card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">

        <div class="px-6 sm:px-8 pt-7 pb-6" style="background:linear-gradient(135deg,#111D33,#1B2A4A);">
            <div class="flex items-start gap-4">
                <div class="w-11 h-11 rounded-xl bg-gold/15 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gold mb-1">Quote Ready</p>
                    <h2 class="font-display text-xl font-bold text-white">Your custom quote is ready</h2>
                    <p class="text-sm text-white/60 mt-1">Pay your initial deposit below to kick off development.</p>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            {{-- Pricing breakdown --}}
            <div class="rounded-xl border border-gray-100 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700 mb-6">
                @if ($project->total_price)
                    <div class="flex items-center justify-between px-5 py-3.5">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Project Price</span>
                        <span class="text-sm font-semibold text-navy dark:text-white">{{ $project->formattedTotalPrice() }}</span>
                    </div>
                @endif
                @if ($project->discount_percent)
                    <div class="flex items-center justify-between px-5 py-3.5">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Discount ({{ rtrim(rtrim(number_format($project->discount_percent, 2), '0'), '.') }}%)</span>
                        <span class="text-sm font-semibold text-teal-dark">-{{ '$'.number_format(($project->total_price - $project->discountedTotalPrice()) / 100, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3.5">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total After Discount</span>
                        <span class="text-sm font-semibold text-navy dark:text-white">{{ $project->formattedDiscountedTotalPrice() }}</span>
                    </div>
                @endif
                <div class="flex items-center justify-between px-5 py-4 bg-gold/5">
                    <div>
                        <span class="text-sm font-bold text-navy dark:text-white">Due Now — {{ $deposit->description }}</span>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">50% deposit to begin work</p>
                    </div>
                    <span class="font-display text-2xl font-bold text-gold-dark shrink-0">{{ $deposit->formattedAmount() }}</span>
                </div>
                @if ($project->total_price)
                    <div class="flex items-center justify-between px-5 py-3.5">
                        <span class="text-sm text-gray-400 dark:text-gray-500">Due at Completion</span>
                        <span class="text-sm font-medium text-gray-400 dark:text-gray-500">{{ '$'.number_format(($project->discountedTotalPrice() - $deposit->amount) / 100, 2) }}</span>
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('portal.payments.checkout', $deposit) }}">
                @csrf
                <input type="hidden" name="timezone" class="js-timezone-field">
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 bg-gold hover:bg-gold-dark text-navy font-bold text-base py-3.5 rounded-xl transition-all hover:-translate-y-0.5 hover:shadow-lg">
                    {{ $deposit->amount > 0 ? 'Pay Deposit Now — '.$deposit->formattedAmount() : 'Continue — No Payment Due' }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </form>
            <p class="text-xs text-gray-400 dark:text-gray-500 text-center mt-3 flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Payments are processed securely by Stripe.
            </p>
        </div>

        <div class="px-6 sm:px-8 pb-7 -mt-1">
            <div class="rounded-xl bg-gray-50 dark:bg-gray-900/40 p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-teal-dark shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                    Once received, VisionBridge and your assigned developer are notified automatically — you'll
                    continue straight on to selecting your Website Care Plan.
                </p>
            </div>
        </div>
    </div>

@else
    {{-- Paid — the middleware normally redirects a client past this gate the
         moment onboarding_step advances, so this only shows briefly if
         they land here right as that update is processing. --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <div class="w-14 h-14 rounded-full bg-teal/10 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-teal-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h2 class="font-display text-lg font-bold text-navy dark:text-white mb-2">Deposit received!</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center justify-center gap-2">
            <svg class="w-4 h-4 animate-spin text-gold" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            Redirecting you to the next step…
        </p>
    </div>
    <meta http-equiv="refresh" content="2">
@endif

</div>

<style>
    @keyframes ping-slow { 0% { transform: scale(1); opacity: .5; } 75%, 100% { transform: scale(1.6); opacity: 0; } }
    .animate-ping-slow { animation: ping-slow 2.5s cubic-bezier(0,0,.2,1) infinite; }
    @keyframes pulse-slow { 0%, 100% { opacity: 1; } 50% { opacity: .55; } }
    .animate-pulse-slow { animation: pulse-slow 2s ease-in-out infinite; }
    @media (prefers-reduced-motion: reduce) {
        .animate-ping-slow, .animate-pulse-slow { animation: none; }
    }
</style>

<script>
    document.querySelectorAll('.js-timezone-field').forEach(function (el) {
        el.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
    });
</script>

@endsection
