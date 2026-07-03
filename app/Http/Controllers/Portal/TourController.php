<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function complete(Request $request)
    {
        $request->user()->update(['tour_completed_at' => now()]);

        return response()->noContent();
    }
}
