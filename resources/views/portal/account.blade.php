@extends('layouts.portal')

@section('title', 'Account Settings – Client Portal')
@section('page-title', 'Account Settings')

@section('content')

@php $user = auth()->user(); @endphp

{{-- Status flash --}}
@if (session('status'))
    <div class="mb-6 flex items-center gap-3 rounded-xl border border-teal/30 bg-teal/10 px-4 py-3">
        <svg class="w-4 h-4 text-teal-dark dark:text-teal-light shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <p class="text-sm font-medium text-teal-dark dark:text-teal-light">{{ session('status') }}</p>
    </div>
@endif

<div class="max-w-2xl space-y-3">

    {{-- Profile card --}}
    <div class="rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
        <div class="px-6 py-6" style="background:linear-gradient(135deg,#111D33,#1B2A4A);">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-full bg-gold/20 text-gold flex items-center justify-center shrink-0 text-xl font-bold font-display">
                    {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strstr($user->name, ' '), 1, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-display text-xl font-bold text-white truncate">{{ $user->name }}</p>
                    <p class="text-sm text-white/60 truncate">{{ $user->email }}</p>
                    <span class="mt-1.5 inline-block text-xs font-semibold uppercase tracking-widest text-gold">Client</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Row: Profile Information --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <button type="button" data-toggle="section-profile"
            class="w-full flex items-center gap-4 px-5 py-4 text-left hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
            <div class="w-9 h-9 rounded-lg bg-navy/8 dark:bg-white/8 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-navy dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-navy dark:text-white">Profile Information</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Update your name and email address</p>
            </div>
            <svg data-chevron="section-profile" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <div id="section-profile" class="hidden border-t border-gray-100 dark:border-gray-700 px-5 py-5">
            <form method="POST" action="{{ route('portal.account.profile.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Phone Number <span class="text-gray-400">(optional)</span></label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white"
                        placeholder="(000) 000-0000">
                    @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Current Password <span class="text-gray-400">(required to confirm changes)</span></label>
                    <input type="password" name="current_password" required
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                    @error('current_password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                    Save Profile
                </button>
            </form>
        </div>
    </div>

    {{-- Row: Password & Security --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <button type="button" data-toggle="section-password"
            class="w-full flex items-center gap-4 px-5 py-4 text-left hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
            <div class="w-9 h-9 rounded-lg bg-navy/8 dark:bg-white/8 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-navy dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-navy dark:text-white">Password &amp; Security</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Change your password</p>
            </div>
            <svg data-chevron="section-password" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <div id="section-password" class="hidden border-t border-gray-100 dark:border-gray-700 px-5 py-5">
            <form method="POST" action="{{ route('portal.account.password.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Current Password</label>
                    <div class="relative">
                        <input type="password" name="current_password" required
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                        <button type="button" class="password-toggle absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-navy dark:hover:text-white" aria-label="Show password">
                            <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('current_password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">New Password</label>
                    <div class="relative">
                        <input type="password" name="password" required
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                        <button type="button" class="password-toggle absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-navy dark:hover:text-white" aria-label="Show password">
                            <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" required
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                        <button type="button" class="password-toggle absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-navy dark:hover:text-white" aria-label="Show password">
                            <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>
                <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                    Update Password
                </button>
            </form>
        </div>
    </div>

    {{-- Row: Notification Preferences --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <button type="button" data-toggle="section-notifications"
            class="w-full flex items-center gap-4 px-5 py-4 text-left hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
            <div class="w-9 h-9 rounded-lg bg-navy/8 dark:bg-white/8 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-navy dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-navy dark:text-white">Notification Preferences</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Choose which emails you receive from us</p>
            </div>
            <svg data-chevron="section-notifications" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <div id="section-notifications" class="hidden border-t border-gray-100 dark:border-gray-700 px-5 py-5">
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Payment receipts and security alerts always send — these can't be turned off.</p>
            <form method="POST" action="{{ route('portal.account.notifications.update') }}" class="space-y-3">
                @csrf
                @method('PATCH')
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="notify_on_replies" value="1" {{ $user->notify_on_replies ? 'checked' : '' }}
                        class="mt-0.5 rounded border-gray-300 dark:border-gray-600 text-gold focus:ring-gold">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Email me when VisionBridge replies to my Website Content or Revisions</span>
                </label>
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="notify_on_consultations" value="1" {{ $user->notify_on_consultations ? 'checked' : '' }}
                        class="mt-0.5 rounded border-gray-300 dark:border-gray-600 text-gold focus:ring-gold">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Email me about consultation confirmations, reschedules, or cancellations</span>
                </label>
                <div class="pt-1">
                    <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                        Save Preferences
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Row: Two-Factor Authentication --}}
    <a href="{{ route('portal.two-factor.show') }}"
       class="flex items-center gap-4 px-5 py-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
        <div class="w-9 h-9 rounded-lg bg-navy/8 dark:bg-white/8 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-navy dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-navy dark:text-white">Two-Factor Authentication</p>
            <p class="text-xs text-gray-400 dark:text-gray-500">
                {{ auth()->user()->hasTwoFactorEnabled() ? 'Enabled — manage recovery codes or disable it' : 'Add an extra layer of security to your account' }}
            </p>
        </div>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    {{-- Row: Login Activity --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <button type="button" data-toggle="section-logins"
            class="w-full flex items-center gap-4 px-5 py-4 text-left hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
            <div class="w-9 h-9 rounded-lg bg-navy/8 dark:bg-white/8 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-navy dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-navy dark:text-white">Login Activity</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Your 5 most recent sign-ins</p>
            </div>
            <svg data-chevron="section-logins" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <div id="section-logins" class="hidden border-t border-gray-100 dark:border-gray-700 px-5 py-5">
            @if ($recentLogins->isEmpty())
                <p class="text-sm text-gray-400 dark:text-gray-500">No login activity recorded yet.</p>
            @else
                <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($recentLogins as $login)
                        <li class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-navy/6 dark:bg-white/6 flex items-center justify-center shrink-0">
                                    @switch($login->simpleBrowser())
                                        @case('Chrome')
                                            <svg class="w-5 h-5" viewBox="0 0 48 48"><circle cx="24" cy="24" r="20" fill="#fff"/><path fill="#4285F4" d="M24 14a10 10 0 019.5 6.9H24a3.1 3.1 0 00-2.9 4L15.5 15A20 20 0 0124 14z" transform="translate(0 0)"/><path fill="#EA4335" d="M24 4a20 20 0 00-17.3 10l7.8 13.5A10 10 0 0124 14h17.3A20 20 0 0024 4z"/><path fill="#FBBC05" d="M6.7 14A20 20 0 004 24a20 20 0 0016.3 19.7l7.8-13.5A10 10 0 0114.5 27.5z"/><path fill="#34A853" d="M41.3 14H24a10 10 0 019.5 13.5l-9.2 16A20 20 0 0041.3 14z"/><circle cx="24" cy="24" r="7" fill="#fff"/><circle cx="24" cy="24" r="6" fill="#4285F4"/></svg>
                                            @break
                                        @case('Safari')
                                            <svg class="w-5 h-5" viewBox="0 0 48 48"><circle cx="24" cy="24" r="21" fill="#2AB9EE"/><circle cx="24" cy="24" r="18" fill="#F5F5F5"/><g stroke="#9AA0A6" stroke-width="1.2"><line x1="24" y1="7" x2="24" y2="11"/><line x1="24" y1="37" x2="24" y2="41"/><line x1="7" y1="24" x2="11" y2="24"/><line x1="37" y1="24" x2="41" y2="24"/></g><path fill="#FF5150" d="M24 24 33 15 27 26z"/><path fill="#F1F1F1" d="M24 24 15 33 21 22z"/></svg>
                                            @break
                                        @case('Firefox')
                                            <svg class="w-5 h-5" viewBox="0 0 48 48"><circle cx="24" cy="25" r="19" fill="#FF9500"/><path fill="#FF3B30" d="M40 14c-1-3-4-7-7-8 1 2 1 4 0 6-2-3-6-5-11-4C11 10 7 17 8 25c1-9 9-13 16-11 6 2 9 8 7 14-2 5-8 8-14 6 5 4 13 3 18-2 5-5 5-13 5-18z"/><circle cx="22" cy="24" r="9" fill="#FFCd00"/></svg>
                                            @break
                                        @case('Microsoft Edge')
                                            <svg class="w-5 h-5" viewBox="0 0 48 48"><path fill="#0C88DA" d="M43 31c-3 8-11 13-19 12-9 0-17-7-18-16 0 6 5 10 11 10 5 0 8-2 11-5 3-2 8-3 15-1z"/><path fill="#33B4E5" d="M6 20C9 10 18 4 27 6c7 1 12 6 13 12-3-4-8-5-13-4-8 2-12 8-13 15-2-3-4-6-8-9z"/><path fill="#2C7A3E" d="M6 20c2-1 5-1 8 1 3 3 3 8 1 13-1 4 0 8 3 11C10 44 3 36 3 27c0-2 1-5 3-7z"/></svg>
                                            @break
                                        @case('Opera')
                                            <svg class="w-5 h-5" viewBox="0 0 48 48"><circle cx="24" cy="24" r="20" fill="#FF1B2D"/><ellipse cx="24" cy="24" rx="9" ry="14" fill="#fff"/></svg>
                                            @break
                                        @default
                                            <svg class="w-4 h-4 text-navy/50 dark:text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                    @endswitch
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-navy dark:text-white">{{ $login->simpleBrowser() }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 truncate">{{ $login->ip_address ?? 'Unknown IP' }}</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 shrink-0">{{ $login->logged_in_at->format('M j, Y g:i A') }}</p>
                        </li>
                    @endforeach
                </ul>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-4">If you don't recognize a sign-in, change your password immediately.</p>
            @endif
        </div>
    </div>

    {{-- Row: Danger Zone --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-red-200 dark:border-red-900/50 overflow-hidden">
        <button type="button" data-toggle="section-danger"
            class="w-full flex items-center gap-4 px-5 py-4 text-left hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors group">
            <div class="w-9 h-9 rounded-lg bg-red-50 dark:bg-red-900/20 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a1 1 0 00.86 1.5h18.64a1 1 0 00.86-1.5L13.71 3.86a1 1 0 00-1.42 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-red-600 dark:text-red-400">Danger Zone</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Request account closure</p>
            </div>
            <svg data-chevron="section-danger" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <div id="section-danger" class="hidden border-t border-red-100 dark:border-red-900/40 px-5 py-5">
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-1 font-medium">Request Account Closure</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
                Submitting this request notifies our team. We will review your request and follow up within 1–2 business days.
                Your account and project data will not be deleted until we confirm with you directly.
            </p>
            <form method="POST" action="{{ route('portal.account.closure-request') }}">
                @csrf
                <button type="submit"
                    onclick="return confirm('Are you sure you want to request account closure? Our team will follow up before anything is deleted.')"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Request Closure
                </button>
            </form>
        </div>
    </div>

</div>

<script>
(function () {
    const triggers = document.querySelectorAll('[data-toggle]');

    triggers.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const target = document.getElementById(btn.dataset.toggle);
            const chevron = document.querySelector('[data-chevron="' + btn.dataset.toggle + '"]');
            const isOpen = !target.classList.contains('hidden');

            // Close all other sections
            triggers.forEach(function (other) {
                if (other !== btn) {
                    const otherTarget = document.getElementById(other.dataset.toggle);
                    const otherChevron = document.querySelector('[data-chevron="' + other.dataset.toggle + '"]');
                    otherTarget.classList.add('hidden');
                    if (otherChevron) otherChevron.style.transform = '';
                }
            });

            // Toggle this one
            target.classList.toggle('hidden', isOpen);
            if (chevron) chevron.style.transform = isOpen ? '' : 'rotate(90deg)';
        });
    });

    // Show/hide password toggles
    document.querySelectorAll('.password-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = btn.closest('.relative')?.querySelector('input');
            if (!input) return;
            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            btn.querySelector('.eye-open')?.classList.toggle('hidden', !showing);
            btn.querySelector('.eye-closed')?.classList.toggle('hidden', showing);
            btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
        });
    });

    // Auto-open the section that has a validation error
    @if ($errors->has('name') || $errors->has('email') || $errors->hasAny(['name','email','current_password']) && !$errors->has('password'))
        document.querySelector('[data-toggle="section-profile"]')?.click();
    @elseif ($errors->has('password') || $errors->has('current_password'))
        document.querySelector('[data-toggle="section-password"]')?.click();
    @endif
})();
</script>

@endsection
