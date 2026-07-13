<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function index(Request $request)
    {
        $project = $request->user()->projects()->with('milestones')->first();

        return view('portal.milestones', [
            'project' => $project,
        ]);
    }
}
