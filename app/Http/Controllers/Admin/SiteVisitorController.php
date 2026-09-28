<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteConversion;
use App\Models\SiteVisit;
use App\Models\SiteVisitMonthlyStat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Super-admin "Website Visitors" report. Recent numbers come live from the raw
 * site_visits log (last SiteVisit::RETENTION_DAYS days); the monthly report
 * comes from site_visit_monthly_stats, which is kept permanently — with the
 * current month always recalculated live so it's never a day stale.
 */
class SiteVisitorController extends Controller
{
    public function index(Request $request)
    {
        $range = fn (Carbon $from) => SiteVisit::where('created_at', '>=', $from);
        $totals = fn (Carbon $from) => [
            'visitors' => $range($from)->distinct()->count('visitor_id'),
            'views' => $range($from)->count(),
        ];

        $thirtyDaysAgo = now()->subDays(29)->startOfDay();

        $daily = $range($thirtyDaysAgo)
            ->selectRaw('DATE(created_at) as day, COUNT(DISTINCT visitor_id) as visitors, COUNT(*) as views')
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $chart = collect(range(0, 29))->map(function ($i) use ($thirtyDaysAgo, $daily) {
            $day = $thirtyDaysAgo->copy()->addDays($i);
            $row = $daily->get($day->toDateString());

            return [
                'label' => $day->format('M j'),
                'visitors' => (int) ($row->visitors ?? 0),
                'views' => (int) ($row->views ?? 0),
            ];
        });

        $breakdown = fn (string $column, int $limit = 8) => $range($thirtyDaysAgo)
            ->whereNotNull($column)
            ->selectRaw("{$column} as label, COUNT(DISTINCT visitor_id) as total")
            ->groupBy($column)
            ->orderByDesc('total')
            ->limit($limit)
            ->pluck('total', 'label');

        $monthly = SiteVisitMonthlyStat::orderByDesc('month')->get()->keyBy(fn ($s) => $s->month->toDateString());
        $currentMonth = now()->startOfMonth();
        $monthly->put($currentMonth->toDateString(), new SiteVisitMonthlyStat([
            'month' => $currentMonth,
            ...SiteVisitMonthlyStat::summarize($currentMonth),
        ]));
        $monthly = $monthly->sortByDesc(fn ($s) => $s->month)->values();

        // ─── Conversions (kept permanently — no IPs stored) ───────────────────
        $leadTypes = array_keys(SiteConversion::LEAD_TYPES);
        $monthTotals = $totals($currentMonth);

        $conversionCounts = SiteConversion::where('created_at', '>=', $currentMonth)
            ->selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');
        $monthLeads = (int) $conversionCounts->only($leadTypes)->sum();

        $leadBreakdown = fn (string $expression) => SiteConversion::whereIn('type', $leadTypes)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw("{$expression} as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'label');

        // Low volume, so grouped in PHP rather than DB-specific date SQL.
        $monthlyLeads = SiteConversion::whereIn('type', $leadTypes)
            ->get(['created_at'])
            ->countBy(fn ($c) => $c->created_at->copy()->startOfMonth()->toDateString());

        $search = trim((string) $request->query('search'));
        $date = $request->query('date');

        $recent = SiteVisit::query()
            ->when($search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('ip_address', 'like', "%{$search}%")
                ->orWhere('country', strtoupper($search))
                ->orWhere('path', 'like', "%{$search}%")
                ->orWhere('browser', 'like', "%{$search}%")
                ->orWhere('platform', 'like', "%{$search}%")
                ->orWhere('referrer_host', 'like', "%{$search}%")))
            ->when($date && strtotime($date), fn ($q) => $q->whereDate('created_at', $date))
            ->latest('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.site-visitors.index', [
            'today' => $totals(now()->startOfDay()),
            'week' => $totals(now()->subDays(6)->startOfDay()),
            'month' => $monthTotals,
            'conversionCounts' => $conversionCounts,
            'monthLeads' => $monthLeads,
            'conversionRate' => $monthTotals['visitors'] > 0 ? $monthLeads / $monthTotals['visitors'] * 100 : null,
            'leadSources' => $leadBreakdown("COALESCE(source, 'Direct')"),
            'leadPages' => $leadBreakdown("COALESCE(last_page, 'Unknown')"),
            'leadCampaigns' => $leadBreakdown("COALESCE(campaign, 'No campaign')"),
            'monthlyLeads' => $monthlyLeads,
            'recentConversions' => SiteConversion::with('subject')->latest('created_at')->limit(15)->get(),
            'campaigns' => $breakdown('utm_campaign', 10),
            'allTimeViews' => $monthly->sum('page_views'),
            'chart' => $chart,
            'browsers' => $breakdown('browser'),
            'devices' => $breakdown('device', 3),
            'platforms' => $breakdown('platform'),
            'topPages' => $breakdown('path', 10),
            'topReferrers' => $breakdown('referrer_host', 10),
            'countries' => $breakdown('country', 10)
                ->mapWithKeys(fn ($total, $code) => [SiteVisit::countryLabel($code) => $total]),
            'monthly' => $monthly,
            'recent' => $recent,
            'search' => $search,
            'date' => $date,
        ]);
    }
}
