<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
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
            'faithstackRate'     => (float) AppSetting::get('faithstack_percentage', 0),
        ]);
    }

    public function setRate(Request $request)
    {
        $validated = $request->validate([
            'faithstack_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'apply_to_existing'     => ['nullable', 'boolean'],
        ]);

        $rate = (float) $validated['faithstack_percentage'];
        AppSetting::set('faithstack_percentage', $rate);

        if ($request->boolean('apply_to_existing') && $rate > 0) {
            PartnerPayout::whereNull('faithstack_amount')
                ->each(function (PartnerPayout $payout) use ($rate) {
                    $payout->update([
                        'faithstack_amount' => (int) round($payout->client_amount * $rate / 100),
                    ]);
                });
        }

        return back()->with('status', 'FaithStack payout rate updated.');
    }

    public function recalculateAll()
    {
        $rate = (float) AppSetting::get('faithstack_percentage', 0);

        if ($rate <= 0) {
            return back()->with('error', 'No FaithStack rate set — set a rate first before recalculating.');
        }

        $count = 0;
        PartnerPayout::each(function (PartnerPayout $payout) use ($rate, &$count) {
            $payout->update([
                'faithstack_amount' => (int) round($payout->client_amount * $rate / 100),
            ]);
            $count++;
        });

        return back()->with('status', "Recalculated FaithStack amount for {$count} ".str($count)->plural('row').'.');
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
