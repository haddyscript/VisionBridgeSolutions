<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** Days a client has to approve or request a refund once a project enters 'review'. */
    public const REVIEW_WINDOW_DAYS = 7;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'preview_url',
        'status',
        'progress_override',
        'total_price',
        'review_started_at',
        'client_approved_at',
    ];

    protected function casts(): array
    {
        return [
            'review_started_at' => 'datetime',
            'client_approved_at' => 'datetime',
        ];
    }

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

    public function agreementSignatures()
    {
        return $this->hasMany(ServiceAgreementSignature::class)->latest();
    }

    public function agreementSignature()
    {
        return $this->hasOne(ServiceAgreementSignature::class)->latestOfMany();
    }

    public function hasSignedCurrentAgreement(): bool
    {
        $active = ServiceAgreementTemplate::currentActive();

        if (! $active) {
            return true; // nothing to sign yet — don't block onboarding on a missing template
        }

        return $this->agreementSignatures()->where('service_agreement_template_id', $active->id)->exists();
    }

    public function questionnaire()
    {
        return $this->hasOne(ProjectQuestionnaire::class);
    }

    public function hasCompletedQuestionnaire(): bool
    {
        return $this->questionnaire?->isCompleted() ?? false;
    }

    public function depositPayment(): ?Payment
    {
        return $this->payments->firstWhere('kind', 'deposit');
    }

    public function finalPayment(): ?Payment
    {
        return $this->payments->firstWhere('kind', 'final');
    }

    public function formattedTotalPrice(): ?string
    {
        return $this->total_price !== null ? '$'.number_format($this->total_price / 100, 2) : null;
    }

    public function reviewDeadline(): ?\Illuminate\Support\Carbon
    {
        return $this->review_started_at?->copy()->addDays(self::REVIEW_WINDOW_DAYS);
    }

    public function isReviewWindowOpen(): bool
    {
        return $this->status === 'review'
            && $this->review_started_at !== null
            && now()->lt($this->reviewDeadline());
    }

    public function daysLeftInReview(): int
    {
        if (! $this->review_started_at) {
            return 0;
        }

        return max(0, self::REVIEW_WINDOW_DAYS - $this->review_started_at->diffInDays(now()));
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
