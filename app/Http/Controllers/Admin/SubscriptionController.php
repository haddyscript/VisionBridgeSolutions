<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Subscription;
use App\Services\SubscriptionReconciler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('project.user')->latest()->get();

        $activeSubscriptions = $subscriptions->filter->isActive();

        // Normalize to a monthly figure — a yearly plan counts as 1/12th of
        // its amount toward MRR. Amounts are stored in cents.
        $monthlyRecurringRevenue = $activeSubscriptions->sum(
            fn (Subscription $subscription) => $subscription->interval === 'year'
                ? $subscription->amount / 12
                : $subscription->amount
        );

        return view('admin.subscriptions.index', [
            'subscriptions' => $subscriptions,
            'monthlyRecurringRevenue' => $monthlyRecurringRevenue,
            'activeCount' => $activeSubscriptions->count(),
            'pendingCount' => $subscriptions->filter->isPending()->count(),
        ]);
    }

    public function store(Request $request, Project $project)
    {
        abort_if($project->subscription && ! $project->subscription->isCanceled(), 422, 'This project already has a care plan.');

        // Maintenance billing doesn't start during development — only once the
        // website has actually launched (per the client onboarding workflow).
        abort_unless(
            in_array($project->status, ['launched', 'maintenance'], true),
            422,
            'This project must be launched before starting a recurring care plan.'
        );

        $validated = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $project->subscriptions()->create([
            'description' => $validated['description'],
            'amount' => (int) round($validated['amount'] * 100),
        ]);

        return back()->with('status', 'Care plan request created.');
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

        return back()->with('status', 'Care plan canceled.');
    }

    /**
     * Admin-side equivalent of the client's own "Refresh Status" button in
     * the portal — re-checks the real status with Stripe rather than relying
     * on whatever the last webhook happened to leave locally.
     */
    public function sync(Subscription $subscription, SubscriptionReconciler $reconciler)
    {
        return back()->with('status', $reconciler->reconcile($subscription));
    }
}
