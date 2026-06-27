<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProjectController extends Controller
{
    public function show(Project $project)
    {
        $project->load('user', 'milestones', 'uploads.user', 'uploads.replies', 'payments', 'subscription', 'questionnaire', 'agreementSignature.template');

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

        // Quoting a price for the first time auto-creates the initial 50%
        // deposit request — the client pays it from their existing Payments tab.
        if ($settingPriceForFirstTime && ! $project->depositPayment()) {
            $project->payments()->create([
                'description' => 'Initial 50% Project Deposit',
                'kind' => 'deposit',
                'amount' => (int) round($project->total_price / 2),
            ]);
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
}
