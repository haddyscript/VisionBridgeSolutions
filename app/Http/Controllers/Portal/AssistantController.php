<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\AssistantService;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function __construct(private AssistantService $assistant)
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
            'escalated' => $conversation->isEscalated(),
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
            'escalated' => $reply->conversation->isEscalated(),
            'remainingToday' => $this->assistant->remainingMessagesToday($request->user()),
        ]);
    }
}
