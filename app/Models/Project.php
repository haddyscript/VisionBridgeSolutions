<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'preview_url',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class)->orderBy('position');
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class)->latest();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class)->latest();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class)->latest();
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function progressPercent(): int
    {
        $total = $this->milestones->count();

        if ($total === 0) {
            return 0;
        }

        $completed = $this->milestones->where('status', 'completed')->count();

        return (int) round(($completed / $total) * 100);
    }
}
