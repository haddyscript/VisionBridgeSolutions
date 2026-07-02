@extends('layouts.auth')

@section('title', 'Sign In – VisionBridge Solutions')

@section('content')
    <h1 class="font-display text-3xl font-extrabold text-navy mb-1">Client Portal</h1>
    <p class="text-gray-700 text-base font-medium mb-6">Sign in to manage your project.</p>

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
            <label class="block text-base font-bold text-navy mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        </div>

        <div>
            <label class="block text-base font-bold text-navy mb-1">Password</label>
            <div class="relative">
                <input type="password" name="password" required
                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 pr-11 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                <button type="button" onclick="togglePasswordVisibility(this)"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5 eye-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <svg class="w-5 h-5 eye-off-icon hidden" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-base font-medium text-gray-700">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-gold focus:ring-gold">
                Remember me
            </label>
            <a href="{{ route('password.request') }}" class="text-base text-gold-dark font-bold hover:underline">Forgot password?</a>
        </div>

        <button type="submit" class="w-full btn-gold-static bg-gold hover:bg-gold-dark text-navy font-bold text-lg py-3.5 rounded-lg transition-colors">
            Sign In
        </button>
    </form>

    <p class="text-center text-base font-medium text-gray-700 mt-6">
        Don't have an account? <a href="{{ route('register') }}" class="text-gold-dark font-bold hover:underline">Create one</a>
    </p>
@endsection
