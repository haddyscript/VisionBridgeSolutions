<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPayout;
use Illuminate\Http\Request;

class SubscriptionPayoutController extends Controller
{
    public function index()
    {
        $payouts = SubscriptionPayout::with('subscription.project.user', 'subscription.maintenancePlan')
            ->latest()
            ->get();

        return view('admin.subscription-payouts.index', [
            'payouts' => $payouts,
            'totalPending' => $payouts->where('status', 'pending')->sum('faithstack_amount'),
        ]);
    }

    public function update(Request $request, SubscriptionPayout $subscriptionPayout)
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $subscriptionPayout->update([
            'status' => 'paid',
            'paid_at' => now(),
            'notes' => $validated['notes'] ?? $subscriptionPayout->notes,
        ]);

        return back()->with('status', 'Marked as paid to FaithStack.');
    }
}
