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
        'category',
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
        'timezone',
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

    public function categoryLabel(): ?string
    {
        return match ($this->category) {
            'phase' => 'Phase',
            'deposit' => 'Deposit',
            'final' => 'Final Payment',
            'one_time' => 'One-Time Payment',
            'milestone_payment' => 'Milestone Payment',
            'progress_payment' => 'Progress Payment',
            'partial_payment' => 'Partial Payment',
            'full_payment' => 'Full Payment',
            'balance_payment' => 'Balance Payment',
            'additional_payment' => 'Additional Payment',
            'change_order' => 'Extra Work / Change Order',
            'rush_fee' => 'Rush / Expedited Fee',
            'website_development' => 'Website Development',
            'landing_page_development' => 'Landing Page Development',
            'web_app_development' => 'Web Application Development',
            'ecommerce_development' => 'E-Commerce Development',
            'website_redesign' => 'Website Redesign',
            'website_migration' => 'Website Migration',
            'domain_registration' => 'Domain Registration',
            'domain_renewal' => 'Domain Renewal',
            'web_hosting' => 'Web Hosting',
            'ssl_certificate' => 'SSL Certificate',
            'email_hosting' => 'Email Hosting',
            'third_party_service' => 'Third-Party Service / API',
            'software_license_fee' => 'Software / License Fee',
            'website_maintenance' => 'Website Maintenance',
            'website_care_plan' => 'Website Care Plan',
            'technical_support' => 'Technical Support',
            'bug_fix' => 'Bug Fix / Issue Resolution',
            'security_maintenance' => 'Security Maintenance',
            'backup_recovery' => 'Backup & Recovery',
            'content_update' => 'Content Update',
            'feature_enhancement' => 'Feature Enhancement',
            'performance_optimization' => 'Performance Optimization',
            'monthly_subscription' => 'Monthly Subscription',
            'quarterly_subscription' => 'Quarterly Subscription',
            'annual_subscription' => 'Annual Subscription',
            'recurring_service' => 'Recurring Service',
            'maintenance_retainer' => 'Maintenance Retainer',
            'support_retainer' => 'Support Retainer',
            'consultation_fee' => 'Consultation Fee',
            'service_fee' => 'Service Fee',
            'setup_fee' => 'Setup Fee',
            'project_management_fee' => 'Project Management Fee',
            'design_fee' => 'Design Fee',
            'development_fee' => 'Development Fee',
            'hosting_infrastructure' => 'Hosting & Infrastructure',
            'reimbursement' => 'Reimbursement',
            'late_payment_fee' => 'Late Payment Fee',
            'cancellation_fee' => 'Cancellation Fee',
            'refund' => 'Refund',
            'refund_adjustment' => 'Refund Adjustment',
            'account_credit' => 'Credit / Account Credit',
            'payment_adjustment' => 'Payment Adjustment',
            'payment_correction' => 'Payment Correction',
            'overpayment' => 'Overpayment',
            'outstanding_balance' => 'Outstanding Balance',
            'other' => 'Other',
            default => null,
        };
    }

    public function formattedRefundedAmount(): ?string
    {
        return $this->refunded_amount !== null ? '$'.number_format($this->refunded_amount / 100, 2) : null;
    }
}
