<?php

namespace App\Http\Controllers\Admin;

use App\Events\ChatMessageDeleted;
use App\Events\ChatMessageSent;
use App\Events\ChatMessageUpdated;
use App\Http\Controllers\Controller;
use App\Mail\ChatMessageRepliedMail;
use App\Models\ChatMessage;
use App\Models\ClientNotification;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ChatController extends Controller
{
    /** One centralized inbox — every client's conversation on the left, the selected one open on the right (see admin/chat/index.blade.php). */
    public function index()
    {
        return $this->renderInbox(null);
    }

    public function show(Project $project)
    {
        return $this->renderInbox($project);
    }

    private function renderInbox(?Project $activeProject)
    {
        $projects = Project::with('user')
            ->withMax('chatMessages', 'created_at')
            ->orderByDesc('chat_messages_max_created_at')
            ->get();

        if ($activeProject) {
            $activeProject->load([
                'user',
                'chatMessages' => fn ($q) => $q->whereNull('hidden_for_admin_at')->with('user'),
            ]);
        }

        return view('admin.chat.index', [
            'projects' => $projects,
            'activeProject' => $activeProject,
        ]);
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
            dispatch(function () use ($project, $message) {
                Mail::to($project->user->email)->send(new ChatMessageRepliedMail($message));
            })->afterResponse();
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

    /** Editing a team message — any admin can edit any team reply (there's no per-admin identity shown in the thread, it's presented as one shared "VisionBridge Team" voice), but never a client's own message. */
    public function update(Request $request, ChatMessage $message)
    {
        $this->authorizeTeamMessage($message);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $message->update([
            'body' => $validated['body'],
            'edited_at' => now(),
        ]);

        broadcast(new ChatMessageUpdated($message))->toOthers();

        return response()->json([
            'message' => 'Updated.',
            'body' => $message->body,
            'editedAt' => $message->edited_at->diffForHumans(),
        ]);
    }

    /** "Delete for everyone" — a tombstone visible to both sides. */
    public function destroyForEveryone(ChatMessage $message)
    {
        $this->authorizeTeamMessage($message);

        $message->update(['deleted_at' => now()]);

        broadcast(new ChatMessageDeleted($message))->toOthers();

        return response()->json(['message' => 'Deleted.']);
    }

    /** "Delete for me" — hides this one message from the admin/team side only; the client's copy is unaffected. Never broadcasts. */
    public function hideForMe(ChatMessage $message)
    {
        $message->update(['hidden_for_admin_at' => now()]);

        return response()->json(['message' => 'Removed.']);
    }

    public function markRead(Project $project)
    {
        $project->chatMessages()
            ->where('user_id', $project->user_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Marked read.']);
    }

    private function authorizeTeamMessage(ChatMessage $message): void
    {
        abort_if($message->user_id === $message->project->user_id, 403);
        abort_if($message->isDeleted(), 422);
    }
}
