<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\QuestionnaireCompletedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProjectQuestionnaireController extends Controller
{
    public function show(Request $request)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project, 404);

        if ($project->hasCompletedQuestionnaire()) {
            return redirect()->route('portal.dashboard');
        }

        return view('portal.questionnaire', [
            'project' => $project,
            'questionnaire' => $project->questionnaire,
        ]);
    }

    public function store(Request $request)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project, 404);

        $validated = $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'organization_type' => ['nullable', 'string', 'max:100'],
            'mission_statement' => ['nullable', 'string', 'max:3000'],
            'vision_statement' => ['nullable', 'string', 'max:3000'],
            'services' => ['nullable', 'array'],
            'services.*' => ['string'],
            'requested_pages' => ['nullable', 'string', 'max:5000'],
            'brand_colors' => ['nullable', 'string', 'max:255'],
            'social_links' => ['nullable', 'array'],
            'social_links.*' => ['nullable', 'string', 'max:255'],
            'additional_notes' => ['nullable', 'string', 'max:3000'],
        ]);

        $project->update(['name' => $validated['organization_name']]);

        $questionnaire = $project->questionnaire()->updateOrCreate(
            ['project_id' => $project->id],
            [
                'organization_type' => $validated['organization_type'] ?? null,
                'mission_statement' => $validated['mission_statement'] ?? null,
                'vision_statement' => $validated['vision_statement'] ?? null,
                'services' => array_values($validated['services'] ?? []),
                'requested_pages' => $validated['requested_pages'] ?? null,
                'brand_colors' => $validated['brand_colors'] ?? null,
                'social_links' => array_filter($validated['social_links'] ?? []),
                'additional_notes' => $validated['additional_notes'] ?? null,
                'completed_at' => now(),
            ]
        );

        Mail::to(config('mail.support_address'))->send(new QuestionnaireCompletedMail($questionnaire));

        $request->user()->update(['onboarding_step' => 6]);

        return redirect()->route('portal.care-plan-agreement.show')
            ->with('status', 'Business information saved — next, select your Website Care Plan.');
    }
}
