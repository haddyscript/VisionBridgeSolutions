<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'preferred_at',
        'message',
        'status',
        'admin_notes',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'preferred_at' => 'datetime',
            'read_at' => 'datetime',
        ];
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }
}
