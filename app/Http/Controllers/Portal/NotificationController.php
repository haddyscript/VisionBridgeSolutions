<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\ClientNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markRead(Request $request)
    {
        ClientNotification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->noContent();
    }
}
