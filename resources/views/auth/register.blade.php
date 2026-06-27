@extends('layouts.auth')

@section('title', 'Create Account – VisionBridge Solutions')

@section('content')
    <h1 class="font-display text-3xl font-extrabold text-navy mb-1">Create Your Account</h1>
    <p class="text-gray-700 text-base font-medium mb-6">Get access to your project's client portal.</p>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-base font-bold text-navy mb-1">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        </div>

        <div>
            <label class="block text-base font-bold text-navy mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        </div>

        <div>
            <label class="block text-base font-bold text-navy mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        </div>

        <div>
            <label class="block text-base font-bold text-navy mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" required
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        </div>

        <button type="submit" class="w-full bg-gold hover:bg-gold-dark text-navy font-bold text-lg py-3.5 rounded-lg transition-colors">
            Create Account
        </button>
    </form>

    <p class="text-center text-base font-medium text-gray-700 mt-6">
        Already have an account? <a href="{{ route('login') }}" class="text-gold-dark font-bold hover:underline">Sign in</a>
    </p>
@endsection
