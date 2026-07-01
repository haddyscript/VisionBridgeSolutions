<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\ServiceAgreementSignedMail;
use App\Models\ServiceAgreementSignature;
use App\Models\ServiceAgreementTemplate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ServiceAgreementController extends Controller
{
    public function summary(Request $request)
    {
        $user = $request->user();
        $project = $user->projects()->first();

        abort_unless($project, 404);

        if (! $project->hasAgreedToCarePlan()) {
            return redirect()->route('portal.care-plan-agreement.show');
        }

        if ($project->hasSignedCurrentAgreement()) {
            return redirect()->route('portal.dashboard');
        }

        $template = ServiceAgreementTemplate::currentActive();
        $carePlanAgreement = $project->carePlanAgreement;

        return view('portal.agreement-summary', [
            'project'           => $project,
            'template'          => $template,
            'carePlanAgreement' => $carePlanAgreement,
            'plan'              => $carePlanAgreement?->maintenancePlan,
        ]);
    }

    public function confirmSummary(Request $request)
    {
        $user = $request->user();
        $project = $user->projects()->first();

        abort_unless($project && $project->hasAgreedToCarePlan(), 422);

        $user->update(['onboarding_step' => 10]);

        return redirect()->route('portal.agreement.show');
    }

    public function show(Request $request)
    {
        $project = $request->user()->projects()->first();

        // A Care Plan must be selected and agreed to before the Service
        // Agreement can even be shown — it's now the first onboarding step.
        if ($project && ! $project->hasAgreedToCarePlan()) {
            return redirect()->route('portal.care-plan-agreement.show');
        }

        $template = ServiceAgreementTemplate::currentActive();

        // Nothing to sign, or already signed — don't show the form again.
        if (! $template || ($project && $project->hasSignedCurrentAgreement())) {
            return redirect()->route('portal.dashboard');
        }

        return view('portal.agreement', [
            'template' => $template,
        ]);
    }

    public function store(Request $request)
    {
        $template = ServiceAgreementTemplate::currentActive();

        abort_unless($template, 404);

        $validated = $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'signer_name'       => ['required', 'string', 'max:255'],
            'title'             => ['required', 'string', 'max:255'],
            'signature_image'   => ['required', 'string', 'starts_with:data:image/png;base64,'],
            'ack_read'          => ['accepted'],
            'ack_terms'         => ['accepted'],
            'ack_billing'       => ['accepted'],
            'ack_binding'       => ['accepted'],
            'ack_electronic'    => ['accepted'],
        ]);

        $user = $request->user();
        $project = $user->projects()->first();

        abort_unless($project, 422, 'No project found for this account.');
        abort_unless($project->hasAgreedToCarePlan(), 422, 'Please select a Care Plan before signing the Service Agreement.');

        if ($project->hasSignedCurrentAgreement()) {
            return redirect()->route('portal.dashboard');
        }

        $imageData = base64_decode(str_replace('data:image/png;base64,', '', $validated['signature_image']));
        $signaturePath = "agreements/{$project->id}/signature-".now()->timestamp.'.png';
        Storage::disk('local')->put($signaturePath, $imageData);

        $signature = ServiceAgreementSignature::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'service_agreement_template_id' => $template->id,
            'organization_name' => $validated['organization_name'],
            'signer_name' => $validated['signer_name'],
            'title' => $validated['title'],
            'signature_image_path' => $signaturePath,
            'agreement_hash' => $template->isPdfBased() ? $template->pdfHash() : hash('sha256', $template->body),
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'signed_at' => now(),
        ]);

        $pdfPath = "agreements/{$project->id}/agreement-{$signature->id}.pdf";

        // PDF-based templates can't be re-rendered from text, and merging a
        // signature page into an uploaded multi-page PDF needs a library we
        // don't have installed — so we generate a one-page certificate that
        // references the uploaded agreement (by title/version/hash) instead.
        // The uploaded agreement itself stays downloadable from the Documents
        // page and this same signing screen.
        $pdf = $template->isPdfBased()
            ? Pdf::loadView('pdfs.service-agreement-certificate', [
                'template' => $template,
                'signature' => $signature,
                'signatureImageBase64' => $validated['signature_image'],
            ])
            : Pdf::loadView('pdfs.service-agreement', [
                'template' => $template,
                'signature' => $signature,
                'signatureImageBase64' => $validated['signature_image'],
            ]);

        Storage::disk('local')->put($pdfPath, $pdf->output());

        $signature->update(['pdf_path' => $pdfPath]);

        Mail::to($user->email)->send(new ServiceAgreementSignedMail($signature));
        Mail::to(config('mail.admin_address'))->send(new ServiceAgreementSignedMail($signature));

        $user->update(['onboarding_step' => 13]);

        return redirect()->route('portal.dashboard')->with('status', 'Agreement signed — thank you!');
    }

    public function download(Request $request, ServiceAgreementSignature $signature)
    {
        $user = $request->user();

        abort_unless($user->isAdmin() || $signature->user_id === $user->id, 403);
        abort_unless($signature->pdf_path && Storage::disk('local')->exists($signature->pdf_path), 404);

        return Storage::disk('local')->download($signature->pdf_path, 'VisionBridge-Service-Agreement.pdf');
    }

    public function preview(Request $request, ServiceAgreementSignature $signature)
    {
        $user = $request->user();

        abort_unless($user->isAdmin() || $signature->user_id === $user->id, 403);
        abort_unless($signature->pdf_path && Storage::disk('local')->exists($signature->pdf_path), 404);

        return Storage::disk('local')->response($signature->pdf_path, 'VisionBridge-Service-Agreement.pdf');
    }

    public function viewTemplate(Request $request, ServiceAgreementTemplate $serviceAgreementTemplate)
    {
        $project = $request->user()->projects()->first();

        // Admins reviewing it (e.g. before publishing) and clients who still
        // need to sign the current version, or who already signed this exact
        // version, may view it. Clients can't view an inactive older version.
        $allowed = $request->user()->isAdmin()
            || $serviceAgreementTemplate->is_active
            || ($project && $project->agreementSignature?->service_agreement_template_id === $serviceAgreementTemplate->id);

        abort_unless($allowed, 403);
        abort_unless($serviceAgreementTemplate->isPdfBased(), 404);

        return Storage::disk('local')->response($serviceAgreementTemplate->pdf_path, $serviceAgreementTemplate->title.'.pdf');
    }
}
