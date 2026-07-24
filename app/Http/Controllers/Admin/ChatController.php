<?php

namespace App\Http\Controllers\Admin;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Mail\ChatMessageRepliedMail;
use App\Models\ClientNotification;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ChatController extends Controller
{
    /** Global inbox — one row per project, most recent chat activity first. */
    public function index()
    {
        return view('admin.chat.index', [
            'projects' => Project::with('user')
                ->withMax('chatMessages', 'created_at')
                ->orderByDesc('chat_messages_max_created_at')
                ->paginate(15),
        ]);
    }

    /** The chat thread itself lives as a tab on the project page (see admin/projects/_chat-thread.blade.php) — this just deep-links there. */
    public function show(Project $project)
    {
        return redirect()->route('admin.projects.show', ['project' => $project, 'tab' => 'chat']);
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $message = $project->chatMessages()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);
        $message->setRelation('project', $project);

        broadcast(new ChatMessageSent($message))->toOthers();

        if ($project->user->notify_on_replies) {
            Mail::to($project->user->email)->send(new ChatMessageRepliedMail($message));
        }

        ClientNotification::send(
            $project->user,
            'chat_reply',
            'VisionBridge sent you a message',
            $message->body,
            route('portal.chat.show'),
            $message,
        );

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

    public function markRead(Project $project)
    {
        $project->chatMessages()
            ->where('user_id', $project->user_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Marked read.']);
    }
}
