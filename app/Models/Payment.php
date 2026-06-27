<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
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
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
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

    public function formattedAmount(): string
    {
        return '$'.number_format($this->amount / 100, 2);
    }
}
