@extends('layouts.auth')

@section('title', 'Welcome — VisionBridge Solutions')

@section('content')

<div class="text-center mb-6">
    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-widest mb-3"
          style="background:rgba(201,168,76,0.12); color:#A8872E;">
        Step 1 of 13
    </span>
    <h1 class="font-display text-2xl font-bold text-navy mb-2">Welcome to VisionBridge Solutions</h1>
    <p class="text-sm text-gray-500 leading-relaxed">
        You're about to set up your client account. This short onboarding process protects you and ensures we can build your site exactly the way you need it.
    </p>
</div>

{{-- Onboarding steps overview --}}
<div class="mb-6 rounded-xl border border-gray-100 bg-gray-50 px-4 py-4">
    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">What to expect</p>
    <ol class="space-y-2">
        @php
        $steps = [
            'Create your client account',
            'Verify your email address',
            'Complete your business information',
            'Select a Website Package',
            'Select a Website Care Plan',
            'Review your Agreement Summary',
            'Read the Master Client Agreement',
            'Confirm your acknowledgments',
            'Provide your electronic signature',
            'Billing authorization',
            'Initial payment',
            'Access your Client Portal',
        ];
        @endphp
        @foreach ($steps as $i => $step)
            <li class="flex items-center gap-2.5 text-sm text-gray-600">
                <span class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 text-xs font-bold"
                      style="background:rgba(27,42,74,0.08); color:#1B2A4A;">
                    {{ $i + 2 }}
                </span>
                {{ $step }}
            </li>
        @endforeach
    </ol>
</div>

<a href="{{ route('register') }}"
   class="block w-full text-center py-3 px-4 rounded-xl font-semibold text-sm text-white transition-colors"
   style="background:#1B2A4A;">
    Get Started &rarr;
</a>

<p class="text-center text-sm text-gray-400 mt-4">
    Already have an account?
    <a href="{{ route('login') }}" class="font-semibold text-navy hover:underline">Sign in</a>
</p>

@endsection
