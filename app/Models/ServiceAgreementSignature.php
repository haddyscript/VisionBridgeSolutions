<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServiceAgreementSignature extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'service_agreement_template_id',
        'signer_name',
        'signature_image_path',
        'agreement_hash',
        'ip_address',
        'user_agent',
        'pdf_path',
        'signed_at',
    ];

    protected function casts(): array
    {
        return [
            'signed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function template()
    {
        return $this->belongsTo(ServiceAgreementTemplate::class, 'service_agreement_template_id');
    }

    public function signatureImageContents(): ?string
    {
        return Storage::disk('local')->exists($this->signature_image_path)
            ? Storage::disk('local')->get($this->signature_image_path)
            : null;
    }
}
