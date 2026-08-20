<?php

namespace App\Http\Controllers;

use App\Models\MaintenancePlan;

class CarePlanController extends Controller
{
    public function show(MaintenancePlan $maintenancePlan)
    {
        abort_unless($maintenancePlan->is_available, 404);

        return view('care-plans.show', [
            'plan' => $maintenancePlan,
            // Powers the plan switcher at the top of the page — every
            // available plan, not just the current one, so a visitor can
            // jump straight to another plan's page without going back to
            // the homepage comparison carousel.
            'allPlans' => MaintenancePlan::where('is_available', true)->orderBy('sort_order')->get(),
        ]);
    }
}
