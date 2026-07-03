<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SatisfactionSurvey;

class SatisfactionSurveyController extends Controller
{
    public function index()
    {
        $submitted = SatisfactionSurvey::whereNotNull('submitted_at')->with('project', 'user');

        return view('admin.satisfaction-surveys.index', [
            'surveys' => $submitted->clone()->latest('submitted_at')->paginate(15),
            'averageRating' => round($submitted->clone()->avg('rating') ?? 0, 1),
            'totalSubmitted' => $submitted->clone()->count(),
        ]);
    }
}
