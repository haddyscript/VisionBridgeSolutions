<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    // Deliberately excludes read_at/edited_at/deleted_at/hidden_for_*_at —
    // these are lifecycle state, never user-supplied input, and are always
    // set via direct property assignment (`$message->deleted_at = now();
    // $message->save();`) or a query-builder bulk update(), neither of which
    // needs (or should have) mass-assignment access to them.
    protected $fillable = [
        'project_id',
        'user_id',
        'body',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'edited_at' => 'datetime',
            'deleted_at' => 'datetime',
            'hidden_for_client_at' => 'datetime',
            'hidden_for_admin_at' => 'datetime',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** "Deleted for everyone" — a tombstone. `body` stays in the row but must never be rendered once this is set. */
    public function isDeleted(): bool
    {
        return $this->deleted_at !== null;
    }

    public function isEdited(): bool
    {
        return $this->edited_at !== null;
    }
}
