<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientNotification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'description',
        'url',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public static function send(User $user, string $type, string $title, ?string $description = null, ?string $url = null): self
    {
        return self::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'url' => $url,
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
