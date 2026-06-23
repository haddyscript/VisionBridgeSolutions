<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Milestone;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month')
            ? Carbon::createFromFormat('Y-m', $request->query('month'))->startOfMonth()
            : now()->startOfMonth();

        $rangeStart = $month->copy()->startOfMonth();
        $rangeEnd = $month->copy()->endOfMonth();

        $consultations = Consultation::whereBetween('preferred_at', [$rangeStart, $rangeEnd])->get();
        $milestones = Milestone::with('project')->whereBetween('due_date', [$rangeStart, $rangeEnd])->get();

        $eventsByDay = [];

        foreach ($consultations as $consultation) {
            $key = $consultation->preferred_at->format('Y-m-d');
            $eventsByDay[$key][] = [
                'type' => 'consultation',
                'title' => $consultation->name,
                'time' => $consultation->preferred_at->format('g:ia'),
                'url' => route('admin.consultations.show', $consultation),
            ];
        }

        foreach ($milestones as $milestone) {
            $key = $milestone->due_date->format('Y-m-d');
            $eventsByDay[$key][] = [
                'type' => 'milestone',
                'title' => $milestone->title.' — '.$milestone->project->name,
                'time' => null,
                'url' => route('admin.projects.show', $milestone->project),
            ];
        }

        return view('admin.calendar.index', [
            'month' => $month,
            'eventsByDay' => $eventsByDay,
            'prevMonth' => $month->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $month->copy()->addMonth()->format('Y-m'),
        ]);
    }
}
