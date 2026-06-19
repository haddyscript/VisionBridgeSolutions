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
            <p class="text-sm text-gray-500">Add or remove admin accounts.</p>
        </div>

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
                <p class="text-xs text-gray-400">New members are created with the default password <span class="font-semibold text-navy">admin123</span>.</p>
                <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                    Add Team Member
                </button>
            </form>
        </div>

        {{-- Existing team members --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-navy mb-3">Admins ({{ $admins->count() }})</h3>
            <div class="space-y-2.5">
                @foreach ($admins as $admin)
                    <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 px-4 py-3">
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
                                </p>
                                <p class="text-xs text-gray-400">{{ $admin->email }}</p>
                            </div>
                        </div>
                        @if (! $admin->is(auth()->user()))
                            <form method="POST" action="{{ route('admin.team.destroy', $admin) }}" onsubmit="return confirm('Remove this team member?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-red-400 hover:text-red-600">Remove</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

@endsection
