<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenancePlan;
use Illuminate\Http\Request;

class MaintenancePlanController extends Controller
{
    public function index()
    {
        $plans = MaintenancePlan::orderBy('sort_order')->get();

        return view('admin.care-plans.index', [
            'plans' => $plans,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePlan($request);

        MaintenancePlan::create($validated);

        return back()->with('status', 'Care plan created.');
    }

    public function update(Request $request, MaintenancePlan $carePlan)
    {
        $validated = $this->validatePlan($request);

        $carePlan->update($validated);

        return back()->with('status', 'Care plan updated.');
    }

    public function destroy(MaintenancePlan $carePlan)
    {
        $carePlan->delete();

        return back()->with('status', 'Care plan removed.');
    }

    private function validatePlan(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'badge' => ['nullable', 'string', 'max:255'],
            'features' => ['nullable', 'string'],
            'cta_label' => ['required', 'string', 'max:255'],
            'cta_url' => ['required', 'string', 'max:255'],
            'is_available' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $validated['price'] = $validated['price'] !== null && $validated['price'] !== ''
            ? (int) round($validated['price'] * 100)
            : null;

        $validated['features'] = collect(explode("\n", $validated['features'] ?? ''))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();

        $validated['is_available'] = $request->boolean('is_available');

        return $validated;
    }
}
