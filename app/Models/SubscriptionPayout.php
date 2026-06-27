<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayout extends Model
{
    /** Days a payout must sit clean (no dispute/refund) before it can be released. */
    public const VERIFICATION_DAYS = 7;

    protected $fillable = [
        'subscription_id',
        'stripe_invoice_id',
        'client_amount',
        'faithstack_amount',
        'status',
        'ready_at',
        'flagged_at',
        'flag_reason',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'ready_at' => 'datetime',
            'flagged_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isReady(): bool
    {
        return $this->status === 'ready';
    }

    public function isFlagged(): bool
    {
        return $this->status === 'flagged';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function verifiesAt(): \Illuminate\Support\Carbon
    {
        return $this->created_at->copy()->addDays(self::VERIFICATION_DAYS);
    }

    public function daysUntilReady(): int
    {
        return max(0, self::VERIFICATION_DAYS - $this->created_at->diffInDays(now()));
    }

    public function formattedFaithstackAmount(): string
    {
        return '$'.number_format($this->faithstack_amount / 100, 2);
    }

    public function formattedClientAmount(): string
    {
        return '$'.number_format($this->client_amount / 100, 2);
    }
}
