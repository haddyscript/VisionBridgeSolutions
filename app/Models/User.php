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
        'password',
        'role',
        'stripe_customer_id',
        'theme',
        'email_verified_at',
        'activity_last_read_at',
        'notify_on_replies',
        'notify_on_consultations',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'password' => 'hashed',
        ];
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

    public function hasPendingPayment(): bool
    {
        return Payment::whereIn('project_id', $this->projects()->pluck('id'))
            ->where('status', 'pending')
            ->exists();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
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
