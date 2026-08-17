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

    /** Spam/bot submissions frequently repeat the same value in both name fields (e.g. "DavidtuhDS DavidtuhDS") — collapse that to one name instead of showing the duplicate everywhere it's displayed. */
    public function displayName(): string
    {
        return strcasecmp(trim($this->first_name), trim($this->last_name)) === 0
            ? $this->first_name
            : trim($this->first_name.' '.$this->last_name);
    }
}
