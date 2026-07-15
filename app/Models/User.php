<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Stripe\Customer;
use Stripe\Stripe;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'job_title',
        'referral_code',
        'referred_by_id',
        'is_super_admin',
        'restricted_access',
        'is_active',
        'stripe_customer_id',
        'theme',
        'email_verified_at',
        'activity_last_read_at',
        'welcomed_at',
        'payment_reminder_shown_at',
        'tour_completed_at',
        'onboarding_step',
        'notify_on_replies',
        'notify_on_consultations',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'activity_last_read_at' => 'datetime',
            'welcomed_at' => 'datetime',
            'payment_reminder_shown_at' => 'datetime',
            'tour_completed_at' => 'datetime',
            'onboarding_step' => 'integer',
            'is_super_admin' => 'boolean',
            'restricted_access' => 'boolean',
            'is_active' => 'boolean',
            'password' => 'hashed',
            'two_factor_secret' => 'encrypted',
            'two_factor_recovery_codes' => 'encrypted:array',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_confirmed_at !== null;
    }

    public function consumeTwoFactorRecoveryCode(string $code): bool
    {
        $codes = $this->two_factor_recovery_codes ?? [];
        $index = array_search(strtoupper(trim($code)), $codes, true);

        if ($index === false) {
            return false;
        }

        unset($codes[$index]);
        $this->update(['two_factor_recovery_codes' => array_values($codes)]);

        return true;
    }

    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(5));
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function projectRequests()
    {
        return $this->hasMany(ProjectRequest::class);
    }

    public function loginActivities()
    {
        return $this->hasMany(LoginActivity::class);
    }

    public function hasPendingPayment(): bool
    {
        return Payment::whereIn('project_id', $this->projects()->pluck('id'))
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * The one permanent, hardcoded root account (the FaithStack shared admin
     * login) — always a super admin, can never be removed or deactivated by
     * anyone else, and is the only account allowed to remove or deactivate
     * another super admin. Deliberately not a toggleable flag: the whole
     * point is that this can't be reassigned or revoked from within the app.
     */
    public const OWNER_EMAIL = 'admin@visionbridgesolutions.com';

    /**
     * Selectable job titles for admin/team accounts. Purely descriptive (a
     * label for who the person is) — it does not grant or restrict access;
     * that's governed separately by is_super_admin / restricted_access /
     * adminPermissions.
     */
    public const JOB_TITLES = [
        'Customer Support Representative',
        'Sales Representative',
        'Developer',
        'Project Manager',
        'Administrative Staff',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOwner(): bool
    {
        return $this->isAdmin() && strcasecmp($this->email, self::OWNER_EMAIL) === 0;
    }

    public function isSuperAdmin(): bool
    {
        return $this->isOwner() || ($this->isAdmin() && $this->is_super_admin);
    }

    /**
     * Whether this admin is a developer for Work Order purposes — purely the
     * existing job_title field, no separate role/permission layer. See
     * FEATURES.md for why this was chosen over a full `developer` role.
     */
    public function isDeveloper(): bool
    {
        return $this->isAdmin() && $this->job_title === 'Developer';
    }

    /** Every admin account tagged with the "Developer" job title, for assignment dropdowns. */
    public static function developers()
    {
        return self::where('role', 'admin')->where('job_title', 'Developer')->orderBy('name')->get();
    }

    public function adminPermissions()
    {
        return $this->hasMany(AdminPagePermission::class);
    }

    /** The client who referred this account (if any). */
    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    /** Accounts that signed up through this client's referral link. */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by_id');
    }

    /**
     * This client's referral code, generating and persisting a unique one on
     * first use so we never have to backfill every account up front.
     */
    public function getReferralCode(): string
    {
        if (! $this->referral_code) {
            do {
                $code = strtoupper(\Illuminate\Support\Str::random(8));
            } while (self::where('referral_code', $code)->exists());

            $this->update(['referral_code' => $code]);
        }

        return $this->referral_code;
    }

    /**
     * Super admins and anyone not explicitly restricted always have access.
     * Only meaningful for the sections listed in \App\Support\AdminPermissions
     * — anything else (dashboard, team, faq) is reachable regardless.
     */
    public function canAccessAdminPage(string $permissionKey): bool
    {
        if ($this->isSuperAdmin() || ! $this->restricted_access) {
            return true;
        }

        // Property access (not the relation method) so Eloquent caches the
        // result after the first check — the sidebar checks this ~16 times
        // per render.
        return $this->adminPermissions->contains('permission_key', $permissionKey);
    }

    public function isDarkTheme(): bool
    {
        return $this->theme === 'dark';
    }

    public function getOrCreateStripeCustomerId(): string
    {
        if ($this->stripe_customer_id) {
            return $this->stripe_customer_id;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $customer = Customer::create([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->update(['stripe_customer_id' => $customer->id]);

        return $customer->id;
    }
}
