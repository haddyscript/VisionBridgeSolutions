<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('project.user')->latest()->get();

        return view('admin.subscriptions.index', [
            'subscriptions' => $subscriptions,
        ]);
    }

    public function store(Request $request, Project $project)
    {
        abort_if($project->subscription && ! $project->subscription->isCanceled(), 422, 'This project already has a maintenance plan.');

        $validated = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $project->subscriptions()->create([
            'description' => $validated['description'],
            'amount' => (int) round($validated['amount'] * 100),
        ]);

        return back()->with('status', 'Maintenance plan request created.');
    }

    public function destroy(Subscription $subscription)
    {
        if ($subscription->stripe_subscription_id) {
            Stripe::setApiKey(config('services.stripe.secret'));

            \Stripe\Subscription::retrieve($subscription->stripe_subscription_id)->cancel();
        }

        $subscription->update(['status' => 'canceled', 'canceled_at' => now()]);

        return back()->with('status', 'Maintenance plan canceled.');
    }
}
