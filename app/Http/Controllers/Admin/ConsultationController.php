<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

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
        ]);

        $consultation->update($validated);

        return back()->with('status', 'Consultation updated.');
    }

    public function toggleRead(Consultation $consultation)
    {
        $consultation->update([
            'read_at' => $consultation->isRead() ? null : now(),
        ]);

        return back()->with('status', $consultation->isRead() ? 'Marked as read.' : 'Marked as unread.');
    }
}
