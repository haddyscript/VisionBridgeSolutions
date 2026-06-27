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
    public function show(Request $request)
    {
        $project = $request->user()->projects()->first();
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
            'signer_name' => ['required', 'string', 'max:255'],
            'signature_image' => ['required', 'string', 'starts_with:data:image/png;base64,'],
            'agree' => ['accepted'],
        ]);

        $user = $request->user();
        $project = $user->projects()->first();

        abort_unless($project, 422, 'No project found for this account.');

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
            'signer_name' => $validated['signer_name'],
            'signature_image_path' => $signaturePath,
            'agreement_hash' => hash('sha256', $template->body),
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'signed_at' => now(),
        ]);

        $pdfPath = "agreements/{$project->id}/agreement-{$signature->id}.pdf";

        $pdf = Pdf::loadView('pdfs.service-agreement', [
            'template' => $template,
            'signature' => $signature,
            'signatureImageBase64' => $validated['signature_image'],
        ]);

        Storage::disk('local')->put($pdfPath, $pdf->output());

        $signature->update(['pdf_path' => $pdfPath]);

        Mail::to($user->email)->send(new ServiceAgreementSignedMail($signature));
        Mail::to(config('mail.admin_address'))->send(new ServiceAgreementSignedMail($signature));

        return redirect()->route('portal.dashboard')->with('status', 'Agreement signed — thank you!');
    }

    public function download(Request $request, ServiceAgreementSignature $signature)
    {
        $user = $request->user();

        abort_unless($user->isAdmin() || $signature->user_id === $user->id, 403);
        abort_unless($signature->pdf_path && Storage::disk('local')->exists($signature->pdf_path), 404);

        return Storage::disk('local')->download($signature->pdf_path, 'VisionBridge-Service-Agreement.pdf');
    }
}
