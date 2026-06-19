<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Portal\CategoryController;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $project = $request->user()->projects()->with('milestones', 'uploads')->first();

        $counts = collect(CategoryController::CATEGORIES)
            ->map(fn ($meta, $category) => [
                'label' => $meta['label'],
                'description' => $meta['description'],
                'count' => $project ? $project->uploads->where('category', $category)->count() : 0,
            ]);

        $showPaymentReminder = $request->session()->pull('show_payment_reminder', false)
            && $request->user()->hasPendingPayment();

        return view('portal.dashboard', [
            'project' => $project,
            'counts' => $counts,
            'showPaymentReminder' => $showPaymentReminder,
        ]);
    }
}
