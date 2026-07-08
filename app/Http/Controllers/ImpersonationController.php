<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    /**
     * Ends an admin's "viewing as client" session (started by
     * Admin\ClientController::impersonate) and logs the admin back in.
     * Reachable while authenticated as the client being impersonated — that's
     * the whole point — so this checks the impersonator_id session value
     * itself rather than relying on route-level admin middleware.
     */
    public function stop(Request $request)
    {
        $adminId = $request->session()->get('impersonator_id');

        abort_unless($adminId, 403, 'You are not currently impersonating a client.');

        $admin = User::find($adminId);

        abort_unless($admin && $admin->isAdmin(), 403);

        $request->session()->forget('impersonator_id');

        Auth::login($admin);

        return redirect()->route('admin.clients.index')->with('status', 'Returned to your admin account.');
    }
}
