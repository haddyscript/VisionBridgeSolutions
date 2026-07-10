@extends('layouts.portal')

@section('title', 'Share Your Feedback – Client Portal')
@section('page-title', 'Share Your Feedback')

@section('content')

<div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-8">

    <h2 class="font-display text-xl font-bold text-navy dark:text-white mb-2">How did we do?</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
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
            <label class="block text-sm font-bold text-navy dark:text-white mb-2">Overall, how satisfied are you? *</label>
            <div class="flex gap-2" id="survey-stars">
                @for ($i = 1; $i <= 5; $i++)
                    <button type="button" data-value="{{ $i }}"
                            class="survey-star w-11 h-11 rounded-lg border-2 border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-300 dark:text-gray-500 hover:border-gold transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.062 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.958z"/></svg>
                    </button>
                @endfor
            </div>
            <input type="hidden" name="rating" id="survey-rating" value="{{ old('rating', in_array((int) request('rating'), [1, 2, 3, 4, 5], true) ? (int) request('rating') : '') }}" required>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Anything you'd like to share? (optional)</label>
            <textarea name="feedback" rows="4"
                      class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">{{ old('feedback') }}</textarea>
        </div>

        <button type="submit" class="w-full bg-gold hover:bg-gold-dark text-navy font-bold text-base py-3 rounded-lg transition-colors shadow">
            Submit Feedback
        </button>
    </form>
</div>

<script>
    (function () {
        const stars = document.querySelectorAll('.survey-star');
        const ratingInput = document.getElementById('survey-rating');

        function paint(value) {
            stars.forEach(function (star) {
                const active = Number(star.dataset.value) <= value;
                star.classList.toggle('border-gold', active);
                star.classList.toggle('text-gold', active);
                star.classList.toggle('border-gray-200', !active);
                star.classList.toggle('text-gray-300', !active);
            });
        }

        stars.forEach(function (star) {
            star.addEventListener('click', function () {
                ratingInput.value = star.dataset.value;
                paint(Number(star.dataset.value));
            });
        });

        if (ratingInput.value) paint(Number(ratingInput.value));
    })();
</script>

@endsection
