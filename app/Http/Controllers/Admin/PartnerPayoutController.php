<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\MaintenancePlan;
use App\Models\PartnerPayout;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

class PartnerPayoutController extends Controller
{
    public function index()
    {
        // A plain with('payable.x') would fail — Subscription and Payment
        // don't share the same relations, so each morph type needs its own
        // eager-load list via morphWith().
        $payouts = PartnerPayout::with(['payable' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    Subscription::class => ['project.user', 'maintenancePlan'],
                    Payment::class => ['project.user'],
                ]);
            }])
            ->latest()
            ->get();

        return view('admin.partner-payouts.index', [
            'payouts'            => $payouts,
            'totalVerifying'     => $payouts->where('status', 'pending')->sum('faithstack_amount'),
            'totalReady'         => $payouts->where('status', 'ready')->sum('faithstack_amount'),
            'totalUndecided'     => $payouts->whereNull('faithstack_amount')->count(),
            'carePlans'          => MaintenancePlan::orderBy('sort_order')->get(),
            'faithstackDueDay'      => AppSetting::get('faithstack_payment_due_day'),
            'faithstackReminderEmail' => AppSetting::get('faithstack_reminder_email', 'johnnydavis45@yahoo.com,hadrianevarula@gmail.com'),
        ]);
    }

    /**
     * Configures the monthly FaithStack payment reminder — SendFaithStackPaymentReminder
     * emails every address here (comma-separated) 5 days before and again on
     * the due day, whenever there's a ready-to-send balance.
     */
    public function setReminderSettings(Request $request)
    {
        $validated = $request->validate([
            'faithstack_payment_due_day' => ['required', 'integer', 'min:1', 'max:28'],
            'faithstack_reminder_email' => ['required', 'string', 'max:500'],
        ]);

        $emails = array_filter(array_map('trim', explode(',', $validated['faithstack_reminder_email'])));

        foreach ($emails as $email) {
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return back()->withErrors(['faithstack_reminder_email' => "\"{$email}\" is not a valid email address."])->withInput();
            }
        }

        AppSetting::set('faithstack_payment_due_day', $validated['faithstack_payment_due_day']);
        AppSetting::set('faithstack_reminder_email', implode(',', $emails));

        return back()->with('status', 'FaithStack payment reminder settings updated.');
    }

    /**
     * Refreshes still-verifying Care Plan payout rows to match each plan's
     * current flat per-cycle payout amount (set on the Care Plan Pricing
     * page) — for backfilling rows created/changed before a plan's rate was
     * set, or picking up a rate change. One-time project payments are never
     * touched here; those stay manually entered.
     */
    public function recalculateAll()
    {
        $count = 0;

        PartnerPayout::where('status', 'pending')
            ->where('payable_type', Subscription::class)
            ->with('payable.maintenancePlan')
            ->get()
            ->each(function (PartnerPayout $payout) use (&$count) {
                $compensation = $payout->payable?->maintenancePlan?->faithstack_compensation;

                if ($compensation === null) {
                    return;
                }

                $payout->update(['faithstack_amount' => $compensation]);
                $count++;
            });

        if ($count === 0) {
            return back()->with('error', 'Nothing to recalculate — set a payout amount on a Care Plan first.');
        }

        return back()->with('status', "Recalculated FaithStack amount for {$count} ".($count === 1 ? 'row' : 'rows').'.');
    }

    public function update(Request $request, PartnerPayout $partnerPayout)
    {
        // Still inside its 7-day verification window — block release even if
        // someone forges the request, not just hide the button in the view.
        abort_if($partnerPayout->isPending(), 422, 'This payout is still in its 7-day verification window.');

        $validated = $request->validate([
            'faithstack_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        abort_if(
            $partnerPayout->faithstack_amount === null && empty($validated['faithstack_amount']),
            422,
            "This payout doesn't have a FaithStack amount yet — enter one before marking it paid."
        );

        $partnerPayout->update([
            'faithstack_amount' => $partnerPayout->faithstack_amount
                ?? (int) round($validated['faithstack_amount'] * 100),
            'status' => 'paid',
            'paid_at' => now(),
            'notes' => $validated['notes'] ?? $partnerPayout->notes,
        ]);

        return back()->with('status', 'Marked as paid to FaithStack.');
    }
}
