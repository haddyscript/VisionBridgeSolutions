@extends('layouts.auth')

@section('title', 'Sign In – VisionBridge Solutions')

@section('content')
    <h1 class="font-display text-2xl font-bold text-navy mb-1">Client Portal</h1>
    <p class="text-gray-500 text-sm mb-6">Sign in to manage your project.</p>

    @if (session('status'))
        <div class="mb-4 text-sm text-teal-dark bg-teal/10 border border-teal/30 rounded-lg px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-navy mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        </div>

        <div>
            <label class="block text-sm font-medium text-navy mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-gold focus:ring-gold">
                Remember me
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-gold-dark font-medium hover:underline">Forgot password?</a>
        </div>

        <button type="submit" class="w-full btn-gold-static bg-gold hover:bg-gold-dark text-navy font-semibold py-3 rounded-lg transition-colors">
            Sign In
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Don't have an account? <a href="{{ route('register') }}" class="text-gold-dark font-medium hover:underline">Create one</a>
    </p>
@endsection
