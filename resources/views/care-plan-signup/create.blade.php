@extends('layouts.app')

@section('title', $plan->name.' Signup – VisionBridge Solutions')

@section('content')

<section class="bg-gray-50 min-h-screen pt-36 pb-24 px-4">
    <div class="max-w-xl mx-auto">

        <div class="text-center mb-8">
            <p class="text-sm font-bold uppercase tracking-widest text-gold-dark mb-3">Website Care Plan Signup</p>
            <h1 class="font-display text-3xl md:text-4xl font-extrabold text-navy mb-3">{{ $plan->name }}</h1>
            <p class="text-gray-700 text-lg font-medium">
                {{ $plan->formattedPrice() }}/{{ $plan->interval }} &mdash; tell us a bit about your organization and
                you'll be redirected to our secure checkout to complete your subscription.
            </p>
        </div>

        @if (request('checkout') === 'cancel')
            <div class="mb-6 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                Checkout was canceled. No charge was made — you can try again below whenever you're ready.
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            <form method="POST" action="{{ route('care-plan-signup.store', $plan) }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-base font-bold text-navy mb-1">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                </div>

                <div>
                    <label class="block text-base font-bold text-navy mb-1">Organization / Business Name *</label>
                    <input type="text" name="organization" value="{{ old('organization') }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-base font-bold text-navy mb-1">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                    <div>
                        <label class="block text-base font-bold text-navy mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-base font-bold text-navy mb-1">Website Domain</label>
                        <input type="text" name="domain" value="{{ old('domain') }}" placeholder="e.g. yourorganization.org"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                    <div>
                        <label class="block text-base font-bold text-navy mb-1">Current Hosting Provider</label>
                        <input type="text" name="hosting_provider" value="{{ old('hosting_provider') }}" placeholder="If applicable"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                </div>

                <div>
                    <label class="block text-base font-bold text-navy mb-1">Anything else we should know?</label>
                    <textarea name="notes" rows="3"
                              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="w-full bg-gold hover:bg-gold-dark text-navy font-bold text-lg py-4 rounded-xl transition-colors shadow">
                    Continue to Secure Checkout
                </button>

                <p class="text-center text-sm font-medium text-gray-600">
                    You'll be redirected to Stripe to enter payment details and authorize monthly billing.
                    No long-term contract — cancel anytime.
                </p>
            </form>
        </div>
    </div>
</section>

@endsection
