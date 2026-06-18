<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $projects = Project::with('user', 'milestones', 'uploads')->latest()->get();

        return view('admin.dashboard', [
            'projects' => $projects,
        ]);
    }
}
