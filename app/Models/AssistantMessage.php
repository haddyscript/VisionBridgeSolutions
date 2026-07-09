<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistantMessage extends Model
{
    protected $fillable = [
        'assistant_conversation_id',
        'role',
        'content',
    ];

    public function conversation()
    {
        return $this->belongsTo(AssistantConversation::class, 'assistant_conversation_id');
    }
}
