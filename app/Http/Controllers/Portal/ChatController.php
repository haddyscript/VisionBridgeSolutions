<?php

namespace App\Http\Controllers\Portal;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Mail\NewClientChatMessageMail;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ChatController extends Controller
{
    public function show(Request $request)
    {
        $project = $request->user()->projects()->first();
        $messages = $project?->chatMessages()->with('user')->get();

        return view('portal.chat', [
            'project' => $project,
            'messages' => $messages,
        ]);
    }

    public function store(Request $request, Project $project)
    {
        $this->authorizeProject($request, $project);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $message = $project->chatMessages()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);
        $message->setRelation('project', $project);

        broadcast(new ChatMessageSent($message))->toOthers();

        dispatch(function () use ($message) {
            Mail::to(config('mail.support_address'))->send(new NewClientChatMessageMail($message));
        })->afterResponse();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Sent.',
                'id' => $message->id,
                'body' => $message->body,
                'sentAt' => $message->created_at->diffForHumans(),
            ]);
        }

        return back()->with('status', 'Sent.');
    }

    public function markRead(Request $request, Project $project)
    {
        $this->authorizeProject($request, $project);

        $project->chatMessages()
            ->where('user_id', '!=', $project->user_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Marked read.']);
    }

    private function authorizeProject(Request $request, Project $project): void
    {
        abort_unless($project->user_id === $request->user()->id, 403);
    }
}
