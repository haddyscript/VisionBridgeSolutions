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
        'website_type',
        'preview_url',
        'status',
        'status_message',
        'progress_override',
        'total_price',
        'discount_percent',
        'review_started_at',
        'client_approved_at',
        'suspended_at',
    ];

    protected function casts(): array
    {
        return [
            'discount_percent' => 'decimal:2',
            'review_started_at' => 'datetime',
            'client_approved_at' => 'datetime',
            'suspended_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function carePlanAgreement()
    {
        return $this->hasOne(CarePlanAgreement::class);
    }

    public function hasAgreedToCarePlan(): bool
    {
        return $this->carePlanAgreement !== null;
    }

    public function isSuspended(): bool
    {
        return $this->suspended_at !== null;
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class)->orderBy('position');
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class)->latest();
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class)->latest();
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class)->latest();
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

    public function satisfactionSurvey()
    {
        return $this->hasOne(SatisfactionSurvey::class);
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

    /** Total price in cents after the discount percentage is applied, or null if no price is set. */
    public function discountedTotalPrice(): ?int
    {
        if ($this->total_price === null) {
            return null;
        }

        if (! $this->discount_percent) {
            return $this->total_price;
        }

        return (int) round($this->total_price * (1 - $this->discount_percent / 100));
    }

    public function formattedDiscountedTotalPrice(): ?string
    {
        $price = $this->discountedTotalPrice();

        return $price !== null ? '$'.number_format($price / 100, 2) : null;
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

    /**
     * "The" current subscription for this project — deliberately excludes
     * canceled rows, not just picking whichever was created most recently.
     * Without this, a canceled duplicate created after the real active/pending
     * one (see specs/CARE_PLAN_SUBSCRIPTION_FLOW.md §6) would incorrectly win
     * over the subscription that's actually in effect.
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class)->where('status', '!=', 'canceled')->latestOfMany();
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
     * The due date of the project's final milestone (by position) — gives
     * the client a rough completion estimate. Null if that milestone has no
     * due date set, so callers can skip rendering rather than show nothing
     * useful.
     */
    public function estimatedCompletionDate(): ?\Illuminate\Support\Carbon
    {
        return $this->milestones->sortBy('position')->last()?->due_date;
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
                    'highlight' => null,
                    'description' => $milestone->title,
                    'at' => $milestone->completed_at,
                    // Already fully shown right here on the Overview page — nothing further to link to.
                    'url' => null,
                ]);
            }
        }

        foreach ($this->uploads as $upload) {
            if ($upload->approved_at) {
                $activity->push([
                    'icon' => 'approved',
                    'title' => 'File approved',
                    'highlight' => null,
                    'description' => $upload->original_name,
                    'at' => $upload->approved_at,
                    'url' => route('portal.category', $upload->category),
                ]);
            }

            foreach ($upload->replies as $reply) {
                if ($reply->user_id === $upload->user_id) {
                    continue;
                }

                $label = \App\Http\Controllers\Portal\CategoryController::CATEGORIES[$upload->category]['label'] ?? 'submission';

                $activity->push([
                    'icon' => 'reply',
                    'title' => 'VisionBridge replied to your',
                    'highlight' => $label,
                    'description' => $reply->body,
                    'at' => $reply->created_at,
                    'url' => route('portal.category', $upload->category),
                ]);
            }
        }

        foreach ($this->payments as $payment) {
            if ($payment->isPaid() && $payment->paid_at) {
                $activity->push([
                    'icon' => 'payment',
                    'title' => 'Payment received',
                    'highlight' => null,
                    'description' => $payment->description.' — '.$payment->formattedAmount(),
                    'at' => $payment->paid_at,
                    'url' => route('portal.payments.index'),
                ]);
            }
        }

        return $activity->sortByDesc('at')->values();
    }
}
