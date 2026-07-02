<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ServiceAgreementSignedMail;
use App\Models\ServiceAgreementSignature;
use App\Models\ServiceAgreementTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ServiceAgreementController extends Controller
{
    public function index()
    {
        return view('admin.service-agreement.index', [
            'activeTemplate' => ServiceAgreementTemplate::currentActive(),
            'templates' => ServiceAgreementTemplate::orderByDesc('version')->get(),
            'signatures' => ServiceAgreementSignature::with('user', 'project', 'template')->latest('signed_at')->get(),
        ]);
    }

    /**
     * Editing never mutates existing text/PDF — it publishes a new version, so
     * signatures already collected stay tied to the exact wording they agreed to.
     * Source is either pasted text OR an uploaded PDF, never both.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'source' => ['required', 'in:text,pdf'],
            'body' => ['required_if:source,text', 'nullable', 'string'],
            'pdf' => ['required_if:source,pdf', 'nullable', 'file', 'mimes:pdf', 'max:25600'],
        ]);

        $nextVersion = (ServiceAgreementTemplate::max('version') ?? 0) + 1;

        $pdfPath = null;
        if ($validated['source'] === 'pdf') {
            $pdfPath = $request->file('pdf')->store('agreements/templates', 'local');
        }

        ServiceAgreementTemplate::where('is_active', true)->update(['is_active' => false]);

        ServiceAgreementTemplate::create([
            'version' => $nextVersion,
            'title' => $validated['title'],
            'body' => $validated['source'] === 'text' ? $validated['body'] : '',
            'pdf_path' => $pdfPath,
            'is_active' => true,
        ]);

        return back()->with('status', "Published Service Agreement version {$nextVersion}. Clients who haven't signed yet will see the new version; existing signatures are unaffected.");
    }

    public function resend(ServiceAgreementSignature $serviceAgreementSignature)
    {
        Mail::to($serviceAgreementSignature->user->email)->send(new ServiceAgreementSignedMail($serviceAgreementSignature));

        return back()->with('status', "Signed agreement resent to {$serviceAgreementSignature->user->email}.");
    }

    public function downloadTemplate(ServiceAgreementTemplate $serviceAgreementTemplate)
    {
        abort_unless($serviceAgreementTemplate->isPdfBased(), 404);

        return Storage::disk('local')->download($serviceAgreementTemplate->pdf_path, $serviceAgreementTemplate->title.'.pdf');
    }

    public function viewTemplate(ServiceAgreementTemplate $serviceAgreementTemplate)
    {
        abort_unless($serviceAgreementTemplate->isPdfBased(), 404);

        return Storage::disk('local')->response($serviceAgreementTemplate->pdf_path, $serviceAgreementTemplate->title.'.pdf', [
            'Content-Disposition' => 'inline',
        ]);
    }
}
