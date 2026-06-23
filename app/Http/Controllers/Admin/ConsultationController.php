<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ConsultationConfirmedMail;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ConsultationController extends Controller
{
    public const SORTS = [
        'newest' => 'Newest First',
        'oldest' => 'Oldest First',
        'unread' => 'Unread First',
        'name' => 'Name (A-Z)',
    ];

    public function index(Request $request)
    {
        $sort = $request->query('sort', 'newest');

        $query = Consultation::query();

        match ($sort) {
            'oldest' => $query->oldest(),
            'unread' => $query->orderBy('read_at')->latest(),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };

        return view('admin.consultations.index', [
            'consultations' => $query->get(),
            'sort' => $sort,
        ]);
    }

    public function show(Consultation $consultation)
    {
        return view('admin.consultations.show', [
            'consultation' => $consultation,
        ]);
    }

    public function update(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,confirmed,rescheduled,cancelled'],
            'preferred_at' => ['nullable', 'date'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
            'meeting_link' => ['nullable', 'url', 'max:255'],
        ]);

        $consultation->update($validated);

        return back()->with('status', 'Consultation updated.');
    }

    public function sendConfirmation(Consultation $consultation)
    {
        abort_unless($consultation->meeting_link, 422, 'Add a meeting link before sending the confirmation email.');

        Mail::to($consultation->email)->send(new ConsultationConfirmedMail($consultation));

        $consultation->update(['confirmation_sent_at' => now()]);

        return back()->with('status', 'Confirmation email sent to the client.');
    }

    public function toggleRead(Consultation $consultation)
    {
        $consultation->update([
            'read_at' => $consultation->isRead() ? null : now(),
        ]);

        return back()->with('status', $consultation->isRead() ? 'Marked as read.' : 'Marked as unread.');
    }
}
