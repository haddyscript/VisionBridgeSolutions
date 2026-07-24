<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

/**
 * Purely ephemeral — nothing is persisted, this just relays "someone is
 * typing right now" over the project's existing chat channel. The receiving
 * side auto-expires the indicator a few seconds after the last event rather
 * than waiting for an explicit "stopped typing" signal, so a closed tab or
 * dropped connection can't leave it stuck showing.
 */
class ChatUserTyping implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public function __construct(public int $projectId, public bool $isFromClient)
    {
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('project.'.$this->projectId.'.chat')];
    }

    public function broadcastAs(): string
    {
        return 'UserTyping';
    }

    public function broadcastWith(): array
    {
        return ['isFromClient' => $this->isFromClient];
    }
}
