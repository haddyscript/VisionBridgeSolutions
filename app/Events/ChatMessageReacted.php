<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class ChatMessageReacted implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public function __construct(public ChatMessage $message)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('project.'.$this->message->project_id.'.chat'),
            // Same reasoning as ChatMessageUpdated/ChatMessageDeleted — keeps
            // the admin conversation list in sync even when the reacting
            // thread isn't the one currently open.
            new PrivateChannel('admin.chat-inbox'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageReacted';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'projectId' => $this->message->project_id,
            'reaction' => $this->message->reaction,
        ];
    }
}
