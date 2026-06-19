@extends('layouts.auth')

@section('title', 'Reset Password – VisionBridge Solutions')

@section('content')
    <h1 class="font-display text-2xl font-bold text-navy mb-1">Reset Password</h1>
    <p class="text-gray-500 text-sm mb-6">Choose a new password for your account.</p>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label class="block text-sm font-medium text-navy mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $email) }}" required autofocus
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        </div>

        <div>
            <label class="block text-sm font-medium text-navy mb-1">New Password</label>
            <input type="password" name="password" required
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        </div>

        <div>
            <label class="block text-sm font-medium text-navy mb-1">Confirm New Password</label>
            <input type="password" name="password_confirmation" required
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        </div>

        <button type="submit" class="w-full btn-gold-static bg-gold hover:bg-gold-dark text-navy font-semibold py-3 rounded-lg transition-colors">
            Reset Password
        </button>
    </form>
@endsection
