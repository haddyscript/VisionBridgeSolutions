<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;

/**
 * Lets a super admin fire any of the app's scheduled/maintenance artisan
 * commands on demand — e.g. to check a fix landed without waiting for the
 * next scheduled run, or to re-run a one-off data fix after an incident.
 * Super-admin only (see routes/web.php): several of these touch real Stripe
 * billing or suspend real client portal access, same sensitivity as Team
 * management.
 *
 * JOBS is a fixed allow-list, not user-supplied input — Artisan::call() only
 * ever runs one of these exact signatures, never a raw string built from the
 * request, so this can't be turned into arbitrary command execution.
 */
class CronController extends Controller
{
    private const JOBS = [
        'payouts:verify' => [
            'label' => 'Verify Partner Payouts',
            'description' => "Promote FaithStack payouts (Care Plan cycles and one-time project payments) to 'ready' once they've sat clean for the verification window.",
            'schedule' => 'Daily',
        ],
        'payouts:send-faithstack-reminder' => [
            'label' => 'Send FaithStack Payment Reminder',
            'description' => 'Email the FaithStack payment reminder 5 days before, and again on, the configured monthly due day.',
            'schedule' => 'Daily',
        ],
        'projects:suspend-overdue' => [
            'label' => 'Suspend Overdue Projects',
            'description' => 'Suspend portal access for any project whose Care Plan payment has stayed past_due beyond the grace period.',
            'schedule' => 'Hourly',
        ],
        'subscriptions:send-renewal-reminders' => [
            'label' => 'Send Renewal Reminders',
            'description' => "Email clients whose Care Plan is renewing within the reminder window.",
            'schedule' => 'Daily',
        ],
        'payments:retry-failed' => [
            'label' => 'Retry Failed Payments',
            'description' => "Force an immediate retry of every past-due Care Plan subscription's unpaid invoice via Stripe, instead of waiting on Stripe's own retry schedule.",
            'schedule' => 'Twice Daily',
        ],
        'subscriptions:backfill-period-end' => [
            'label' => 'Backfill Subscription Period End',
            'description' => 'Backfill current_period_end from Stripe for active subscriptions where it is missing. One-off data fix, safe to re-run.',
            'schedule' => 'Manual only',
        ],
        'subscriptions:cancel-duplicates' => [
            'label' => 'Cancel Duplicate Care Plan Subscriptions',
            'description' => 'Cancel orphaned pending Care Plan subscriptions on projects that already have an active one. Local status change only — never touches a real Stripe subscription.',
            'schedule' => 'Manual only',
        ],
    ];

    public function index()
    {
        return view('admin.cron.index', [
            'jobs' => self::JOBS,
        ]);
    }

    public function run(Request $request)
    {
        $validated = $request->validate([
            'command' => ['required', 'string', Rule::in(array_keys(self::JOBS))],
        ]);

        $exitCode = Artisan::call($validated['command']);

        return response()->json([
            'output' => trim(Artisan::output()),
            'success' => $exitCode === 0,
        ]);
    }
}
