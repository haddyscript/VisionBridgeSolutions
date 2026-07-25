<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

/** "Deleted for everyone" only — "deleted for me" never broadcasts, since it only affects the requester's own view. */
class ChatMessageDeleted implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public function __construct(public ChatMessage $message)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('project.'.$this->message->project_id.'.chat'),
            // Same reasoning as ChatMessageSent/ChatMessageUpdated — keeps
            // the admin conversation list in sync even when the deleting
            // thread isn't the one currently open.
            new PrivateChannel('admin.chat-inbox'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageDeleted';
    }

    /** Deliberately no `body` — the whole point is that it's gone. */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'projectId' => $this->message->project_id,
        ];
    }
}
