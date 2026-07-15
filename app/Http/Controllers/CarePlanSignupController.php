<?php

namespace App\Http\Controllers;

use App\Mail\FaithStackNewClientMail;
use App\Mail\WelcomeClientMail;
use App\Models\MaintenancePlan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class CarePlanSignupController extends Controller
{
    public function create(MaintenancePlan $maintenancePlan)
    {
        abort_unless($maintenancePlan->is_available && $maintenancePlan->price !== null, 404);

        return view('care-plan-signup.create', ['plan' => $maintenancePlan]);
    }

    public function checkEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        return response()->json([
            'exists' => User::where('email', $validated['email'])->exists(),
        ]);
    }

    public function store(Request $request, MaintenancePlan $maintenancePlan)
    {
        abort_unless($maintenancePlan->is_available && $maintenancePlan->price !== null, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'organization' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'domain' => ['nullable', 'string', 'max:255'],
            'hosting_provider' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'timezone' => ['nullable', 'string', 'max:100'],
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
                'phone' => $validated['phone'],
                'password' => Str::random(40),
                'role' => 'client',
                'email_verified_at' => now(),
            ]);

            $project = $user->projects()->create([
                'name' => $validated['organization'],
                'status' => 'onboarding',
            ]);

            // Billing is deferred until launch, same as every other onboarding
            // path (Portal\CarePlanAgreementController::store()) — this used
            // to go straight to Stripe Checkout and charge immediately, which
            // contradicted the "no Care Plan charges during development"
            // policy for anyone using this as their entry point for a new
            // build rather than an existing site. The account still gets
            // created and the client still gets a portal invite right away;
            // only the actual charge moves to launch time.
            return $project->subscriptions()->create([
                'maintenance_plan_id' => $maintenancePlan->id,
                'description' => $maintenancePlan->name,
                'amount' => $maintenancePlan->price,
                'currency' => 'usd',
                'interval' => $maintenancePlan->interval,
                'status' => 'pending',
                'client_phone' => $validated['phone'],
                'domain' => $validated['domain'] ?? null,
                'hosting_provider' => $validated['hosting_provider'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'timezone' => $validated['timezone'] ?? null,
            ]);
        });

        $subscription->load('project.user', 'maintenancePlan');

        // Moved here from StripeWebhookController::welcomeNewCarePlanClient(),
        // which used to fire this only once Stripe confirmed payment — now
        // that there's no immediate charge, account creation itself is the
        // trigger instead.
        $user = $subscription->project->user;
        $resetToken = Password::createToken($user);
        $resetUrl = route('password.reset', ['token' => $resetToken, 'email' => $user->email]);

        dispatch(function () use ($user, $resetUrl, $subscription) {
            Mail::to($user->email)->send(new WelcomeClientMail($user, $resetUrl));
            Mail::to(config('mail.faithstack_address'))->send(new FaithStackNewClientMail($subscription));
        })->afterResponse();

        return redirect()->route('care-plan-signup.confirmation', ['subscription' => $subscription->id]);
    }

    public function confirmation(Request $request)
    {
        $subscription = null;

        if ($subscriptionId = $request->query('subscription')) {
            $subscription = Subscription::with('maintenancePlan')->find($subscriptionId);
        } elseif ($sessionId = $request->query('session_id')) {
            $subscription = Subscription::with('maintenancePlan')
                ->where('stripe_checkout_session_id', $sessionId)
                ->first();
        }

        return view('care-plan-signup.confirmation', ['subscription' => $subscription]);
    }
}
