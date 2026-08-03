<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminAssistantService;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function __construct(private AdminAssistantService $assistant)
    {
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'history' => ['sometimes', 'array'],
            'history.*.role' => ['required_with:history', 'in:user,assistant'],
            'history.*.content' => ['required_with:history', 'string', 'max:2000'],
        ]);

        $reply = $this->assistant->reply($request->user(), $validated['message'], $validated['history'] ?? []);

        return response()->json([
            'reply' => $reply,
            'remainingToday' => $this->assistant->remainingMessagesToday($request->user()),
        ]);
    }
}
