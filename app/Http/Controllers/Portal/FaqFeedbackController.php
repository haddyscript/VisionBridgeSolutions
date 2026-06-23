<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\FaqFeedback;
use Illuminate\Http\Request;

class FaqFeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'helpful' => ['required', 'boolean'],
        ]);

        FaqFeedback::create([
            'user_id' => $request->user()->id,
            'question' => $validated['question'],
            'helpful' => $validated['helpful'],
        ]);

        return response()->json(['message' => 'Thanks for your feedback!']);
    }
}
