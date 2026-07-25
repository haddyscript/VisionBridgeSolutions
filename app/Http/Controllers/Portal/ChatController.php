<?php

namespace App\Http\Controllers\Portal;

use App\Events\ChatMessageDeleted;
use App\Events\ChatMessageSent;
use App\Events\ChatMessageUpdated;
use App\Events\ChatUserTyping;
use App\Http\Controllers\Controller;
use App\Mail\NewClientChatMessageMail;
use App\Models\ChatMessage;
use App\Models\Project;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ChatController extends Controller
{
    public function show(Request $request)
    {
        $project = $request->user()->projects()->first();
        $messages = $project?->chatMessages()->whereNull('hidden_for_client_at')->with('user')->get();

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

        $this->broadcastSafely(new ChatMessageSent($message));

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

    /** Editing your own message — the client can only ever edit messages they sent themselves. */
    public function update(Request $request, ChatMessage $message)
    {
        $this->authorizeOwnMessage($request, $message);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        // Direct property assignment, not update([...]) — edited_at/deleted_at/
        // hidden_for_*_at are deliberately not mass-assignable (see
        // ChatMessage::$fillable), so lifecycle timestamps are only ever set
        // this way, never from an array that could trace back to request input.
        $message->body = $validated['body'];
        $message->edited_at = now();
        $message->save();

        $this->broadcastSafely(new ChatMessageUpdated($message));

        return response()->json([
            'message' => 'Updated.',
            'body' => $message->body,
            'editedAt' => $message->edited_at->diffForHumans(),
        ]);
    }

    /** "Delete for everyone" — a tombstone visible to both sides, sender-only. */
    public function destroyForEveryone(Request $request, ChatMessage $message)
    {
        $this->authorizeOwnMessage($request, $message);

        $message->deleted_at = now();
        $message->save();

        $this->broadcastSafely(new ChatMessageDeleted($message));

        return response()->json(['message' => 'Deleted.']);
    }

    /** "Delete for me" — hides this one message from the client's own thread only; the team's copy is unaffected. Never broadcasts. */
    public function hideForMe(Request $request, ChatMessage $message)
    {
        $this->authorizeProject($request, $message->project);

        $message->hidden_for_client_at = now();
        $message->save();

        return response()->json(['message' => 'Removed.']);
    }

    public function markRead(Request $request, Project $project)
    {
        $this->authorizeProject($request, $project);

        $count = $project->chatMessages()
            ->where('user_id', '!=', $project->user_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Marked read.', 'count' => $count]);
    }

    /** Ephemeral "I'm typing" ping — nothing persisted, just relayed to whoever else is on this project's channel. */
    public function typing(Request $request, Project $project)
    {
        $this->authorizeProject($request, $project);

        $this->broadcastSafely(new ChatUserTyping($project->id, true));

        return response()->noContent();
    }

    private function authorizeProject(Request $request, Project $project): void
    {
        abort_unless($project->user_id === $request->user()->id, 403);
    }

    private function authorizeOwnMessage(Request $request, ChatMessage $message): void
    {
        abort_unless($message->project->user_id === $request->user()->id, 403);
        abort_unless($message->user_id === $request->user()->id, 403);
        abort_if($message->isDeleted(), 422);
    }

    /**
     * A Pusher hiccup (latency, a transient outage) is a third-party problem
     * with a side effect — it must never fail the request for an action
     * (send/edit/delete/typing) that already succeeded in the database
     * before this runs. Failures are logged, never surfaced as a 500.
     */
    private function broadcastSafely(ShouldBroadcastNow $event): void
    {
        try {
            broadcast($event)->toOthers();
        } catch (Throwable $e) {
            report($e);
        }
    }
}
