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
        'progress_override',
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
        if ($this->progress_override !== null) {
            return $this->progress_override;
        }

        $total = $this->milestones->count();

        if ($total > 0) {
            $completed = $this->milestones->where('status', 'completed')->count();

            return (int) round(($completed / $total) * 100);
        }

        return match ($this->status) {
            'onboarding' => 10,
            'in_progress' => 40,
            'review' => 75,
            'launched', 'maintenance' => 100,
            default => 0,
        };
    }

    public function isProgressOverridden(): bool
    {
        return $this->progress_override !== null;
    }

    public function nextMilestone(): ?Milestone
    {
        return $this->milestones
            ->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->sortBy('due_date')
            ->first();
    }

    /**
     * Builds the project's activity feed (admin replies, approvals, completed
     * milestones, received payments), newest first. Assumes milestones,
     * uploads.replies, and payments are already eager loaded.
     */
    public function recentActivity()
    {
        $activity = collect();

        foreach ($this->milestones as $milestone) {
            if ($milestone->status === 'completed' && $milestone->completed_at) {
                $activity->push([
                    'icon' => 'milestone',
                    'title' => 'Milestone completed',
                    'description' => $milestone->title,
                    'at' => $milestone->completed_at,
                ]);
            }
        }

        foreach ($this->uploads as $upload) {
            if ($upload->approved_at) {
                $activity->push([
                    'icon' => 'approved',
                    'title' => 'File approved',
                    'description' => $upload->original_name,
                    'at' => $upload->approved_at,
                ]);
            }

            foreach ($upload->replies as $reply) {
                if ($reply->user_id === $upload->user_id) {
                    continue;
                }

                $label = \App\Http\Controllers\Portal\CategoryController::CATEGORIES[$upload->category]['label'] ?? 'submission';

                $activity->push([
                    'icon' => 'reply',
                    'title' => 'VisionBridge replied to your '.$label,
                    'description' => $reply->body,
                    'at' => $reply->created_at,
                ]);
            }
        }

        foreach ($this->payments as $payment) {
            if ($payment->isPaid() && $payment->paid_at) {
                $activity->push([
                    'icon' => 'payment',
                    'title' => 'Payment received',
                    'description' => $payment->description.' — '.$payment->formattedAmount(),
                    'at' => $payment->paid_at,
                ]);
            }
        }

        return $activity->sortByDesc('at')->values();
    }
}
