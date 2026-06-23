<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
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
        $tasks = CalendarEvent::whereBetween('date', [$rangeStart, $rangeEnd])->orderBy('time')->get();

        $eventsByDay = [];

        foreach ($tasks as $task) {
            $key = $task->date->format('Y-m-d');
            $eventsByDay[$key][] = [
                'type' => 'task',
                'title' => $task->title,
                'time' => $task->time ? Carbon::parse($task->time)->format('g:ia') : null,
                'url' => null,
                'id' => $task->id,
            ];
        }

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
            'tasks' => $tasks,
            'prevMonth' => $month->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $month->copy()->addMonth()->format('Y-m'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'time' => ['nullable', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $request->user()->calendarEvents()->create($validated);

        return back()->with('status', 'Task added to calendar.');
    }

    public function destroy(CalendarEvent $calendarEvent)
    {
        $calendarEvent->delete();

        return back()->with('status', 'Task removed.');
    }
}
