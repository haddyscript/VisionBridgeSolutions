<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markRead(Request $request)
    {
        $request->user()->update(['activity_last_read_at' => now()]);

        return response()->noContent();
    }
}
