@extends('layouts.admin')

@section('title', 'Team Members – Admin')
@section('page-title', 'Team Members')

@section('content')

<div class="max-w-5xl grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

    {{-- My Account --}}
    <div class="space-y-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">My Account</p>
            <p class="text-sm text-gray-500">Manage your own profile and password.</p>
        </div>

        {{-- My profile --}}
        <details class="group bg-white rounded-xl border border-gray-200 p-5">
            <summary class="flex items-center justify-between cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                <h3 class="text-sm font-semibold text-navy">My Profile</h3>
                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </summary>
            <form method="POST" action="{{ route('admin.team.profile.update') }}" class="space-y-4 mt-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-xs font-medium text-navy mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                </div>
                <div>
                    <label class="block text-xs font-medium text-navy mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                </div>
                <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                    Save Profile
                </button>
            </form>
        </details>

        {{-- Change password --}}
        <details class="group bg-white rounded-xl border border-gray-200 p-5">
            <summary class="flex items-center justify-between cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                <h3 class="text-sm font-semibold text-navy">Change Password</h3>
                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </summary>
            <form method="POST" action="{{ route('admin.team.password.update') }}" class="space-y-4 mt-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-xs font-medium text-navy mb-1">Current Password</label>
                    <div class="relative">
                        <input type="password" name="current_password" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        <button type="button" class="password-toggle absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-navy" aria-label="Show password">
                            <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-navy mb-1">New Password</label>
                    <div class="relative">
                        <input type="password" name="password" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        <button type="button" class="password-toggle absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-navy" aria-label="Show password">
                            <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-navy mb-1">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        <button type="button" class="password-toggle absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-navy" aria-label="Show password">
                            <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>
                <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                    Update Password
                </button>
            </form>
        </details>
    </div>

    {{-- Team Management --}}
    @if (auth()->user()->isSuperAdmin() || auth()->user()->canAccessAdminPage('team'))
    <div class="space-y-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Team Management</p>
            <p class="text-sm text-gray-500">
                @if (auth()->user()->isSuperAdmin())
                    Add or remove admin accounts.
                @else
                    Only a super admin can add or remove team members.
                @endif
            </p>
        </div>

        @if (auth()->user()->isSuperAdmin())
            {{-- Add team member --}}
            <details class="group bg-white rounded-xl border border-gray-200 p-5">
                <summary class="flex items-center justify-between cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                    <h3 class="text-sm font-semibold text-navy">Add Team Member</h3>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </summary>
                <form method="POST" action="{{ route('admin.team.store') }}" class="space-y-4 mt-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-navy mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-navy mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-navy mb-1">Job Title</label>
                        <select name="job_title"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                            <option value="">— Select a role —</option>
                            @foreach ($jobTitles as $title)
                                <option value="{{ $title }}" {{ old('job_title') === $title ? 'selected' : '' }}>{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-navy">
                        <input type="checkbox" name="is_super_admin" value="1" {{ old('is_super_admin') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-gold focus:ring-gold">
                        Grant super admin access
                    </label>
                    <p class="text-xs text-gray-400">New members are created with the default password <span class="font-semibold text-navy">admin123</span>.</p>
                    <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                        Add Team Member
                    </button>
                </form>
            </details>
        @endif

        {{-- Existing team members --}}
        <div class="bg-white rounded-xl border border-gray-200 flex flex-col max-h-[36rem]">
            <h3 class="text-sm font-semibold text-navy px-5 pt-5 pb-3 shrink-0">Admins ({{ $admins->count() }})</h3>
            <div class="space-y-2.5 overflow-y-auto px-5 pb-5">
                @foreach ($admins as $admin)
                    <div class="rounded-lg border border-gray-200">
                        <div class="admin-row flex flex-wrap items-center justify-between gap-x-4 gap-y-2 px-4 py-3 cursor-pointer hover:bg-gray-50 transition-colors" data-modal="access-modal-{{ $admin->id }}">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center text-sm font-semibold shrink-0">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="text-sm font-medium text-navy whitespace-nowrap">{{ $admin->name }}</span>
                                        @if ($admin->is(auth()->user()))
                                            <span class="text-xs text-gray-400">(you)</span>
                                        @endif
                                        @if ($admin->isOwner())
                                            <span class="inline-flex items-center text-xs font-semibold uppercase tracking-wide text-white bg-navy px-2 py-0.5 rounded-full shrink-0">Owner</span>
                                        @elseif ($admin->isSuperAdmin())
                                            <span class="inline-flex items-center text-xs font-semibold uppercase tracking-wide text-gold-dark bg-gold/15 px-2 py-0.5 rounded-full shrink-0">Super Admin</span>
                                        @elseif ($admin->restricted_access)
                                            <span class="inline-flex items-center text-xs font-semibold uppercase tracking-wide text-navy bg-gray-100 px-2 py-0.5 rounded-full shrink-0">Restricted</span>
                                        @endif
                                        @if (! $admin->is_active)
                                            <span class="inline-flex items-center text-xs font-semibold uppercase tracking-wide text-red-500 bg-red-50 px-2 py-0.5 rounded-full shrink-0">Inactive</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $admin->email }}</p>
                                    @if ($admin->job_title)
                                        <p class="text-xs font-medium text-gold-dark mt-0.5">{{ $admin->job_title }}</p>
                                    @endif
                                </div>
                            </div>
                            @if (auth()->user()->isSuperAdmin())
                                @php
                                    // "Set Job Title" is always available to a super admin, so the
                                    // actions menu always renders here.
                                    $hasAnyAction = true;
                                @endphp
                                @if ($hasAnyAction)
                                    <div class="relative shrink-0 ml-auto" x-data="{ open: false }">
                                        <button type="button" @click="open = !open" @click.outside="open = false"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-100 transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                                            </svg>
                                        </button>
                                        <div x-show="open" x-transition class="fixed w-52 bg-white rounded-xl border border-gray-200 shadow-lg z-50 py-1">
                                            <button type="button" class="permissions-toggle w-full text-left px-3 py-2 text-xs text-navy hover:bg-gray-50 transition-colors" data-target="job-title-{{ $admin->id }}">
                                                Set Job Title
                                            </button>
                                            @if (! $admin->isSuperAdmin())
                                                <button type="button" class="permissions-toggle w-full text-left px-3 py-2 text-xs text-navy hover:bg-gray-50 transition-colors" data-target="permissions-{{ $admin->id }}">
                                                    Manage Access
                                                </button>
                                            @endif
                                            @if (! $admin->isOwner() && ! ($admin->isSuperAdmin() && $admin->is(auth()->user())))
                                                <form method="POST" action="{{ route('admin.team.toggle-super-admin', $admin) }}"
                                                      onsubmit="return confirm('{{ $admin->isSuperAdmin() ? 'Revoke' : 'Grant' }} super admin access for {{ $admin->name }}?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="w-full text-left px-3 py-2 text-xs text-navy hover:bg-gray-50 transition-colors">
                                                        {{ $admin->isSuperAdmin() ? 'Revoke Super Admin' : 'Grant Super Admin' }}
                                                    </button>
                                                </form>
                                            @endif
                                            @if (auth()->user()->isOwner() && ! $admin->is(auth()->user()) && $admin->is_active)
                                                <form method="POST" action="{{ route('admin.team.impersonate', $admin) }}"
                                                      onsubmit="return confirm('Log in as {{ $admin->name }}? You will see exactly what they see.')">
                                                    @csrf
                                                    <button type="submit" class="w-full text-left px-3 py-2 text-xs text-navy hover:bg-gray-50 transition-colors">
                                                        Log In as Admin
                                                    </button>
                                                </form>
                                            @endif
                                            @if (auth()->user()->isOwner() && ! $admin->is(auth()->user()))
                                                <form method="POST" action="{{ route('admin.team.toggle-active', $admin) }}"
                                                      onsubmit="return confirm('{{ $admin->is_active ? 'Deactivate' : 'Reactivate' }} {{ $admin->name }}? {{ $admin->is_active ? 'They will be logged out and blocked from logging back in until reactivated.' : '' }}')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="w-full text-left px-3 py-2 text-xs {{ $admin->is_active ? 'text-orange-500' : 'text-teal-dark' }} hover:bg-gray-50 transition-colors">
                                                        {{ $admin->is_active ? 'Deactivate' : 'Reactivate' }}
                                                    </button>
                                                </form>
                                            @endif
                                            @if (! $admin->is(auth()->user()) && ! $admin->isOwner())
                                                <div class="border-t border-gray-100 mt-1 pt-1">
                                                    <form method="POST" action="{{ route('admin.team.destroy', $admin) }}" onsubmit="return confirm('Remove this team member?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full text-left px-3 py-2 text-xs text-red-500 hover:bg-red-50 transition-colors">Remove</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>

                        @if (auth()->user()->isSuperAdmin())
                            <div id="job-title-{{ $admin->id }}" class="hidden border-t border-gray-200 bg-gray-50 px-5 py-5">
                                <form method="POST" action="{{ route('admin.team.job-title.update', $admin) }}" class="space-y-3">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="block text-xs font-medium text-navy mb-1">Job Title</label>
                                        <select name="job_title"
                                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
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
                                        <button type="button" class="permissions-toggle text-xs font-semibold text-gray-400 hover:text-navy" data-target="job-title-{{ $admin->id }}">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        @if (auth()->user()->isSuperAdmin() && ! $admin->isSuperAdmin())
                            @php
                                $adminPermissionKeys = $admin->adminPermissions->pluck('permission_key')->all();
                                $permissionGroups = [
                                    'Client Work' => ['clients', 'calendar', 'consultations', 'intake-submissions', 'project-requests', 'recommendations'],
                                    'Billing' => ['payments', 'refund-requests', 'subscriptions', 'partner-payouts', 'care-plan-pricing'],
                                    'Content & Communication' => ['contact-messages', 'service-agreement', 'email-templates', 'satisfaction-surveys', 'announcements'],
                                    'Administration' => ['team'],
                                ];
                            @endphp
                            <div id="permissions-{{ $admin->id }}" class="permissions-panel hidden border-t border-gray-200 bg-gray-50 px-5 py-5">
                                <form method="POST" action="{{ route('admin.team.permissions.update', $admin) }}" class="space-y-4">
                                    @csrf
                                    @method('PATCH')

                                    <label class="flex items-start gap-2.5 bg-white border border-gray-200 rounded-lg px-3.5 py-3 cursor-pointer">
                                        <input type="checkbox" name="restricted_access" value="1" class="restricted-access-checkbox mt-0.5 rounded border-gray-300 text-gold focus:ring-gold focus:ring-offset-0"
                                               {{ $admin->restricted_access ? 'checked' : '' }} data-panel="permissions-fields-{{ $admin->id }}">
                                        <span>
                                            <span class="block text-sm font-semibold text-navy">Restrict this admin's access</span>
                                            <span class="block text-xs text-gray-400 mt-0.5">Off = full access to every section (default). On = only the pages checked below.</span>
                                        </span>
                                    </label>

                                    <div id="permissions-fields-{{ $admin->id }}" class="{{ $admin->restricted_access ? '' : 'hidden' }}">
                                        <div class="flex items-center justify-end gap-3 pb-2">
                                            <button type="button" class="select-all-permissions text-xs font-semibold text-gold-dark hover:underline" data-panel="permissions-fields-{{ $admin->id }}">Select All</button>
                                            <span class="text-gray-300">|</span>
                                            <button type="button" class="select-none-permissions text-xs font-semibold text-gray-400 hover:underline" data-panel="permissions-fields-{{ $admin->id }}">Select None</button>
                                        </div>

                                        <div class="space-y-4 max-h-72 overflow-y-auto pr-1">
                                            @foreach ($permissionGroups as $groupLabel => $groupKeys)
                                                <div>
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">{{ $groupLabel }}</p>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        @foreach ($groupKeys as $key)
                                                            <label class="flex items-center gap-2 text-sm text-navy bg-white border border-gray-200 rounded-lg px-3 py-2 cursor-pointer transition-colors has-[:checked]:border-gold has-[:checked]:bg-gold/5 hover:border-gray-300">
                                                                <input type="checkbox" name="permissions[]" value="{{ $key }}" class="rounded border-gray-300 text-gold focus:ring-gold focus:ring-offset-0"
                                                                       {{ in_array($key, $adminPermissionKeys, true) ? 'checked' : '' }}>
                                                                {{ $sections[$key]['label'] }}
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 pt-1">
                                        <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                            Save Access
                                        </button>
                                        <button type="button" class="permissions-toggle text-xs font-semibold text-gray-400 hover:text-navy" data-target="permissions-{{ $admin->id }}">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Access details modal (read-only) — opens when the row is clicked --}}
                        @php
                            $modalFullAccess = $admin->isSuperAdmin() || ! $admin->restricted_access;
                            $modalAccessKeys = $admin->adminPermissions->pluck('permission_key')->all();
                        @endphp
                        <div id="access-modal-{{ $admin->id }}" class="access-modal hidden fixed inset-0 z-[60] items-center justify-center bg-black/40 px-4">
                            <div class="access-modal-panel bg-white rounded-2xl border border-gray-200 shadow-xl w-full max-w-md max-h-[85vh] overflow-y-auto">
                                <div class="flex items-start justify-between gap-3 p-5 border-b border-gray-100">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center text-sm font-semibold shrink-0">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-1.5">
                                                <span class="text-sm font-semibold text-navy">{{ $admin->name }}</span>
                                                @if ($admin->isOwner())
                                                    <span class="inline-flex items-center text-xs font-semibold uppercase tracking-wide text-white bg-navy px-2 py-0.5 rounded-full">Owner</span>
                                                @elseif ($admin->isSuperAdmin())
                                                    <span class="inline-flex items-center text-xs font-semibold uppercase tracking-wide text-gold-dark bg-gold/15 px-2 py-0.5 rounded-full">Super Admin</span>
                                                @elseif ($admin->restricted_access)
                                                    <span class="inline-flex items-center text-xs font-semibold uppercase tracking-wide text-navy bg-gray-100 px-2 py-0.5 rounded-full">Restricted</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $admin->email }}</p>
                                            @if ($admin->job_title)
                                                <p class="text-xs font-medium text-gold-dark mt-0.5">{{ $admin->job_title }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <button type="button" class="access-modal-close w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-100 transition-colors shrink-0" aria-label="Close">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <div class="p-5">
                                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Admin Page Access</p>
                                    @if ($modalFullAccess)
                                        <div class="flex items-start gap-2 bg-teal-50 border border-teal-100 rounded-lg px-3.5 py-3">
                                            <svg class="w-5 h-5 text-teal-dark shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <p class="text-sm text-navy">Full access to <span class="font-semibold">every</span> admin section.</p>
                                        </div>
                                    @elseif (count($modalAccessKeys) === 0)
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-3">
                                            <p class="text-sm text-gray-500">No sections enabled — this member currently has no admin page access.</p>
                                        </div>
                                    @endif
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-3">
                                        @foreach ($sections as $key => $section)
                                            @php $canAccess = $modalFullAccess || in_array($key, $modalAccessKeys, true); @endphp
                                            <div class="flex items-center gap-2 text-sm rounded-lg border px-3 py-2 {{ $canAccess ? 'border-gold/40 bg-gold/5 text-navy' : 'border-gray-200 bg-white text-gray-400' }}">
                                                @if ($canAccess)
                                                    <svg class="w-4 h-4 text-gold-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                @endif
                                                {{ $section['label'] }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Team Management</p>
        <p class="text-sm text-gray-500">You don't have access to this section.</p>
    </div>
    @endif

</div>

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
        // Backdrop click (outside the panel) closes.
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
</script>

@endsection
