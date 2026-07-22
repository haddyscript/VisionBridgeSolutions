@extends('layouts.admin')

@section('title', 'Team Members – Admin')
@section('page-title', 'Team Members')

@section('content')

@php
    $me = auth()->user();
    // Debug account excluded everywhere on this page, same as the existing
    // "Admins (N)" heading below already did — kept consistent for the new
    // KPI counts too.
    $visibleAdmins = $admins->reject(fn ($a) => $a->email === 'debug@visionbridgesolutions.com');
    $teamMemberCount = $visibleAdmins->count();
    $ownerCount = $visibleAdmins->filter(fn ($a) => $a->isOwner())->count();
    $restrictedCount = $visibleAdmins->filter(fn ($a) => ! $a->isOwner() && ! $a->isSuperAdmin() && $a->restricted_access)->count();
    // "Administrators" = everyone with full/elevated access (owner + super
    // admins + any non-restricted standard admin) — the complement of
    // Restricted, not a duplicate of Team Members.
    $administratorCount = $teamMemberCount - $restrictedCount;

    $jobTitleColors = [
        'Developer' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
        'Customer Support Representative' => 'bg-teal/10 text-teal-dark',
        'Sales Representative' => 'bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400',
        'Project Manager' => 'bg-gold/15 text-gold-dark',
        'Administrative Staff' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
    ];
    $roleBadge = function (\App\Models\User $a) {
        if ($a->isOwner()) return ['label' => 'Owner', 'class' => 'text-white bg-navy', 'icon' => 'crown'];
        if ($a->isSuperAdmin()) return ['label' => 'Super Admin', 'class' => 'text-gold-dark bg-gold/15', 'icon' => 'shield'];
        if ($a->restricted_access) return ['label' => 'Restricted', 'class' => 'text-navy dark:text-white bg-gray-100 dark:bg-gray-700', 'icon' => 'lock'];
        return null;
    };
    $roleIcon = function (string $name) {
        return match ($name) {
            'crown' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l3 3 3-6 3 6 3-6 3 6 3-3-2 10H5L3 8z"/>',
            'shield' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
            'lock' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v2"/>',
            default => '',
        };
    };
@endphp

{{-- SECTION 1 — hero subtitle (page-title itself is owned by layouts.admin,
     shared across every admin page, left untouched). --}}
<p class="text-sm text-gray-500 dark:text-gray-400 max-w-2xl leading-relaxed mb-8">
    Manage administrator accounts, permissions, and platform access for your organization.
</p>

