<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
        'timezone',
        'preferred_at',
        'message',
        'status',
        'admin_notes',
        'meeting_link',
        'confirmation_sent_at',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'preferred_at' => 'datetime',
            'confirmation_sent_at' => 'datetime',
            'read_at' => 'datetime',
        ];
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function countryFlag(): ?string
    {
        if (! $this->country) {
            return null;
        }

        $match = collect(config('dial_codes'))->firstWhere('name', $this->country);

        return $match['flag'] ?? null;
    }
}
