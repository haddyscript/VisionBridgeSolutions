<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebsiteTypeController extends Controller
{
    public const TYPES = [
        'Church Website',
        'Ministry Website',
        'Nonprofit Website',
        'Small Business Website',
        'E-commerce Website',
        'Custom Website',
    ];

    public function show(Request $request)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project, 404);

        if ($project->website_type) {
            return redirect()->route('portal.care-plan-agreement.show');
        }

        return view('portal.website-type', [
            'project' => $project,
            'types'   => self::TYPES,
        ]);
    }

    public function store(Request $request)
    {
        $project = $request->user()->projects()->first();

        abort_unless($project, 404);

        $validated = $request->validate([
            'website_type' => ['required', 'string', 'in:' . implode(',', self::TYPES)],
        ]);

        $project->update(['website_type' => $validated['website_type']]);

        $request->user()->update(['onboarding_step' => 7]);

        return redirect()->route('portal.care-plan-agreement.show')
            ->with('status', 'Got it — next, select your Website Care Plan.');
    }
}
