<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\ConsultationReceivedMail;
use App\Mail\NewConsultationMail;
use App\Models\Consultation;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ConsultationController extends Controller
{
    /**
     * Categories that count as an actual uploaded file (excludes the
     * text-only 'content'/'revision' categories from CategoryController).
     */
    private const FILE_CATEGORIES = ['image', 'video', 'logo', 'document', 'marketing'];

    public function create(Request $request)
    {
        $bookedSlots = Consultation::where('preferred_at', '>=', now())
            ->whereIn('status', ['new', 'confirmed', 'rescheduled'])
            ->pluck('preferred_at')
            ->map(fn ($dt) => $dt->format('Y-m-d\TH:i'))
            ->values();

        $project = $request->user()->projects()->first();

        return view('portal.consultation', [
            'bookedSlots' => $bookedSlots,
            'user' => $request->user(),
            'hasUploadedFile' => $this->hasUploadedFile($project),
        ]);
    }

    public function store(Request $request)
    {
        $project = $request->user()->projects()->first();

        if (! $this->hasUploadedFile($project)) {
            $message = 'Please upload at least one project file (image, video, logo, document, or marketing material) before booking a consultation.';

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => $message,
                    'errors' => ['files' => [$message]],
                ], 422);
            }

            return back()->withErrors(['files' => $message])->withInput();
        }

        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:100'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'preferred_at' => ['nullable', 'date'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $user = $request->user();

        $slotTaken = ! empty($validated['preferred_at']) && Consultation::where('preferred_at', $validated['preferred_at'])
            ->whereIn('status', ['new', 'confirmed', 'rescheduled'])
            ->exists();

        if ($slotTaken) {
            $message = 'That time slot was just booked by someone else. Please pick another.';

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => $message,
                    'errors' => ['preferred_at' => [$message]],
                ], 422);
            }

            return back()->withErrors(['preferred_at' => $message])->withInput();
        }

        $consultation = Consultation::create([
            ...$validated,
            'name' => $user->name,
            'email' => $user->email,
        ]);

        Mail::to(config('mail.admin_address'))
            ->cc(config('mail.contact_address'))
            ->send(new NewConsultationMail($consultation));

        Mail::to($consultation->email)->send(new ConsultationReceivedMail($consultation));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Thanks! Your consultation request has been received. We'll be in touch within 24 hours to confirm.",
            ]);
        }

        return redirect(route('portal.consultation.create'))->with('status', 'consultation_sent');
    }

    private function hasUploadedFile(?Project $project): bool
    {
        return $project && $project->uploads()
            ->whereIn('category', self::FILE_CATEGORIES)
            ->whereNotNull('path')
            ->exists();
    }
}
