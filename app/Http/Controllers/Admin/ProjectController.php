<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ProjectQuoteReadyMail;
use App\Models\ClientNotification;
use App\Models\Project;
use App\Models\SatisfactionSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ProjectController extends Controller
{
    public function show(Project $project)
    {
        $project->load('user', 'milestones', 'uploads.user', 'uploads.replies', 'payments', 'subscription', 'questionnaire', 'agreementSignature.template', 'carePlanAgreement.maintenancePlan', 'recommendations.submittedBy');

        return view('admin.projects.show', [
            'project' => $project,
            'uploadsByCategory' => $project->uploads->groupBy('category'),
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'status' => ['sometimes', 'required', 'in:onboarding,in_progress,review,launched,maintenance,canceled'],
            'preview_url' => ['sometimes', 'nullable', 'url', 'max:255'],
            'progress_override' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:100'],
            'total_price' => ['sometimes', 'nullable', 'numeric', 'min:1'],
        ]);

        $settingPriceForFirstTime = array_key_exists('total_price', $validated)
            && $validated['total_price'] !== null
            && $project->total_price === null;

        $startingReview = ($validated['status'] ?? null) === 'review' && $project->status !== 'review';
        $launching = ($validated['status'] ?? null) === 'launched' && $project->status !== 'launched';

        if (array_key_exists('total_price', $validated)) {
            $validated['total_price'] = $validated['total_price'] !== null
                ? (int) round($validated['total_price'] * 100)
                : null;
        }

        if ($startingReview) {
            // A fresh review cycle — any prior approval no longer applies.
            $validated['review_started_at'] = now();
            $validated['client_approved_at'] = null;
        }

        $project->update($validated);

        if ($launching) {
            SatisfactionSurvey::firstOrCreate(
                ['project_id' => $project->id],
                ['user_id' => $project->user_id],
            );
        }

        // Quoting a price for the first time auto-creates the initial 50%
        // deposit request and emails the client — they're shown a "preparing
        // your quote" waiting state on their dashboard until this happens.
        if ($settingPriceForFirstTime && ! $project->depositPayment()) {
            $depositPayment = $project->payments()->create([
                'description' => 'Initial 50% Project Deposit',
                'kind' => 'deposit',
                'amount' => (int) round($project->total_price / 2),
            ]);

            Mail::to($project->user->email)->send(new ProjectQuoteReadyMail($project, $depositPayment));

            ClientNotification::send(
                $project->user,
                'quote_ready',
                'Your project quote is ready',
                'Pay your initial 50% deposit to kick off development.',
                route('portal.payments.index'),
            );
        }

        return back()->with('status', 'Project updated.');
    }

    public function resetClientPassword(Project $project)
    {
        $project->user->update([
            'password' => Hash::make('admin123'),
        ]);

        return back()->with('status', "Password reset to \"admin123\" for {$project->user->name}.");
    }

    /**
     * Manual override for support cases — e.g. a client paid by some method
     * Stripe never sees, or the automated grace-period check needs overriding.
     * The normal path is fully automatic (StripeWebhookController restores
     * access the moment Stripe confirms the subscription is active again).
     */
    public function restoreAccess(Project $project)
    {
        abort_unless($project->isSuspended(), 422, 'This project is not suspended.');

        $project->update(['suspended_at' => null]);

        return back()->with('status', 'Access restored for '.$project->user->name.'.');
    }
}
