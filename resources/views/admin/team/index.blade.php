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
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-navy mb-3">My Profile</h3>
            <form method="POST" action="{{ route('admin.team.profile.update') }}" class="space-y-4">
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
        </div>

        {{-- Change password --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-navy mb-3">Change Password</h3>
            <form method="POST" action="{{ route('admin.team.password.update') }}" class="space-y-4">
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
        </div>
    </div>

    {{-- Team Management --}}
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
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-navy mb-3">Add Team Member</h3>
                <form method="POST" action="{{ route('admin.team.store') }}" class="space-y-4">
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
            </div>
        @endif

        {{-- Existing team members --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-navy mb-3">Admins ({{ $admins->count() }})</h3>
            <div class="space-y-2.5">
                @foreach ($admins as $admin)
                    <div class="rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between gap-4 px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center text-sm font-semibold shrink-0">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-navy">
                                        {{ $admin->name }}
                                        @if ($admin->is(auth()->user()))
                                            <span class="text-xs text-gray-400">(you)</span>
                                        @endif
                                        @if ($admin->isSuperAdmin())
                                            <span class="inline-flex items-center text-xs font-semibold uppercase tracking-wide text-gold-dark bg-gold/15 px-2 py-0.5 rounded-full ml-1">Super Admin</span>
                                        @elseif ($admin->restricted_access)
                                            <span class="inline-flex items-center text-xs font-semibold uppercase tracking-wide text-navy bg-gray-100 px-2 py-0.5 rounded-full ml-1">Restricted</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $admin->email }}</p>
                                </div>
                            </div>
                            @if (auth()->user()->isSuperAdmin())
                                <div class="flex items-center gap-3 shrink-0">
                                    @if (! $admin->isSuperAdmin())
                                        <button type="button" class="permissions-toggle text-xs font-semibold text-navy hover:text-gold-dark" data-target="permissions-{{ $admin->id }}">
                                            Manage Access
                                        </button>
                                    @endif
                                    @if (! ($admin->isSuperAdmin() && $admin->is(auth()->user())))
                                        <form method="POST" action="{{ route('admin.team.toggle-super-admin', $admin) }}"
                                              onsubmit="return confirm('{{ $admin->isSuperAdmin() ? 'Revoke' : 'Grant' }} super admin access for {{ $admin->name }}?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs font-semibold text-navy hover:text-gold-dark">
                                                {{ $admin->isSuperAdmin() ? 'Revoke Super Admin' : 'Grant Super Admin' }}
                                            </button>
                                        </form>
                                    @endif
                                    @if (! $admin->is(auth()->user()))
                                        <form method="POST" action="{{ route('admin.team.destroy', $admin) }}" onsubmit="return confirm('Remove this team member?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold text-red-400 hover:text-red-600">Remove</button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>

                        @if (auth()->user()->isSuperAdmin() && ! $admin->isSuperAdmin())
                            @php $adminPermissionKeys = $admin->adminPermissions->pluck('permission_key')->all(); @endphp
                            <div id="permissions-{{ $admin->id }}" class="permissions-panel hidden border-t border-gray-200 bg-gray-50 px-4 py-4">
                                <form method="POST" action="{{ route('admin.team.permissions.update', $admin) }}" class="space-y-3">
                                    @csrf
                                    @method('PATCH')
                                    <label class="flex items-center gap-2 text-sm font-medium text-navy">
                                        <input type="checkbox" name="restricted_access" value="1" class="restricted-access-checkbox rounded border-gray-300 text-gold focus:ring-gold"
                                               {{ $admin->restricted_access ? 'checked' : '' }} data-panel="permissions-fields-{{ $admin->id }}">
                                        Restrict this admin's access
                                    </label>
                                    <p class="text-xs text-gray-400">Unchecked = full access to every section (default). Checked = only the pages selected below.</p>

                                    <div id="permissions-fields-{{ $admin->id }}" class="grid grid-cols-2 gap-x-4 gap-y-1.5 {{ $admin->restricted_access ? '' : 'hidden' }}">
                                        @foreach ($sections as $key => $section)
                                            <label class="flex items-center gap-2 text-sm text-navy">
                                                <input type="checkbox" name="permissions[]" value="{{ $key }}" class="rounded border-gray-300 text-gold focus:ring-gold"
                                                       {{ in_array($key, $adminPermissionKeys, true) ? 'checked' : '' }}>
                                                {{ $section['label'] }}
                                            </label>
                                        @endforeach
                                    </div>

                                    <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                        Save Access
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

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
</script>

@endsection
