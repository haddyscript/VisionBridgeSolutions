@extends('layouts.app')

@section('title', 'Welcome to VisionBridge Solutions')

@section('content')

<section class="bg-white min-h-screen flex items-center pt-20 pb-28 px-4">
    <div class="max-w-lg mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm p-10 text-center">
        <div class="w-16 h-16 rounded-full bg-teal/10 flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="font-display text-3xl font-extrabold text-navy mb-3">Thank you for joining the VisionBridge Website Care Plan!</h1>

        @if ($subscription?->maintenancePlan)
            <p class="text-gray-700 text-lg font-medium mb-6">
                You're all set with the <strong class="text-navy">{{ $subscription->maintenancePlan->name }}</strong> plan.
            </p>
        @endif

        <p class="text-gray-700 text-base font-medium leading-relaxed mb-6">
            Our team will be in touch shortly to get your website care started. You'll also receive a confirmation
            email with your receipt and billing details, plus a separate email shortly with a link to set up your
            Client Portal password &mdash; that's where you'll upload content, submit revision requests, and track
            everything going forward.
        </p>

        <a href="{{ route('home') }}" class="inline-block bg-gold hover:bg-gold-dark text-navy font-bold text-lg px-8 py-3.5 rounded-lg transition-colors shadow">
            Back to Homepage
        </a>
    </div>
</section>

{{-- Next-step pop-up: payment happens before the agreement in this flow only
     (every other onboarding path signs the agreement before charging the
     card), so this is the one place a "sign your agreement next" prompt
     belongs. Only shown when we actually matched a subscription from the
     Stripe session — otherwise there's no "payment received" to confirm. --}}
@if ($subscription)
<div id="next-step-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-navy-dark/60 backdrop-blur-sm"></div>

    <div class="relative w-full max-w-md">
        <div class="relative overflow-hidden rounded-2xl shadow-2xl" style="background:linear-gradient(135deg,#111D33,#1B2A4A 60%,#1B2A4A);">
            <div class="absolute -top-20 -right-12 w-56 h-56 rounded-full" style="background:radial-gradient(circle,rgba(201,168,76,0.20) 0%,transparent 70%);"></div>

            <button type="button" onclick="document.getElementById('next-step-modal').remove()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/70 hover:text-white transition-colors z-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="relative px-7 pt-8 pb-6 text-center">
                <div class="w-14 h-14 rounded-full mx-auto mb-4 flex items-center justify-center bg-gold/15">
                    <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-1">Payment Received</p>
                <h2 class="font-display text-2xl font-bold text-white mb-3">One More Step</h2>
                <p class="text-sm text-white/70 leading-relaxed">
                    Check your email for a link to set up your Client Portal password. Once you're in, the next
                    step is signing your Website Care Plan &amp; Service Agreement &mdash; that's what officially
                    kicks off your project.
                </p>
            </div>

            <div class="relative bg-white rounded-t-2xl px-7 py-6">
                <button type="button" onclick="document.getElementById('next-step-modal').remove()" class="block w-full text-center bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-3 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                    Got It
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
