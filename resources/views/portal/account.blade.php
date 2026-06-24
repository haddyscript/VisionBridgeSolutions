@extends('layouts.portal')

@section('title', 'Account – Client Portal')
@section('page-title', 'Account')

@section('content')

<div class="max-w-2xl space-y-6">

    {{-- My profile --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="font-display text-base font-bold text-navy dark:text-white mb-4">My Profile</h3>
        <form method="POST" action="{{ route('portal.account.profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Full Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Current Password</label>
                <input type="password" name="current_password" required
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">Required to confirm any changes to your profile, including your email.</p>
            </div>
            <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                Save Profile
            </button>
        </form>
    </div>

    {{-- Change password --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="font-display text-base font-bold text-navy dark:text-white mb-4">Change Password</h3>
        <form method="POST" action="{{ route('portal.account.password.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Current Password</label>
                <input type="password" name="current_password" required
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">New Password</label>
                <input type="password" name="password" required
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Confirm New Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
            </div>
            <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                Update Password
            </button>
        </form>
    </div>

    {{-- Notification preferences --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="font-display text-base font-bold text-navy dark:text-white mb-1">Notification Preferences</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Payment receipts and security alerts (password/email changes) always send — these can't be turned off.</p>
        <form method="POST" action="{{ route('portal.account.notifications.update') }}" class="space-y-3">
            @csrf
            @method('PATCH')
            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                <input type="checkbox" name="notify_on_replies" value="1" {{ auth()->user()->notify_on_replies ? 'checked' : '' }}
                       class="rounded border-gray-300 dark:border-gray-600 text-gold focus:ring-gold">
                Email me when VisionBridge replies to my Website Content or Revisions
            </label>
            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                <input type="checkbox" name="notify_on_consultations" value="1" {{ auth()->user()->notify_on_consultations ? 'checked' : '' }}
                       class="rounded border-gray-300 dark:border-gray-600 text-gold focus:ring-gold">
                Email me about consultation confirmations, reschedules, or cancellations
            </label>
            <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                Save Preferences
            </button>
        </form>
    </div>

</div>

@endsection
