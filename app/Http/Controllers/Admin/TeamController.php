<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AdminPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class TeamController extends Controller
{
    public function index()
    {
        return view('admin.team.index', [
            'admins' => User::where('role', 'admin')->orderBy('name')->with('adminPermissions')->get(),
            'sections' => AdminPermissions::SECTIONS,
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

    public function toggleSuperAdmin(Request $request, User $user)
    {
        abort_unless($user->isAdmin(), 404);

        if ($user->isOwner()) {
            return back()->withErrors(['team' => 'The owner account is always a super admin — this can\'t be changed.']);
        }

        if ($user->isSuperAdmin() && $user->is($request->user())) {
            return back()->withErrors(['team' => 'You cannot revoke your own super admin access. Ask another super admin to do it.']);
        }

        if ($user->isSuperAdmin() && User::where('role', 'admin')->where('is_super_admin', true)->count() <= 1) {
            return back()->withErrors(['team' => 'At least one super admin account must remain.']);
        }

        $user->update(['is_super_admin' => ! $user->is_super_admin]);

        return back()->with('status', $user->isSuperAdmin()
            ? $user->name.' is now a super admin.'
            : $user->name.' is no longer a super admin.');
    }

    /**
     * Owner-only. Reversible suspension — blocks login/kicks an active
     * session (see EnsureUserIsAdmin) without deleting the account or any of
     * its history/associations, unlike destroy().
     */
    public function toggleActive(Request $request, User $user)
    {
        abort_unless($user->isAdmin(), 404);

        if ($user->is($request->user())) {
            return back()->withErrors(['team' => 'You cannot deactivate your own account.']);
        }

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('status', $user->is_active
            ? $user->name.' has been reactivated.'
            : $user->name.' has been deactivated.');
    }

    public function updatePermissions(Request $request, User $user)
    {
        abort_unless($user->isAdmin(), 404);

        if ($user->isSuperAdmin()) {
            return back()->withErrors(['team' => 'Super admins always have full access — remove super admin first if you want to restrict this account.']);
        }

        $validated = $request->validate([
            'restricted_access' => ['nullable', 'boolean'],
            'permissions' => ['array'],
            'permissions.*' => ['string', Rule::in(array_keys(AdminPermissions::SECTIONS))],
        ]);

        $user->update(['restricted_access' => (bool) ($validated['restricted_access'] ?? false)]);

        $user->adminPermissions()->delete();

        foreach ($validated['permissions'] ?? [] as $key) {
            $user->adminPermissions()->create(['permission_key' => $key]);
        }

        return back()->with('status', 'Access updated for '.$user->name.'.');
    }

    public function destroy(Request $request, User $user)
    {
        abort_unless($user->isAdmin(), 404);

        if ($user->is($request->user())) {
            return back()->withErrors(['team' => 'You cannot remove your own account.']);
        }

        if ($user->isOwner()) {
            return back()->withErrors(['team' => 'The owner account can\'t be removed.']);
        }

        // The owner can remove anyone — including the last remaining super
        // admin — since the owner itself always counts as a super admin and
        // can never be locked out. Every other super admin still has to
        // respect the safety nets below.
        if (! $request->user()->isOwner()) {
            if (User::where('role', 'admin')->count() <= 1) {
                return back()->withErrors(['team' => 'At least one admin account must remain.']);
            }

            if ($user->isSuperAdmin() && User::where('role', 'admin')->where('is_super_admin', true)->count() <= 1) {
                return back()->withErrors(['team' => 'At least one super admin account must remain.']);
            }
        }

        $user->delete();

        return back()->with('status', 'Team member removed.');
    }
}
