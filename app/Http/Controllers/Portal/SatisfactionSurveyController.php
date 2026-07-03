<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SatisfactionSurveyController extends Controller
{
    public function show(Request $request)
    {
        $project = $request->user()->projects()->first();
        $survey = $project?->satisfactionSurvey;

        abort_if(! $survey || $survey->isSubmitted(), 404);

        return view('portal.survey', [
            'survey' => $survey,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $project = $request->user()->projects()->first();
        $survey = $project?->satisfactionSurvey;

        abort_if(! $survey || $survey->isSubmitted(), 404);

        $survey->update($validated + ['submitted_at' => now()]);

        return redirect()->route('portal.dashboard')->with('status', 'Thanks for your feedback!');
    }
}
