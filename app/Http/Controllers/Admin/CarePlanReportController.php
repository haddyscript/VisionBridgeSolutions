<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CarePlanReportMail;
use App\Models\CarePlanReport;
use App\Models\ClientNotification;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class CarePlanReportController extends Controller
{
    public function index(Request $request)
    {
        $months = CarePlanReport::selectRaw('DISTINCT month')->orderByDesc('month')->pluck('month')
            ->map(fn ($m) => Carbon::parse($m)->format('Y-m'));

        $selected = $request->query('month') ?: $months->first() ?: now()->subMonthNoOverflow()->format('Y-m');
        $monthDate = Carbon::createFromFormat('Y-m', $selected)->startOfMonth();

        $reports = CarePlanReport::with('project.user', 'subscription.maintenancePlan')
            ->whereDate('month', $monthDate->toDateString())
            ->get()
            ->sortBy([fn ($a, $b) => $a->isSent() <=> $b->isSent(), fn ($a, $b) => strcmp($a->project?->user?->name ?? '', $b->project?->user?->name ?? '')]);

        return view('admin.care-plan-reports.index', [
            'reports' => $reports,
            'months' => $months,
            'selected' => $selected,
            'monthDate' => $monthDate,
            'activePlanCount' => Subscription::whereIn('status', ['active', 'past_due'])->count(),
        ]);
    }

    /** Manually draft reports for a month — same as the scheduled job, for catching up or a first run. */
    public function generate(Request $request)
    {
        $validated = $request->validate(['month' => ['required', 'date_format:Y-m']]);
        $month = Carbon::createFromFormat('Y-m', $validated['month'])->startOfMonth();

        $created = Subscription::with('project')
            ->whereIn('status', ['active', 'past_due'])
            ->whereHas('project')
            ->get()
            ->filter(fn ($s) => CarePlanReport::draftFor($s, $month))
            ->count();

        return redirect()->route('admin.care-plan-reports.index', ['month' => $validated['month']])
            ->with('success', $created ? "Created {$created} draft report(s) for {$month->format('F Y')}." : 'Every active Care Plan already has a report for that month.');
    }

    public function edit(CarePlanReport $carePlanReport)
    {
        $carePlanReport->load('project.user', 'subscription.maintenancePlan', 'sentBy');

        return view('admin.care-plan-reports.edit', ['report' => $carePlanReport]);
    }

    public function update(Request $request, CarePlanReport $carePlanReport)
    {
        $validated = $request->validate([
            'work_items' => ['nullable', 'array', 'max:100'],
            'work_items.*.title' => ['nullable', 'string', 'max:200'],
            'work_items.*.type' => ['nullable', Rule::in(array_keys(CarePlanReport::WORK_TYPES))],
            'work_items.*.date' => ['nullable', 'date'],
            'maintenance' => ['nullable', 'array'],
            'maintenance.*' => [Rule::in(array_keys(CarePlanReport::MAINTENANCE_TASKS))],
            'notes' => ['nullable', 'string', 'max:5000'],
            'action' => ['required', Rule::in(['save', 'send', 'refresh'])],
        ]);

        if ($validated['action'] === 'refresh') {
            $carePlanReport->update([
                'work_items' => CarePlanReport::collectWorkItems($carePlanReport->project, $carePlanReport->month),
            ]);

            return back()->with('success', 'Work list refreshed from this month\'s completed revisions and support tickets.');
        }

        $workItems = collect($validated['work_items'] ?? [])
            ->filter(fn ($item) => trim((string) ($item['title'] ?? '')) !== '')
            ->map(fn ($item) => [
                'title' => trim($item['title']),
                'type' => $item['type'] ?? 'other',
                'date' => $item['date'] ?? null,
            ])
            ->values()
            ->all();

        $carePlanReport->update([
            'work_items' => $workItems,
            'maintenance' => array_values($validated['maintenance'] ?? []),
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($validated['action'] === 'send' && ! $carePlanReport->isSent()) {
            $this->send($request, $carePlanReport);

            return redirect()->route('admin.care-plan-reports.index', ['month' => $carePlanReport->month->format('Y-m')])
                ->with('success', "Report sent to {$carePlanReport->project->user->name}.");
        }

        return back()->with('success', $carePlanReport->isSent() ? 'Report updated — the client sees the new version in their portal.' : 'Draft saved.');
    }

    private function send(Request $request, CarePlanReport $report): void
    {
        $report->update(['status' => 'sent', 'sent_at' => now(), 'sent_by_id' => $request->user()->id]);
        $client = $report->project->user;

        ClientNotification::send(
            $client,
            'care_plan_report',
            "Your {$report->monthLabel()} website report is ready",
            'See everything we took care of on your website this month.',
            route('portal.care-plan-reports.show', $report),
            $report,
        );

        try {
            Mail::to($client->email)->send(new CarePlanReportMail($report));
        } catch (\Throwable $e) {
            Log::error('Care Plan report email failed', ['care_plan_report_id' => $report->id, 'exception' => $e]);
        }
    }
}
