@extends('layouts.portal')

@section('title', 'Account Settings – Client Portal')
@section('page-title', 'Account Settings')

@section('content')

@php
    $user = auth()->user();
    $twoFactorEnabled = $user->hasTwoFactorEnabled();
@endphp

{{-- Overview card --}}
<div class="rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
    <div class="px-6 py-6" style="background:linear-gradient(135deg,#111D33,#1B2A4A);">
        <div class="flex items-center gap-5">
            <div id="account-header-initials" class="w-16 h-16 rounded-full bg-gold/20 text-gold flex items-center justify-center shrink-0 text-xl font-bold font-display">
                {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strstr($user->name, ' '), 1, 1)) }}
            </div>
            <div class="min-w-0">
                <p id="account-header-name" class="font-sans text-xl font-extrabold tracking-tight text-white truncate">{{ $user->name }}</p>
                <p id="account-header-email" class="text-sm text-white/60 truncate">{{ $user->email }}</p>
                <span class="mt-1.5 inline-block text-xs font-bold uppercase tracking-widest text-gray-100">Client</span>
            </div>
        </div>
    </div>
</div>

{{-- Two-column settings layout --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    {{-- Left: vertical nav tabs --}}
    <div class="lg:col-span-4 xl:col-span-3">
        <nav class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden divide-y divide-gray-100 dark:divide-gray-700">
            <button type="button" data-settings-tab="profile" class="settings-tab-btn is-active w-full flex items-center gap-3 px-4 py-3.5 text-left border-l-2 transition-colors">
                <div class="settings-tab-icon w-9 h-9 rounded-lg flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="settings-tab-label text-sm font-semibold">Profile Information</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Update your name and email</p>
                </div>
            </button>

            <button type="button" data-settings-tab="business-info" class="settings-tab-btn w-full flex items-center gap-3 px-4 py-3.5 text-left border-l-2 transition-colors">
                <div class="settings-tab-icon w-9 h-9 rounded-lg flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="settings-tab-label text-sm font-semibold">Business Information</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Edit your onboarding questionnaire answers</p>
                </div>
            </button>

            <button type="button" data-settings-tab="password" class="settings-tab-btn w-full flex items-center gap-3 px-4 py-3.5 text-left border-l-2 transition-colors">
                <div class="settings-tab-icon w-9 h-9 rounded-lg flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="settings-tab-label text-sm font-semibold">Password &amp; Security</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Change your password</p>
                </div>
            </button>

            <button type="button" data-settings-tab="notifications" class="settings-tab-btn w-full flex items-center gap-3 px-4 py-3.5 text-left border-l-2 transition-colors">
                <div class="settings-tab-icon w-9 h-9 rounded-lg flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="settings-tab-label text-sm font-semibold">Notification Preferences</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Choose which emails you receive</p>
                </div>
            </button>

            <button type="button" data-settings-tab="two-factor" class="settings-tab-btn w-full flex items-center gap-3 px-4 py-3.5 text-left border-l-2 transition-colors">
                <div class="settings-tab-icon w-9 h-9 rounded-lg flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="settings-tab-label text-sm font-semibold">Two-Factor Authentication</p>
                    </div>
                    @if ($twoFactorEnabled)
                        <span class="inline-block mt-0.5 text-[0.65rem] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full bg-teal/15 text-teal-dark">Enabled</span>
                    @else
                        <span class="inline-block mt-0.5 text-[0.65rem] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500">Disabled</span>
                    @endif
                </div>
            </button>

            <button type="button" data-settings-tab="logins" class="settings-tab-btn w-full flex items-center gap-3 px-4 py-3.5 text-left border-l-2 transition-colors">
                <div class="settings-tab-icon w-9 h-9 rounded-lg flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="settings-tab-label text-sm font-semibold">Login Activity</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Your 5 most recent sign-ins</p>
                </div>
            </button>
        </nav>
    </div>

    {{-- Right: active tab content --}}
    <div class="lg:col-span-8 xl:col-span-9">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">

            {{-- Profile Information --}}
            <div data-settings-panel="profile" class="settings-panel">
                <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Profile Information</h3>
                <form id="profile-form" method="POST" action="{{ route('portal.account.profile.update') }}" class="space-y-4 max-w-md">
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
                        @unless ($user->hasVerifiedEmail())
                            <div class="flex items-center justify-between gap-3 mt-2 rounded-lg border border-amber-200 dark:border-amber-500/20 bg-amber-50 dark:bg-amber-500/10 px-3 py-2">
                                <p class="text-xs text-amber-700 dark:text-amber-400">Your email address is not verified.</p>
                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit" class="text-xs font-semibold text-amber-700 dark:text-amber-400 hover:underline whitespace-nowrap">
                                        Resend Verification Email
                                    </button>
                                </form>
                            </div>
                        @endunless
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
                    <button type="submit" class="settings-action-btn bg-gold text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                        <span class="btn-fill"></span>
                        <span class="btn-label">Save Profile</span>
                    </button>
                </form>
            </div>

            {{-- Business Information (onboarding questionnaire, editable after the fact) --}}
            <div data-settings-panel="business-info" class="settings-panel hidden">
                <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-1">Business Information</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-5">These are the answers you gave during onboarding — update them any time.</p>
                <form id="business-info-form" method="POST" action="{{ route('portal.account.business-info.update') }}" class="space-y-4 max-w-xl">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Organization Name</label>
                        <input type="text" name="organization_name" value="{{ old('organization_name', $project?->name) }}" required
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                        @error('organization_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Organization Type</label>
                        <select name="organization_type"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                            <option value="">Select one&hellip;</option>
                            @foreach (['Church', 'Ministry', 'Nonprofit', 'Small Business', 'Entrepreneur', 'Other'] as $type)
                                <option value="{{ $type }}" {{ old('organization_type', $questionnaire?->organization_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative" id="brand-colors-wrap">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Brand Colors</label>
                        <input type="hidden" name="brand_colors" id="brand-colors-input" value="{{ old('brand_colors', $questionnaire?->brand_colors) }}">

                        <div id="brand-colors-tags" class="flex flex-wrap items-center gap-1.5 w-full rounded-lg border border-gray-300 dark:border-gray-600 px-2 py-1.5 focus-within:ring-2 focus-within:ring-gold focus-within:border-gold dark:bg-gray-900">
                            <input type="text" id="brand-colors-tag-input" placeholder="Type a name, press Enter…"
                                   class="flex-1 min-w-[140px] text-sm bg-transparent focus:outline-none dark:text-white py-1 px-1">
                            <button type="button" id="brand-color-picker-toggle"
                                    class="relative shrink-0 w-7 h-7 rounded-md border border-gray-300 dark:border-gray-600 overflow-hidden hover:ring-2 hover:ring-slate-400 transition-all" title="Custom color picker">
                                <span id="brand-color-picker-swatch" class="absolute inset-0" style="background:#C9A84C;"></span>
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">Type a color name and press Enter, or use the picker to add an exact hex color. Click any color circle to edit it.</p>

                        {{-- Custom color picker popover — styled to match the app, but the actual
                             visual color selection still happens through the native OS color
                             wheel (triggered by the preview swatch below); the R/G/B/Hex fields
                             are for precise typed adjustment, not the only way to pick a color. --}}
                        <div id="brand-color-popover" class="hidden absolute z-20 right-0 mt-2 w-60 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-xl p-4">
                            <label class="relative block w-full h-14 rounded-lg mb-1 border border-gray-100 dark:border-gray-700 cursor-pointer overflow-hidden" title="Click to pick a color visually">
                                <input type="color" id="brand-color-native-picker" class="absolute -top-4 -left-4 w-[calc(100%+2rem)] h-[calc(100%+2rem)] cursor-pointer">
                                <div id="brand-color-popover-preview" class="absolute inset-0 pointer-events-none" style="background:#C9A84C;"></div>
                            </label>
                            <p class="text-[0.65rem] text-gray-400 dark:text-gray-500 mb-3">Click the swatch above to pick a color visually, or fine-tune the exact values below.</p>
                            <div class="grid grid-cols-3 gap-2 mb-3">
                                <div>
                                    <label class="block text-[0.65rem] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1">R</label>
                                    <input type="number" min="0" max="255" id="brand-color-r"
                                           class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-2 py-1.5 text-sm text-center font-sans dark:bg-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                                </div>
                                <div>
                                    <label class="block text-[0.65rem] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1">G</label>
                                    <input type="number" min="0" max="255" id="brand-color-g"
                                           class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-2 py-1.5 text-sm text-center font-sans dark:bg-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                                </div>
                                <div>
                                    <label class="block text-[0.65rem] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1">B</label>
                                    <input type="number" min="0" max="255" id="brand-color-b"
                                           class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-2 py-1.5 text-sm text-center font-sans dark:bg-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-[0.65rem] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1">Hex</label>
                                <input type="text" id="brand-color-hex" maxlength="7"
                                       class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-2 py-1.5 text-sm font-mono dark:bg-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                            </div>
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" id="brand-color-cancel" class="text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white px-3 py-1.5">Cancel</button>
                                <button type="button" id="brand-color-add" class="text-xs font-semibold bg-gold hover:bg-gold-dark text-navy-dark px-3 py-1.5 rounded-lg transition-colors">Add Color</button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Mission Statement</label>
                        <textarea name="mission_statement" rows="3"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">{{ old('mission_statement', $questionnaire?->mission_statement) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Vision Statement</label>
                        <textarea name="vision_statement" rows="3"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">{{ old('vision_statement', $questionnaire?->vision_statement) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Services Interested In</label>
                        @php $selectedServices = old('services', $questionnaire?->services ?? []); @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            @foreach ([
                                'Custom Website Development', 'Landing Page Development', 'Church Website Development',
                                'Ministry Website Development', 'Nonprofit Website Development', 'Small Business Website Development',
                                'Website Redesign Services', 'Website Care Services', 'Hosting Management', 'Website Consulting',
                            ] as $service)
                                <label class="flex items-center gap-2.5 text-sm text-gray-700 dark:text-gray-300">
                                    <input type="checkbox" name="services[]" value="{{ $service }}"
                                           {{ in_array($service, $selectedServices) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-gold focus:ring-gold">
                                    {{ $service }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Requested Pages / Requirements</label>
                        <textarea name="requested_pages" rows="4"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">{{ old('requested_pages', $questionnaire?->requested_pages) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Social Media Links</label>
                        @php $links = old('social_links', $questionnaire?->social_links ?? []); @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ([
                                'website' => 'Current Website', 'facebook' => 'Facebook', 'instagram' => 'Instagram',
                                'twitter' => 'Twitter / X', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube', 'tiktok' => 'TikTok',
                            ] as $key => $label)
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $label }}</label>
                                    <input type="text" name="social_links[{{ $key }}]" value="{{ $links[$key] ?? '' }}" placeholder="https://"
                                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Additional Notes</label>
                        <textarea name="additional_notes" rows="3"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">{{ old('additional_notes', $questionnaire?->additional_notes) }}</textarea>
                    </div>
                    <button type="submit" class="settings-action-btn bg-gold text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                        <span class="btn-fill"></span>
                        <span class="btn-label">Save Business Information</span>
                    </button>
                </form>
            </div>

            {{-- Password & Security --}}
            <div data-settings-panel="password" class="settings-panel hidden">
                <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Password &amp; Security</h3>
                <form id="password-form" method="POST" action="{{ route('portal.account.password.update') }}" class="space-y-4 max-w-md">
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
                    <button type="submit" class="settings-action-btn bg-gold text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                        <span class="btn-fill"></span>
                        <span class="btn-label">Update Password</span>
                    </button>
                </form>
            </div>

            {{-- Notification Preferences --}}
            <div data-settings-panel="notifications" class="settings-panel hidden">
                <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-1">Notification Preferences</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-5">Payment receipts and security alerts always send — these can't be turned off.</p>
                <form id="notifications-form" method="POST" action="{{ route('portal.account.notifications.update') }}" class="space-y-3 max-w-md">
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
                        <button type="submit" class="settings-action-btn bg-gold text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg">
                            <span class="btn-fill"></span>
                            <span class="btn-label">Save Preferences</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Two-Factor Authentication — status here; full setup/recovery-codes flow stays on its own dedicated page --}}
            <div data-settings-panel="two-factor" class="settings-panel hidden">
                <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Two-Factor Authentication</h3>
                <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-100 dark:border-gray-700 px-5 py-4 max-w-md">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-semibold text-navy dark:text-white">Status</span>
                            @if ($twoFactorEnabled)
                                <span class="text-[0.65rem] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full bg-teal/15 text-teal-dark">Enabled</span>
                            @else
                                <span class="text-[0.65rem] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500">Disabled</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            {{ $twoFactorEnabled ? 'Manage recovery codes or disable it below.' : 'Add an extra layer of security to your account.' }}
                        </p>
                    </div>
                    <a href="{{ route('portal.two-factor.show') }}" class="settings-action-btn is-navy shrink-0 bg-navy text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">
                        <span class="btn-fill"></span>
                        <span class="btn-label">{{ $twoFactorEnabled ? 'Manage' : 'Set Up' }}</span>
                    </a>
                </div>
            </div>

            {{-- Login Activity --}}
            <div data-settings-panel="logins" class="settings-panel hidden">
                <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Login Activity</h3>
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

                <div class="mt-6 pt-5 border-t border-gray-100 dark:border-gray-700 max-w-md">
                    <p class="text-sm font-semibold text-navy dark:text-white mb-1">Log Out of All Other Devices</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">This won't log out your current session here — just every other device or browser signed in as you.</p>
                    <form id="logout-other-devices-form" method="POST" action="{{ route('portal.account.logout-other-devices') }}" class="flex items-start gap-2">
                        @csrf
                        <div class="flex-1">
                            <input type="password" name="current_password" required placeholder="Current password"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                            @error('current_password', 'logoutOtherDevices') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="settings-action-btn is-navy shrink-0 bg-navy text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                            <span class="btn-fill"></span>
                            <span class="btn-label">Log Out Others</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Danger Zone — fully detached from the settings group --}}
<div class="mt-10 pt-8 border-t border-gray-200 dark:border-gray-700">
    <div class="rounded-xl border border-red-200 dark:border-red-900/50 bg-red-50/50 dark:bg-red-900/10 p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-9 h-9 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a1 1 0 00.86 1.5h18.64a1 1 0 00.86-1.5L13.71 3.86a1 1 0 00-1.42 0z"/>
                </svg>
            </div>
            <p class="text-sm font-bold text-red-600 dark:text-red-400 uppercase tracking-wide">Danger Zone</p>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-1 font-medium">Request Account Closure</p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-5 max-w-xl">
            Submitting this request notifies our team. We will review your request and follow up within 1–2 business days.
            Your account and project data will not be deleted until we confirm with you directly.
        </p>
        <form id="closure-form" method="POST" action="{{ route('portal.account.closure-request') }}">
            @csrf
            <button type="submit"
                onclick="return confirm('Are you sure you want to request account closure? Our team will follow up before anything is deleted.')"
                class="settings-action-btn is-red px-5 py-2.5 rounded-lg bg-red-600 text-white text-sm font-semibold transition-colors">
                <span class="btn-fill"></span>
                <span class="btn-label">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Request Closure
                </span>
            </button>
        </form>
    </div>
</div>

{{-- Transient toast for no-reload form submissions on this page --}}
<div id="toast" class="fixed bottom-6 right-6 z-50 max-w-sm transform translate-y-2 opacity-0 transition-all duration-300 pointer-events-none">
    <div class="flex items-center gap-2.5 text-sm font-medium text-teal-dark dark:text-teal-light bg-white dark:bg-gray-800 border border-teal/30 shadow-lg rounded-lg px-4 py-3">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        <span id="toast-message"></span>
    </div>
</div>

<style>
    .settings-tab-btn { border-left-color: transparent; }
    .settings-tab-btn .settings-tab-icon { background: rgba(27,42,74,0.08); color: #1B2A4A; }
    .dark .settings-tab-btn .settings-tab-icon { background: rgba(255,255,255,0.08); color: #ffffff; }
    .settings-tab-btn .settings-tab-label { color: #1B2A4A; }
    .dark .settings-tab-btn .settings-tab-label { color: #ffffff; }
    .settings-tab-btn:hover { background: rgba(0,0,0,0.02); }
    .dark .settings-tab-btn:hover { background: rgba(255,255,255,0.03); }
    .settings-tab-btn.is-active { background: rgba(201,168,76,0.08); border-left-color: #C9A84C; }
    .settings-tab-btn.is-active .settings-tab-icon { background: rgba(201,168,76,0.18); color: #A8872E; }
    .settings-tab-btn.is-active .settings-tab-label { color: #A8872E; }

    /* Every action button on this page: white fill sweeps in from the left on hover. */
    .settings-action-btn { position: relative; overflow: hidden; isolation: isolate; }
    .settings-action-btn .btn-fill {
        position: absolute; inset: 0; background: #ffffff;
        transform: scaleX(0); transform-origin: left;
        transition: transform .4s cubic-bezier(.65,0,.35,1);
        z-index: 0;
    }
    .settings-action-btn .btn-label {
        position: relative; z-index: 1;
        display: inline-flex; align-items: center; gap: 8px;
        transition: color .3s ease;
    }
    @media (hover: hover) and (pointer: fine) {
        .settings-action-btn:hover .btn-fill { transform: scaleX(1); }
        .settings-action-btn.is-navy:hover .btn-label { color: #1B2A4A; }
        .settings-action-btn.is-red:hover .btn-label { color: #DC2626; }
    }
</style>

<script>
(function () {
    const tabs = document.querySelectorAll('[data-settings-tab]');
    const panels = document.querySelectorAll('[data-settings-panel]');

    function switchSettingsTab(name) {
        tabs.forEach(function (tab) {
            tab.classList.toggle('is-active', tab.dataset.settingsTab === name);
        });
        panels.forEach(function (panel) {
            panel.classList.toggle('hidden', panel.dataset.settingsPanel !== name);
        });
    }
    window.switchSettingsTab = switchSettingsTab;

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            switchSettingsTab(tab.dataset.settingsTab);
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

    // Auto-open the tab that has a validation error. current_password is
    // shared by both the profile and password forms — when it's the only
    // error present there's no way to tell which form it came from, so it
    // defaults to the profile tab (matches this page's original behavior).
    // Only matters as a no-JS fallback now — the AJAX path below handles
    // its own tab-switching without ever reloading the page.
    @if ($errors->getBag('logoutOtherDevices')->any())
        switchSettingsTab('logins');
    @elseif ($errors->has('organization_name') || $errors->has('organization_type') || $errors->has('mission_statement'))
        switchSettingsTab('business-info');
    @elseif ($errors->has('name') || $errors->has('email') || ($errors->has('current_password') && ! $errors->has('password')))
        switchSettingsTab('profile');
    @elseif ($errors->has('password') || $errors->has('current_password'))
        switchSettingsTab('password');
    @endif
})();
</script>

<script>
(function () {
    let toastTimeout;
    window.showToast = function (message) {
        const toast = document.getElementById('toast');
        document.getElementById('toast-message').textContent = message;
        clearTimeout(toastTimeout);
        toast.classList.remove('translate-y-2', 'opacity-0');
        toastTimeout = setTimeout(function () {
            toast.classList.add('translate-y-2', 'opacity-0');
        }, 3000);
    };

    // Every form on this page submits via fetch instead of a full page
    // reload. Validation errors render inline next to the field they belong
    // to (Laravel returns them as JSON when the request declares
    // Accept: application/json); success shows the toast above instead of
    // relying on a reloaded page to display the flashed session status.
    function submitAjaxForm(form, onSuccess) {
        const submitBtn = form.querySelector('button[type="submit"]');

        form.querySelectorAll('.ajax-field-error').forEach(function (el) { el.remove(); });

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';
            submitBtn.style.cursor = 'wait';
        }

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: new FormData(form),
        })
            .then(function (response) {
                return response.json().catch(function () { return {}; }).then(function (data) {
                    return { ok: response.ok, status: response.status, data: data };
                });
            })
            .then(function (result) {
                if (result.ok) {
                    window.showToast(result.data.message || 'Saved.');
                    if (onSuccess) onSuccess(result.data);
                } else if (result.status === 422 && result.data.errors) {
                    Object.keys(result.data.errors).forEach(function (field) {
                        const input = form.querySelector('[name="' + field + '"]');
                        if (!input) return;
                        const p = document.createElement('p');
                        p.className = 'ajax-field-error text-xs text-red-500 mt-1';
                        p.textContent = result.data.errors[field][0];
                        input.closest('div').appendChild(p);
                    });
                } else {
                    window.showToast(result.data.message || 'Something went wrong. Please try again.');
                }
            })
            .catch(function () {
                window.showToast('Something went wrong. Please try again.');
            })
            .finally(function () {
                // onSuccess callbacks (e.g. the closure form) can opt out of
                // having their button state restored, by setting this flag,
                // when they intentionally want it to stay disabled.
                if (submitBtn && submitBtn.dataset.keepDisabled !== '1') {
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '';
                    submitBtn.style.cursor = '';
                }
            });
    }

    document.getElementById('profile-form')?.addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        submitAjaxForm(form, function () {
            const name = form.querySelector('[name="name"]').value;
            const email = form.querySelector('[name="email"]').value;
            document.getElementById('account-header-name').textContent = name;
            document.getElementById('account-header-email').textContent = email;
            const initials = (name.trim().split(/\s+/).map(function (w) { return w[0]; }).join('') || '').slice(0, 2).toUpperCase();
            document.getElementById('account-header-initials').textContent = initials;
            form.querySelector('[name="current_password"]').value = '';
        });
    });

    document.getElementById('business-info-form')?.addEventListener('submit', function (e) {
        e.preventDefault();
        submitAjaxForm(e.target);
    });

    // Brand Colors renders as removable tag pills (not a plain text field) —
    // brand_colors is still just a string column underneath, so tags are
    // joined back into the hidden input's comma-separated value on every
    // change. Non-hex tags (e.g. a typed color name) get a neutral dashed
    // swatch instead of a real color, since there's no hex to render or edit.
    (function () {
        const wrap = document.getElementById('brand-colors-wrap');
        const tagsContainer = document.getElementById('brand-colors-tags');
        const tagInput = document.getElementById('brand-colors-tag-input');
        const hiddenInput = document.getElementById('brand-colors-input');
        const pickerToggle = document.getElementById('brand-color-picker-toggle');
        const pickerSwatch = document.getElementById('brand-color-picker-swatch');
        const popover = document.getElementById('brand-color-popover');
        const popoverPreview = document.getElementById('brand-color-popover-preview');
        const nativePicker = document.getElementById('brand-color-native-picker');
        const rInput = document.getElementById('brand-color-r');
        const gInput = document.getElementById('brand-color-g');
        const bInput = document.getElementById('brand-color-b');
        const hexInput = document.getElementById('brand-color-hex');
        const addBtn = document.getElementById('brand-color-add');
        const cancelBtn = document.getElementById('brand-color-cancel');
        if (!wrap || !tagsContainer || !hiddenInput || !popover) return;

        const HEX_RE = /^#(?:[0-9a-fA-F]{3}){1,2}$/;
        let tags = hiddenInput.value.split(',').map(function (s) { return s.trim(); }).filter(Boolean);
        let editingIndex = null;

        function hexToRgb(hex) {
            let h = hex.replace('#', '');
            if (h.length === 3) h = h.split('').map(function (c) { return c + c; }).join('');
            const num = parseInt(h, 16);
            return { r: (num >> 16) & 255, g: (num >> 8) & 255, b: num & 255 };
        }

        function rgbToHex(r, g, b) {
            return '#' + [r, g, b].map(function (v) {
                return Math.max(0, Math.min(255, parseInt(v) || 0)).toString(16).padStart(2, '0');
            }).join('').toUpperCase();
        }

        function syncHiddenInput() {
            hiddenInput.value = tags.join(', ');
        }

        function renderTags() {
            tagsContainer.querySelectorAll('.brand-color-tag').forEach(function (el) { el.remove(); });

            tags.forEach(function (value, index) {
                const isHex = HEX_RE.test(value);

                const tag = document.createElement('span');
                tag.className = 'brand-color-tag inline-flex items-center gap-1.5 pl-1 pr-2 py-1 rounded-full border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-navy dark:text-white';

                const swatch = document.createElement('span');
                swatch.className = 'w-4 h-4 rounded-full shrink-0 transition-all ' + (isHex
                    ? 'border border-black/10 cursor-pointer hover:ring-2 hover:ring-slate-400 hover:scale-110'
                    : 'border border-dashed border-gray-300 dark:border-gray-500');
                if (isHex) {
                    swatch.style.background = value;
                    swatch.title = 'Click to edit';
                    swatch.addEventListener('click', function () {
                        openPopover(index, value, swatch);
                    });
                }

                const label = document.createElement('span');
                label.className = isHex ? 'font-mono text-xs' : 'text-xs';
                label.textContent = value;

                const remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'text-gray-400 hover:text-red-500 ml-0.5 leading-none';
                remove.setAttribute('aria-label', 'Remove ' + value);
                remove.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
                remove.addEventListener('click', function () {
                    tags.splice(index, 1);
                    syncHiddenInput();
                    renderTags();
                });

                tag.appendChild(swatch);
                tag.appendChild(label);
                tag.appendChild(remove);
                tagsContainer.insertBefore(tag, tagInput);
            });

            syncHiddenInput();
        }

        function addTag(value) {
            value = value.trim();
            if (!value) return;
            tags.push(value);
            renderTags();
        }

        tagInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                addTag(tagInput.value);
                tagInput.value = '';
            } else if (e.key === 'Backspace' && tagInput.value === '' && tags.length) {
                tags.pop();
                renderTags();
            }
        });

        tagInput.addEventListener('blur', function () {
            if (tagInput.value.trim()) {
                addTag(tagInput.value);
                tagInput.value = '';
            }
        });

        function setRgbFields(hex) {
            const rgb = hexToRgb(hex);
            rInput.value = rgb.r;
            gInput.value = rgb.g;
            bInput.value = rgb.b;
            hexInput.value = hex.toUpperCase();
            popoverPreview.style.background = hex;
            if (nativePicker) nativePicker.value = hex;
        }

        // Anchored via the `right-0` class (always flush with the tag input's
        // right edge) so it can never overflow sideways regardless of viewport
        // width — only the vertical offset needs computing here.
        function openPopover(index, hex, anchorEl) {
            editingIndex = index;
            setRgbFields(hex || '#C9A84C');
            popover.classList.remove('hidden');

            const anchor = anchorEl || pickerToggle;
            const rect = anchor.getBoundingClientRect();
            const wrapRect = wrap.getBoundingClientRect();
            popover.style.top = (rect.bottom - wrapRect.top + 8) + 'px';
            addBtn.textContent = editingIndex !== null ? 'Update Color' : 'Add Color';
        }

        function closePopover() {
            popover.classList.add('hidden');
            editingIndex = null;
        }

        pickerToggle.addEventListener('click', function () {
            if (!popover.classList.contains('hidden') && editingIndex === null) {
                closePopover();
            } else {
                openPopover(null, hexInput.value || '#C9A84C', pickerToggle);
            }
        });

        if (nativePicker) {
            nativePicker.addEventListener('input', function () {
                setRgbFields(nativePicker.value.toUpperCase());
            });
        }

        [rInput, gInput, bInput].forEach(function (input) {
            input.addEventListener('input', function () {
                const hex = rgbToHex(rInput.value, gInput.value, bInput.value);
                hexInput.value = hex;
                popoverPreview.style.background = hex;
                if (nativePicker) nativePicker.value = hex;
            });
        });

        hexInput.addEventListener('input', function () {
            let val = hexInput.value.trim();
            if (!val.startsWith('#')) val = '#' + val;
            if (HEX_RE.test(val)) setRgbFields(val);
        });

        addBtn.addEventListener('click', function () {
            let hex = hexInput.value.trim();
            if (!hex.startsWith('#')) hex = '#' + hex;
            if (!HEX_RE.test(hex)) return;
            hex = hex.toUpperCase();

            if (editingIndex !== null) {
                tags[editingIndex] = hex;
            } else {
                tags.push(hex);
            }
            renderTags();
            closePopover();
            pickerSwatch.style.background = hex;
        });

        cancelBtn.addEventListener('click', closePopover);

        document.addEventListener('click', function (e) {
            if (!popover.classList.contains('hidden') && !popover.contains(e.target) && e.target !== pickerToggle && !pickerToggle.contains(e.target)) {
                closePopover();
            }
        });

        renderTags();
    })();

    document.getElementById('password-form')?.addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        submitAjaxForm(form, function () {
            form.reset();
        });
    });

    document.getElementById('notifications-form')?.addEventListener('submit', function (e) {
        e.preventDefault();
        submitAjaxForm(e.target);
    });

    document.getElementById('logout-other-devices-form')?.addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        submitAjaxForm(form, function () {
            form.querySelector('[name="current_password"]').value = '';
        });
    });

    document.getElementById('closure-form')?.addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        submitAjaxForm(form, function () {
            const submitBtn = form.querySelector('button[type="submit"]');
            const label = submitBtn?.querySelector('.btn-label');
            if (label) label.textContent = 'Request Sent';
            if (submitBtn) {
                submitBtn.dataset.keepDisabled = '1';
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
                submitBtn.style.cursor = 'not-allowed';
            }
        });
    });
})();
</script>

@endsection
