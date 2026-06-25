<?php

namespace App\Http\Controllers;

use App\Mail\ConsultationReceivedMail;
use App\Mail\NewConsultationMail;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ConsultationController extends Controller
{
    public function create()
    {
        $bookedSlots = Consultation::where('preferred_at', '>=', now())
            ->whereIn('status', ['new', 'confirmed', 'rescheduled'])
            ->pluck('preferred_at')
            ->map(fn ($dt) => $dt->format('Y-m-d\TH:i'))
            ->values();

        return view('consultation', [
            'bookedSlots' => $bookedSlots,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:100'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'preferred_at' => ['nullable', 'date'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);

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

        $consultation = Consultation::create($validated);

        Mail::to(config('mail.admin_address'))
            ->cc(config('mail.contact_address'))
            ->send(new NewConsultationMail($consultation));

        Mail::to($consultation->email)->send(new ConsultationReceivedMail($consultation));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Thanks! Your consultation request has been received. We'll be in touch within 24 hours to confirm.",
            ]);
        }

        return redirect(route('consultation.create'))->with('status', 'consultation_sent');
    }
}
