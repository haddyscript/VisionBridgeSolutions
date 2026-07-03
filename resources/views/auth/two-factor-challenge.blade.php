@extends('layouts.auth')

@section('title', 'Two-Factor Verification – VisionBridge Solutions')

@section('content')
    <h1 class="font-display text-3xl font-extrabold text-navy mb-1">Two-Factor Verification</h1>
    <p class="text-gray-700 text-base font-medium mb-6">Enter the 6-digit code from your authenticator app, or one of your recovery codes.</p>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('two-factor.challenge.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-base font-bold text-navy mb-1">Code</label>
            <input type="text" name="code" required autofocus autocomplete="one-time-code" inputmode="numeric"
                   placeholder="123456 or XXXXXXXX-XXXXXXXX"
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base text-center tracking-widest focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        </div>

        <button type="submit" class="w-full btn-gold-static bg-gold hover:bg-gold-dark text-navy font-bold text-lg py-3.5 rounded-lg transition-colors">
            Verify
        </button>
    </form>

    <p class="text-center text-base font-medium text-gray-700 mt-6">
        <a href="{{ route('login') }}" class="text-gold-dark font-bold hover:underline">Back to sign in</a>
    </p>
@endsection
