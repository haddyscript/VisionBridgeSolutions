<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Portal\CategoryController;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $project = $request->user()->projects()->with('milestones', 'uploads.replies', 'payments', 'recommendations')->first();

        $recommendations = $project?->recommendations->filter(fn ($r) => $r->isVisibleToClient()) ?? collect();

        $counts = collect(CategoryController::CATEGORIES)
            ->map(fn ($meta, $category) => [
                'label' => $meta['label'],
                'description' => $meta['description'],
                'why' => $meta['why'],
                'count' => $project ? $project->uploads->where('category', $category)->count() : 0,
            ]);

        $pendingPayment = $project?->payments->firstWhere('status', 'pending');

        $showPaymentReminder = $request->session()->pull('show_payment_reminder', false)
            && $pendingPayment !== null;

        $user = $request->user();
        $firstVisit = is_null($user->welcomed_at);

        if ($project) {
            $updates = ['activity_last_read_at' => now()];
            if ($firstVisit) {
                $updates['welcomed_at'] = now();
            }
            $user->update($updates);
        }

        return view('portal.dashboard', [
            'project' => $project,
            'counts' => $counts,
            'showPaymentReminder' => $showPaymentReminder,
            'pendingPayment' => $pendingPayment,
            'activity' => $project ? $project->recentActivity()->take(8) : collect(),
            'recommendations' => $recommendations,
            'firstVisit' => $firstVisit,
        ]);
    }
}
