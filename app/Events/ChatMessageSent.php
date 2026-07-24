<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

/**
 * Broadcast synchronously (ShouldBroadcastNow, not ShouldBroadcast) — there's
 * no queue worker in this app (QUEUE_CONNECTION=sync, shared hosting with no
 * persistent process), so a queued broadcast would have nothing to pick it up
 * outside of the current request anyway. This just makes that explicit.
 */
class ChatMessageSent implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public function __construct(public ChatMessage $message)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('project.'.$this->message->project_id.'.chat'),
            // Lets the admin conversation list bump/update this client's row
            // live even when nobody's currently looking at this project's
            // own thread (the channel above only reaches an open thread).
            new PrivateChannel('admin.chat-inbox'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'projectId' => $this->message->project_id,
            'body' => $this->message->body,
            'userId' => $this->message->user_id,
            'senderName' => $this->message->user?->name,
            'isFromClient' => $this->message->user_id === $this->message->project->user_id,
            'sentAt' => $this->message->created_at->diffForHumans(),
        ];
    }
}
