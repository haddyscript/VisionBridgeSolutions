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

    /**
     * Human-friendly remaining time in the verification window — drops from
     * days to hours to minutes as it closes, since "0d left" stops being
     * informative once under a day remains.
     */
    public function timeUntilReady(): string
    {
        $secondsLeft = $this->verifiesAt()->getTimestamp() - now()->getTimestamp();

        if ($secondsLeft <= 0) {
            return 'Ready';
        }

        $days = intdiv($secondsLeft, 86400);
        if ($days >= 1) {
            return "{$days}d left";
        }

        $hours = intdiv($secondsLeft, 3600);
        if ($hours >= 1) {
            return "{$hours}h left";
        }

        $minutes = max(1, intdiv($secondsLeft, 60));

        return "{$minutes}m left";
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
