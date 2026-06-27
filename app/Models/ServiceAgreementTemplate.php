<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAgreementTemplate extends Model
{
    protected $fillable = [
        'version',
        'title',
        'body',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function signatures()
    {
        return $this->hasMany(ServiceAgreementSignature::class);
    }

    public static function currentActive(): ?self
    {
        return static::where('is_active', true)->latest('version')->first();
    }
}
