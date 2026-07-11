@extends('layouts.admin')

@section('title', 'Satisfaction Surveys – Admin')
@section('page-title', 'Satisfaction Surveys')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 px-5 py-4">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1.5">Average Rating</p>
        <p class="font-display text-navy dark:text-white">
            <span class="text-3xl font-bold">{{ $averageRating }}</span>
            <span class="text-sm font-normal text-gray-400 dark:text-gray-500">/ 5</span>
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 px-5 py-4">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1.5">Responses</p>
        <p class="font-display text-3xl font-bold text-navy dark:text-white">{{ $totalSubmitted }}</p>
    </div>
</div>

{{-- Toolbar: search, sort, archived toggle --}}
<div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-5">
    <form method="GET" action="{{ route('admin.satisfaction-surveys.index') }}" class="flex-1 flex gap-2">
        @if ($showArchived)
            <input type="hidden" name="archived" value="1">
        @endif
        <input type="text" name="search" value="{{ $search }}" placeholder="Search client, project, or feedback…"
               class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
        <select name="sort" onchange="this.form.requestSubmit()"
                class="rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest</option>
            <option value="highest" {{ $sort === 'highest' ? 'selected' : '' }}>Highest Rating</option>
            <option value="lowest" {{ $sort === 'lowest' ? 'selected' : '' }}>Lowest Rating</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-navy hover:bg-navy-light text-white text-sm font-semibold rounded-lg transition-colors">
            Search
        </button>
        @if ($search)
            <a href="{{ route('admin.satisfaction-surveys.index', ['archived' => $showArchived ? 1 : null]) }}"
               class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                Clear
            </a>
        @endif
    </form>

    @if ($archivedCount > 0 || $showArchived)
        <a href="{{ route('admin.satisfaction-surveys.index', $showArchived ? [] : ['archived' => 1]) }}"
           class="shrink-0 inline-flex items-center gap-1.5 text-sm font-medium px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            {{ $showArchived ? 'Back to Active' : 'Archived ('.$archivedCount.')' }}
        </a>
    @endif
</div>

@if ($surveys->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-400 dark:text-gray-500 text-sm">
            {{ $search ? 'No reviews match your search.' : ($showArchived ? 'No archived reviews.' : 'No survey responses yet.') }}
        </p>
    </div>
@else
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @foreach ($surveys as $survey)
            <div class="relative bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 max-w-prose">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-navy dark:text-white truncate">{{ $survey->project->name }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $survey->user->name }} &middot; {{ $survey->submitted_at->format('M j, Y') }}</p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        @if ($survey->isFeatured())
                            <span class="text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gold/15 text-gold-dark">Featured</span>
                        @endif
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $survey->rating >= 4 ? 'bg-teal/10 text-teal-dark' : ($survey->rating === 3 ? 'bg-gold/15 text-gold-dark' : 'bg-red-50 dark:bg-red-500/10 text-red-500') }}">
                            {{ $survey->rating }} / 5
                        </span>

                        {{-- Actions menu --}}
                        <div class="relative survey-menu">
                            <button type="button" class="survey-menu-toggle w-7 h-7 rounded-full flex items-center justify-center text-gray-400 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-navy dark:hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4z"/></svg>
                            </button>
                            <div class="survey-menu-panel hidden absolute right-0 top-8 z-10 w-44 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-lg py-1">
                                <form method="POST" action="{{ route('admin.satisfaction-surveys.feature', $survey) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full text-left px-3.5 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        {{ $survey->isFeatured() ? 'Unfeature' : 'Mark as Featured' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.satisfaction-surveys.archive', $survey) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full text-left px-3.5 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        {{ $survey->isArchived() ? 'Unarchive' : 'Archive' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.satisfaction-surveys.destroy', $survey) }}" onsubmit="return confirm('Delete this review? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left px-3.5 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($survey->feedback)
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">{{ $survey->feedback }}</p>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $surveys->links() }}</div>
@endif

<script>
    document.querySelectorAll('.survey-menu-toggle').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const panel = btn.nextElementSibling;
            document.querySelectorAll('.survey-menu-panel').forEach((p) => {
                if (p !== panel) p.classList.add('hidden');
            });
            panel.classList.toggle('hidden');
        });
    });
    document.addEventListener('click', () => {
        document.querySelectorAll('.survey-menu-panel').forEach((p) => p.classList.add('hidden'));
    });
</script>

@endsection
