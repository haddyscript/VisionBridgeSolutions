<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    /**
     * Ends an admin's "viewing as client" (Admin\ClientController::impersonate)
     * or the owner's "logged in as admin" (Admin\TeamController::impersonate)
     * session, and logs the real user back in. Reachable while authenticated
     * as whoever is being impersonated — that's the whole point — so this
     * checks the impersonator_id session value itself rather than relying on
     * route-level admin middleware.
     */
    public function stop(Request $request)
    {
        $adminId = $request->session()->get('impersonator_id');

        abort_unless($adminId, 403, 'You are not currently impersonating anyone.');

        $admin = User::find($adminId);

        abort_unless($admin && $admin->isAdmin(), 403);

        // Capture before Auth::login() below switches $request->user() back
        // to the real account — this is how we know whether we were viewing
        // as a client or logged in as another admin.
        $wasImpersonatingAdmin = $request->user()->isAdmin();

        $request->session()->forget('impersonator_id');

        Auth::login($admin);

        return redirect()->route($wasImpersonatingAdmin ? 'admin.team.index' : 'admin.clients.index')
            ->with('status', 'Returned to your own account.');
    }
}
