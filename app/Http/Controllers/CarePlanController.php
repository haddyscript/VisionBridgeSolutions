<?php

namespace App\Http\Controllers;

use App\Models\MaintenancePlan;

class CarePlanController extends Controller
{
    public function show(MaintenancePlan $maintenancePlan)
    {
        abort_unless($maintenancePlan->is_available, 404);

        return view('care-plans.show', ['plan' => $maintenancePlan]);
    }
}
