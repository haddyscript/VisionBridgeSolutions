@extends('layouts.portal')

@section('title', 'Project Deposit – Client Portal')
@section('page-title', 'Project Deposit')

@section('content')

@include('portal.partials.onboarding-progress', ['step' => 3, 'label' => 'Project Deposit'])

@if (! $deposit)
    <div class="text-center py-10">
        <div class="w-14 h-14 rounded-full bg-gold/10 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <h2 class="text-lg font-bold text-navy dark:text-white mb-2">We're preparing your custom quote</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">
            VisionBridge is reviewing your project and putting together a custom proposal. Once your quote is ready,
            you'll receive an email with a link to pay your initial 50% deposit — you don't need to do anything else
            right now.
        </p>
    </div>
@elseif ($deposit->isPending())
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
        Your custom quote is ready. Pay your initial 50% deposit below to kick off development on your project.
    </p>

    <div class="rounded-xl border-2 border-gold/40 bg-gold/5 p-5 mb-6 flex items-center justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-gold-dark mb-1">{{ $deposit->description }}</p>
            <p class="text-2xl font-bold text-navy dark:text-white">{{ $deposit->formattedAmount() }}</p>
        </div>
        <form method="POST" action="{{ route('portal.payments.checkout', $deposit) }}">
            @csrf
            <input type="hidden" name="timezone" class="js-timezone-field">
            <button type="submit"
                    class="bg-gold hover:bg-gold-dark text-navy font-bold text-sm px-6 py-3 rounded-lg transition-colors shadow whitespace-nowrap">
                Pay Deposit Now →
            </button>
        </form>
    </div>

    <p class="text-xs text-gray-400 dark:text-gray-500">
        Once your deposit is received, VisionBridge and your assigned developer are notified automatically and
        you'll continue on to selecting your Website Care Plan.
    </p>
@else
    {{-- Paid — the middleware normally redirects a client past this gate the
         moment onboarding_step advances, so this only shows briefly if
         they land here right as that update is processing. --}}
    <p class="text-sm text-gray-500 dark:text-gray-400">
        Your deposit has been received — redirecting you to the next step...
    </p>
    <meta http-equiv="refresh" content="2">
@endif

<script>
    document.querySelectorAll('.js-timezone-field').forEach(function (el) {
        el.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
    });
</script>

@endsection
