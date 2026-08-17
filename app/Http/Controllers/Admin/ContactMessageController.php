<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactMessageController extends Controller
{
    public const SORTS = [
        'newest' => 'Newest First',
        'oldest' => 'Oldest First',
        'unread' => 'Unread First',
        'name'   => 'Name (A-Z)',
    ];

    public function index(Request $request)
    {
        $sort = $request->query('sort', 'newest');

        $query = ContactMessage::query();

        match ($sort) {
            'oldest' => $query->oldest(),
            'unread' => $query->orderBy('read_at')->latest(),
            'name' => $query->orderBy('first_name')->orderBy('last_name'),
            default => $query->latest(),
        };

        return view('admin.contact-messages.index', [
            'messages' => $query->paginate(15)->withQueryString(),
            'sort' => $sort,
        ]);
    }

    public function show(ContactMessage $contactMessage)
    {
        if (! $contactMessage->isRead()) {
            $contactMessage->update(['read_at' => now()]);
        }

        return view('admin.contact-messages.show', [
            'message' => $contactMessage,
        ]);
    }

    public function toggleRead(ContactMessage $contactMessage)
    {
        $contactMessage->update([
            'read_at' => $contactMessage->isRead() ? null : now(),
        ]);

        return back()->with('status', $contactMessage->isRead() ? 'Marked as read.' : 'Marked as unread.');
    }

    public function updateLabel(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'label' => ['nullable', Rule::in(array_keys(ContactMessage::LABELS))],
        ]);

        $contactMessage->update(['label' => $validated['label'] ?? null]);

        return back()->with('status', 'Label updated.');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')->with('status', 'Message deleted.');
    }
}
