<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TeamController extends Controller
{
    public function index()
    {
        return view('admin.team.index', [
            'admins' => User::where('role', 'admin')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'is_super_admin' => ['nullable', 'boolean'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_super_admin' => $validated['is_super_admin'] ?? false,
        ]);

        return back()->with('status', 'Team member added.');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
        ]);

        $request->user()->update($validated);

        return back()->with('status', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'Password updated.');
    }

    public function destroy(Request $request, User $user)
    {
        abort_unless($user->isAdmin(), 404);

        if ($user->is($request->user())) {
            return back()->withErrors(['team' => 'You cannot remove your own account.']);
        }

        if (User::where('role', 'admin')->count() <= 1) {
            return back()->withErrors(['team' => 'At least one admin account must remain.']);
        }

        if ($user->isSuperAdmin() && User::where('role', 'admin')->where('is_super_admin', true)->count() <= 1) {
            return back()->withErrors(['team' => 'At least one super admin account must remain.']);
        }

        $user->delete();

        return back()->with('status', 'Team member removed.');
    }
}
