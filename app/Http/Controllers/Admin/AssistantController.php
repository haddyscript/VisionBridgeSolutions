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

    public function show(Request $request)
    {
        $conversation = $this->assistant->conversationFor($request->user());

        return response()->json([
            'messages' => $conversation->messages->map(fn ($message) => [
                'role' => $message->role,
                'content' => $message->content,
            ]),
            'remainingToday' => $this->assistant->remainingMessagesToday($request->user()),
        ]);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $reply = $this->assistant->reply($request->user(), $validated['message']);

        return response()->json([
            'reply' => $reply->content,
            'remainingToday' => $this->assistant->remainingMessagesToday($request->user()),
        ]);
    }
}
