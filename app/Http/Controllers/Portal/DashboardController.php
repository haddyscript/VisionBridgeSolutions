<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $project = $request->user()->projects()->with('milestones', 'uploads.user')->first();

        return view('portal.dashboard', [
            'project' => $project,
        ]);
    }
}
