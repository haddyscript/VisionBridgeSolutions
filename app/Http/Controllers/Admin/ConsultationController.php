<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ConsultationCancelledMail;
use App\Mail\ConsultationConfirmedMail;
use App\Mail\ConsultationGetStartedMail;
use App\Mail\ConsultationRescheduledMail;
use App\Models\ClientNotification;
use App\Models\Consultation;
use App\Models\User;
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
            'consultations' => $query->paginate(15)->withQueryString(),
            'sort' => $sort,
        ]);
    }

    public function show(Consultation $consultation)
    {
        if (! $consultation->isRead()) {
            $consultation->update(['read_at' => now()]);
        }

        return view('admin.consultations.show', [
            'consultation' => $consultation,
        ]);
    }

    public function update(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,confirmed,rescheduled,cancelled,proceed'],
            'preferred_at' => ['nullable', 'date'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
            'meeting_link' => ['nullable', 'url', 'max:255'],
        ]);

        // Proceed has no prerequisite fields (unlike Confirmed/Rescheduled), so
        // moving a consultation into it fires the Get Started email right away
        // instead of waiting for a separate "Notify Client" click.
        $movingToProceed = $validated['status'] === 'proceed' && $consultation->status !== 'proceed';

        $consultation->update($validated);

        if ($movingToProceed) {
            $this->sendStatusEmail($consultation);

            return back()->with('status', 'Consultation updated — Get Started email sent to the client.');
        }

        return back()->with('status', 'Consultation updated.');
    }

    public function notifyClient(Consultation $consultation)
    {
        if ($consultation->status === 'confirmed') {
            abort_unless($consultation->meeting_link, 422, 'Add a meeting link before notifying the client.');
        } elseif ($consultation->status === 'rescheduled') {
            abort_unless($consultation->preferred_at, 422, 'Set the new preferred date/time before notifying the client.');
        } elseif (! in_array($consultation->status, ['cancelled', 'proceed'], true)) {
            abort(422, 'Set the status to Confirmed, Rescheduled, Cancelled, or Proceed before notifying the client.');
        }

        $this->sendStatusEmail($consultation);

        return back()->with('status', 'Notification email sent to the client.');
    }

    private function sendStatusEmail(Consultation $consultation): void
    {
        $mailable = match ($consultation->status) {
            'confirmed' => new ConsultationConfirmedMail($consultation),
            'rescheduled' => new ConsultationRescheduledMail($consultation),
            'cancelled' => new ConsultationCancelledMail($consultation),
            'proceed' => new ConsultationGetStartedMail($consultation),
        };

        $account = User::where('email', $consultation->email)->first();

        if (! $account || $account->notify_on_consultations) {
            Mail::to($consultation->email)->send($mailable);
        }

        if ($account) {
            $statusLabels = ['confirmed' => 'confirmed', 'rescheduled' => 'rescheduled', 'cancelled' => 'canceled', 'proceed' => 'moving forward'];

            ClientNotification::send(
                $account,
                'consultation_update',
                'Consultation '.$statusLabels[$consultation->status],
                $consultation->status === 'confirmed'
                    ? 'Your consultation on '.$consultation->preferred_at->format('F j, Y \a\t g:i A').' has been confirmed.'
                    : null,
                route('portal.consultation.create'),
            );
        }

        $consultation->update(['confirmation_sent_at' => now()]);
    }

    public function toggleRead(Consultation $consultation)
    {
        $consultation->update([
            'read_at' => $consultation->isRead() ? null : now(),
        ]);

        return back()->with('status', $consultation->isRead() ? 'Marked as read.' : 'Marked as unread.');
    }

    public function destroy(Consultation $consultation)
    {
        $consultation->delete();

        return redirect()->route('admin.consultations.index')->with('status', 'Consultation deleted.');
    }
}
