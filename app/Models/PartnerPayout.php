<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PartnerPayout extends Model
{
    /** Days a payout must sit clean (no dispute/refund) before it can be released. */
    public const VERIFICATION_DAYS = 7;

    protected $fillable = [
        'payable_type',
        'payable_id',
        'stripe_invoice_id',
        'stripe_payment_intent_id',
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

    protected static function booted(): void
    {
        static::creating(function (self $payout) {
            if ($payout->faithstack_amount === null) {
                $rate = (float) AppSetting::get('faithstack_percentage', 0);
                if ($rate > 0) {
                    $payout->faithstack_amount = (int) round($payout->client_amount * $rate / 100);
                }
            }
        });
    }

    public function payable()
    {
        return $this->morphTo();
    }

    /** Convenience accessor: the client + project this payout traces back to, regardless of type. */
    public function project(): ?Project
    {
        return match (true) {
            $this->payable instanceof Subscription => $this->payable->project,
            $this->payable instanceof Payment => $this->payable->project,
            default => null,
        };
    }

    /** A short label for what this payout is for — a recurring Care Plan cycle or a one-time project payment. */
    public function sourceLabel(): string
    {
        if ($this->payable instanceof Subscription) {
            return $this->payable->maintenancePlan?->name ?? $this->payable->description;
        }

        if ($this->payable instanceof Payment) {
            return $this->payable->kind ? ucfirst($this->payable->kind).' Payment' : $this->payable->description;
        }

        return 'Unknown';
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

    public function hasFaithstackAmount(): bool
    {
        return $this->faithstack_amount !== null;
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
        return $this->hasFaithstackAmount()
            ? '$'.number_format($this->faithstack_amount / 100, 2)
            : 'TBD';
    }

    public function formattedClientAmount(): string
    {
        return '$'.number_format($this->client_amount / 100, 2);
    }
}
