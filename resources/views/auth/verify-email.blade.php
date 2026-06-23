@extends('layouts.auth')

@section('title', 'Verify Your Email – VisionBridge Solutions')

@section('content')
    <h1 class="font-display text-2xl font-bold text-navy mb-1">Verify Your Email</h1>
    <p class="text-gray-500 text-sm mb-6">Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed you. If you didn't receive it, we'll gladly send another.</p>

    @if (session('status'))
        <div class="mb-4 text-sm text-teal-dark bg-teal/10 border border-teal/30 rounded-lg px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
        @csrf
        <button type="submit" class="w-full bg-gold hover:bg-gold-dark text-navy font-semibold py-3 rounded-lg transition-colors">
            Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-center text-sm text-gray-500 hover:text-navy transition-colors">
            Sign out
        </button>
    </form>
@endsection
