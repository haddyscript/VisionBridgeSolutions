<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WebsiteCheck extends Model
{
    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'consultation_booked' => 'Consultation Booked',
        'won' => 'Became a Client',
        'not_interested' => 'Not Interested',
    ];

    protected $fillable = [
        'token',
        'url',
        'final_url',
        'score',
        'checks',
        'name',
        'email',
        'organization',
        'phone',
        'status',
        'admin_notes',
        'ip_address',
        'report_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'checks' => 'array',
            'report_sent_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    /** Only checks where the visitor left their details. */
    public function scopeLeads(Builder $query): Builder
    {
        return $query->whereNotNull('email');
    }

    public function host(): string
    {
        return (string) parse_url($this->final_url ?: $this->url, PHP_URL_HOST);
    }

    public function issues(): array
    {
        return array_values(array_filter($this->checks, fn ($c) => $c['status'] !== 'pass'));
    }

    /** Failures before warnings, for the "top issues" teaser. */
    public function topIssues(int $limit = 3): array
    {
        $issues = $this->issues();
        usort($issues, fn ($a, $b) => ($a['status'] === 'fail' ? 0 : 1) <=> ($b['status'] === 'fail' ? 0 : 1));

        return array_slice($issues, 0, $limit);
    }

    public function grade(): array
    {
        return match (true) {
            $this->score >= 85 => ['label' => 'Great', 'color' => '#2A9D8F'],
            $this->score >= 65 => ['label' => 'Needs Some Work', 'color' => '#C9A84C'],
            default => ['label' => 'Needs Attention', 'color' => '#DC2626'],
        };
    }
}
