<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuspendedController extends Controller
{
    public function show(Request $request)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project && $project->isSuspended(), 404);

        return view('portal.suspended', [
            'project' => $project,
            'subscription' => $project->subscription,
        ]);
    }
}
