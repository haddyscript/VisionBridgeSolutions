<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class ClientController extends Controller
{
    /**
     * "Login as client" — lets an admin see the portal exactly as the client
     * sees it, without ever needing the client's password (e.g. to reproduce
     * a bug or a support complaint). Every use is logged to login_activities
     * with the acting admin recorded via impersonator_id, and the client-side
     * portal.blade.php layout shows a persistent "Return to Admin" banner for
     * as long as session('impersonator_id') is set.
     */
    public function impersonate(Request $request, User $client)
    {
        abort_if($client->isAdmin(), 403, 'Admin accounts cannot be impersonated.');

        $adminId = $request->user()->id;

        LoginActivity::create([
            'user_id' => $client->id,
            'impersonator_id' => $adminId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'logged_in_at' => now(),
        ]);

        $request->session()->put('impersonator_id', $adminId);

        Auth::login($client);

        return redirect()->route('portal.dashboard');
    }

    public function update(Request $request, User $client)
    {
        abort_if($client->isAdmin(), 403);

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($client->id)],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $client->update($validated);

        return back()->with('status', "{$client->name}'s information updated.");
    }

    /**
     * Permanently deletes a client account and everything tied to it —
     * projects, payments, subscriptions, uploads, agreements, etc. all
     * cascade at the DB level (foreign keys use cascadeOnDelete()). Any live
     * Stripe subscription is canceled first so a deleted client doesn't keep
     * getting billed with no local record left to track it, and uploaded
     * files on disk are removed since the DB cascade doesn't touch those.
     */
    public function destroy(User $client)
    {
        abort_if($client->isAdmin(), 403, 'Admin accounts cannot be deleted here.');

        Stripe::setApiKey(config('services.stripe.secret'));

        foreach ($client->projects as $project) {
            foreach ($project->subscriptions as $subscription) {
                if ($subscription->stripe_subscription_id && ! $subscription->isCanceled()) {
                    try {
                        \Stripe\Subscription::retrieve($subscription->stripe_subscription_id)->cancel();
                    } catch (ApiErrorException $e) {
                        Log::warning('Could not cancel Stripe subscription while deleting client.', [
                            'client_id' => $client->id,
                            'subscription_id' => $subscription->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }

            Storage::disk('client_uploads')->deleteDirectory("projects/{$project->id}");
        }

        $name = $client->name;
        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('status', "{$name}'s account and all related data have been permanently removed.");
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $clients = User::where(function ($q) {
                $q->where('role', '!=', 'admin')->orWhereNull('role');
            })
            ->with(['projects' => fn ($q) => $q->select('id', 'user_id', 'name', 'status')])
            ->when($search, fn ($q) => $q->where(fn ($s) =>
                $s->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
            ))
            ->orderByDesc('created_at')
            ->get();

        return view('admin.clients.index', [
            'clients' => $clients,
            'search'  => $search,
        ]);
    }
}
