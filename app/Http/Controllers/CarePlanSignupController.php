<?php

namespace App\Http\Controllers;

use App\Models\MaintenancePlan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Stripe;

class CarePlanSignupController extends Controller
{
    public function create(MaintenancePlan $maintenancePlan)
    {
        abort_unless($maintenancePlan->is_available && $maintenancePlan->price !== null, 404);

        return view('care-plan-signup.create', ['plan' => $maintenancePlan]);
    }

    public function store(Request $request, MaintenancePlan $maintenancePlan)
    {
        abort_unless($maintenancePlan->is_available && $maintenancePlan->price !== null, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'organization' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'domain' => ['nullable', 'string', 'max:255'],
            'hosting_provider' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if (User::where('email', $validated['email'])->exists()) {
            return back()->withErrors([
                'email' => 'An account already exists with this email. Please log in to manage your plan, or contact us if you need help.',
            ])->withInput();
        }

        $subscription = DB::transaction(function () use ($validated, $maintenancePlan) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Str::random(40),
                'role' => 'client',
                'email_verified_at' => now(),
            ]);

            $project = $user->projects()->create([
                'name' => $validated['organization'],
                'status' => 'onboarding',
            ]);

            return $project->subscriptions()->create([
                'maintenance_plan_id' => $maintenancePlan->id,
                'description' => $maintenancePlan->name,
                'amount' => $maintenancePlan->price,
                'currency' => 'usd',
                'interval' => $maintenancePlan->interval,
                'client_phone' => $validated['phone'] ?? null,
                'domain' => $validated['domain'] ?? null,
                'hosting_provider' => $validated['hosting_provider'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        $subscription->load('project.user');

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = CheckoutSession::create([
            'mode' => 'subscription',
            'payment_method_types' => ['card'],
            'customer' => $subscription->project->user->getOrCreateStripeCustomerId(),
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => $subscription->currency,
                    'unit_amount' => $subscription->amount,
                    'recurring' => ['interval' => $subscription->interval],
                    'product_data' => [
                        'name' => $maintenancePlan->name.' — Website Care Plan',
                    ],
                ],
            ]],
            'success_url' => route('care-plan-signup.confirmation').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('care-plan-signup.create', $maintenancePlan).'?checkout=cancel',
            'metadata' => [
                'subscription_id' => $subscription->id,
            ],
            'subscription_data' => [
                'metadata' => [
                    'subscription_id' => $subscription->id,
                ],
            ],
        ]);

        $subscription->update(['stripe_checkout_session_id' => $session->id]);

        return redirect()->away($session->url);
    }

    public function confirmation(Request $request)
    {
        $subscription = null;

        if ($sessionId = $request->query('session_id')) {
            $subscription = Subscription::with('maintenancePlan')
                ->where('stripe_checkout_session_id', $sessionId)
                ->first();
        }

        return view('care-plan-signup.confirmation', ['subscription' => $subscription]);
    }
}
