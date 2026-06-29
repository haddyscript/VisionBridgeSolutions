<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /** Days a recurring payment can stay past_due before the project is suspended. */
    public const GRACE_PERIOD_DAYS = 3;

    protected $fillable = [
        'project_id',
        'maintenance_plan_id',
        'description',
        'amount',
        'currency',
        'interval',
        'status',
        'past_due_at',
        'client_phone',
        'domain',
        'hosting_provider',
        'notes',
        'stripe_checkout_session_id',
        'stripe_subscription_id',
        'current_period_end',
        'cancel_at_period_end',
        'canceled_at',
    ];

    protected function casts(): array
    {
        return [
            'past_due_at' => 'datetime',
            'current_period_end' => 'datetime',
            'cancel_at_period_end' => 'boolean',
            'canceled_at' => 'datetime',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function maintenancePlan()
    {
        return $this->belongsTo(MaintenancePlan::class);
    }

    public function payouts()
    {
        return $this->morphMany(PartnerPayout::class, 'payable')->latest();
    }

    public function payments()
    {
        return $this->hasMany(SubscriptionPayment::class)->latest();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCanceled(): bool
    {
        return $this->status === 'canceled';
    }

    public function isPastDue(): bool
    {
        return $this->status === 'past_due';
    }

    public function isPastDueBeyondGrace(): bool
    {
        return $this->isPastDue()
            && $this->past_due_at !== null
            && now()->gt($this->past_due_at->copy()->addDays(self::GRACE_PERIOD_DAYS));
    }

    public function formattedAmount(): string
    {
        return '$'.number_format($this->amount / 100, 2).'/'.$this->interval;
    }
}
