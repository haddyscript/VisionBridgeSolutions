<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenancePlan;
use App\Models\Project;
use App\Models\ServiceAgreementTemplate;

class OnboardingPreviewController extends Controller
{
    public const STEPS = [
        1 => ['label' => 'Complete Business Information', 'view' => 'admin.projects.onboarding-steps.1-questionnaire'],
        2 => ['label' => 'Select Website Type', 'view' => 'admin.projects.onboarding-steps.2-website-type'],
        3 => ['label' => 'Select Website Care Plan', 'view' => 'admin.projects.onboarding-steps.3-care-plan'],
        4 => ['label' => 'Agreement Summary', 'view' => 'admin.projects.onboarding-steps.4-agreement-summary'],
        5 => ['label' => 'Read & Sign Agreement', 'view' => 'admin.projects.onboarding-steps.5-agreement'],
    ];

    /** Read-only render of the client's actual onboarding screens, pre-filled with their real data — no form on this page ever submits anything. */
    public function show(Project $project, int $step = 1)
    {
        abort_unless(array_key_exists($step, self::STEPS), 404);

        $data = ['project' => $project];

        switch ($step) {
            case 1:
                $data['questionnaire'] = $project->questionnaire;
                break;
            case 3:
                $data['plans'] = MaintenancePlan::where('is_available', true)
                    ->whereNotNull('price')
                    ->orderBy('sort_order')
                    ->get();
                break;
            case 4:
                $carePlanAgreement = $project->carePlanAgreement;
                $data['carePlanAgreement'] = $carePlanAgreement;
                $data['plan'] = $carePlanAgreement?->maintenancePlan;
                $data['template'] = ServiceAgreementTemplate::currentActive();
                break;
            case 5:
                $data['signature'] = $project->agreementSignature;
                $data['template'] = $project->agreementSignature?->template ?? ServiceAgreementTemplate::currentActive();
                break;
        }

        return view('admin.projects.onboarding-preview', [
            'project' => $project,
            'step' => $step,
            'steps' => self::STEPS,
            'stepView' => self::STEPS[$step]['view'],
            'stepData' => $data,
        ]);
    }
}
