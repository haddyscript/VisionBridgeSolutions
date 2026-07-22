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
        'notifiable_type',
        'notifiable_id',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    /** @param  Model|null  $notifiable  The Upload/ProjectRequest/etc. this notification is about, if any — lets resolveFor() later mark it (and only it) read once that item is resolved. */
    public static function send(User $user, string $type, string $title, ?string $description = null, ?string $url = null, ?Model $notifiable = null): self
    {
        return self::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'url' => $url,
            'notifiable_type' => $notifiable?->getMorphClass(),
            'notifiable_id' => $notifiable?->getKey(),
        ]);
    }

    /** Marks every still-unread notification tied to this specific item as read — called right before a fresh "it's done" notification is created, so old in-progress/reply notices about a now-resolved item stop lingering as unread clutter. */
    public static function resolveFor(Model $notifiable): void
    {
        self::query()
            ->where('notifiable_type', $notifiable->getMorphClass())
            ->where('notifiable_id', $notifiable->getKey())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }
}
