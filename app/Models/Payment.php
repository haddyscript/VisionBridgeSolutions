<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** How many days after payment a client can still request a refund. */
    public const REFUND_REQUEST_WINDOW_DAYS = 30;

    protected $fillable = [
        'project_id',
        'description',
        'kind',
        'amount',
        'currency',
        'status',
        'stripe_checkout_session_id',
        'stripe_payment_intent_id',
        'stripe_receipt_url',
        'paid_at',
        'refunded_amount',
        'refunded_at',
        'stripe_refund_id',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'refunded_at' => 'datetime',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function payouts()
    {
        return $this->morphMany(PartnerPayout::class, 'payable')->latest();
    }

    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class)->latest();
    }

    /**
     * Whether a client can still submit a new refund request for this
     * payment — must be paid, within the request window, and not already
     * have a pending or approved request on file (a declined one can be
     * re-requested).
     */
    public function isRefundRequestable(): bool
    {
        if (! $this->isPaid() || ! $this->paid_at) {
            return false;
        }

        if ($this->paid_at->lt(now()->subDays(self::REFUND_REQUEST_WINDOW_DAYS))) {
            return false;
        }

        return ! $this->refundRequests()->whereIn('status', ['pending', 'approved'])->exists();
    }

    public function isDeposit(): bool
    {
        return $this->kind === 'deposit';
    }

    public function isFinal(): bool
    {
        return $this->kind === 'final';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    public function formattedAmount(): string
    {
        return '$'.number_format($this->amount / 100, 2);
    }

    public function formattedRefundedAmount(): ?string
    {
        return $this->refunded_amount !== null ? '$'.number_format($this->refunded_amount / 100, 2) : null;
    }
}
