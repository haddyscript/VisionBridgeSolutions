@extends('layouts.admin')

@section('title', $project->name.' — Onboarding Preview – Admin')
@section('page-title', $project->name)

@section('content')

<div class="mb-6 flex items-start gap-3 rounded-xl border border-gold/30 bg-gold/10 px-5 py-4">
    <svg class="w-5 h-5 text-gold-dark shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
    <div class="min-w-0">
        <p class="text-sm font-bold text-navy dark:text-white">Admin Preview — Read Only</p>
        <p class="text-sm text-gray-600 dark:text-gray-300 mt-0.5">
            This renders exactly what {{ $project->user->name }} saw (or sees) on each onboarding step, filled in with their real, already-submitted data. Nothing on this page can be edited or submitted.
        </p>
    </div>
    <a href="{{ route('admin.projects.show', $project) }}?tab=onboarding" class="ml-auto shrink-0 text-sm font-semibold text-gold-dark hover:underline">
        &larr; Back to Project
    </a>
</div>

{{-- Step switcher — mirrors the client's own progress bar but clickable, since an admin can jump to any step regardless of the client's actual progress --}}
<div class="flex items-center gap-1.5 mb-6 overflow-x-auto pb-1">
    @foreach ($steps as $n => $meta)
        <a href="{{ route('admin.projects.onboarding-preview', [$project, $n]) }}"
           class="shrink-0 flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-lg transition-colors {{ $step === $n ? 'bg-gold/15 text-gold-dark' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold {{ $step === $n ? 'bg-gold text-navy' : 'bg-gray-200 dark:bg-gray-700' }}">{{ $n }}</span>
            {{ $meta['label'] }}
        </a>
    @endforeach
</div>

{{-- The actual step content — a read-only render of that step's real client screen.
     No partial below includes a <form action> or a submit button, so there is
     nothing here that can write data even without the disabled fieldset —
     it's just an extra safety net for any native input/select/textarea/checkbox. --}}
<fieldset disabled>
    @include($stepView, $stepData)
</fieldset>

<div class="flex items-center justify-between mt-6">
    @if ($step > 1)
        <a href="{{ route('admin.projects.onboarding-preview', [$project, $step - 1]) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Previous Step
        </a>
    @else
        <span></span>
    @endif

    @if ($step < 5)
        <a href="{{ route('admin.projects.onboarding-preview', [$project, $step + 1]) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gold-dark hover:underline">
            Next Step
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    @endif
</div>

@endsection
