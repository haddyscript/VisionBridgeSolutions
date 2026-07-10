@extends('layouts.portal')

@section('title', 'Share Your Feedback – Client Portal')
@section('page-title', 'Share Your Feedback')

@section('content')

{{-- Center the card within the content panel, both axes --}}
<div class="min-h-[calc(100vh-9rem)] flex items-center justify-center">
    <div class="w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-8">

        <h2 class="font-display text-2xl font-bold text-navy dark:text-white mb-2">How did we do?</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-7">
            Your project just launched — we'd love to know how the experience felt from start to finish.
        </p>

        <form method="POST" action="{{ route('portal.survey.store') }}">
            @csrf

            @if ($errors->any())
                <div class="mb-5 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="mb-6">
                <label class="block text-sm font-bold text-navy dark:text-white mb-3">Overall, how satisfied are you? <span class="text-gold-dark">*</span></label>
                <div class="flex items-center gap-1.5" id="survey-stars" role="radiogroup" aria-label="Rate your experience">
                    @for ($i = 1; $i <= 5; $i++)
                        <button type="button" data-value="{{ $i }}" aria-label="{{ $i }} star{{ $i > 1 ? 's' : '' }}"
                                class="survey-star text-gray-300 dark:text-gray-600 hover:scale-110 transition-transform duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-gold rounded-md">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.062 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.958z"/></svg>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="survey-rating" value="{{ old('rating', in_array((int) request('rating'), [1, 2, 3, 4, 5], true) ? (int) request('rating') : '') }}" required>
            </div>

            <div class="mb-7">
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Anything you'd like to share? <span class="font-normal text-gray-400">(optional)</span></label>
                <textarea name="feedback" rows="4"
                          placeholder="Tell us what went well or how we can improve..."
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 text-sm text-navy dark:text-white placeholder-gray-400 dark:placeholder-gray-500 dark:bg-gray-900 transition focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/40">{{ old('feedback') }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-gold hover:bg-gold-dark text-navy font-bold text-sm px-6 py-3 rounded-lg shadow-sm hover:shadow transition-all hover:-translate-y-0.5">
                    Submit Feedback
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        const stars = Array.from(document.querySelectorAll('.survey-star'));
        const ratingInput = document.getElementById('survey-rating');

        function paint(value) {
            stars.forEach(function (star) {
                const active = Number(star.dataset.value) <= value;
                star.classList.toggle('text-gold', active);
                star.classList.toggle('text-gray-300', !active);
                star.classList.toggle('dark:text-gray-600', !active);
            });
        }

        let selected = Number(ratingInput.value) || 0;

        stars.forEach(function (star) {
            const value = Number(star.dataset.value);
            star.addEventListener('mouseenter', function () { paint(value); });
            star.addEventListener('mouseleave', function () { paint(selected); });
            star.addEventListener('click', function () {
                selected = value;
                ratingInput.value = value;
                paint(selected);
            });
        });

        paint(selected);
    })();
</script>

@endsection
