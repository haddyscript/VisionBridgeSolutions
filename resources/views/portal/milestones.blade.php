@extends('layouts.portal')

@section('title', 'Project Timeline – Client Portal')
@section('page-title', 'Project Timeline')

@section('content')

@if (! $project)

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No project has been set up for your account yet. Please contact your VisionBridge representative.</p>
    </div>

@else

    @php
        $percent = $total > 0 ? (int) round($completed / $total * 100) : $project->progressPercent();

        $statusMeta = [
            'completed' => ['label' => 'Completed', 'dot' => 'bg-teal', 'pill' => 'bg-teal/10 text-teal-dark', 'title' => 'text-gray-400 dark:text-gray-500'],
            'in_progress' => ['label' => 'In Progress', 'dot' => 'bg-gold border-2 border-gold', 'pill' => 'bg-gold/15 text-gold-dark', 'title' => 'text-navy dark:text-white'],
            'pending' => ['label' => 'Pending', 'dot' => 'bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600', 'pill' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400', 'title' => 'text-gray-500 dark:text-gray-400'],
        ];
    @endphp

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 sm:p-7 mb-6">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="font-display text-xl font-bold text-navy dark:text-white">{{ $project->name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $completed }} of {{ $total }} milestone{{ $total === 1 ? '' : 's' }} complete
                </p>
            </div>
            <span class="font-sans text-3xl font-extrabold tracking-tight text-navy dark:text-white leading-none">{{ $percent }}<span class="text-base font-semibold text-gray-400 dark:text-gray-500 ml-0.5">%</span></span>
        </div>

        @if ($total > 0)
            {{-- Status filter — client-side only, scoped to the current page --}}
            <div id="milestone-filters" class="inline-flex flex-wrap items-center gap-1 bg-gray-100 dark:bg-gray-900 rounded-full p-1 mb-6">
                <button type="button" data-filter="all" class="milestone-filter-btn is-active px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors">All ({{ $milestones->count() }})</button>
                <button type="button" data-filter="completed" class="milestone-filter-btn px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors">Completed ({{ $milestones->where('status', 'completed')->count() }})</button>
                <button type="button" data-filter="in_progress" class="milestone-filter-btn px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors">In Progress ({{ $milestones->where('status', 'in_progress')->count() }})</button>
                <button type="button" data-filter="pending" class="milestone-filter-btn px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors">Pending ({{ $milestones->where('status', 'pending')->count() }})</button>
            </div>

            <div class="relative">
                <div class="absolute left-4 top-2 bottom-2 border-l-2 border-slate-100 dark:border-gray-700"></div>

                <ul id="milestone-timeline" class="relative space-y-1">
                    @foreach ($milestones as $milestone)
                        @php $meta = $statusMeta[$milestone->status] ?? $statusMeta['pending']; @endphp
                        <li data-status="{{ $milestone->status }}" class="milestone-timeline-item flex items-start gap-4 py-3">
                            <span class="relative z-10 w-8 h-8 rounded-full {{ $meta['dot'] }} ring-4 ring-white dark:ring-gray-800 flex items-center justify-center shrink-0">
                                @if ($milestone->status === 'completed')
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </span>
                            <div class="min-w-0 flex-1 pb-1">
                                <div class="flex flex-wrap items-start justify-between gap-x-3 gap-y-1">
                                    <p class="text-sm font-semibold {{ $meta['title'] }}">{{ $milestone->title }}</p>
                                    <span class="shrink-0 text-[0.65rem] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $meta['pill'] }}">{{ $meta['label'] }}</span>
                                </div>
                                @if ($milestone->description)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">{{ $milestone->description }}</p>
                                @endif
                                @if ($milestone->status === 'completed' && $milestone->completed_at)
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">Completed {{ $milestone->completed_at->format('M j, Y') }}</p>
                                @elseif ($milestone->due_date)
                                    <p class="text-xs {{ $milestone->due_date->isPast() ? 'text-red-500 font-medium' : 'text-gray-400 dark:text-gray-500' }} mt-1.5">
                                        {{ $milestone->due_date->isPast() ? 'Overdue since' : 'Due' }} {{ $milestone->due_date->format('M j, Y') }}
                                        <a href="{{ route('portal.milestones.ics', $milestone) }}" class="inline-flex items-center gap-1 ml-2 text-gold-dark hover:underline font-medium">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Add to Calendar
                                        </a>
                                    </p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>

                <p id="milestone-empty-filter" class="hidden text-sm text-gray-400 dark:text-gray-500 text-center py-8">No milestones in this state.</p>
            </div>

            <div class="mt-6">
                {{ $milestones->links() }}
            </div>
        @else
            <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-8">No milestones have been added to your project yet.</p>
        @endif
    </div>

@endif

<style>
    .milestone-filter-btn { color: #6B7280; }
    .dark .milestone-filter-btn { color: #9CA3AF; }
    .milestone-filter-btn.is-active { background: #1B2A4A; color: #fff; }
    .dark .milestone-filter-btn.is-active { background: #C9A84C; color: #1B2A4A; }
</style>

<script>
    (function () {
        const buttons = document.querySelectorAll('.milestone-filter-btn');
        const items = document.querySelectorAll('.milestone-timeline-item');
        const emptyState = document.getElementById('milestone-empty-filter');
        if (!buttons.length) return;

        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                buttons.forEach(function (b) { b.classList.toggle('is-active', b === btn); });

                const filter = btn.dataset.filter;
                let visibleCount = 0;

                items.forEach(function (item) {
                    const show = filter === 'all' || item.dataset.status === filter;
                    item.classList.toggle('hidden', !show);
                    if (show) visibleCount++;
                });

                if (emptyState) emptyState.classList.toggle('hidden', visibleCount > 0);
            });
        });
    })();
</script>

@endsection
