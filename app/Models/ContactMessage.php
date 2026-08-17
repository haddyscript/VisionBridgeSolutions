<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    public const LABELS = [
        'spam' => 'Spam',
        'not_helpful' => 'Not Helpful',
        'follow_up' => 'Follow Up',
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'organization',
        'service',
        'message',
        'label',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }
}
