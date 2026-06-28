<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
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

        // Maintenance billing doesn't start during development — only once the
        // website has actually launched (per the client onboarding workflow).
        abort_unless(
            in_array($project->status, ['launched', 'maintenance'], true),
            422,
            'This project must be launched before starting a recurring maintenance plan.'
        );

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

            try {
                \Stripe\Subscription::retrieve($subscription->stripe_subscription_id)->cancel();
            } catch (ApiErrorException $e) {
                Log::warning('Could not cancel Stripe subscription.', [
                    'subscription_id' => $subscription->id,
                    'stripe_subscription_id' => $subscription->stripe_subscription_id,
                    'error' => $e->getMessage(),
                ]);

                return back()->withErrors([
                    'subscription' => 'Could not reach Stripe to cancel this plan ('.$e->getMessage().'). The plan was NOT canceled locally either, so it\'s still showing as active. Please try again or cancel it directly in the Stripe dashboard.',
                ]);
            }
        }

        $subscription->update(['status' => 'canceled', 'canceled_at' => now()]);

        return back()->with('status', 'Maintenance plan canceled.');
    }
}
