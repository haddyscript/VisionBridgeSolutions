<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class SiteVisitMonthlyStat extends Model
{
    protected $fillable = [
        'month',
        'page_views',
        'unique_visitors',
        'browsers',
        'devices',
        'top_pages',
        'top_referrers',
    ];

    protected $casts = [
        'month' => 'date',
        'browsers' => 'array',
        'devices' => 'array',
        'top_pages' => 'array',
        'top_referrers' => 'array',
    ];

    /**
     * Build a month's totals from the raw site_visits rows. Only accurate
     * while that month's raw rows are still retained — which is why
     * `visits:rollup` only ever recalculates the current and previous month.
     */
    public static function summarize(Carbon $month): array
    {
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();
        $visits = SiteVisit::whereBetween('created_at', [$start, $end]);

        $countBy = fn (string $column, int $limit) => (clone $visits)
            ->whereNotNull($column)
            ->selectRaw("{$column} as label, COUNT(*) as total")
            ->groupBy($column)
            ->orderByDesc('total')
            ->limit($limit)
            ->pluck('total', 'label')
            ->all();

        return [
            'page_views' => (clone $visits)->count(),
            'unique_visitors' => (clone $visits)->distinct()->count('visitor_id'),
            'browsers' => $countBy('browser', 10),
            'devices' => $countBy('device', 5),
            'top_pages' => $countBy('path', 10),
            'top_referrers' => $countBy('referrer_host', 10),
        ];
    }

    public static function rollUp(Carbon $month): self
    {
        return self::updateOrCreate(
            ['month' => $month->copy()->startOfMonth()->toDateString()],
            self::summarize($month),
        );
    }
}
