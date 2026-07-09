<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistantConversation extends Model
{
    protected $fillable = [
        'user_id',
        'last_message_at',
        'escalated_at',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
            'escalated_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(AssistantMessage::class)->orderBy('id');
    }

    public function isEscalated(): bool
    {
        return $this->escalated_at !== null;
    }
}