{{-- SECTION 2 — KPI cards. Safe to use a transform-based hover lift here —
     nothing inside these cards uses position:fixed. --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="kpi-card bg-white/95 dark:bg-navy/95 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 px-5 py-5 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-10 h-10 rounded-xl bg-navy/5 dark:bg-white/10 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-navy dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8"/></svg>
        </div>
        <p class="text-2xl font-bold text-navy dark:text-white">{{ $teamMemberCount }}</p>
        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-0.5">Team Members</p>
        <p class="text-[0.7rem] text-gray-500 dark:text-gray-400 mt-1">Total Members</p>
    </div>

    <div class="kpi-card bg-white/95 dark:bg-navy/95 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 px-5 py-5 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <p class="text-2xl font-bold text-navy dark:text-white">{{ $administratorCount }}</p>
        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-0.5">Administrators</p>
        <p class="text-[0.7rem] text-gray-500 dark:text-gray-400 mt-1">Full Access Accounts</p>
    </div>

    <div class="kpi-card bg-white/95 dark:bg-navy/95 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 px-5 py-5 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v2"/></svg>
        </div>
        <p class="text-2xl font-bold text-navy dark:text-white">{{ $restrictedCount }}</p>
        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-0.5">Restricted Accounts</p>
        <p class="text-[0.7rem] text-gray-500 dark:text-gray-400 mt-1">Limited Access</p>
    </div>

    <div class="kpi-card bg-white/95 dark:bg-navy/95 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 px-5 py-5 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-10 h-10 rounded-xl bg-gold/10 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l3 3 3-6 3 6 3-6 3 6 3-3-2 10H5L3 8z"/></svg>
        </div>
        <p class="text-2xl font-bold text-navy dark:text-white">{{ $ownerCount }}</p>
        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-0.5">Owners</p>
        <p class="text-[0.7rem] text-gray-500 dark:text-gray-400 mt-1">Full Access Accounts</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

    {{-- ═══════════════════════════════════════════════════════════════════
         SECTION 3 — My Account
         ═══════════════════════════════════════════════════════════════════ --}}
    <div class="space-y-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">My Account</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage your own profile and password.</p>
        </div>

        {{-- Profile summary card --}}
        <div class="bg-white/95 dark:bg-navy/95 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm px-6 py-6 flex items-center gap-4">
            <span class="w-16 h-16 rounded-2xl bg-gradient-to-br from-navy to-navy/75 text-gold text-xl font-bold flex items-center justify-center shrink-0 ring-2 ring-gold/30 shadow-sm">
                {{ strtoupper(substr($me->name, 0, 1)) }}
            </span>
            <div class="min-w-0">
                <p class="text-lg font-semibold text-navy dark:text-white truncate">{{ $me->name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $me->email }}</p>
                <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                    @php $myBadge = $roleBadge($me); @endphp
                    @if ($myBadge)
                        <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $myBadge['class'] }}">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $roleIcon($myBadge['icon']) !!}</svg>
                            {{ $myBadge['label'] }}
                        </span>
                    @endif
                    @if ($me->job_title)
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $jobTitleColors[$me->job_title] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">{{ $me->job_title }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick Actions — Edit Profile and Change Password are real,
             fully-functional forms (same routes/fields as before, just
             restyled as expandable "quick action" rows). Two-Factor
             Authentication and Activity Logs are explicitly not built yet
             — shown as disabled "Coming Soon" rows rather than silently
             omitted or faked as working. --}}
        <div class="bg-white/95 dark:bg-navy/95 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm divide-y divide-gray-100 dark:divide-gray-700 overflow-hidden">
            <p class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400 px-5 pt-4 pb-2">Quick Actions</p>

            <details class="quick-action-details group">
                <summary class="quick-action-row flex items-center gap-3.5 px-5 py-4 cursor-pointer list-none [&::-webkit-details-marker]:hidden hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                    <span class="w-9 h-9 rounded-xl bg-gold/10 text-gold-dark flex items-center justify-center shrink-0">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block text-sm font-semibold text-navy dark:text-white">Edit Profile</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5">Update your name and email address.</span>
                    </span>
                    <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200 group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </summary>
                <form method="POST" action="{{ route('admin.team.profile.update') }}" class="space-y-4 px-5 pb-5 pt-1">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-medium text-navy dark:text-white mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $me->name) }}" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-navy-dark dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-navy dark:text-white mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $me->email) }}" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-navy-dark dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-navy dark:text-white mb-1">Recovery/Notification Email <span class="text-gray-400 font-normal">(optional)</span></label>
                        <input type="email" name="notification_email" value="{{ old('notification_email', $me->notification_email) }}" placeholder="Leave blank to use the email above"
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-navy-dark dark:text-white dark:placeholder-gray-500 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        <p class="text-xs text-gray-400 mt-1">Where password-reset and account-recovery emails actually get delivered — set this if the email above isn't a real inbox you check.</p>
                    </div>
                    <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
                        Save Profile
                    </button>
                </form>
            </details>

            <details class="quick-action-details group">
                <summary class="quick-action-row flex items-center gap-3.5 px-5 py-4 cursor-pointer list-none [&::-webkit-details-marker]:hidden hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                    <span class="w-9 h-9 rounded-xl bg-gold/10 text-gold-dark flex items-center justify-center shrink-0">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v2"/></svg>
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block text-sm font-semibold text-navy dark:text-white">Change Password</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5">Update your account password.</span>
                    </span>
                    <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200 group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </summary>
                <form method="POST" action="{{ route('admin.team.password.update') }}" class="space-y-4 px-5 pb-5 pt-1">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-medium text-navy dark:text-white mb-1">Current Password</label>
                        <div class="relative">
                            <input type="password" name="current_password" required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-navy-dark dark:text-white px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                            <button type="button" class="password-toggle absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-navy dark:hover:text-white" aria-label="Show password">
                                <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-navy dark:text-white mb-1">New Password</label>
                        <div class="relative">
                            <input type="password" name="password" required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-navy-dark dark:text-white px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                            <button type="button" class="password-toggle absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-navy dark:hover:text-white" aria-label="Show password">
                                <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-navy dark:text-white mb-1">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-navy-dark dark:text-white px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                            <button type="button" class="password-toggle absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-navy dark:hover:text-white" aria-label="Show password">
                                <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
                        Update Password
                    </button>
                </form>
            </details>

            <a href="{{ route('admin.two-factor.show') }}"
               class="flex items-center gap-3.5 px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                <span class="w-9 h-9 rounded-xl {{ $me->hasTwoFactorEnabled() ? 'bg-teal/10 text-teal-dark' : 'bg-gold/10 text-gold-dark' }} flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block text-sm font-semibold text-navy dark:text-white">Two-Factor Authentication</span>
                    <span class="block text-xs text-gray-400 dark:text-gray-500 mt-0.5">Add an extra layer of login security.</span>
                </span>
                @if ($me->hasTwoFactorEnabled())
                    <span class="text-[0.65rem] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full bg-teal/10 text-teal-dark shrink-0">Enabled</span>
                @endif
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <div class="flex items-center gap-3.5 px-5 py-4 opacity-60 cursor-not-allowed">
                <span class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center shrink-0">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block text-sm font-semibold text-gray-500 dark:text-gray-400">Activity Logs</span>
                    <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5">A history of actions taken on your account.</span>
                </span>
                <span class="text-[0.65rem] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 shrink-0">Coming Soon</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         SECTION 4-11 — Team Management
         ═══════════════════════════════════════════════════════════════════ --}}
    @if ($me->isSuperAdmin() || $me->canAccessAdminPage('team'))
    <div class="space-y-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Team Management</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                @if ($me->isSuperAdmin())
                    Add or remove admin accounts.
                @else
                    Only a super admin can add or remove team members.
                @endif
            </p>
        </div>

        @if ($me->isSuperAdmin())
            {{-- Add Team Member — premium action card --}}
            <details class="group bg-white/95 dark:bg-navy/95 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
                <summary class="flex items-center gap-4 px-6 py-5 cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                    <span class="w-11 h-11 rounded-xl bg-gold/15 text-gold-dark flex items-center justify-center shrink-0">
                        <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block text-sm font-bold text-navy dark:text-white">Add Team Member</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5">Invite a new administrator to the platform.</span>
                    </span>
                    <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200 group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </summary>
                <form method="POST" action="{{ route('admin.team.store') }}" class="space-y-4 px-6 pb-6 pt-1 border-t border-gray-100 dark:border-gray-700">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-navy dark:text-white mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-navy-dark dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-navy dark:text-white mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-navy-dark dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-navy dark:text-white mb-1">Job Title</label>
                        <select name="job_title"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-navy-dark dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                            <option value="">— Select a role —</option>
                            @foreach ($jobTitles as $title)
                                <option value="{{ $title }}" {{ old('job_title') === $title ? 'selected' : '' }}>{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-navy dark:text-white">
                        <input type="checkbox" name="is_super_admin" value="1" {{ old('is_super_admin') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-gold focus:ring-gold">
                        Grant super admin access
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400">New members are created with the default password <span class="font-semibold text-navy dark:text-white">admin123</span>.</p>
                    <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
                        Add Team Member
                    </button>
                </form>
            </details>
        @endif

        {{-- SECTION 8-9 — search + filters. Client-side only (filters the
             already-rendered list below via data attributes) — there's no
             backend filter route to wire this to, and a non-functional
             search box would be worse UX than none at all. --}}
        @php
            $jobTitlesInUse = $visibleAdmins->pluck('job_title')->filter()->unique()->values();
        @endphp
        <div class="bg-white/90 dark:bg-navy/90 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-3">
            <div class="relative mb-3">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="team-search" placeholder="Search team members…"
                       class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-navy-dark dark:text-white pl-10 pr-4 py-2.5 text-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold transition-shadow">
            </div>
            <div class="flex flex-wrap gap-1.5">
                <button type="button" class="team-filter-chip is-active px-3 py-1.5 rounded-full text-xs font-semibold border border-gray-300 dark:border-gray-600 text-navy dark:text-white transition-colors" data-role="all">All Members</button>
                <button type="button" class="team-filter-chip px-3 py-1.5 rounded-full text-xs font-semibold border border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 transition-colors" data-role="owner">Owners</button>
                <button type="button" class="team-filter-chip px-3 py-1.5 rounded-full text-xs font-semibold border border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 transition-colors" data-role="super_admin">Super Admins</button>
                <button type="button" class="team-filter-chip px-3 py-1.5 rounded-full text-xs font-semibold border border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 transition-colors" data-role="restricted">Restricted</button>
                @foreach ($jobTitlesInUse as $title)
                    <button type="button" class="team-filter-chip px-3 py-1.5 rounded-full text-xs font-semibold border border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 transition-colors" data-role="job:{{ $title }}">{{ $title }}</button>
                @endforeach
            </div>
        </div>

        {{-- Existing team members --}}
        {{-- No backdrop-blur-sm here, deliberately — this card wraps every
             .access-modal (and the three-dot menu) below, and
             backdrop-filter is, per spec, in the same category as
             transform/filter/opacity<1/will-change: any ancestor with it
             becomes the containing block for position:fixed descendants
             instead of the viewport. That's what was pinning the modal
             inside this card's box rather than centering it on the page. --}}
        <div class="bg-white/95 dark:bg-navy/95 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col max-h-[42rem]">
            <h3 class="text-sm font-semibold text-navy dark:text-white px-5 pt-5 pb-3 shrink-0">Admins ({{ max(0, $admins->count() - 1) }})</h3>
            <div id="team-list" class="space-y-3 overflow-y-auto px-5 pb-5">
                @foreach ($admins as $admin)
                @if ($admin->email !== "debug@visionbridgesolutions.com")
                    @php
                        $badge = $roleBadge($admin);
                        $roleFilterKey = $admin->isOwner() ? 'owner' : ($admin->isSuperAdmin() ? 'super_admin' : ($admin->restricted_access ? 'restricted' : 'standard'));
                    @endphp
                    {{-- SECTION 5/9/13 — member card. Intentionally NO
                         transform on this wrapper (no hover:-translate-y) —
                         the three-dot menu below is position:fixed,
                         JS-computed from getBoundingClientRect(), and any
                         transform on an ancestor becomes its containing
                         block, breaking those coordinates. Shadow/border
                         hover only; see the Developers-page fix for why. --}}
                    <div class="team-member-card rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-gold/40 dark:hover:border-gold/30 shadow-sm hover:shadow-md transition-shadow duration-300"
                         data-search="{{ strtolower($admin->name.' '.$admin->email) }}"
                         data-role="{{ $roleFilterKey }}"
                         data-job-title="{{ $admin->job_title }}">
                        <div class="admin-row flex flex-wrap items-center justify-between gap-x-4 gap-y-2 px-5 py-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors rounded-2xl" data-modal="access-modal-{{ $admin->id }}">
                            <div class="flex items-center gap-3.5 min-w-0">
                                {{-- SECTION 7 — avatar --}}
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-gold/25 to-gold/10 text-gold-dark flex items-center justify-center text-base font-bold shrink-0 ring-2 ring-gold/25">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="text-base font-semibold text-navy dark:text-white whitespace-nowrap">{{ $admin->name }}</span>
                                        @if ($admin->is($me))
                                            <span class="text-xs text-gray-500 dark:text-gray-400">(you)</span>
                                        @endif
                                        {{-- SECTION 6 — role badge with icon --}}
                                        @if ($badge)
                                            <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wide px-2.5 py-0.5 rounded-full shrink-0 {{ $badge['class'] }}">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $roleIcon($badge['icon']) !!}</svg>
                                                {{ $badge['label'] }}
                                            </span>
                                        @endif
                                        @if (! $admin->is_active)
                                            <span class="inline-flex items-center text-xs font-semibold uppercase tracking-wide text-red-500 bg-red-50 dark:bg-red-500/10 px-2.5 py-0.5 rounded-full shrink-0">Inactive</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $admin->email }}</p>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5">
                                        @if ($admin->job_title)
                                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $jobTitleColors[$admin->job_title] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">{{ $admin->job_title }}</span>
                                        @endif
                                        {{-- SECTION 11 — real data, not placeholders: both
                                             last_login_at and created_at are genuine
                                             columns already populated on every user. --}}
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            Last active: {{ $admin->last_login_at?->diffForHumans() ?? 'Never' }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            Joined {{ $admin->created_at->format('M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @if ($me->isSuperAdmin())
                                <div class="relative shrink-0 ml-auto" x-data="{ open: false }">
                                    <button type="button" @click="open = !open" @click.outside="open = false"
                                            class="w-9 h-9 rounded-full flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-navy dark:hover:text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gold">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" x-transition class="fixed w-52 bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg z-50 py-1">
                                        <button type="button" class="permissions-toggle w-full text-left px-3 py-2 text-xs text-navy dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" data-target="edit-name-{{ $admin->id }}">
                                            Edit Name
                                        </button>
                                        <button type="button" class="permissions-toggle w-full text-left px-3 py-2 text-xs text-navy dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" data-target="job-title-{{ $admin->id }}">
                                            Set Job Title
                                        </button>
                                        @if (! $admin->isOwner() && ! ($admin->isSuperAdmin() && $admin->is($me)))
                                            <form method="POST" action="{{ route('admin.team.toggle-super-admin', $admin) }}"
                                                  onsubmit="return confirm('{{ $admin->isSuperAdmin() ? 'Revoke' : 'Grant' }} super admin access for {{ $admin->name }}?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full text-left px-3 py-2 text-xs text-navy dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                    {{ $admin->isSuperAdmin() ? 'Revoke Super Admin' : 'Grant Super Admin' }}
                                                </button>
                                            </form>
                                        @endif
                                        @if ($me->isOwner() && ! $admin->is($me) && $admin->is_active)
                                            <form method="POST" action="{{ route('admin.team.impersonate', $admin) }}"
                                                  onsubmit="return confirm('Log in as {{ $admin->name }}? You will see exactly what they see.')">
                                                @csrf
                                                <button type="submit" class="w-full text-left px-3 py-2 text-xs text-navy dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                    Log In as Admin
                                                </button>
                                            </form>
                                        @endif
                                        @if ($me->isOwner() && ! $admin->is($me))
                                            <form method="POST" action="{{ route('admin.team.toggle-active', $admin) }}"
                                                  onsubmit="return confirm('{{ $admin->is_active ? 'Deactivate' : 'Reactivate' }} {{ $admin->name }}? {{ $admin->is_active ? 'They will be logged out and blocked from logging back in until reactivated.' : '' }}')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full text-left px-3 py-2 text-xs {{ $admin->is_active ? 'text-orange-500' : 'text-teal-dark' }} hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                    {{ $admin->is_active ? 'Deactivate' : 'Reactivate' }}
                                                </button>
                                            </form>
                                        @endif
                                        @if (! $admin->is($me) && ! $admin->isOwner())
                                            <div class="border-t border-gray-100 dark:border-gray-700 mt-1 pt-1">
                                                <form method="POST" action="{{ route('admin.team.destroy', $admin) }}" onsubmit="return confirm('Remove this team member?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full text-left px-3 py-2 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">Remove</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($me->isSuperAdmin())
                            <div id="edit-name-{{ $admin->id }}" class="hidden border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-navy-dark/40 px-5 py-5">
                                <form method="POST" action="{{ route('admin.team.name.update', $admin) }}" class="space-y-3">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="block text-xs font-medium text-navy dark:text-white mb-1">Full Name</label>
                                        <input type="text" name="name" value="{{ $admin->name }}" required
                                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-navy-dark dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                            Save Name
                                        </button>
                                        <button type="button" class="permissions-toggle text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white" data-target="edit-name-{{ $admin->id }}">Cancel</button>
                                    </div>
                                </form>
                            </div>
                            <div id="job-title-{{ $admin->id }}" class="hidden border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-navy-dark/40 px-5 py-5">
                                <form method="POST" action="{{ route('admin.team.job-title.update', $admin) }}" class="space-y-3">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="block text-xs font-medium text-navy dark:text-white mb-1">Job Title</label>
                                        <select name="job_title"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-navy-dark dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                                            <option value="">— No role —</option>
                                            @foreach ($jobTitles as $title)
                                                <option value="{{ $title }}" {{ $admin->job_title === $title ? 'selected' : '' }}>{{ $title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                            Save Job Title
                                        </button>
                                        <button type="button" class="permissions-toggle text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white" data-target="job-title-{{ $admin->id }}">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Access modal — unchanged logic/fields, only restyled --}}
                        @php
                            $modalFullAccess = $admin->isSuperAdmin() || ! $admin->restricted_access;
                            $modalAccessKeys = $admin->adminPermissions->pluck('permission_key')->all();
                            $canEditAccess = $me->isSuperAdmin() && ! $admin->isSuperAdmin();
                        @endphp
                        <div id="access-modal-{{ $admin->id }}" class="access-modal hidden fixed inset-0 z-[60] items-center justify-center bg-black/40 backdrop-blur-sm px-4">
                            <div class="access-modal-panel bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 shadow-2xl w-full max-w-md max-h-[85vh] overflow-y-auto">
                                <div class="flex items-start justify-between gap-3 p-5 border-b border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-gold/25 to-gold/10 text-gold-dark flex items-center justify-center text-sm font-bold shrink-0 ring-2 ring-gold/25">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-1.5">
                                                <span class="text-sm font-semibold text-navy dark:text-white">{{ $admin->name }}</span>
                                                @if ($badge)
                                                    <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">{{ $admin->email }}</p>
                                            @if ($admin->job_title)
                                                <p class="text-xs font-medium text-gold-dark mt-0.5">{{ $admin->job_title }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <button type="button" class="access-modal-close w-8 h-8 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors shrink-0" aria-label="Close">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <div class="p-5">
                                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-3">Admin Page Access</p>

                                    @if ($canEditAccess)
                                        <form method="POST" action="{{ route('admin.team.permissions.update', $admin) }}" class="space-y-4">
                                            @csrf
                                            @method('PATCH')

                                            <label class="flex items-start gap-2.5 bg-gray-50 dark:bg-navy-dark/40 border border-gray-200 dark:border-gray-700 rounded-lg px-3.5 py-3 cursor-pointer">
                                                <input type="checkbox" name="restricted_access" value="1" class="restricted-access-checkbox mt-0.5 rounded border-gray-300 text-gold focus:ring-gold focus:ring-offset-0"
                                                       {{ $admin->restricted_access ? 'checked' : '' }} data-panel="modal-permissions-fields-{{ $admin->id }}">
                                                <span>
                                                    <span class="block text-sm font-semibold text-navy dark:text-white">Restrict this admin's access</span>
                                                    <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5">Off = full access to every section (default). On = only the pages checked below.</span>
                                                </span>
                                            </label>

                                            <div id="modal-permissions-fields-{{ $admin->id }}" class="{{ $admin->restricted_access ? '' : 'hidden' }} space-y-3">
                                                <div class="flex items-center justify-end gap-3">
                                                    <button type="button" class="select-all-permissions text-xs font-semibold text-gold-dark hover:underline" data-panel="modal-permissions-fields-{{ $admin->id }}">Select All</button>
                                                    <span class="text-gray-300 dark:text-gray-600">|</span>
                                                    <button type="button" class="select-none-permissions text-xs font-semibold text-gray-500 dark:text-gray-400 hover:underline" data-panel="modal-permissions-fields-{{ $admin->id }}">Select None</button>
                                                </div>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                    @foreach ($sections as $key => $section)
                                                        <label class="flex items-center gap-2 text-sm text-navy dark:text-white bg-white dark:bg-navy-dark border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 cursor-pointer transition-colors has-[:checked]:border-gold has-[:checked]:bg-gold/5 hover:border-gray-300 dark:hover:border-gray-600">
                                                            <input type="checkbox" name="permissions[]" value="{{ $key }}" class="rounded border-gray-300 text-gold focus:ring-gold focus:ring-offset-0"
                                                                   {{ in_array($key, $modalAccessKeys, true) ? 'checked' : '' }}>
                                                            {{ $section['label'] }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-3 pt-1">
                                                <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">Save Access</button>
                                                <button type="button" class="access-modal-close text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white">Cancel</button>
                                            </div>
                                        </form>
                                    @else
                                        @if ($modalFullAccess)
                                            <div class="flex items-start gap-2 bg-teal-50 dark:bg-teal/10 border border-teal-100 dark:border-teal/20 rounded-lg px-3.5 py-3">
                                                <svg class="w-5 h-5 text-teal-dark shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                <p class="text-sm text-navy dark:text-white">Full access to <span class="font-semibold">every</span> admin section.</p>
                                            </div>
                                        @elseif (count($modalAccessKeys) === 0)
                                            <div class="bg-gray-50 dark:bg-navy-dark/40 border border-gray-200 dark:border-gray-700 rounded-lg px-3.5 py-3">
                                                <p class="text-sm text-gray-500 dark:text-gray-400">No sections enabled — this member currently has no admin page access.</p>
                                            </div>
                                        @endif
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-3">
                                            @foreach ($sections as $key => $section)
                                                @php $canAccess = $modalFullAccess || in_array($key, $modalAccessKeys, true); @endphp
                                                <div class="flex items-center gap-2 text-sm rounded-lg border px-3 py-2 {{ $canAccess ? 'border-gold/40 bg-gold/5 text-navy dark:text-white' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-navy-dark text-gray-500 dark:text-gray-400' }}">
                                                    @if ($canAccess)
                                                        <svg class="w-4 h-4 text-gold-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    @endif
                                                    {{ $section['label'] }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @endforeach
            </div>
            <p id="team-empty-state" class="hidden text-sm text-gray-500 dark:text-gray-400 text-center py-8 px-5">No team members match your search or filter.</p>
        </div>
    </div>
    @else
    <div class="bg-white/95 dark:bg-navy/95 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-1">Team Management</p>
        <p class="text-sm text-gray-500 dark:text-gray-400">You don't have access to this section.</p>
    </div>
    @endif

</div>

<style>
    @keyframes team-card-fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    /* Only .kpi-card gets the transform-based entrance — .team-member-card
       deliberately excluded (see the comment on it above) since a lingering
       transform:translateY(0) from animation-fill-mode:both would break the
       three-dot menu's position:fixed coordinates just like a hover
       transform would. */
    .kpi-card {
        animation: team-card-fade-in 0.45s ease-out both;
    }
    .kpi-card:nth-child(1) { animation-delay: 0s; }
    .kpi-card:nth-child(2) { animation-delay: 0.05s; }
    .kpi-card:nth-child(3) { animation-delay: 0.1s; }
    .kpi-card:nth-child(4) { animation-delay: 0.15s; }
    /* No entrance animation on .team-member-card, deliberately — even an
       opacity-only CSS `animation` makes an element a containing block for
       position:fixed descendants while animation-fill-mode:both holds it
       applied, which breaks both the access modal and the three-dot menu
       nested inside these cards (both rely on real position:fixed,
       viewport-relative coordinates). This isn't just a transform problem —
       opacity/filter/perspective/will-change all trigger the same
       containing-block behavior per spec. Correctness over a decorative
       fade here, same as the missing hover-lift above. */
    .team-filter-chip.is-active {
        background-color: rgb(212 175 55 / 0.15);
        border-color: rgb(212 175 55 / 0.5) !important;
        color: #8a6d1f;
    }
    @media (prefers-reduced-motion: reduce) {
        .kpi-card, .team-member-card { animation: none; }
    }
</style>

<script>
    // Open the access modal when an admin row is clicked — but ignore clicks
    // that land on the three-dot actions menu (or anything inside it).
    document.querySelectorAll('.admin-row').forEach((row) => {
        row.addEventListener('click', (e) => {
            if (e.target.closest('[x-data]')) return;
            const modal = document.getElementById(row.dataset.modal);
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    function closeAccessModal(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.querySelectorAll('.access-modal').forEach((modal) => {
        modal.addEventListener('click', (e) => {
            if (!e.target.closest('.access-modal-panel')) closeAccessModal(modal);
        });
        modal.querySelectorAll('.access-modal-close').forEach((btn) => {
            btn.addEventListener('click', () => closeAccessModal(modal));
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('.access-modal:not(.hidden)').forEach(closeAccessModal);
    });

    document.querySelectorAll('.password-toggle').forEach((btn) => {
        btn.addEventListener('click', () => {
            const input = btn.closest('.relative')?.querySelector('input');
            if (!input) return;
            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            btn.querySelector('.eye-open')?.classList.toggle('hidden', !showing);
            btn.querySelector('.eye-closed')?.classList.toggle('hidden', showing);
            btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
        });
    });

    document.querySelectorAll('.permissions-toggle').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.getElementById(btn.dataset.target)?.classList.toggle('hidden');
        });
    });

    document.querySelectorAll('.restricted-access-checkbox').forEach((checkbox) => {
        checkbox.addEventListener('change', () => {
            document.getElementById(checkbox.dataset.panel)?.classList.toggle('hidden', !checkbox.checked);
        });
    });

    document.querySelectorAll('.select-all-permissions, .select-none-permissions').forEach((btn) => {
        btn.addEventListener('click', () => {
            const panel = document.getElementById(btn.dataset.panel);
            const checked = btn.classList.contains('select-all-permissions');
            panel?.querySelectorAll('input[name="permissions[]"]').forEach((box) => { box.checked = checked; });
        });
    });

    // Lightweight Alpine-style toggle without requiring Alpine.js (same
    // pattern as admin/clients/index.blade.php). Uses `position: fixed`
    // computed via getBoundingClientRect() rather than CSS `absolute` —
    // the Admins list has overflow-y-auto for its scroll/rounded corners,
    // which clips anything `absolute`-positioned near the bottom of that box.
    document.querySelectorAll('[x-data]').forEach(function (el) {
        let open = false;
        const btn = el.querySelector('button');
        const menu = el.querySelector('[x-show]');

        if (!btn || !menu) return;
        menu.style.display = 'none';

        function positionMenu() {
            const rect = btn.getBoundingClientRect();
            menu.style.top = (rect.bottom + 4) + 'px';
            menu.style.right = (window.innerWidth - rect.right) + 'px';
        }

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            open = !open;
            if (open) positionMenu();
            menu.style.display = open ? 'block' : 'none';
        });

        document.addEventListener('click', function () {
            open = false;
            menu.style.display = 'none';
        });

        window.addEventListener('scroll', function () {
            if (open) positionMenu();
        }, true);

        window.addEventListener('resize', function () {
            if (open) positionMenu();
        });
    });

    // SECTION 8-9 — client-side search + role/job-title filter over the
    // already-rendered list (no backend filter route exists for this).
    (function () {
        const searchInput = document.getElementById('team-search');
        const chips = document.querySelectorAll('.team-filter-chip');
        const cards = document.querySelectorAll('.team-member-card');
        const emptyState = document.getElementById('team-empty-state');
        let activeRole = 'all';

        function applyFilters() {
            const query = (searchInput?.value || '').trim().toLowerCase();
            let visibleCount = 0;

            cards.forEach((card) => {
                const matchesSearch = !query || card.dataset.search.includes(query);
                const matchesRole = activeRole === 'all'
                    || (activeRole.startsWith('job:') ? card.dataset.jobTitle === activeRole.slice(4) : card.dataset.role === activeRole);
                const visible = matchesSearch && matchesRole;
                card.classList.toggle('hidden', !visible);
                if (visible) visibleCount++;
            });

            if (emptyState) emptyState.classList.toggle('hidden', visibleCount !== 0);
        }

        searchInput?.addEventListener('input', applyFilters);

        chips.forEach((chip) => {
            chip.addEventListener('click', () => {
                chips.forEach((c) => c.classList.remove('is-active'));
                chip.classList.add('is-active');
                activeRole = chip.dataset.role;
                applyFilters();
            });
        });
    })();
</script>

@endsection
