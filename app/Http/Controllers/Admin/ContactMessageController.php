<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

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

    public function toggleRead(ContactMessage $contactMessage)
    {
        $contactMessage->update([
            'read_at' => $contactMessage->isRead() ? null : now(),
        ]);

        return back()->with('status', $contactMessage->isRead() ? 'Marked as read.' : 'Marked as unread.');
    }
}
