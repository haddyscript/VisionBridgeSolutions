<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class ChatMessageUpdated implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public function __construct(public ChatMessage $message)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('project.'.$this->message->project_id.'.chat'),
            // Keeps the admin conversation list's own copy of this project's
            // activity in sync too — without this, an edit only reaches
            // whoever already has this specific thread open (see
            // ChatMessageSent, which broadcasts on both channels for the
            // same reason).
            new PrivateChannel('admin.chat-inbox'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'projectId' => $this->message->project_id,
            'body' => $this->message->body,
            'editedAt' => $this->message->edited_at->diffForHumans(),
        ];
    }
}
