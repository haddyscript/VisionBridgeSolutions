@extends('layouts.portal')

@section('title', 'Overview – Client Portal')
@section('page-title', 'Overview')

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
        $categoryIcons = [
            'image' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
            'video' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
            'logo' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343',
            'document' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'marketing' => 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z',
            'content' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z',
            'revision' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
        ];
    @endphp

    @if ($project->status === 'onboarding')
        <div class="rounded-xl p-6 mb-8" style="background:linear-gradient(135deg,#111D33,#1B2A4A);">
            <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-2">Welcome to Your Client Portal</p>
            <h2 class="font-display text-xl font-bold text-white mb-2">Let's get your project started 👋</h2>
            <p class="text-sm text-white/60 mb-5 max-w-2xl">
                This is where you'll share everything we need to build your website. Use the sections below to
                upload files and submit content — we'll review everything and keep your progress updated here.
            </p>
            <div class="flex flex-col sm:flex-row sm:items-start gap-3">
                @php $steps = [
                    'Upload your logo, photos, and any documents',
                    'Submit your website content and marketing materials',
                    'Track your project\'s progress on this page anytime',
                ]; @endphp
                @foreach ($steps as $i => $step)
                    <div class="flex items-start gap-2.5 flex-1">
                        <span class="w-5 h-5 rounded-full bg-gold/20 text-gold text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $i + 1 }}</span>
                        <span class="text-sm text-white/80">{{ $step }}</span>
                    </div>
                    @if (! $loop->last)
                        <svg class="hidden sm:block w-4 h-4 text-white/25 shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- Project header --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div>
                <h2 class="font-display text-2xl font-bold text-navy">{{ $project->name }}</h2>
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

    {{-- Category tiles --}}
    <h3 class="font-display text-base font-bold text-navy mb-4">Project Sections</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($counts as $cat => $info)
            <a href="{{ route('portal.category', $cat) }}" class="bg-white rounded-xl border border-gray-200 p-5 hover:border-gold/40 hover:shadow-sm transition-all group">
                <div class="w-10 h-10 rounded-lg bg-navy/5 group-hover:bg-gold/15 flex items-center justify-center mb-3 transition-colors">
                    <svg class="w-5 h-5 text-navy group-hover:text-gold-dark transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $categoryIcons[$cat] }}"/>
                    </svg>
                </div>
                <p class="font-semibold text-navy text-sm">{{ $info['label'] }}</p>
                <p class="text-xs text-gray-400 mt-1 mb-2">{{ $info['description'] }}</p>
                @if ($info['count'] > 0)
                    <p class="text-xs font-medium text-teal-dark">{{ $info['count'] }} item{{ $info['count'] === 1 ? '' : 's' }} uploaded</p>
                @else
                    <p class="text-xs font-medium text-gold-dark">Nothing here yet &middot; tap to add</p>
                @endif
            </a>
        @endforeach
    </div>

@endif

@endsection
