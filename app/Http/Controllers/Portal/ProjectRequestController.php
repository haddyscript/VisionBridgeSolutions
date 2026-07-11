<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\NewProjectRequestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProjectRequestController extends Controller
{
    public function show(Request $request)
    {
        return view('portal.project-request', [
            'requests' => $request->user()->projectRequests()->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:25600'],
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $validated['attachment_original_name'] = $file->getClientOriginalName();
            $validated['attachment_path'] = $file->store("project-requests/{$request->user()->id}", 'client_uploads');
            unset($validated['attachment']);
        }

        $projectRequest = $request->user()->projectRequests()->create($validated);

        dispatch(function () use ($projectRequest) {
            Mail::to(config('mail.support_address'))->send(new NewProjectRequestMail($projectRequest));
        })->afterResponse();

        return redirect()->route('portal.project-requests.show')
            ->with('status', 'Your project request has been sent — we\'ll be in touch soon.');
    }
}
