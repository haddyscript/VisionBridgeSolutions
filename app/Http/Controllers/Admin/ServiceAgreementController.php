<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceAgreementSignature;
use App\Models\ServiceAgreementTemplate;
use Illuminate\Http\Request;

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
     * Editing never mutates existing text — it publishes a new version, so
     * signatures already collected stay tied to the exact wording they agreed to.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $nextVersion = (ServiceAgreementTemplate::max('version') ?? 0) + 1;

        ServiceAgreementTemplate::where('is_active', true)->update(['is_active' => false]);

        ServiceAgreementTemplate::create([
            'version' => $nextVersion,
            'title' => $validated['title'],
            'body' => $validated['body'],
            'is_active' => true,
        ]);

        return back()->with('status', "Published Service Agreement version {$nextVersion}. Clients who haven't signed yet will see the new wording; existing signatures are unaffected.");
    }
}
