<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteCheck;
use App\Support\WebsiteChecker;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WebsiteCheckController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $leads = WebsiteCheck::leads()
            ->when($status && isset(WebsiteCheck::STATUSES[$status]), fn ($q) => $q->where('status', $status))
            ->when($search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('organization', 'like', "%{$search}%")
                ->orWhere('url', 'like', "%{$search}%")))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $monthStart = now()->startOfMonth();
        $checksThisMonth = WebsiteCheck::where('created_at', '>=', $monthStart)->count();
        $leadsThisMonth = WebsiteCheck::leads()->where('created_at', '>=', $monthStart)->count();

        return view('admin.website-checks.index', [
            'leads' => $leads,
            'search' => $search,
            'status' => $status,
            'stats' => [
                'checks' => $checksThisMonth,
                'leads' => $leadsThisMonth,
                'rate' => $checksThisMonth ? round($leadsThisMonth / $checksThisMonth * 100) : 0,
                'new' => WebsiteCheck::leads()->where('status', 'new')->count(),
                'won' => WebsiteCheck::leads()->where('status', 'won')->count(),
            ],
        ]);
    }

    public function show(WebsiteCheck $websiteCheck)
    {
        abort_unless($websiteCheck->email, 404);

        return view('admin.website-checks.show', [
            'check' => $websiteCheck,
            'categories' => WebsiteChecker::CATEGORIES,
        ]);
    }

    public function update(Request $request, WebsiteCheck $websiteCheck)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(WebsiteCheck::STATUSES))],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $websiteCheck->update($validated);

        return back()->with('success', 'Lead updated.');
    }
}
