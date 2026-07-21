@extends('layouts.admin')

@section('title', 'Satisfaction Surveys – Admin')
@section('page-title', 'Satisfaction Surveys')

@section('content')

@php
    $ratingLabel = fn ($r) => match (true) {
        $r >= 4.5 => 'Excellent',
        $r >= 3.5 => 'Good',
        $r >= 2.5 => 'Fair',
        default => 'Needs Attention',
    };
    $avgLabel = $ratingLabel($averageRating);

    $starPath = 'M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.538 1.118l-3.367-2.447a1 1 0 00-1.176 0l-3.367 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z';

    // Real feedback text, escaped first, then a subtle gold highlight
    // wrapped around whole-word matches only — never raw/unescaped input.
    $highlightWords = ['Outstanding', 'Excellent', 'Professional', 'Reliable', 'Highly Recommend', 'Amazing', 'Fast'];
    $highlightFeedback = function (string $text) use ($highlightWords) {
        $escaped = e($text);
        foreach ($highlightWords as $word) {
            $escaped = preg_replace(
                '/\b('.preg_quote($word, '/').')\b/i',
                '<span class="bg-gold/15 text-gold-dark font-semibold rounded px-1">$1</span>',
                $escaped
            );
        }
        return $escaped;
    };
@endphp

{{-- SECTION 1 — hero subtitle beneath the page-title chrome (owned by
     layouts.admin, left untouched — that's shared across every admin page). --}}
<p class="text-sm text-gray-500 dark:text-gray-400 max-w-2xl leading-relaxed mb-8">
    Monitor customer feedback, ratings, testimonials, and client satisfaction to continuously improve our services and client experience.
</p>

{{-- SECTION 2 — KPI cards. "Positive Reviews" replaces the brief's
     "Recommendation Rate" — there's no would-recommend field anywhere in
     the data model, so this shows a real, computed proxy (4-5★ share)
     instead of a fabricated number. --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="kpi-card bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 px-5 py-5 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-10 h-10 rounded-xl bg-gold/10 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-gold-dark" fill="currentColor" viewBox="0 0 20 20"><path d="{{ $starPath }}"/></svg>
        </div>
        <p class="text-2xl font-bold text-navy dark:text-white">{{ $averageRating }}<span class="text-sm font-medium text-gray-400 dark:text-gray-500">/5</span></p>
        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-0.5">Average Rating</p>
        <p class="text-[0.7rem] text-gray-400 dark:text-gray-500 mt-1">{{ $avgLabel }} Overall</p>
    </div>

    <div class="kpi-card bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 px-5 py-5 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-10 h-10 rounded-xl bg-teal/10 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-teal-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8-1.5 0-2.9-.32-4.14-.9L3 20l1.1-3.3C3.4 15.6 3 13.85 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <p class="text-2xl font-bold text-navy dark:text-white">{{ $totalSubmitted }}</p>
        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-0.5">Responses</p>
        <p class="text-[0.7rem] text-gray-400 dark:text-gray-500 mt-1">Client Reviews</p>
    </div>

    <div class="kpi-card bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 px-5 py-5 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-500/10 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </div>
        <p class="text-2xl font-bold text-navy dark:text-white">{{ $positiveReviewPercent }}%</p>
        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-0.5">Positive Reviews</p>
        <p class="text-[0.7rem] text-gray-400 dark:text-gray-500 mt-1">Rated 4★ or Higher</p>
    </div>

    <div class="kpi-card bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 px-5 py-5 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-10 h-10 rounded-xl bg-gold/10 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="text-2xl font-bold text-navy dark:text-white">{{ $fiveStarPercent }}%</p>
        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-0.5">Five-Star Reviews</p>
        <p class="text-[0.7rem] text-gray-400 dark:text-gray-500 mt-1">Perfect Satisfaction</p>
    </div>
</div>

{{-- SECTION 16 — summary banner --}}
<div class="relative overflow-hidden rounded-2xl border border-gold/25 bg-gradient-to-br from-gold/10 via-white to-white dark:from-gold/10 dark:via-gray-800 dark:to-gray-800 px-6 py-5 mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
    <div>
        <p class="text-xs font-bold uppercase tracking-widest text-gold-dark mb-1.5">Customer Satisfaction</p>
        <div class="flex items-center gap-2.5">
            <div class="flex items-center gap-0.5">
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-gold' : 'text-gray-200 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="{{ $starPath }}"/></svg>
                @endfor
            </div>
            <span class="text-lg font-bold text-navy dark:text-white">{{ $averageRating }} / 5</span>
        </div>
    </div>
    <div class="flex items-center gap-6">
        <div>
            <p class="text-lg font-bold text-navy dark:text-white">{{ $positiveReviewPercent }}%</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Positive Reviews</p>
        </div>
        <div class="w-px h-9 bg-gold/25"></div>
        <div>
            <p class="text-lg font-bold text-navy dark:text-white">{{ $totalSubmitted }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Client Responses</p>
        </div>
    </div>
</div>

{{-- SECTION 3 — toolbar --}}
<div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-3 mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center gap-2.5">
        <form method="GET" action="{{ route('admin.satisfaction-surveys.index') }}" class="flex-1 flex flex-col sm:flex-row gap-2.5">
            @if ($showArchived)
                <input type="hidden" name="archived" value="1">
            @endif
            <div class="relative flex-1">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by client, project, or review content…"
                       class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 dark:text-white pl-10 pr-4 py-2.5 text-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold transition-shadow">
            </div>
            <select name="sort" onchange="this.form.requestSubmit()"
                    class="rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 dark:text-white px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold transition-shadow">
                <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest</option>
                <option value="highest" {{ $sort === 'highest' ? 'selected' : '' }}>Highest Rating</option>
                <option value="lowest" {{ $sort === 'lowest' ? 'selected' : '' }}>Lowest Rating</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-navy hover:bg-navy-light text-white text-sm font-semibold rounded-xl transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
                Search
            </button>
            @if ($search)
                <a href="{{ route('admin.satisfaction-surveys.index', ['archived' => $showArchived ? 1 : null]) }}"
                   class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-center">
                    Clear
                </a>
            @endif
        </form>

        @if ($archivedCount > 0 || $showArchived)
            <a href="{{ route('admin.satisfaction-surveys.index', $showArchived ? [] : ['archived' => 1]) }}"
               class="shrink-0 inline-flex items-center justify-center gap-1.5 text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                {{ $showArchived ? 'Back to Active' : 'Archived ('.$archivedCount.')' }}
            </a>
        @endif
    </div>
</div>

{{-- SECTION 10 — empty state --}}
@if ($surveys->isEmpty())
    <div class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 py-16 px-6 text-center">
        <div class="w-16 h-16 rounded-full bg-gold/10 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.538 1.118l-3.367-2.447a1 1 0 00-1.176 0l-3.367 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118l-3.368-2.447c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/></svg>
        </div>
        <p class="font-bold text-navy dark:text-white mb-1.5">
            {{ $search ? 'No Reviews Match Your Search' : ($showArchived ? 'No Archived Reviews' : 'No Satisfaction Surveys Yet') }}
        </p>
        <p class="text-sm text-gray-400 dark:text-gray-500 max-w-sm mx-auto leading-relaxed">
            {{ $search
                ? 'Try a different search term, or clear your search to see everything.'
                : ($showArchived ? 'Archived reviews will appear here once you archive one.' : 'Once clients complete surveys, their feedback will appear here.') }}
        </p>
    </div>
@else
    {{-- SECTION 13 — grid: 1 col mobile, 2 cols tablet+desktop, equal
         heights (CSS grid rows stretch children by default). --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach ($surveys as $survey)
            @php
                $rating = (int) $survey->rating;
                $ratingText = $ratingLabel($rating);
                $needsExpand = $survey->feedback && mb_strlen($survey->feedback) > 220;
            @endphp
            {{-- SECTION 4 — review card. No overflow-hidden here: the
                 actions menu below is position:absolute and needs to be
                 able to sit outside the card's own box without being clipped. --}}
            <div class="review-card bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-gold/40 dark:hover:border-gold/30 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 p-6 flex flex-col">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-11 h-11 rounded-xl bg-navy text-gold text-sm font-bold flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($survey->project->name, 0, 1)) }}
                        </span>
                        <div class="min-w-0">
                            <p class="text-xl font-semibold text-navy dark:text-white truncate leading-snug">{{ $survey->project->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $survey->user->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $survey->submitted_at->format('M j, Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-1.5 shrink-0">
                        @if ($survey->isFeatured())
                            <span class="text-[0.65rem] font-bold uppercase tracking-wide px-2 py-1 rounded-full bg-gold/15 text-gold-dark whitespace-nowrap">Featured</span>
                        @endif

                        {{-- SECTION 11 — overflow menu --}}
                        <div class="relative survey-menu">
                            <button type="button" class="survey-menu-toggle w-8 h-8 rounded-full flex items-center justify-center text-gray-400 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-navy dark:hover:text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gold">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4z"/></svg>
                            </button>
                            <div class="survey-menu-panel hidden absolute right-0 top-9 z-10 w-44 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg py-1.5">
                                <form method="POST" action="{{ route('admin.satisfaction-surveys.feature', $survey) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full text-left px-3.5 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        {{ $survey->isFeatured() ? 'Unfeature' : 'Mark as Featured' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.satisfaction-surveys.archive', $survey) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full text-left px-3.5 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        {{ $survey->isArchived() ? 'Unarchive' : 'Archive' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.satisfaction-surveys.destroy', $survey) }}" onsubmit="return confirm('Delete this review? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left px-3.5 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 5 — rating badge --}}
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="flex items-center gap-0.5">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $rating ? 'text-gold' : 'text-gray-200 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="{{ $starPath }}"/></svg>
                        @endfor
                    </div>
                    <span class="text-sm font-bold text-navy dark:text-white">{{ number_format($rating, 1) }}</span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $rating >= 4 ? 'bg-teal/10 text-teal-dark' : ($rating === 3 ? 'bg-gold/15 text-gold-dark' : 'bg-red-50 dark:bg-red-500/10 text-red-500') }}">
                        {{ $ratingText }}
                    </span>
                </div>

                {{-- SECTION 6-8 — review content + expand --}}
                @if ($survey->feedback)
                    <div class="review-body flex-1 flex flex-col">
                        <p class="review-feedback-text text-base text-gray-600 dark:text-gray-300 leading-relaxed {{ $needsExpand ? 'review-clamp' : '' }}">
                            {!! $highlightFeedback($survey->feedback) !!}
                        </p>
                        @if ($needsExpand)
                            <button type="button" class="review-expand-btn mt-2.5 self-start inline-flex items-center gap-1 text-xs font-semibold text-gold-dark hover:text-navy dark:hover:text-white transition-colors">
                                <span data-expand-label>Read Full Review</span>
                                <svg class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $surveys->links() }}</div>
@endif

<style>
    /* SECTION 15 — subtle staggered entrance. Safe to use transform here
       (unlike the Developers page): the overflow menu above is
       position:absolute anchored to its own .survey-menu (position:relative)
       wrapper, not position:fixed, so a transform on an outer ancestor
       during/after this animation doesn't change what it's anchored to. */
    @keyframes survey-card-fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .kpi-card, .review-card {
        animation: survey-card-fade-in 0.45s ease-out both;
    }
    .kpi-card:nth-child(1) { animation-delay: 0s; }
    .kpi-card:nth-child(2) { animation-delay: 0.05s; }
    .kpi-card:nth-child(3) { animation-delay: 0.1s; }
    .kpi-card:nth-child(4) { animation-delay: 0.15s; }
    .review-card:nth-child(1) { animation-delay: 0.1s; }
    .review-card:nth-child(2) { animation-delay: 0.15s; }
    .review-card:nth-child(3) { animation-delay: 0.2s; }
    .review-card:nth-child(4) { animation-delay: 0.25s; }
    @media (prefers-reduced-motion: reduce) {
        .kpi-card, .review-card { animation: none; }
    }

    /* Manual line-clamp (what Tailwind's own utility compiles down to
       anyway) so this doesn't depend on which Tailwind CDN build is
       loaded having the line-clamp utility included. */
    .review-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

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

    // SECTION 8 — Read Full Review / Show Less
    document.querySelectorAll('.review-expand-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const text = btn.closest('.review-body')?.querySelector('.review-feedback-text');
            if (!text) return;
            const isNowClamped = text.classList.toggle('review-clamp');
            btn.querySelector('[data-expand-label]').textContent = isNowClamped ? 'Read Full Review' : 'Show Less';
            btn.querySelector('svg').style.transform = isNowClamped ? '' : 'rotate(180deg)';
        });
    });
</script>

@endsection
