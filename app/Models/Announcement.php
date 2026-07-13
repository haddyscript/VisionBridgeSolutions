<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    /**
     * Selectable audiences an announcement can target (value => label). A
     * developer is also an admin/team member, so a "team" announcement is
     * seen by developers too; a "developer" announcement is not seen by
     * non-developer admins or clients. See userInAudience() below.
     */
    public const AUDIENCES = [
        'client' => 'Clients',
        'team' => 'Team',
        'developer' => 'Developers',
    ];

    protected $fillable = [
        'title',
        'body',
        'audiences',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'audiences' => 'array',
        ];
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dismissals()
    {
        return $this->hasMany(AnnouncementDismissal::class);
    }

    public function isDismissedBy(User $user): bool
    {
        return $this->dismissals()->where('user_id', $user->id)->exists();
    }

    /**
     * Whether a given user belongs to one of the named audience groups.
     */
    public static function userInAudience(User $user, string $audience): bool
    {
        return match ($audience) {
            'client' => ! $user->isAdmin(),
            'team' => $user->isAdmin(),
            'developer' => $user->isDeveloper(),
            default => false,
        };
    }

    /**
     * Whether this announcement targets an audience the user belongs to.
     * Legacy rows with no audiences set are treated as visible to everyone.
     */
    public function isVisibleTo(User $user): bool
    {
        $audiences = $this->audiences ?: array_keys(self::AUDIENCES);

        foreach ($audiences as $audience) {
            if (self::userInAudience($user, $audience)) {
                return true;
            }
        }

        return false;
    }

    /**
     * The single active announcement a user should currently see — the most
     * recent active one that targets their audience and they haven't
     * dismissed. Returns null when there's nothing to show them.
     */
    public static function activeFor(User $user): ?self
    {
        return static::where('is_active', true)
            ->whereDoesntHave('dismissals', fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->get()
            ->first(fn (self $announcement) => $announcement->isVisibleTo($user));
    }

    /**
     * Human-readable audience labels for display (e.g. ['Clients', 'Team']).
     */
    public function audienceLabels(): array
    {
        return collect($this->audiences ?: array_keys(self::AUDIENCES))
            ->map(fn ($audience) => self::AUDIENCES[$audience] ?? $audience)
            ->all();
    }
}
