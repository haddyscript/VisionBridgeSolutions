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
                    <input type="password" name="current_password" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                </div>
                <div>
                    <label class="block text-xs font-medium text-navy mb-1">New Password</label>
                    <input type="password" name="password" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                </div>
                <div>
                    <label class="block text-xs font-medium text-navy mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
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
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-navy mb-3">Admins ({{ $admins->count() }})</h3>
            <div class="space-y-2.5">
                @foreach ($admins as $admin)
                    <div class="rounded-lg border border-gray-200">
                        <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-2 px-4 py-3">
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
                                        @if ($admin->isSuperAdmin())
                                            <span class="inline-flex items-center text-xs font-semibold uppercase tracking-wide text-gold-dark bg-gold/15 px-2 py-0.5 rounded-full shrink-0">Super Admin</span>
                                        @elseif ($admin->restricted_access)
                                            <span class="inline-flex items-center text-xs font-semibold uppercase tracking-wide text-navy bg-gray-100 px-2 py-0.5 rounded-full shrink-0">Restricted</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $admin->email }}</p>
                                </div>
                            </div>
                            @if (auth()->user()->isSuperAdmin())
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 justify-end shrink-0 ml-auto">
                                    @if (! $admin->isSuperAdmin())
                                        <button type="button" class="permissions-toggle whitespace-nowrap text-xs font-semibold text-navy hover:text-gold-dark" data-target="permissions-{{ $admin->id }}">
                                            Manage Access
                                        </button>
                                    @endif
                                    @if (! ($admin->isSuperAdmin() && $admin->is(auth()->user())))
                                        <form method="POST" action="{{ route('admin.team.toggle-super-admin', $admin) }}"
                                              onsubmit="return confirm('{{ $admin->isSuperAdmin() ? 'Revoke' : 'Grant' }} super admin access for {{ $admin->name }}?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="whitespace-nowrap text-xs font-semibold text-navy hover:text-gold-dark">
                                                {{ $admin->isSuperAdmin() ? 'Revoke Super Admin' : 'Grant Super Admin' }}
                                            </button>
                                        </form>
                                    @endif
                                    @if (! $admin->is(auth()->user()))
                                        <form method="POST" action="{{ route('admin.team.destroy', $admin) }}" onsubmit="return confirm('Remove this team member?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="whitespace-nowrap text-xs font-semibold text-red-400 hover:text-red-600">Remove</button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>

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

                                    <div id="permissions-fields-{{ $admin->id }}" class="space-y-4 {{ $admin->restricted_access ? '' : 'hidden' }}">
                                        <div class="flex items-center justify-end gap-3 -mb-1">
                                            <button type="button" class="select-all-permissions text-xs font-semibold text-gold-dark hover:underline" data-panel="permissions-fields-{{ $admin->id }}">Select All</button>
                                            <span class="text-gray-300">|</span>
                                            <button type="button" class="select-none-permissions text-xs font-semibold text-gray-400 hover:underline" data-panel="permissions-fields-{{ $admin->id }}">Select None</button>
                                        </div>

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

                                    <div class="flex items-center gap-3 pt-1">
                                        <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                            Save Access
                                        </button>
                                        <button type="button" class="permissions-toggle text-xs font-semibold text-gray-400 hover:text-navy" data-target="permissions-{{ $admin->id }}">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        @endif
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
</script>

@endsection
