<?php

namespace App\Http\Middleware;

use App\Support\AdminPermissions;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanAccessAdminPage
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = AdminPermissions::keyForRoute($request->route()?->getName());

        if ($key !== null) {
            abort_unless($request->user()?->canAccessAdminPage($key), 403, "You don't have access to this section. Ask a super admin to grant it.");
        }

        return $next($request);
    }
}
