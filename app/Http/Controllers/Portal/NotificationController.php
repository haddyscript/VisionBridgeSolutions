<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\ClientNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Explicit "mark all as read" — only fired by the dropdown's own "Mark
     * all as read" button, never automatically just from opening the bell
     * or visiting the dashboard (see markOneRead() for the per-item
     * equivalent, which is what normal clicking triggers).
     */
    public function markRead(Request $request)
    {
        ClientNotification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->noContent();
    }

    /**
     * Marks a single notification read — fired when the client actually
     * clicks that specific notification, so a notification is never marked
     * "read" before the client has actually opened it.
     */
    public function markOneRead(Request $request, ClientNotification $notification)
    {
        abort_unless($notification->user_id === $request->user()->id, 403);

        if (! $notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        return response()->noContent();
    }
}
