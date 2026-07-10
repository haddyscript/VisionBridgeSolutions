<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'impersonator_id', 'ip_address', 'user_agent', 'logged_in_at'];

    protected function casts(): array
    {
        return ['logged_in_at' => 'datetime'];
    }

    public function impersonator()
    {
        return $this->belongsTo(User::class, 'impersonator_id');
    }

    public function simpleBrowser(): string
    {
        return static::browserFromAgent($this->user_agent);
    }

    public static function browserFromAgent(?string $ua): string
    {
        $ua = $ua ?? '';

        if (str_contains($ua, 'Edg/')) return 'Microsoft Edge';
        if (str_contains($ua, 'Chrome/')) return 'Chrome';
        if (str_contains($ua, 'Firefox/')) return 'Firefox';
        if (str_contains($ua, 'Safari/') && str_contains($ua, 'Version/')) return 'Safari';
        if (str_contains($ua, 'OPR/') || str_contains($ua, 'Opera')) return 'Opera';

        return 'Unknown browser';
    }
}
