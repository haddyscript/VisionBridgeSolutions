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
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'stripe_price_id' => ['nullable', 'string', 'max:255'],
            'badge' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'response_time' => ['nullable', 'string', 'max:255'],
            'features' => ['nullable', 'string'],
            'cta_label' => ['required', 'string', 'max:255'],
            'cta_url' => ['required', 'string', 'max:255'],
            'is_available' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $validated['price'] = $validated['price'] !== null && $validated['price'] !== ''
            ? (int) round($validated['price'] * 100)
            : null;

        $validated['stripe_price_id'] = $validated['stripe_price_id'] !== '' ? $validated['stripe_price_id'] : null;

        // Each line is "Feature Title | Short description" — the description half is optional.
        $validated['features'] = collect(explode("\n", $validated['features'] ?? ''))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->map(function ($line) {
                [$title, $description] = array_pad(explode('|', $line, 2), 2, null);

                return [
                    'title' => trim($title),
                    'description' => $description !== null ? trim($description) : null,
                ];
            })
            ->values()
            ->all();

        $validated['is_available'] = $request->boolean('is_available');

        return $validated;
    }
}
