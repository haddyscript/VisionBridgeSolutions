@extends('layouts.admin')

@section('title', $project->name.' – Admin')
@section('page-title', $project->name)

@section('content')

@php
    $categories = \App\Http\Controllers\Portal\CategoryController::CATEGORIES;

    $statusLabels = [
        'onboarding'  => 'Onboarding',
        'in_progress' => 'In Progress',
        'review'      => 'In Review',
        'launched'    => 'Launched',
        'maintenance' => 'Care',
        'canceled'    => 'Canceled',
    ];
    // Color-codes the Project Status dropdown so each stage is recognizable
    // at a glance instead of reading as plain text.
    $projectStatusDots = [
        'onboarding'  => 'bg-gray-400',
        'in_progress' => 'bg-gold',
        'review'      => 'bg-amber-500',
        'launched'    => 'bg-teal',
        'maintenance' => 'bg-blue-500',
        'canceled'    => 'bg-red-500',
    ];
    $milestoneStatuses = ['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed'];
    // Drives both the row's left-accent/tint and the status select's own
    // badge coloring — one source of truth for "what does this state look
    // like," instead of scattered ad-hoc classes per status.
    $milestoneStateStyles = [
        'completed' => [
            'card' => 'border-l-4 border-l-teal border-teal/20 bg-teal/[0.04] dark:bg-teal/[0.06]',
            'badge' => 'bg-teal/15 text-teal-dark border-teal/30',
        ],
        'in_progress' => [
            'card' => 'border-l-4 border-l-gold border-gold/20 bg-gold/[0.04] dark:bg-gold/[0.06]',
            'badge' => 'bg-gold/15 text-gold-dark border-gold/30',
        ],
        'pending' => [
            'card' => 'border-l-4 border-l-gray-300 dark:border-l-gray-600 border-gray-200 dark:border-gray-700',
            'badge' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 border-gray-200 dark:border-gray-600',
        ],
    ];
    $empty = collect();
@endphp

<a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-navy mb-6">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    All Projects
</a>

