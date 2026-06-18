@extends('layouts.portal')

@section('title', 'Client Portal – VisionBridge Solutions')

@section('content')

@if (! $project)

    <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
        <p class="text-gray-500">No project has been set up for your account yet. Please contact your VisionBridge representative.</p>
    </div>

@else

    @php
        $statusLabels = [
            'onboarding'  => 'Onboarding',
            'in_progress' => 'In Progress',
            'review'      => 'In Review',
            'launched'    => 'Launched',
            'maintenance' => 'Maintenance',
        ];
        $uploadsByCategory = $project->uploads->groupBy('category');
        $empty = collect();
    @endphp

    {{-- Project header --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div>
                <h1 class="font-display text-2xl font-bold text-navy">{{ $project->name }}</h1>
                @if ($project->description)
                    <p class="text-gray-500 text-sm mt-1">{{ $project->description }}</p>
                @endif
            </div>
            <span class="inline-block text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full bg-gold/15 text-gold-dark">
                {{ $statusLabels[$project->status] ?? $project->status }}
            </span>
        </div>

        <div class="flex items-center justify-between text-sm text-gray-500 mb-2">
            <span>Project Progress</span>
            <span class="font-semibold text-navy">{{ $project->progressPercent() }}%</span>
        </div>
        <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
            <div class="h-full bg-gold rounded-full" style="width: {{ $project->progressPercent() }}%"></div>
        </div>

        @if ($project->milestones->isNotEmpty())
            <ul class="mt-5 space-y-2">
                @foreach ($project->milestones as $milestone)
                    <li class="flex items-center gap-2.5 text-sm">
                        @if ($milestone->status === 'completed')
                            <span class="w-4 h-4 rounded-full bg-teal flex items-center justify-center shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-gray-400 line-through">{{ $milestone->title }}</span>
                        @elseif ($milestone->status === 'in_progress')
                            <span class="w-4 h-4 rounded-full border-2 border-gold shrink-0"></span>
                            <span class="text-navy font-medium">{{ $milestone->title }}</span>
                        @else
                            <span class="w-4 h-4 rounded-full border-2 border-gray-300 shrink-0"></span>
                            <span class="text-gray-500">{{ $milestone->title }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- File uploads --}}
    <h2 class="font-display text-lg font-bold text-navy mb-4">Project Files</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        @include('portal.partials.file-upload-section', ['category' => 'image', 'label' => 'Images', 'accept' => 'image/*', 'items' => $uploadsByCategory->get('image', $empty)])
        @include('portal.partials.file-upload-section', ['category' => 'video', 'label' => 'Videos', 'accept' => 'video/*', 'items' => $uploadsByCategory->get('video', $empty)])
        @include('portal.partials.file-upload-section', ['category' => 'logo', 'label' => 'Logos', 'accept' => 'image/*', 'items' => $uploadsByCategory->get('logo', $empty)])
        @include('portal.partials.file-upload-section', ['category' => 'document', 'label' => 'Documents', 'accept' => '.pdf,.doc,.docx,.txt', 'items' => $uploadsByCategory->get('document', $empty)])
        @include('portal.partials.file-upload-section', ['category' => 'marketing', 'label' => 'Marketing Materials', 'accept' => '', 'items' => $uploadsByCategory->get('marketing', $empty)])
    </div>

    {{-- Content & revisions --}}
    <h2 class="font-display text-lg font-bold text-navy mb-4">Content &amp; Revisions</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @include('portal.partials.text-submission-section', ['category' => 'content', 'label' => 'Submit Website Content', 'placeholder' => 'Paste or describe the website copy you would like used...', 'items' => $uploadsByCategory->get('content', $empty)])
        @include('portal.partials.text-submission-section', ['category' => 'revision', 'label' => 'Submit Revisions', 'placeholder' => 'Describe the changes you would like made...', 'items' => $uploadsByCategory->get('revision', $empty)])
    </div>

@endif

@endsection
