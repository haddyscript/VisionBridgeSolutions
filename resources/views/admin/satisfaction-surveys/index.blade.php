@extends('layouts.admin')

@section('title', 'Satisfaction Surveys – Admin')
@section('page-title', 'Satisfaction Surveys')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Average Rating</p>
        <p class="text-2xl font-bold text-navy">{{ $averageRating }} <span class="text-sm font-normal text-gray-400">/ 5</span></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Responses</p>
        <p class="text-2xl font-bold text-navy">{{ $totalSubmitted }}</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-5 max-w-3xl">
    @if ($surveys->isEmpty())
        <p class="text-sm text-gray-400 text-center py-6">No survey responses yet.</p>
    @else
        <div class="space-y-2.5">
            @foreach ($surveys as $survey)
                <div class="rounded-lg border border-gray-200 px-4 py-3">
                    <div class="flex items-center justify-between gap-4 mb-1">
                        <p class="text-sm font-semibold text-navy">{{ $survey->project->name }} — {{ $survey->user->name }}</p>
                        <span class="text-sm font-bold text-gold-dark">{{ $survey->rating }} / 5</span>
                    </div>
                    @if ($survey->feedback)
                        <p class="text-sm text-gray-600">{{ $survey->feedback }}</p>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">Submitted {{ $survey->submitted_at->format('M j, Y') }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $surveys->links() }}</div>
    @endif
</div>

@endsection