{{-- Client + project header --}}
<div id="header-card" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">

    {{-- Suspension banner --}}
    @if ($project->isSuspended())
        <div class="flex items-center justify-between gap-3 bg-red-50 dark:bg-red-500/10 border-b border-red-200 dark:border-red-500/20 px-6 py-3">
            <p class="text-sm text-red-600 dark:text-red-400">
                <strong>Suspended for non-payment</strong> — portal access blocked since {{ $project->suspended_at->format('M j, Y \a\t g:i A') }}.
            </p>
            <form method="POST" action="{{ route('admin.projects.restore-access', $project) }}" data-ajax-target="header-card">
                @csrf
                <button type="submit" class="shrink-0 border border-red-300 text-red-600 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors">
                    Restore Access
                </button>
            </form>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 divide-y lg:divide-y-0 lg:divide-x divide-gray-100 dark:divide-gray-700">

        {{-- Left: Status & Info Sidebar --}}
        <div class="p-6 flex flex-col h-full">
            {{-- Client identity --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-3">Client</p>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold shrink-0"
                        style="background:#1B2A4A20; color:#1B2A4A;">
                        {{ strtoupper(substr($project->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-navy dark:text-white">{{ $project->user->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $project->user->email }}</p>
                        @if ($project->user->phone)
                            <p class="text-sm text-gray-400 dark:text-gray-500">{{ $project->user->phone }}</p>
                        @endif
                    </div>
                </div>
                <button type="button" onclick="openResetPasswordModal()"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 dark:text-amber-400 border border-amber-300 dark:border-amber-500/40 hover:bg-amber-50 dark:hover:bg-amber-500/10 hover:border-amber-400 dark:hover:border-amber-400/60 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Reset Client Password
                </button>
            </div>

            {{-- Project Status: auto-saves on change, stays its own form --}}
            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                <form method="POST" action="{{ route('admin.projects.update', $project) }}" data-ajax-target="header-card">
                    @csrf
                    @method('PATCH')
                    <label class="block text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1.5">Project Status</label>
                    <input type="hidden" name="status" value="{{ $project->status }}">

                    <div class="relative" data-status-dropdown>
                        <button type="button" data-status-toggle aria-haspopup="listbox" aria-expanded="false"
                                class="w-full flex items-center justify-between gap-2 rounded-lg border border-gray-300 dark:border-gray-600 pl-3 pr-2.5 py-1.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                            <span data-status-toggle-label class="flex items-center gap-2 text-navy dark:text-white">
                                <span class="w-2 h-2 rounded-full shrink-0 {{ $projectStatusDots[$project->status] ?? 'bg-gray-400' }}"></span>
                                {{ $statusLabels[$project->status] ?? ucfirst($project->status) }}
                            </span>
                            <svg data-status-toggle-chevron class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div data-status-menu class="hidden absolute z-20 left-0 right-0 mt-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                            @foreach ($statusLabels as $value => $label)
                                <button type="button" data-status-option="{{ $value }}" role="option" aria-selected="{{ $project->status === $value ? 'true' : 'false' }}"
                                        class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $project->status === $value ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                    <span class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full shrink-0 {{ $projectStatusDots[$value] ?? 'bg-gray-400' }}"></span>
                                        {{ $label }}
                                    </span>
                                    <svg class="w-4 h-4 text-gold-dark shrink-0 {{ $project->status === $value ? '' : 'invisible' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            {{-- Project Progress — pinned to the bottom of the sidebar; the
                 override input submits with the settings form on the right
                 via the form="" attribute even though it lives over here. --}}
            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700 mt-auto">
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Project Progress
                        @if ($project->milestones->isNotEmpty() && ! $project->isProgressOverridden())
                            <span class="text-gray-400">({{ $project->milestones->where('status', 'completed')->count() }}/{{ $project->milestones->count() }} milestones)</span>
                        @elseif ($project->isProgressOverridden())
                            <span class="text-gold-dark">(manually set)</span>
                        @endif
                    </label>
                    <span class="text-sm font-bold text-navy dark:text-white">{{ $project->progressPercent() }}%</span>
                </div>
                <div class="w-full h-3 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden mb-3">
                    <div class="h-full bg-gold rounded-full transition-all duration-500" style="width: {{ $project->progressPercent() }}%"></div>
                </div>
                <div class="flex items-stretch">
                    <input type="number" name="progress_override" form="project-settings-form" min="0" max="100"
                        placeholder="Auto (from milestones)"
                        value="{{ old('progress_override', $project->progress_override) }}"
                        class="w-full min-w-0 {{ $project->isProgressOverridden() ? 'rounded-l-lg' : 'rounded-lg' }} border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold focus:z-10 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                    @if ($project->isProgressOverridden())
                        <button type="button"
                            onclick="document.getElementById('clear-override-form').requestSubmit()"
                            title="Clear override"
                            class="shrink-0 inline-flex items-center gap-1 text-xs font-medium text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 border border-l-0 border-gray-300 dark:border-gray-600 px-3 rounded-r-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Clear
                        </button>
                    @endif
                </div>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Leave blank to calculate automatically from milestone completion.</p>
            </div>

            {{-- Hidden form for clearing the progress override --}}
            @if ($project->isProgressOverridden())
                <form id="clear-override-form" method="POST" action="{{ route('admin.projects.update', $project) }}" data-ajax-target="header-card" class="hidden">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="progress_override" value="">
                </form>
            @endif
        </div>

        {{-- Right: Project settings (unified form), compressed into a tighter grid --}}
        <div class="p-6 flex flex-col h-full">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4">Project Settings</p>

            <form id="project-settings-form" method="POST" action="{{ route('admin.projects.update', $project) }}" data-ajax-target="header-card" class="flex flex-col flex-1 space-y-4">
                @csrf
                @method('PATCH')

                {{-- Row 1: Status Message — free-text banner shown to the
                     client on their Overview page. Manual, not tied to any
                     automatic event, so you control exactly when/what it
                     says (e.g. "Payment Received — your project is now in
                     development" right after confirming a phase payment
                     cleared). --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status Message (shown to client)</label>
                    <input type="text" name="status_message" value="{{ old('status_message', $project->status_message) }}"
                        placeholder="e.g. Payment Received — your project is now in development"
                        maxlength="255"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Shows as a banner on the client's Overview page. Leave blank to hide it.</p>
                </div>

                {{-- Row 2: Preview URL --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Preview URL</label>
                        @if ($project->preview_url)
                            <a href="{{ $project->preview_url }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1 text-xs font-semibold text-gold-dark hover:underline">
                                Open
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @endif
                    </div>
                    <input type="url" name="preview_url" value="{{ old('preview_url', $project->preview_url) }}"
                        placeholder="https://staging.example.com"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Shown to client as a "View Live Preview" button on their dashboard.</p>
                </div>

                {{-- Row 3: Total Project Price / Discount, side by side --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Total Project Price ($)</label>
                        <input type="number" id="total-price-input" name="total_price" step="0.01" min="1" placeholder="e.g. 2500.00"
                            value="{{ old('total_price', $project->total_price !== null ? $project->total_price / 100 : '') }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Discount (%)</label>
                        <div class="relative">
                            <input type="number" id="discount-percent-input" name="discount_percent" step="0.01" min="0" max="100" placeholder="e.g. 10"
                                value="{{ old('discount_percent', $project->discount_percent) }}"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 pl-3 pr-9 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-gray-400 dark:text-gray-500 pointer-events-none">%</span>
                        </div>
                        <p id="discounted-total-preview" class="text-xs text-gray-400 dark:text-gray-500 mt-1 {{ $project->discount_percent ? '' : 'hidden' }}">
                            Discounted: <strong id="discounted-total-value" class="text-navy dark:text-white">{{ $project->formattedDiscountedTotalPrice() }}</strong>
                        </p>
                    </div>
                </div>

                {{-- Row 4: price/deposit status banner --}}
                @if ($project->total_price === null)
                    <div class="flex items-start gap-1.5 text-xs text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-lg px-2.5 py-2">
                        <svg class="w-3.5 h-3.5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Setting this for the first time creates the initial 50% deposit request and <strong>emails the client their quote</strong>.</span>
                    </div>
                @elseif ($project->depositPayment())
                    @if ($project->depositPayment()->isPaid())
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            Deposit: <strong>{{ $project->depositPayment()->formattedAmount() }}</strong> — paid.
                        </p>
                    @else
                        <div class="flex items-start gap-1.5 text-xs text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-lg px-2.5 py-2">
                            <svg class="w-3.5 h-3.5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Deposit: <strong>{{ $project->depositPayment()->formattedAmount() }}</strong> — still pending payment from the client.</span>
                        </div>
                    @endif
                @endif

                {{-- Footer: Save, bottom-right --}}
                <div class="flex justify-end items-end flex-1 pt-1">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Project Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@php
    // Includes the Care Plan subscription (if still pending) alongside
    // one-time payments, so the badge reflects everything awaiting the
    // client's action, not just one-time invoices.
    $pendingPaymentCount = $project->payments->where('status', 'pending')->count()
        + ($project->subscription && $project->subscription->status === 'pending' ? 1 : 0);
@endphp

{{-- Tabs — high-contrast pill segmented control --}}
<div class="inline-flex flex-wrap items-center gap-1 bg-gray-100 dark:bg-gray-900 rounded-full p-1 mb-6">
    <button type="button" data-tab-button="overview" onclick="showProjectTab('overview')"
            class="tab-pill flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold transition-colors bg-navy text-white dark:bg-gold dark:text-navy">
        Overview
    </button>
    <button id="tabbtn-billing" type="button" data-tab-button="billing" onclick="showProjectTab('billing')"
            class="tab-pill flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white transition-colors">
        Billing
        @if ($pendingPaymentCount > 0)
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gold/15 text-gold-dark">{{ $pendingPaymentCount }}</span>
        @endif
    </button>
    <button type="button" data-tab-button="onboarding" onclick="showProjectTab('onboarding')"
            class="tab-pill flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white transition-colors">
        Onboarding
        @if (! $project->hasAgreedToCarePlan() || ! $project->hasSignedCurrentAgreement() || ! $project->hasCompletedQuestionnaire())
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gold/15 text-gold-dark">Pending</span>
        @endif
    </button>
    <button type="button" data-tab-button="files" onclick="showProjectTab('files')"
            class="tab-pill flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white transition-colors">
        Files
    </button>
    <button type="button" data-tab-button="content" onclick="showProjectTab('content')"
            class="tab-pill flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white transition-colors">
        Website Content
    </button>
    <button id="tabbtn-revision" type="button" data-tab-button="revision" onclick="showProjectTab('revision')"
            class="tab-pill flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white transition-colors">
        Revisions
        @php $openRevisionCount = $uploadsByCategory->get('revision', $empty)->where('status', '!=', 'completed')->count(); @endphp
        @if ($openRevisionCount > 0)
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500">{{ $openRevisionCount }}</span>
        @endif
    </button>
    <button type="button" data-tab-button="recommendations" onclick="showProjectTab('recommendations')"
            class="tab-pill flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white transition-colors">
        Recommendations
        @if ($project->recommendations->where('status', 'pending_review')->isNotEmpty())
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gold/15 text-gold-dark">{{ $project->recommendations->where('status', 'pending_review')->count() }}</span>
        @endif
    </button>
</div>

<div id="panel-overview" data-tab-panel="overview">

{{-- Milestones --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <h3 class="font-semibold text-navy dark:text-white mb-4">Milestones</h3>

    <div class="space-y-3 mb-6">
        @foreach ($project->milestones as $milestone)
            @php $state = $milestoneStateStyles[$milestone->status] ?? $milestoneStateStyles['pending']; @endphp
            <div class="rounded-lg border {{ $state['card'] }} px-4 py-3.5">
                {{-- Collapsed summary row — always visible; expand to edit
                     title/due date/description. Starts collapsed so a project
                     with many milestones doesn't turn this into a huge wall
                     of open forms. --}}
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" class="milestone-toggle shrink-0 w-6 h-6 rounded-full text-gray-400 dark:text-gray-500 hover:bg-black/5 dark:hover:bg-white/10 flex items-center justify-center transition-colors" data-target="milestone-body-{{ $milestone->id }}" aria-expanded="false" title="Expand to edit">
                        <svg class="milestone-toggle-chevron w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <button type="button" class="milestone-toggle flex-1 min-w-[10rem] text-left text-sm font-medium text-navy dark:text-white truncate" data-target="milestone-body-{{ $milestone->id }}">
                        {{ $milestone->title }}
                    </button>

                    <div class="flex items-center gap-2 shrink-0">
                        <form method="POST" action="{{ route('admin.milestones.update', $milestone) }}" data-ajax-target="header-card panel-overview" onclick="event.stopPropagation()">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="title" value="{{ $milestone->title }}">
                            <input type="hidden" name="description" value="{{ $milestone->description }}">
                            <input type="hidden" name="due_date" value="{{ $milestone->due_date?->format('Y-m-d') }}">
                            <select name="status" onchange="this.form.requestSubmit()"
                                    class="rounded-full border {{ $state['badge'] }} pl-3 pr-7 py-1.5 text-xs font-semibold uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-gold appearance-none bg-no-repeat bg-[right_0.5rem_center] bg-[length:0.85rem]"
                                    style="background-image:url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27currentColor%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272.5%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E');">
                                @foreach ($milestoneStatuses as $value => $label)
                                    <option value="{{ $value }}" {{ $milestone->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </form>
                        @if ($milestone->status === 'completed' && $milestone->completed_at)
                            <p class="text-[0.65rem] text-teal-dark px-1 whitespace-nowrap">{{ $milestone->completed_at->format('M j, Y') }}</p>
                        @elseif ($milestone->due_date)
                            <p class="text-[0.65rem] text-gray-400 dark:text-gray-500 px-1 whitespace-nowrap">Due {{ $milestone->due_date->format('M j, Y') }}</p>
                        @endif
                        <form method="POST" action="{{ route('admin.milestones.destroy', $milestone) }}" data-confirm="Remove this milestone?" data-ajax-target="header-card panel-overview">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Delete milestone" class="w-8 h-8 rounded-full text-gray-300 dark:text-gray-600 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500 flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Expanded edit form — hidden until the row above is clicked --}}
                <div id="milestone-body-{{ $milestone->id }}" class="milestone-body hidden mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                    <form method="POST" action="{{ route('admin.milestones.update', $milestone) }}" data-ajax-target="header-card panel-overview" class="flex flex-col gap-2.5">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $milestone->status }}">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <input type="text" name="title" value="{{ $milestone->title }}" required
                                   class="flex-1 min-w-[14rem] rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                            <input type="date" name="due_date" value="{{ $milestone->due_date?->format('Y-m-d') }}"
                                   class="w-40 shrink-0 rounded-lg border border-gray-300 dark:border-gray-600 px-2.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                            <button type="submit" title="Save title, due date &amp; details" class="shrink-0 inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 hover:bg-navy hover:text-white dark:hover:bg-navy px-3 py-2 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                                Save
                            </button>
                        </div>
                        <textarea name="description" rows="2" placeholder="Add details for this milestone (optional)..."
                                  class="w-full min-h-[4.5rem] resize-y rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm leading-relaxed focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">{{ $milestone->description }}</textarea>
                    </form>
                </div>
            </div>
        @endforeach
        @if ($project->milestones->isEmpty())
            <p class="text-sm text-gray-400 dark:text-gray-500">No milestones yet.</p>
        @endif
    </div>

    {{-- Distinct "create" zone — dashed border sets it apart from the solid,
         state-tinted cards above so it never reads as just another row. --}}
    <div class="rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50/60 dark:bg-gray-900/20 p-4">
        <p class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-3">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Add a New Milestone
        </p>
        <form method="POST" action="{{ route('admin.milestones.store', $project) }}" class="space-y-2.5" data-ajax-target="header-card panel-overview">
            @csrf
            <div class="flex flex-wrap items-center gap-2.5">
                <input type="text" name="title" placeholder="Milestone title..." required
                       class="flex-1 min-w-[14rem] rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                <input type="date" name="due_date"
                       class="w-44 rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                <button type="submit" class="shrink-0 inline-flex items-center gap-1.5 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Add
                </button>
            </div>
            <textarea name="description" rows="2" placeholder="Add details for this milestone (optional)..."
                      class="w-full min-h-[4rem] resize-y rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm leading-relaxed focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"></textarea>
        </form>
    </div>
</div>

</div>

<div id="panel-billing" data-tab-panel="billing" class="hidden">

{{-- Payments --}}
@php
    $paymentStatusColors = [
        'pending' => 'bg-gold/15 text-gold-dark',
        'paid' => 'bg-teal/15 text-teal-dark',
        'failed' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
        'canceled' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
    ];
@endphp
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <div class="flex items-center justify-between gap-3 mb-4">
        <h3 class="font-semibold text-navy dark:text-white">Payments</h3>

        {{-- One-off for Unity Auto Group's special phased payment plan (see
        AdminProjectController::sendPaymentInstructions) — not a general
        per-project feature, so intentionally scoped to just this project. --}}
        @if ($project->id === 7)
            <form method="POST" action="{{ route('admin.projects.payment-instructions', $project) }}" data-confirm="Email the phased payment plan instructions to {{ $project->user->name }}?">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gold-dark border border-gold/40 hover:bg-gold/10 px-3 py-1.5 rounded-full transition-colors">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Payment Instruction
                </button>
            </form>
        @endif
    </div>

    <div class="space-y-2 mb-5 pb-5 border-b border-gray-100 dark:border-gray-700">
        @foreach ($project->payments as $payment)
            <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-2.5">
                <div>
                    @if ($payment->categoryLabel())
                        <span class="inline-block text-[0.65rem] font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-navy/5 dark:bg-white/10 text-navy dark:text-white mr-2 align-middle">{{ $payment->categoryLabel() }}</span>
                    @endif
                    <span class="text-sm text-navy dark:text-white">{{ $payment->description }}</span>
                    <span class="text-sm text-gray-400 dark:text-gray-500 ml-2">{{ $payment->formattedAmount() }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $paymentStatusColors[$payment->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                    @if ($payment->isPending() && $payment->stripe_checkout_session_id)
                        <span class="text-xs text-gray-400 dark:text-gray-500" title="A Stripe checkout session is in progress for this payment — sync or wait before removing.">Checkout in progress</span>
                    @elseif ($payment->isPending())
                        <form method="POST" action="{{ route('admin.payments.send-email', $payment) }}" data-confirm="Email this ${{ number_format($payment->amount / 100, 2) }} invoice to {{ $project->user->name }} now?" data-ajax-target="panel-billing tabbtn-billing">
                            @csrf
                            <button type="submit" title="Send invoice email" class="inline-flex items-center gap-1.5 h-7 px-2.5 rounded-full text-xs font-semibold text-gold-dark border border-gold/40 hover:bg-gold/10 transition-colors">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Send Email
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.payments.destroy', $payment) }}" data-confirm="Remove this payment request?" data-ajax-target="panel-billing tabbtn-billing">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Remove this payment request" class="w-7 h-7 rounded-full text-gray-400 dark:text-gray-500 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 flex items-center justify-center transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
        @if ($project->payments->isEmpty())
            <p class="text-sm text-gray-400 dark:text-gray-500">No payment requests yet.</p>
        @endif
    </div>

    <form method="POST" action="{{ route('admin.payments.store', $project) }}" class="space-y-3 max-w-lg" data-ajax-target="panel-billing tabbtn-billing">
        @csrf
        <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Description</label>
            <input type="text" name="description" placeholder="What's this payment for..." required
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
        </div>
        <div class="w-56">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Category</label>
            <input type="hidden" name="category" id="payment-category-input" value="">

            <div class="relative" data-payment-category-dropdown>
                <button type="button" data-payment-category-toggle aria-haspopup="listbox" aria-expanded="false"
                        class="w-full flex items-center justify-between gap-2 rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm text-left focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                    <span data-payment-category-label class="text-gray-400 dark:text-gray-500">No category</span>
                    <svg data-payment-category-chevron class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div data-payment-category-menu class="hidden absolute z-20 left-0 right-0 mt-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                    @foreach (['' => 'No category', 'phase' => 'Phase', 'one_time' => 'One-Time Payment', 'deposit' => 'Deposit', 'final' => 'Final Payment', 'other' => 'Other'] as $value => $label)
                        <button type="button" data-payment-category-option="{{ $value }}" role="option" aria-selected="{{ $value === '' ? 'true' : 'false' }}"
                                class="w-full flex items-center justify-between gap-2 px-4 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $value === '' ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                            {{ $label }}
                            <svg class="w-4 h-4 text-gold-dark shrink-0 {{ $value === '' ? '' : 'invisible' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex items-end gap-3">
            <div class="w-44">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Amount</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 dark:text-gray-500 pointer-events-none">$</span>
                    <input type="number" name="amount" step="0.01" min="1" placeholder="0.00" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 pl-6 pr-12 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-gray-400 dark:text-gray-500 pointer-events-none">USD</span>
                </div>
            </div>
            <button type="submit" class="shrink-0 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">
                Request
            </button>
        </div>
    </form>
</div>

{{-- Maintenance Plan --}}
@php
    $subscriptionStatusColors = [
        'pending' => 'bg-gold/15 text-gold-dark',
        'active' => 'bg-teal/15 text-teal-dark',
        'past_due' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
        'canceled' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
    ];
    $subscriptionStatusLabels = [
        'pending' => 'Pending',
        'active' => 'Active',
        'past_due' => 'Past Due',
        'canceled' => 'Canceled',
    ];
    $currentSubscription = $project->subscription;
@endphp
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <h3 class="font-semibold text-navy dark:text-white mb-4">Care Plan</h3>

    @if ($currentSubscription && ! $currentSubscription->isCanceled())
        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-2.5">
            <div>
                <span class="text-sm text-navy dark:text-white">{{ $currentSubscription->description }}</span>
                <span class="text-sm text-gray-400 dark:text-gray-500 ml-2">{{ $currentSubscription->formattedAmount() }}</span>
                @if ($currentSubscription->cancel_at_period_end && $currentSubscription->current_period_end)
                    <span class="text-xs text-red-500 ml-2">cancels {{ $currentSubscription->current_period_end->format('M j, Y') }}</span>
                @elseif ($currentSubscription->current_period_end)
                    <span class="text-xs text-gray-400 dark:text-gray-500 ml-2">renews {{ $currentSubscription->current_period_end->format('M j, Y') }}</span>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $subscriptionStatusColors[$currentSubscription->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                    {{ $subscriptionStatusLabels[$currentSubscription->status] ?? $currentSubscription->status }}
                </span>
                @if ($currentSubscription->isPending())
                    <form method="POST" action="{{ route('admin.subscriptions.send-reminder', $currentSubscription) }}" data-confirm="Send a payment reminder email to {{ $project->user->name }}?" data-ajax-target="panel-billing">
                        @csrf
                        <button type="submit" title="Send payment reminder email" class="inline-flex items-center gap-1.5 h-7 px-2.5 rounded-full text-xs font-semibold text-gold-dark border border-gold/40 hover:bg-gold/10 transition-colors">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Send Email Reminder
                        </button>
                    </form>
                @endif
                <form method="POST" action="{{ route('admin.subscriptions.sync', $currentSubscription) }}" data-ajax-target="panel-billing">
                    @csrf
                    <button type="submit" title="Refresh status from Stripe" class="w-7 h-7 rounded-full text-gray-400 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-navy dark:hover:text-white flex items-center justify-center transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.subscriptions.destroy', $currentSubscription) }}" data-confirm="Cancel this care plan?" data-ajax-target="panel-billing">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Cancel this care plan" class="w-7 h-7 rounded-full text-gray-400 dark:text-gray-500 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 flex items-center justify-center transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </form>
            </div>
        </div>
    @else
        <p class="text-sm text-gray-400 dark:text-gray-500 mb-4">No active care plan.</p>
        @if (! in_array($project->status, ['launched', 'maintenance'], true))
            <p class="text-sm text-gold-dark bg-gold/10 border border-gold/30 rounded-lg px-4 py-3">
                Care Plan billing doesn't start until this project is launched — set status to "Launched" on the
                Overview tab first (or it'll happen automatically once the final payment clears and the client has approved).
            </p>
        @else
            <form method="POST" action="{{ route('admin.subscriptions.store', $project) }}" class="space-y-3 max-w-lg" data-ajax-target="panel-billing">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Description</label>
                    <input type="text" name="description" placeholder="e.g. Monthly Website Care" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                </div>
                <div class="flex items-end gap-3">
                    <div class="w-44">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Amount / Month</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 dark:text-gray-500 pointer-events-none">$</span>
                            <input type="number" name="amount" step="0.01" min="1" placeholder="0.00" required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 pl-6 pr-12 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-gray-400 dark:text-gray-500 pointer-events-none">USD</span>
                        </div>
                    </div>
                    <button type="submit" class="shrink-0 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">
                        Request
                    </button>
                </div>
            </form>
        @endif
    @endif
</div>

</div>

<div id="panel-onboarding" data-tab-panel="onboarding" class="hidden">

    <div class="flex items-center justify-between mb-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <div>
            <h3 class="font-semibold text-navy dark:text-white">Walk Through Client Onboarding</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">See exactly what {{ $project->user->name }} saw on each of the 5 onboarding steps, filled in with their real answers — read only.</p>
        </div>
        <a href="{{ route('admin.projects.onboarding-preview', $project) }}" class="shrink-0 inline-flex items-center gap-1.5 text-sm font-semibold text-navy dark:text-white bg-gold/15 hover:bg-gold/25 px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            Start Walkthrough
        </a>
    </div>

    {{-- Care Plan Agreement --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h3 class="font-semibold text-navy dark:text-white mb-4">Care Plan Agreement</h3>
        @if ($project->carePlanAgreement)
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Agreed to <strong class="text-navy dark:text-white">{{ $project->carePlanAgreement->maintenancePlan->name }}</strong>
                ({{ $project->carePlanAgreement->maintenancePlan->formattedPrice() }}/{{ $project->carePlanAgreement->maintenancePlan->interval }})
                on {{ $project->carePlanAgreement->agreed_at->format('M j, Y \a\t g:i A') }}
            </p>
        @else
            <p class="text-sm text-gray-400 dark:text-gray-500">Not selected yet.</p>
        @endif
    </div>

    {{-- Service Agreement --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h3 class="font-semibold text-navy dark:text-white mb-4">Service Agreement</h3>
        @if ($project->agreementSignature)
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                Signed by <strong class="text-navy dark:text-white">{{ $project->agreementSignature->signer_name }}</strong>
                on {{ $project->agreementSignature->signed_at->format('M j, Y \a\t g:i A') }}
                (v{{ $project->agreementSignature->template->version }})
            </p>
            <div class="flex flex-wrap items-center gap-x-5 gap-y-2">
                <a href="{{ route('portal.agreement.preview', $project->agreementSignature) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm text-gold-dark font-semibold hover:underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview
                </a>
                <a href="{{ route('portal.agreement.download', $project->agreementSignature) }}" class="inline-flex items-center gap-1.5 text-sm text-gold-dark font-semibold hover:underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                    Download signed PDF
                </a>
                @if ($project->agreementSignature->filled_pdf_path)
                    <a href="{{ route('portal.agreement.filled', $project->agreementSignature) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm text-navy dark:text-white font-semibold hover:underline" title="The complete filled-in agreement (Care Plan + signature block), not just the signature certificate">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        View Full Signed Agreement
                    </a>
                @endif
            </div>
        @else
            <p class="text-sm text-gray-400 dark:text-gray-500">Not signed yet.</p>
        @endif
    </div>

    {{-- Onboarding Questionnaire --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="font-semibold text-navy dark:text-white mb-4">Onboarding Questionnaire</h3>
        @if ($project->questionnaire?->isCompleted())
            @php
                $q = $project->questionnaire;
                $isBlank = fn ($v) => empty($v) || strtolower(trim((string) $v)) === 'none';
                $longFormFields = [
                    ['label' => 'Mission Statement', 'value' => $q->mission_statement],
                    ['label' => 'Vision Statement', 'value' => $q->vision_statement],
                    ['label' => 'Requested Pages / Requirements', 'value' => $q->requested_pages],
                    ['label' => 'Additional Notes', 'value' => $q->additional_notes],
                ];
            @endphp
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-5">Submitted {{ $q->completed_at->format('M j, Y \a\t g:i A') }}</p>

            {{-- Short metadata --}}
            <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-5 mb-6">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Organization Type</dt>
                    <dd class="text-base font-medium text-navy dark:text-white">{{ $q->organization_type ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Brand Colors</dt>
                    <dd class="text-base font-medium text-navy dark:text-white">{{ $q->brand_colors ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Services Interested In</dt>
                    <dd class="text-base font-medium text-navy dark:text-white">{{ !empty($q->services) ? implode(', ', $q->services) : '—' }}</dd>
                </div>
            </dl>

            {{-- Social Links — consolidated pills; "none"/empty values read as muted, not a wall of "none" text --}}
            <div class="mb-6">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Social Links</p>
                @if (!empty($q->social_links))
                    <div class="flex flex-wrap gap-2">
                        @foreach ($q->social_links as $platform => $url)
                            @if ($isBlank($url))
                                <span class="inline-flex items-center text-xs font-medium text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700 rounded-full px-3 py-1.5">
                                    {{ ucfirst($platform) }}
                                </span>
                            @elseif (filter_var($url, FILTER_VALIDATE_URL) !== false)
                                <a href="{{ $url }}" target="_blank" rel="noopener" class="inline-flex items-center text-sm font-medium text-navy dark:text-white bg-gold/10 border border-gold/25 hover:bg-gold/15 rounded-full px-3 py-1.5 transition-colors">
                                    {{ ucfirst($platform) }}
                                </a>
                            @else
                                <span title="{{ $url }}" class="inline-flex items-center text-sm font-medium text-navy dark:text-white bg-gold/10 border border-gold/25 rounded-full px-3 py-1.5">
                                    {{ ucfirst($platform) }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-base font-medium text-navy dark:text-white">—</p>
                @endif
            </div>

            {{-- Long-form fields — own card each, isolated from the short metadata above --}}
            <div class="space-y-3">
                @foreach ($longFormFields as $field)
                    <div class="bg-gray-50/50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-700 rounded-lg p-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">{{ $field['label'] }}</p>
                        <p class="text-base font-medium text-navy dark:text-white whitespace-pre-wrap">{{ $field['value'] ?: '—' }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-400 dark:text-gray-500">Not submitted yet.</p>
        @endif
    </div>

</div>

<div id="panel-files" data-tab-panel="files" class="hidden">

{{-- Project files --}}
@foreach ($categories as $cat => $meta)
    @continue ($meta['type'] !== 'file')
    @php $items = $uploadsByCategory->get($cat, $empty); @endphp

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-navy dark:text-white">{{ $meta['label'] }}</h3>
            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $items->count() }} item{{ $items->count() === 1 ? '' : 's' }}</span>
        </div>

        @if ($items->isEmpty())
            <p class="text-sm text-gray-400 dark:text-gray-500">Nothing here yet.</p>
        @else
            <div class="space-y-2.5">
                @foreach ($items as $item)
                    <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-3">
                        <a href="{{ $item->url() }}" target="_blank" class="flex items-center gap-3 min-w-0 group">
                            <span class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-700 flex items-center justify-center shrink-0 overflow-hidden">
                                @if (in_array($cat, ['image', 'logo']))
                                    <img src="{{ $item->url() }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-[0.6rem] font-bold uppercase text-gray-500 dark:text-gray-400">{{ $item->extension() ?: 'FILE' }}</span>
                                @endif
                            </span>
                            <span class="min-w-0">
                                <span class="block text-sm font-medium text-navy dark:text-white group-hover:text-gold-dark truncate">{{ $item->original_name }}</span>
                                <span class="block text-xs text-gray-400 dark:text-gray-500">
                                    {{ $item->created_at->format('M j, Y') }}
                                    @if ($item->formattedSize()) &middot; {{ $item->formattedSize() }} @endif
                                    &middot; from {{ $item->user->name }}
                                </span>
                            </span>
                        </a>
                        <form method="POST" action="{{ route('admin.uploads.approve', $item) }}" class="shrink-0" data-ajax-target="panel-files">
                            @csrf
                            @method('PATCH')
                            @if ($item->isApproved())
                                <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-semibold text-teal-dark bg-teal/10 border border-teal/30 px-3 py-1.5 rounded-full hover:bg-teal/15 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    Approved
                                </button>
                            @else
                                <button type="submit" class="text-xs font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gold/15 hover:text-gold-dark border border-gray-200 dark:border-gray-700 px-3 py-1.5 rounded-full transition-colors">
                                    Approve
                                </button>
                            @endif
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endforeach

</div>

<div id="panel-content" data-tab-panel="content" class="hidden">
    @include('admin.projects._text-thread', ['cat' => 'content', 'meta' => $categories['content'], 'panelId' => 'panel-content'])
</div>

<div id="panel-revision" data-tab-panel="revision" class="hidden">
    @include('admin.projects._text-thread', ['cat' => 'revision', 'meta' => $categories['revision'], 'panelId' => 'panel-revision'])
</div>

<div id="panel-recommendations" data-tab-panel="recommendations" class="hidden">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h3 class="font-semibold text-navy dark:text-white mb-4">Submit a Recommendation</h3>
        <form method="POST" action="{{ route('admin.recommendations.store', $project) }}" class="space-y-3" data-ajax-target="panel-recommendations">
            @csrf
            <input type="text" name="title" required placeholder="e.g. Add a sticky donate button"
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
            <select name="category" required
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                <option value="" disabled selected>Choose a category...</option>
                @foreach (\App\Models\Recommendation::CATEGORIES as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            <textarea name="description" rows="3" required placeholder="What's the improvement and why would it help?"
                      class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"></textarea>
            <div class="flex justify-end">
                <button type="submit" class="bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                    Submit
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
        @forelse ($project->recommendations as $item)
            <div class="px-6 py-4">
                <div class="flex items-center justify-between gap-4 mb-1">
                    <p class="text-sm font-semibold text-navy dark:text-white">{{ $item->title }}</p>
                    <span class="text-xs text-gray-400 dark:text-gray-500">by {{ $item->submittedBy->name }} &middot; {{ $item->created_at->format('M j, Y') }}</span>
                </div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gold-dark mb-2">{{ \App\Models\Recommendation::CATEGORIES[$item->category] ?? $item->category }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line mb-3">{{ $item->description }}</p>
                <form method="POST" action="{{ route('admin.recommendations.update', $item) }}" data-ajax-target="panel-recommendations">
                    @csrf
                    @method('PATCH')
                    <select name="status" onchange="this.form.requestSubmit()"
                            class="rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                        @foreach (\App\Models\Recommendation::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ $item->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        @empty
            <p class="text-sm text-gray-400 dark:text-gray-500 px-6 py-8 text-center">No recommendations submitted for this project yet.</p>
        @endforelse
    </div>
</div>

<script>
// Admin Website Content / Revisions thread UI (list <-> detail, unread
// badges, message truncation, always-visible composer). Defined here --
// outside any data-ajax-target panel -- as global functions referenced via
// inline onclick/onsubmit attributes in admin/projects/_text-thread.blade.php,
// since injected <script> tags inside an ajax-swapped panel never
// re-execute, but inline event attributes are re-attached automatically
// every time their element is inserted into the DOM.
function openAdminThread(cat, itemId, hasUnread, markReadUrl) {
    const list = document.getElementById('thread-list-' + cat);
    if (list) list.classList.add('hidden');

    document.querySelectorAll('.admin-thread').forEach(function (thread) {
        thread.classList.toggle('hidden', thread.id !== 'thread-' + cat + '-' + itemId);
    });

    const thread = document.getElementById('thread-' + cat + '-' + itemId);
    if (thread) {
        thread.querySelectorAll('.message-text').forEach(function (el) {
            if (el.scrollHeight > el.clientHeight + 2) {
                const btn = el.nextElementSibling;
                if (btn && btn.classList.contains('message-toggle')) {
                    btn.classList.remove('hidden');
                }
            }
        });

        const scrollEl = thread.querySelector('.admin-thread-scroll');
        if (scrollEl) scrollEl.scrollTop = scrollEl.scrollHeight;
    }

    if (hasUnread && markReadUrl) {
        fetch(markReadUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            },
        });
    }
}

function closeAdminThread(cat) {
    document.querySelectorAll('.admin-thread').forEach(function (thread) {
        thread.classList.add('hidden');
    });
    const list = document.getElementById('thread-list-' + cat);
    if (list) list.classList.remove('hidden');
}

function toggleAdminMessage(btn) {
    const el = btn.previousElementSibling;
    if (!el || !el.classList.contains('message-text')) return;

    const expanded = el.classList.toggle('message-expanded');
    el.classList.toggle('max-h-24', !expanded);
    el.classList.toggle('overflow-hidden', !expanded);
    btn.textContent = expanded ? 'See less' : 'See more';
}

function submitAdminReply(form, event) {
    event.preventDefault();

    const uploadId = form.dataset.uploadId;
    const cat = form.dataset.cat;
    const textarea = form.querySelector('textarea[name="admin_reply"]');
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnHtml = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML =
        '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">' +
            '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
            '<path class="opacity-75" fill="currentColor" d="M12 2a10 10 0 0110 10h-4a6 6 0 00-6-6V2z"></path>' +
        '</svg>';

    fetch(form.action, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            'X-HTTP-Method-Override': 'PATCH',
        },
        body: new FormData(form),
    })
        .then(function (response) {
            if (!response.ok) throw new Error('Request failed');
            return response.json();
        })
        .then(function (data) {
            const repliesContainer = document.getElementById('replies-' + uploadId);
            const bubble = document.createElement('div');
            bubble.className = 'flex items-start justify-end gap-2.5 max-w-[85%] ml-auto mt-3';
            bubble.innerHTML =
                '<div class="rounded-2xl rounded-tr-sm bg-navy text-white px-4 py-2.5">' +
                    '<p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold mb-1">VisionBridge Team</p>' +
                    '<p class="text-sm whitespace-pre-line message-text max-h-24 overflow-hidden"></p>' +
                    '<button type="button" onclick="toggleAdminMessage(this)" class="message-toggle hidden text-xs font-semibold text-gold hover:text-white mt-1">See more</button>' +
                    '<p class="text-xs text-white/40 mt-1.5"></p>' +
                '</div>' +
                '<span class="w-7 h-7 rounded-full bg-navy text-gold text-xs font-bold flex items-center justify-center shrink-0">VB</span>';
            bubble.querySelector('.message-text').textContent = data.body;
            bubble.querySelector('.text-xs').textContent = data.sentAt;
            repliesContainer.appendChild(bubble);

            const scrollEl = document.getElementById('thread-' + cat + '-' + uploadId)?.querySelector('.admin-thread-scroll');
            if (scrollEl) scrollEl.scrollTop = scrollEl.scrollHeight;

            textarea.value = '';
        })
        .catch(function () {
            alert('Could not send the reply. Please try again.');
        })
        .finally(function () {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
        });

    return false;
}
</script>

{{-- Reset Password confirm modal --}}
<div id="reset-password-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div id="reset-password-backdrop" class="absolute inset-0 bg-navy-dark/60 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>

    <div id="reset-password-panel" class="relative w-full max-w-sm transform scale-95 opacity-0 transition-all duration-200">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6">
            <div class="w-11 h-11 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h2 class="font-display text-lg font-bold text-navy dark:text-white mb-2">Reset client password?</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                {{ $project->user->name }}'s password will be reset to <strong class="text-navy dark:text-white">"admin123"</strong>. They should change it immediately after logging in.
            </p>
            <div class="flex justify-end gap-2.5">
                <button type="button" onclick="closeResetPasswordModal()" class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <form method="POST" action="{{ route('admin.projects.reset-client-password', $project) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 rounded-lg text-sm font-semibold bg-gold hover:bg-gold-dark text-navy-dark transition-colors">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Generic confirm modal, used for delete/cancel actions instead of the native browser confirm() --}}
<div id="confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div id="confirm-modal-backdrop" class="absolute inset-0 bg-navy-dark/60 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>

    <div id="confirm-modal-panel" class="relative w-full max-w-sm transform scale-95 opacity-0 transition-all duration-200">
        <div class="relative overflow-hidden rounded-2xl shadow-2xl" style="background:linear-gradient(135deg,#111D33,#1B2A4A 60%,#1B2A4A);">
            <div class="absolute -top-20 -right-12 w-56 h-56 rounded-full" style="background:radial-gradient(circle,rgba(201,168,76,0.20) 0%,transparent 70%);"></div>
            <div class="absolute -bottom-24 -left-10 w-56 h-56 rounded-full" style="background:radial-gradient(circle,rgba(220,38,38,0.16) 0%,transparent 70%);"></div>

            <div class="relative px-7 pt-8 pb-6 text-center">
                <div class="w-14 h-14 rounded-full mx-auto mb-4 flex items-center justify-center bg-red-500/15">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-8.18 14.18A1 1 0 003 19.5h18a1 1 0 00.86-1.46L13.71 3.86a1 1 0 00-1.72 0z"/></svg>
                </div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-1">Confirm Action</p>
                <h2 class="font-display text-2xl font-bold text-white">Are you sure?</h2>
            </div>

            <div class="relative bg-white dark:bg-gray-800 rounded-t-2xl px-7 py-6">
                <p id="confirm-modal-message" class="text-sm text-gray-500 dark:text-gray-400 mb-6 text-center"></p>
                <div class="flex justify-end gap-2.5">
                    <button type="button" id="confirm-modal-cancel" class="px-4 py-2.5 rounded-lg text-sm font-medium text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="button" id="confirm-modal-confirm" class="px-4 py-2.5 rounded-lg text-sm font-semibold bg-red-500 hover:bg-red-600 text-white transition-all hover:-translate-y-0.5 hover:shadow-lg">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const modal = document.getElementById('confirm-modal');
        const backdrop = document.getElementById('confirm-modal-backdrop');
        const panel = document.getElementById('confirm-modal-panel');
        const message = document.getElementById('confirm-modal-message');
        const cancelBtn = document.getElementById('confirm-modal-cancel');
        const confirmBtn = document.getElementById('confirm-modal-confirm');

        function openConfirmModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(function () {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('scale-95', 'opacity-0');
            });
        }

        function closeConfirmModal() {
            backdrop.classList.add('opacity-0');
            panel.classList.add('scale-95', 'opacity-0');
            setTimeout(function () {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }

        window.confirmAction = function (text) {
            message.textContent = text;
            openConfirmModal();

            return new Promise(function (resolve) {
                function onConfirm() {
                    cleanup();
                    closeConfirmModal();
                    resolve(true);
                }
                function onCancel() {
                    cleanup();
                    closeConfirmModal();
                    resolve(false);
                }
                function cleanup() {
                    confirmBtn.removeEventListener('click', onConfirm);
                    cancelBtn.removeEventListener('click', onCancel);
                    backdrop.removeEventListener('click', onCancel);
                }

                confirmBtn.addEventListener('click', onConfirm);
                cancelBtn.addEventListener('click', onCancel);
                backdrop.addEventListener('click', onCancel);
            });
        };
    })();
</script>

<script>
    const validProjectTabs = ['overview', 'billing', 'onboarding', 'files', 'content', 'revision', 'recommendations'];
    let currentProjectTab = 'overview';

    // updateUrl=false is used for restoring state (initial deep-link load,
    // browser back/forward, re-applying the active tab after an AJAX panel
    // swap) — those aren't a new user navigation, so they shouldn't push
    // another history entry or redundantly rewrite a URL we're already at.
    function showProjectTab(tab, updateUrl = true) {
        currentProjectTab = tab;

        document.querySelectorAll('[data-tab-panel]').forEach((el) => {
            el.classList.toggle('hidden', el.dataset.tabPanel !== tab);
        });
        document.querySelectorAll('[data-tab-button]').forEach((el) => {
            const active = el.dataset.tabButton === tab;
            el.classList.toggle('bg-navy', active);
            el.classList.toggle('text-white', active);
            el.classList.toggle('dark:bg-gold', active);
            el.classList.toggle('dark:text-navy', active);
            el.classList.toggle('text-gray-500', !active);
            el.classList.toggle('dark:text-gray-400', !active);
            el.classList.toggle('hover:text-navy', !active);
            el.classList.toggle('dark:hover:text-white', !active);
        });

        if (updateUrl) {
            const url = new URL(window.location.href);
            if (tab === 'overview') {
                url.searchParams.delete('tab');
            } else {
                url.searchParams.set('tab', tab);
            }
            history.pushState({ tab }, '', url);
        }
    }

    // Deep link / reload: restore whichever tab the URL asks for.
    (function () {
        const requested = new URLSearchParams(window.location.search).get('tab');
        if (requested && validProjectTabs.includes(requested)) {
            showProjectTab(requested, false);
        }
    })();

    window.addEventListener('popstate', function () {
        const tab = new URLSearchParams(window.location.search).get('tab') || 'overview';
        showProjectTab(validProjectTabs.includes(tab) ? tab : 'overview', false);
    });

    // Custom-styled Project Status dropdown (replaces a native <select>) so the
    // current stage renders as a color-coded pill instead of plain text.
    // Picking an option auto-submits via the same no-reload flow as every
    // other form on this page (see bindAjaxForms below).
    (function () {
        function bind(root) {
            root.querySelectorAll('[data-status-dropdown]').forEach((wrap) => {
                if (wrap.dataset.bound) return;
                wrap.dataset.bound = '1';

                const toggle = wrap.querySelector('[data-status-toggle]');
                const menu = wrap.querySelector('[data-status-menu]');
                const chevron = wrap.querySelector('[data-status-toggle-chevron]');
                const label = wrap.querySelector('[data-status-toggle-label]');
                const hiddenInput = wrap.closest('form').querySelector('input[name="status"]');
                if (!toggle || !menu || !hiddenInput) return;

                function closeMenu() {
                    menu.classList.add('hidden');
                    toggle.setAttribute('aria-expanded', 'false');
                    chevron.style.transform = '';
                }

                function openMenu() {
                    menu.classList.remove('hidden');
                    toggle.setAttribute('aria-expanded', 'true');
                    chevron.style.transform = 'rotate(180deg)';
                }

                toggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    menu.classList.contains('hidden') ? openMenu() : closeMenu();
                });

                menu.querySelectorAll('[data-status-option]').forEach(function (option) {
                    option.addEventListener('click', function () {
                        const value = option.dataset.statusOption;
                        closeMenu();
                        if (hiddenInput.value === value) return;

                        hiddenInput.value = value;
                        label.innerHTML = option.querySelector('span').innerHTML;
                        hiddenInput.form.requestSubmit();
                    });
                });

                document.addEventListener('click', function (e) {
                    if (!wrap.contains(e.target)) closeMenu();
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') closeMenu();
                });
            });
        }

        window.bindStatusDropdown = bind;
        bind(document);
    })();

    // Generic reusable pill dropdown — same toggle/menu/keyboard-close pattern
    // as the Project Status dropdown above, parameterized by a data-attribute
    // prefix and the hidden input's field name so one function drives every
    // revision-thread dropdown (Status, Priority, Assigned Developer,
    // Developer Status) instead of four near-identical copies.
    function bindPillDropdown(root, prefix, fieldName, onSelect) {
        root.querySelectorAll('[data-' + prefix + '-dropdown]').forEach((wrap) => {
            if (wrap.dataset.bound) return;
            wrap.dataset.bound = '1';

            const toggle = wrap.querySelector('[data-' + prefix + '-toggle]');
            const menu = wrap.querySelector('[data-' + prefix + '-menu]');
            const chevron = wrap.querySelector('[data-' + prefix + '-toggle-chevron]');
            const label = wrap.querySelector('[data-' + prefix + '-toggle-label]');
            const hiddenInput = wrap.closest('form')?.querySelector('input[name="' + fieldName + '"]');
            if (!toggle || !menu || !hiddenInput) return;

            function closeMenu() {
                menu.classList.add('hidden');
                toggle.setAttribute('aria-expanded', 'false');
                if (chevron) chevron.style.transform = '';
            }

            function openMenu() {
                menu.classList.remove('hidden');
                toggle.setAttribute('aria-expanded', 'true');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            }

            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                menu.classList.contains('hidden') ? openMenu() : closeMenu();
            });

            menu.querySelectorAll('[data-' + prefix + '-option]').forEach(function (option) {
                option.addEventListener('click', function () {
                    const value = option.getAttribute('data-' + prefix + '-option');
                    const changed = hiddenInput.value !== value;
                    closeMenu();

                    hiddenInput.value = value;
                    if (label) {
                        const inner = option.querySelector('[data-option-label]');
                        label.innerHTML = inner ? inner.innerHTML : option.textContent.trim();
                    }
                    if (toggle.dataset.colorClass) toggle.classList.remove(...toggle.dataset.colorClass.split(' '));
                    const newColorClass = option.dataset.colorClass;
                    if (newColorClass) {
                        toggle.classList.add(...newColorClass.split(' '));
                        toggle.dataset.colorClass = newColorClass;
                    }

                    menu.querySelectorAll('[data-' + prefix + '-option]').forEach(function (opt) {
                        const isSelected = opt === option;
                        opt.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                        opt.classList.toggle('text-gold-dark', isSelected);
                        opt.classList.toggle('font-semibold', isSelected);
                        opt.classList.toggle('text-gray-700', !isSelected);
                        opt.classList.toggle('dark:text-gray-300', !isSelected);
                        const check = opt.querySelector('[data-option-check]');
                        if (check) check.classList.toggle('invisible', !isSelected);
                    });

                    if (!changed) return;
                    if (typeof onSelect === 'function' && onSelect(value, wrap, hiddenInput) === false) return;
                    hiddenInput.form.requestSubmit();
                });
            });

            document.addEventListener('click', function (e) {
                if (!wrap.contains(e.target)) closeMenu();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeMenu();
            });
        });
    }

    // Revision-thread dropdowns — picking "Closed" reveals a required reason
    // field instead of auto-submitting (every other status/field still
    // submits right away on change).
    function bindRevisionDropdowns(root) {
        bindPillDropdown(root, 'revision-status', 'status', function (value, wrap) {
            const reasonWrap = wrap.closest('form')?.querySelector('.closed-reason-wrap');
            if (!reasonWrap) return;

            if (value === 'closed') {
                reasonWrap.classList.remove('hidden');
                reasonWrap.querySelector('input[name="closed_reason"]')?.focus();
                return false;
            }

            reasonWrap.classList.add('hidden');
        });
        bindPillDropdown(root, 'priority', 'priority');
        bindPillDropdown(root, 'assigned-developer', 'assigned_developer_id');
        bindPillDropdown(root, 'developer-status', 'developer_status');
    }

    window.bindRevisionDropdowns = bindRevisionDropdowns;
    bindRevisionDropdowns(document);

    // Live-recalculates the discounted total as either the price or discount
    // % field changes — purely a preview; the actual figure is computed
    // server-side (Project::discountedTotalPrice()) on save.
    (function () {
        function bind(root) {
            const priceInput = root.querySelector ? root.querySelector('#total-price-input') : null;
            const discountInput = root.querySelector ? root.querySelector('#discount-percent-input') : null;
            const preview = root.querySelector ? root.querySelector('#discounted-total-preview') : null;
            const previewValue = root.querySelector ? root.querySelector('#discounted-total-value') : null;
            if (!priceInput || !discountInput || !preview || !previewValue || priceInput.dataset.discountBound) return;
            priceInput.dataset.discountBound = '1';

            function recalc() {
                const price = parseFloat(priceInput.value);
                const discount = parseFloat(discountInput.value);

                if (!isNaN(price) && price > 0 && !isNaN(discount) && discount > 0) {
                    previewValue.textContent = '$' + (price * (1 - discount / 100)).toFixed(2);
                    preview.classList.remove('hidden');
                } else {
                    preview.classList.add('hidden');
                }
            }

            priceInput.addEventListener('input', recalc);
            discountInput.addEventListener('input', recalc);
        }

        window.bindDiscountCalculator = bind;
        bind(document);
    })();

    // Category dropdown on the "Request Payment" form — same custom
    // toggle/menu pattern as the Project Status dropdown above.
    (function () {
        function bind(root) {
            root.querySelectorAll('[data-payment-category-dropdown]').forEach((wrap) => {
                if (wrap.dataset.bound) return;
                wrap.dataset.bound = '1';

                const toggle = wrap.querySelector('[data-payment-category-toggle]');
                const menu = wrap.querySelector('[data-payment-category-menu]');
                const chevron = wrap.querySelector('[data-payment-category-chevron]');
                const label = wrap.querySelector('[data-payment-category-label]');
                const hiddenInput = wrap.closest('form').querySelector('#payment-category-input');
                if (!toggle || !menu || !hiddenInput) return;

                function closeMenu() {
                    menu.classList.add('hidden');
                    toggle.setAttribute('aria-expanded', 'false');
                    chevron.style.transform = '';
                }

                function openMenu() {
                    menu.classList.remove('hidden');
                    toggle.setAttribute('aria-expanded', 'true');
                    chevron.style.transform = 'rotate(180deg)';
                }

                toggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    menu.classList.contains('hidden') ? openMenu() : closeMenu();
                });

                menu.querySelectorAll('[data-payment-category-option]').forEach(function (option) {
                    option.addEventListener('click', function () {
                        hiddenInput.value = option.dataset.paymentCategoryOption;
                        label.textContent = option.textContent.trim();
                        label.classList.toggle('text-gray-400', option.dataset.paymentCategoryOption === '');
                        label.classList.toggle('dark:text-gray-500', option.dataset.paymentCategoryOption === '');
                        label.classList.toggle('text-navy', option.dataset.paymentCategoryOption !== '');
                        label.classList.toggle('dark:text-white', option.dataset.paymentCategoryOption !== '');

                        menu.querySelectorAll('[data-payment-category-option]').forEach(function (opt) {
                            const isSelected = opt === option;
                            opt.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                            opt.classList.toggle('text-gold-dark', isSelected);
                            opt.classList.toggle('font-semibold', isSelected);
                            opt.classList.toggle('text-gray-700', !isSelected);
                            opt.classList.toggle('dark:text-gray-300', !isSelected);
                            opt.querySelector('svg').classList.toggle('invisible', !isSelected);
                        });

                        closeMenu();
                    });
                });

                document.addEventListener('click', function (e) {
                    if (!wrap.contains(e.target)) closeMenu();
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') closeMenu();
                });
            });
        }

        window.bindPaymentCategoryDropdown = bind;
        bind(document);
    })();

    // Milestone collapse/expand — each item starts collapsed to a summary
    // row; clicking the chevron or title reveals the full edit form.
    (function () {
        function bind(root) {
            root.querySelectorAll('.milestone-toggle').forEach((btn) => {
                if (btn.dataset.bound) return;
                btn.dataset.bound = '1';

                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const body = document.getElementById(btn.dataset.target);
                    if (!body) return;

                    const isHidden = body.classList.contains('hidden');
                    body.classList.toggle('hidden', !isHidden);

                    document.querySelectorAll('.milestone-toggle[data-target="' + btn.dataset.target + '"]').forEach((peer) => {
                        peer.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
                        const chevron = peer.querySelector('.milestone-toggle-chevron');
                        if (chevron) chevron.style.transform = isHidden ? 'rotate(90deg)' : '';
                    });
                });
            });
        }

        window.bindMilestoneToggles = bind;
        bind(document);
    })();

    // Generic no-reload form submission: any form with data-ajax-target submits via
    // fetch, swaps in the freshly rendered HTML for each listed container id, then
    // reapplies the current tab's visibility/styling since swapped-in markup reflects
    // the server's default state, not the client's current tab selection.
    (function () {
        function bindAjaxForms(root) {
            root.querySelectorAll('form[data-ajax-target]').forEach((form) => {
                if (form.dataset.ajaxBound) return;
                form.dataset.ajaxBound = '1';

                form.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    if (form.dataset.confirm) {
                        const confirmed = await window.confirmAction(form.dataset.confirm);
                        if (!confirmed) return;
                    }

                    const targetIds = form.dataset.ajaxTarget.split(' ').filter(Boolean);
                    // Also covers the Project Status dropdown's toggle button
                    // (type="button", not "submit" — it opens/closes a menu
                    // rather than submitting directly), so it gets the same
                    // spinner + auto re-enable-on-error as every submit button.
                    const submitBtns = form.querySelectorAll('button[type="submit"], [data-status-toggle]');

                    // If this form lives inside an open Website Content/Revisions
                    // thread, remember which one -- a full panel swap below
                    // resets to the closed list view by default, which otherwise
                    // makes something like a status change look like it "reloaded"
                    // and kicked the admin back out of the thread they were on.
                    const openThread = form.closest('.admin-thread');
                    const openThreadMatch = openThread && !openThread.classList.contains('hidden')
                        ? openThread.id.match(/^thread-(.+)-(\d+)$/)
                        : null;
                    submitBtns.forEach((b) => {
                        b.dataset.originalHtml = b.innerHTML;
                        b.disabled = true;
                        b.classList.add('opacity-60', 'cursor-wait');
                        b.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Saving…';
                    });

                    fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: new FormData(form),
                    })
                        .then((response) => response.text())
                        .then((html) => {
                            const doc = new DOMParser().parseFromString(html, 'text/html');

                            const errorBanner = doc.querySelector('main .bg-red-50.border-red-200');
                            if (errorBanner) {
                                alert(errorBanner.textContent.trim());
                                return;
                            }

                            targetIds.forEach((id) => {
                                const freshEl = doc.getElementById(id);
                                const liveEl = document.getElementById(id);
                                if (freshEl && liveEl) {
                                    liveEl.replaceWith(freshEl);
                                    bindAjaxForms(freshEl);
                                    window.bindStatusDropdown?.(freshEl);
                                    window.bindDiscountCalculator?.(freshEl);
                                    window.bindPaymentCategoryDropdown?.(freshEl);
                                    window.bindMilestoneToggles?.(freshEl);
                                    window.bindRevisionDropdowns?.(freshEl);
                                }
                            });

                            showProjectTab(currentProjectTab, false);

                            if (openThreadMatch) {
                                openAdminThread(openThreadMatch[1], openThreadMatch[2], false, null);
                            }
                        })
                        .catch(() => {
                            alert('Something went wrong. Please try again.');
                        })
                        .finally(() => {
                            // Harmless no-op for buttons whose form got replaced on
                            // success (fresh server-rendered markup already has its
                            // own default button state) — only matters when the
                            // form stays in place after an error above.
                            submitBtns.forEach((b) => {
                                b.disabled = false;
                                b.classList.remove('opacity-60', 'cursor-wait');
                                if (b.dataset.originalHtml) b.innerHTML = b.dataset.originalHtml;
                            });
                        });
                });
            });
        }

        bindAjaxForms(document);
    })();

    (function () {
        const modal = document.getElementById('reset-password-modal');
        const backdrop = document.getElementById('reset-password-backdrop');
        const panel = document.getElementById('reset-password-panel');

        window.openResetPasswordModal = function () {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(function () {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('scale-95', 'opacity-0');
            });
        };

        window.closeResetPasswordModal = function () {
            backdrop.classList.add('opacity-0');
            panel.classList.add('scale-95', 'opacity-0');
            setTimeout(function () {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        };

        backdrop?.addEventListener('click', closeResetPasswordModal);
    })();
</script>

@endsection
