<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'project_id',
        'description',
        'amount',
        'currency',
        'status',
        'stripe_checkout_session_id',
        'stripe_payment_intent_id',
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
