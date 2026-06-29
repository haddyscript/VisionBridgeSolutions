<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServiceAgreementTemplate extends Model
{
    protected $fillable = [
        'version',
        'title',
        'body',
        'pdf_path',
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

    public function isPdfBased(): bool
    {
        return $this->pdf_path !== null;
    }

    public function pdfHash(): ?string
    {
        return $this->isPdfBased() && Storage::disk('local')->exists($this->pdf_path)
            ? hash('sha256', Storage::disk('local')->get($this->pdf_path))
            : null;
    }
}
