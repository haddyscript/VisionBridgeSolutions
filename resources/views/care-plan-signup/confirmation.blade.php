@extends('layouts.app')

@section('title', 'Welcome to VisionBridge Solutions')

@section('content')

<section class="bg-gray-50 min-h-screen flex items-center pt-20 pb-24 px-4">
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

@endsection
