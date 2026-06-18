<?php

namespace App\Http\Controllers;

use App\Mail\NewIntakeSubmissionMail;
use App\Models\IntakeSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class IntakeController extends Controller
{
    private const FILE_FIELDS = [
        'photos' => 'photo',
        'videos' => 'video',
        'logos' => 'logo',
    ];

    public function create()
    {
        return view('intake.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'organization_type' => ['nullable', 'string', 'max:100'],
            'mission_statement' => ['nullable', 'string', 'max:3000'],
            'vision_statement' => ['nullable', 'string', 'max:3000'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'services' => ['nullable', 'array'],
            'services.*' => ['string'],
            'website_requirements' => ['nullable', 'string', 'max:5000'],
            'social_links' => ['nullable', 'array'],
            'social_links.*' => ['nullable', 'string', 'max:255'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['file', 'image', 'max:10240'],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['file', 'mimetypes:video/mp4,video/quicktime,video/webm,video/x-msvideo', 'max:51200'],
            'logos' => ['nullable', 'array'],
            'logos.*' => ['file', 'image', 'max:10240'],
        ]);

        $submission = IntakeSubmission::create([
            'organization_name' => $validated['organization_name'],
            'organization_type' => $validated['organization_type'] ?? null,
            'mission_statement' => $validated['mission_statement'] ?? null,
            'vision_statement' => $validated['vision_statement'] ?? null,
            'contact_name' => $validated['contact_name'],
            'contact_email' => $validated['contact_email'],
            'contact_phone' => $validated['contact_phone'] ?? null,
            'services' => array_values($validated['services'] ?? []),
            'website_requirements' => $validated['website_requirements'] ?? null,
            'social_links' => array_filter($validated['social_links'] ?? []),
        ]);

        foreach (self::FILE_FIELDS as $field => $category) {
            foreach ($request->file($field, []) as $file) {
                $path = $file->store("intake/{$submission->id}/{$category}", 'client_uploads');

                $submission->files()->create([
                    'category' => $category,
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                ]);
            }
        }

        Mail::to(config('mail.admin_address'))->send(new NewIntakeSubmissionMail($submission));

        return redirect()->route('intake.create')->with('status', 'submitted');
    }
}
