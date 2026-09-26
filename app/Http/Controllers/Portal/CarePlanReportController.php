<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\CarePlanReport;
use Illuminate\Http\Request;

class CarePlanReportController extends Controller
{
    public function index(Request $request)
    {
        $project = $request->user()->projects()->first();

        return view('portal.care-plan-reports.index', [
            'reports' => $project
                ? CarePlanReport::where('project_id', $project->id)->where('status', 'sent')->orderByDesc('month')->get()
                : collect(),
        ]);
    }

    public function show(Request $request, CarePlanReport $carePlanReport)
    {
        $project = $request->user()->projects()->first();
        abort_unless($project && (int) $carePlanReport->project_id === (int) $project->id && $carePlanReport->isSent(), 404);

        $carePlanReport->load('subscription.maintenancePlan');

        return view('portal.care-plan-reports.show', ['report' => $carePlanReport]);
    }
}
