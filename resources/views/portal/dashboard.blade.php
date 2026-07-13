@extends('layouts.portal')

@section('title', 'Overview – Client Portal')
@section('page-title', 'Overview')

@section('content')

@if ($announcement)
    @include('partials.announcement-banner', [
        'announcement' => $announcement,
        'dismissUrl' => route('portal.announcements.dismiss', $announcement),
        'domId' => 'portal-announcement',
    ])
@endif

{{-- The launch feedback card is rendered side-by-side with "What's Next" below
     (inside the project section) — see the two-column grid there. --}}

@if ($showPaymentReminder)
    <div id="payment-reminder-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-200">
        <div id="payment-reminder-card" class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden scale-95 transition-transform duration-200">

            <button type="button" id="payment-reminder-close" aria-label="Close" class="absolute top-4 right-4 text-white/70 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="px-6 pt-7 pb-6" style="background:linear-gradient(135deg,#111D33,#1B2A4A);">
                <div class="w-12 h-12 rounded-full bg-gold/15 text-gold flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-8.18 14.18A1 1 0 003 19.5h18a1 1 0 00.86-1.46L13.71 3.86a1 1 0 00-1.72 0z"/>
                    </svg>
                </div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-1">Payment Reminder</p>
                <h2 class="font-display text-xl font-bold text-white">You have a pending payment</h2>
            </div>

            <div class="px-6 py-6">
                @if ($pendingPayment)
                    <div class="flex items-center justify-between rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-3 mb-5">
                        <div class="min-w-0 pr-3">
                            <p class="text-sm font-medium text-navy dark:text-white truncate">{{ $pendingPayment->description }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Pending</p>
                        </div>
                        <p class="font-display text-lg font-bold text-navy dark:text-white shrink-0">{{ $pendingPayment->formattedAmount() }}</p>
                    </div>
                @endif

                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    Please complete this payment to keep your project moving forward without delay.
                </p>

                <div class="flex flex-col sm:flex-row sm:justify-end gap-2.5">
                    <button type="button" id="payment-reminder-dismiss" class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Remind me later
                    </button>
                    <a href="{{ route('portal.payments.index') }}" class="text-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-gold text-navy hover:bg-gold-dark transition-colors">
                        View &amp; Pay Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const overlay = document.getElementById('payment-reminder-modal');
            const card = document.getElementById('payment-reminder-card');

            requestAnimationFrame(() => {
                overlay.classList.remove('opacity-0');
                card.classList.remove('scale-95');
            });

            function closeModal() {
                overlay.classList.add('opacity-0');
                card.classList.add('scale-95');
                setTimeout(() => overlay.remove(), 200);
            }

            document.getElementById('payment-reminder-dismiss')?.addEventListener('click', closeModal);
            document.getElementById('payment-reminder-close')?.addEventListener('click', closeModal);
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) closeModal();
            });
        })();
    </script>
@endif

@if ($showSurveyModal)
    <div id="survey-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-200">
        <div id="survey-card" class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden scale-95 transition-transform duration-200">

            <button type="button" id="survey-close" aria-label="Close" class="absolute top-4 right-4 text-white/70 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="px-6 pt-6 pb-5" style="background:linear-gradient(135deg,#0F766E,#0D9488);">
                <div class="w-12 h-12 rounded-full bg-white/15 text-white flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.062 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.958z"/>
                    </svg>
                </div>
                <p class="text-xs font-semibold uppercase tracking-widest text-white/70 mb-1">Your Feedback</p>
                <h2 class="font-display text-xl font-bold text-white">Your project launched — how did we do?</h2>
            </div>

            <div class="px-6 pt-5 pb-6">
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-4">
                    We'd love to hear about your experience. It only takes a minute, and it helps us serve you better.
                </p>

                {{-- Interactive rating --}}
                <div id="survey-stars" role="radiogroup" aria-label="Rate your experience" class="flex items-center justify-center gap-1.5 mb-5">
                    @for ($i = 1; $i <= 5; $i++)
                        <button type="button" class="survey-star text-gray-300 dark:text-gray-600 hover:scale-110 transition-transform duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-gold rounded"
                                data-value="{{ $i }}" aria-label="{{ $i }} star{{ $i > 1 ? 's' : '' }}">
                            <svg class="w-9 h-9" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.786 1.401 8.169L12 18.896l-7.335 3.869 1.401-8.169L.132 9.21l8.2-1.192z"/></svg>
                        </button>
                    @endfor
                </div>

                <div class="flex items-center justify-center gap-2">
                    <button type="button" id="survey-dismiss" class="inline-flex items-center justify-center h-11 px-4 rounded-lg text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Maybe later
                    </button>
                    <a id="survey-submit" href="{{ route('portal.survey.show') }}" class="inline-flex items-center justify-center h-11 px-5 rounded-lg text-sm font-semibold bg-teal-dark text-white hover:bg-teal transition-colors">
                        Share Feedback
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const overlay = document.getElementById('survey-modal');
            const card = document.getElementById('survey-card');

            requestAnimationFrame(() => {
                overlay.classList.remove('opacity-0');
                card.classList.remove('scale-95');
            });

            function closeModal() {
                overlay.classList.add('opacity-0');
                card.classList.add('scale-95');
                setTimeout(() => overlay.remove(), 200);
            }

            document.getElementById('survey-dismiss')?.addEventListener('click', closeModal);
            document.getElementById('survey-close')?.addEventListener('click', closeModal);
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) closeModal();
            });

            // Interactive star rating — hover previews, click selects, and the
            // choice is carried to the survey page via ?rating=N.
            const stars = Array.from(document.querySelectorAll('.survey-star'));
            const submit = document.getElementById('survey-submit');
            const baseHref = submit ? submit.getAttribute('href') : '';
            let selected = 0;

            function paint(n) {
                stars.forEach(function (s, i) {
                    const on = i < n;
                    s.classList.toggle('text-gold', on);
                    s.classList.toggle('text-gray-300', !on);
                    s.classList.toggle('dark:text-gray-600', !on);
                });
            }

            stars.forEach(function (star, i) {
                star.addEventListener('mouseenter', function () { paint(i + 1); });
                star.addEventListener('mouseleave', function () { paint(selected); });
                star.addEventListener('click', function () {
                    selected = i + 1;
                    paint(selected);
                    if (submit) {
                        submit.setAttribute('href', baseHref + (baseHref.includes('?') ? '&' : '?') + 'rating=' + selected);
                    }
                });
            });
        })();
    </script>
@endif

@if ($firstVisit)
    <div id="welcome-banner" class="relative overflow-hidden rounded-2xl mb-6 sm:mb-8 shadow-lg">
        <div class="px-5 py-6 sm:px-8 sm:py-8 md:py-10" style="background:linear-gradient(135deg,#111D33,#1B2A4A);">

            <button type="button" id="welcome-banner-close" aria-label="Dismiss"
                class="absolute top-4 right-4 text-white/40 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-5">
                <div class="shrink-0 w-12 h-12 rounded-full bg-gold/15 text-gold flex items-center justify-center mt-0.5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3l14 9-14 9V3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-1">Welcome to VisionBridge</p>
                    <h2 class="font-display text-xl md:text-2xl font-bold text-white mb-2">
                        You're in — let's build something great.
                    </h2>
                    <p class="text-sm text-white/70 max-w-xl leading-relaxed">
                        Your client portal is ready. You can upload files, submit website content, track your project
                        milestones, and manage payments — all from right here. We'll be in touch as your project
                        gets underway.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('portal.category', 'logo') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-gold text-navy text-sm font-semibold hover:bg-gold-dark transition-colors">
                            Upload Your Files
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="{{ route('portal.faq') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-white/10 text-white text-sm font-medium hover:bg-white/20 transition-colors">
                            View FAQ
                        </a>
                        <button type="button" id="welcome-banner-tour-btn"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-white/10 text-white text-sm font-medium hover:bg-white/20 transition-colors">
                            Take a Tour
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        (function () {
            document.getElementById('welcome-banner-close')?.addEventListener('click', function () {
                const banner = document.getElementById('welcome-banner');
                banner.style.transition = 'opacity 200ms, max-height 300ms';
                banner.style.overflow = 'hidden';
                banner.style.maxHeight = banner.offsetHeight + 'px';
                requestAnimationFrame(() => {
                    banner.style.opacity = '0';
                    banner.style.maxHeight = '0';
                    banner.style.marginBottom = '0';
                });
                banner.addEventListener('transitionend', () => banner.remove(), { once: true });
            });

            document.getElementById('welcome-banner-tour-btn')?.addEventListener('click', function () {
                document.getElementById('tour-replay-trigger')?.click();
            });

            @if (! auth()->user()->tour_completed_at)
                window.autoStartTour = true;
            @endif
        })();
    </script>
@endif

@if (! $project)

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No project has been set up for your account yet. Please contact your VisionBridge representative.</p>
    </div>

@else

    @php
        $statusLabels = [
            'onboarding'  => 'Onboarding',
            'in_progress' => 'In Progress',
            'review'      => 'In Review',
            'launched'    => 'Launched',
            'maintenance' => 'Care',
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

    @if ($project->status === 'onboarding' && $project->total_price === null)
        <div class="rounded-xl p-4 sm:p-6 mb-6 sm:mb-8 border border-gold/20 dark:border-gold/10" style="background:linear-gradient(135deg,rgba(201,168,76,0.10),rgba(42,157,143,0.08));">
            <p class="text-xs font-semibold uppercase tracking-widest text-gold-dark mb-2">Hey {{ explode(' ', auth()->user()->name)[0] }} 👋</p>
            <h2 class="font-display text-xl font-bold text-navy dark:text-white mb-2">We're preparing your project quote</h2>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 max-w-2xl">
                Thanks for completing your agreement and questionnaire — our team is now putting together your
                custom project quote. You'll get an email the moment it's ready, and can pay your initial 50%
                deposit here to kick off development. In the meantime, feel free to start uploading your logo,
                photos, and content below — no need to wait.
            </p>
            <a href="{{ route('portal.faq') }}#file-formats" class="inline-flex items-center gap-1.5 text-sm text-gold-dark hover:underline">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                What file formats should I upload?
            </a>
        </div>
    @elseif ($project->status === 'onboarding')
        <div class="rounded-xl p-4 sm:p-6 mb-6 sm:mb-8 border border-gold/20 dark:border-gold/10" style="background:linear-gradient(135deg,rgba(201,168,76,0.10),rgba(42,157,143,0.08));">
            <p class="text-xs font-semibold uppercase tracking-widest text-gold-dark mb-2">Hey {{ explode(' ', auth()->user()->name)[0] }} 👋</p>
            <h2 class="font-display text-xl font-bold text-navy dark:text-white mb-2">Glad to have you here — let's get started</h2>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-5 max-w-2xl">
                Drop your logo, photos, and any content you'd like on the site below, and we'll take it from there.
                You can check back here anytime to see how things are coming along.
            </p>
            <div class="flex flex-col sm:flex-row sm:items-start gap-3">
                @php $steps = [
                    'Add your logo, photos, and docs',
                    'Tell us what you want the site to say',
                    'Watch the progress bar move as we build',
                ]; @endphp
                @foreach ($steps as $i => $step)
                    <div class="flex items-start gap-2.5 flex-1">
                        <span class="w-5 h-5 rounded-full bg-gold/20 text-gold-dark text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $i + 1 }}</span>
                        <span class="text-sm text-navy/70 dark:text-white/80">{{ $step }}</span>
                    </div>
                    @if (! $loop->last)
                        <svg class="hidden sm:block w-4 h-4 text-navy/20 dark:text-white/25 shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    @endif
                @endforeach
            </div>
            <a href="{{ route('portal.faq') }}#file-formats" class="inline-flex items-center gap-1.5 text-sm text-gold-dark hover:underline mt-4">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                What file formats should I upload?
            </a>
        </div>
    @endif

    @if ($pendingPayment)
        <a href="{{ route('portal.payments.index') }}" class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-red-200 dark:border-red-500/30 bg-red-50 dark:bg-red-500/10 px-4 py-4 sm:px-5 mb-6 sm:mb-8 hover:border-red-300 dark:hover:border-red-500/50 transition-colors">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-full bg-red-500/15 text-red-500 flex items-center justify-center shrink-0">
                    <svg class="w-[1.125rem] h-[1.125rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-8.18 14.18A1 1 0 003 19.5h18a1 1 0 00.86-1.46L13.71 3.86a1 1 0 00-1.72 0z"/></svg>
                </span>
                <p class="text-sm text-red-700 dark:text-red-300">
                    <span class="font-semibold">Payment due: {{ $pendingPayment->formattedAmount() }}</span>
                    &middot; Pending since {{ $pendingPayment->created_at->format('M j, Y') }}
                </p>
            </div>
            <span class="text-xs font-semibold uppercase tracking-wide text-red-600 dark:text-red-400 shrink-0">View &amp; Pay &rarr;</span>
        </a>
    @endif

    @if ($project->status === 'review')
        <div class="rounded-xl p-4 sm:p-6 mb-6 sm:mb-8 border border-teal/20 dark:border-teal/10" style="background:linear-gradient(135deg,rgba(42,157,143,0.10),rgba(201,168,76,0.08));">
            @if ($errors->has('cancel'))
                <p class="text-sm font-medium text-red-600 mb-4">{{ $errors->first('cancel') }}</p>
            @endif

            @if ($project->client_approved_at)
                <p class="text-xs font-semibold uppercase tracking-widest text-teal-dark mb-2">Approved — Final Payment Ready</p>
                <h2 class="font-display text-xl font-bold text-navy dark:text-white mb-2">Thanks for approving your website!</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 max-w-2xl">
                    Your final payment request is ready whenever you're set to complete it.
                </p>
                <a href="{{ route('portal.payments.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy dark:text-white bg-gold/15 hover:bg-gold/25 px-4 py-2.5 rounded-lg transition-colors">
                    View Final Payment
                </a>
            @else
                <p class="text-xs font-semibold uppercase tracking-widest text-teal-dark mb-2">7-Day Landing Page Review Period</p>
                <h2 class="font-display text-xl font-bold text-navy dark:text-white mb-2">Take a look and let us know what you think</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 max-w-2xl">
                    @if ($project->isReviewWindowOpen())
                        You have <strong>{{ $project->daysLeftInReview() }} day{{ $project->daysLeftInReview() === 1 ? '' : 's' }}</strong>
                        left in your 7-Day Landing Page Review Period to request minor revisions. This is a review
                        period, not a free trial — at the end of it, you can approve the landing page as your
                        completed project or authorize us to continue building the rest of your site.
                    @else
                        Your 7-Day Landing Page Review Period has closed, but you can still approve below or reach out if you need revisions.
                    @endif
                </p>

                <div class="flex flex-wrap items-center gap-3">
                    <form method="POST" action="{{ route('portal.review.approve') }}" onsubmit="return confirm('Approve the final website? This will create your final 50% payment request.')">
                        @csrf
                        <button type="submit" class="bg-gold hover:bg-gold-dark text-navy font-bold text-sm px-5 py-2.5 rounded-lg transition-colors shadow">
                            Approve Final Website
                        </button>
                    </form>

                    <a href="{{ route('portal.category', 'revision') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy dark:text-white bg-white/70 dark:bg-gray-800 hover:bg-white px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 transition-colors">
                        Request Revisions
                    </a>

                    @if ($project->isReviewWindowOpen())
                        <form method="POST" action="{{ route('portal.review.cancel') }}" onsubmit="return confirm('This cancels your project and refunds your deposit, minus Stripe\'s processing fee. This cannot be undone — continue?')">
                            @csrf
                            <button type="submit" class="text-sm font-semibold text-red-500 hover:underline px-2 py-2.5">
                                Not moving forward — cancel &amp; refund me
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    @endif

    {{-- Launch feedback + What's Next — side by side (stack on mobile) --}}
    @if ($pendingSurvey || $whatsNext)
        <div class="grid grid-cols-1 {{ ($pendingSurvey && $whatsNext) ? 'lg:grid-cols-2' : '' }} gap-4 sm:gap-6 mb-6 sm:mb-8 items-stretch">

            @if ($pendingSurvey)
                {{-- Feedback card — teal accent --}}
                <div class="flex flex-col rounded-xl border border-teal/30 bg-teal/10 p-5">
                    <div class="flex items-center gap-2.5 mb-3">
                        <span class="w-9 h-9 rounded-full bg-teal/20 text-teal-dark flex items-center justify-center shrink-0">
                            <svg class="w-[1.125rem] h-[1.125rem]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.786 1.401 8.169L12 18.896l-7.335 3.869 1.401-8.169L.132 9.21l8.2-1.192z"/></svg>
                        </span>
                        <p class="text-xs font-semibold uppercase tracking-widest text-teal-dark">Your Project Launched</p>
                    </div>
                    <p class="text-base font-bold text-navy dark:text-white mb-1">🎉 Congratulations — your site is live!</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">How did we do? A minute of your feedback helps us serve you even better.</p>
                    <a href="{{ route('portal.survey.show') }}" class="group relative mt-auto self-start inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-teal-dark overflow-hidden transition-colors duration-300 hover:text-teal-dark">
                        <span class="absolute inset-0 bg-white origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></span>
                        <span class="relative z-10 inline-flex items-center gap-1.5">
                            Share Feedback
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </span>
                    </a>
                </div>
            @endif

            @if ($whatsNext)
                {{-- What's Next — subtle action card --}}
                <div class="flex flex-col rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60 p-5">
                    <div class="flex items-center gap-2.5 mb-3">
                        <span class="w-9 h-9 rounded-full {{ $whatsNext['actionable'] ? 'bg-gold/15 text-gold-dark' : 'bg-teal/10 text-teal-dark' }} flex items-center justify-center shrink-0">
                            @if ($whatsNext['actionable'])
                                <svg class="w-[1.125rem] h-[1.125rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            @else
                                <svg class="w-[1.125rem] h-[1.125rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </span>
                        <p class="text-xs font-semibold uppercase tracking-widest {{ $whatsNext['actionable'] ? 'text-gold-dark' : 'text-teal-dark' }}">What's Next</p>
                    </div>
                    <p class="text-base font-bold text-navy dark:text-white mb-1">{{ $whatsNext['title'] }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $whatsNext['description'] }}</p>
                    @if ($whatsNext['url'])
                        <a href="{{ $whatsNext['url'] }}" class="mt-auto self-start px-4 py-2.5 rounded-lg text-sm font-semibold bg-gold text-navy hover:bg-gold-dark transition-colors">
                            {{ $whatsNext['actionLabel'] }}
                        </a>
                    @endif
                </div>
            @endif

        </div>
    @endif

    {{-- Progress Tracker --}}
    @php
        // Onboarding is always complete here — the dashboard sits behind the
        // `onboarding.complete` middleware, so reaching it means it's done.
        $milestonesDone = $project->milestones->where('status', 'completed')->count();
        $milestonesTotal = $project->milestones->count();

        $totalDue = $project->payments->sum('amount');
        $totalPaid = $project->payments->where('status', 'paid')->sum('amount');
        $paymentPercent = $totalDue > 0 ? (int) round($totalPaid / $totalDue * 100) : 100;

        // Build % reflects actual milestone completion (e.g. 1 of 5 = 20%) so it
        // matches its own subtext; only falls back to the project's overall
        // progress figure when there are no milestones yet.
        $buildPercent = $milestonesTotal > 0
            ? (int) round($milestonesDone / $milestonesTotal * 100)
            : $project->progressPercent();

        $progressRings = [
            ['label' => 'Onboarding', 'sub' => 'Getting set up', 'percent' => 100, 'color' => '#2A9D8F'],
            ['label' => 'Project Build', 'sub' => $milestonesTotal > 0 ? "{$milestonesDone} of {$milestonesTotal} milestones" : 'Overall progress', 'percent' => $buildPercent, 'color' => '#C9A84C'],
            ['label' => 'Payments', 'sub' => $totalDue > 0 ? 'Paid to date' : 'Nothing due', 'percent' => $paymentPercent, 'color' => '#22C55E'],
        ];
    @endphp
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 flex flex-col">
        <h2 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Progress Tracker</h2>
        <div class="flex-1 flex flex-col justify-center gap-8">
            {{-- Content by section (horizontal bar chart) --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Your Content by Section</p>
                @php $maxCount = max(1, (int) $counts->max('count')); @endphp
                @if ((int) $counts->sum('count') > 0)
                    <div class="space-y-2.5">
                        @foreach ($counts as $cat)
                            <div class="flex items-center gap-3">
                                <span class="w-24 sm:w-36 shrink-0 text-xs text-gray-500 dark:text-gray-400 leading-tight">{{ $cat['label'] }}</span>
                                <div class="flex-1 h-2.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                    <div class="h-full rounded-full bg-gold" style="width: {{ $cat['count'] > 0 ? max(round($cat['count'] / $maxCount * 100), 5) : 0 }}%"></div>
                                </div>
                                <span class="w-5 shrink-0 text-right text-xs font-semibold text-navy dark:text-white">{{ $cat['count'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 dark:text-gray-500">No files uploaded yet — add your logo, photos, and content to get started.</p>
                @endif
            </div>

            {{-- Progress rings --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach ($progressRings as $ring)
                @php $pct = max(0, min(100, (int) $ring['percent'])); @endphp
                <div class="group flex flex-col items-center text-center">
                    <div class="relative w-28 h-28 transition-transform duration-300 group-hover:scale-105">
                        <svg class="w-28 h-28 -rotate-90 drop-shadow-sm" viewBox="0 0 36 36">
                            <circle class="stroke-gray-200 dark:stroke-gray-700" cx="18" cy="18" r="15.915" fill="none" stroke-width="3.2"/>
                            <circle class="progress-ring-arc" cx="18" cy="18" r="15.915" fill="none" stroke="{{ $ring['color'] }}" stroke-width="3.2" stroke-linecap="round" stroke-dasharray="0 100" data-pct="{{ $pct }}"/>
                        </svg>
                        <span class="progress-ring-value absolute inset-0 flex items-center justify-center font-display text-2xl font-bold text-navy dark:text-white" data-pct="{{ $pct }}">0%</span>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-navy dark:text-white">{{ $ring['label'] }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $ring['sub'] }}</p>
                </div>
            @endforeach
            </div>
        </div>

        <style>
            .progress-ring-arc { transition: stroke-dasharray 2.4s cubic-bezier(0.33, 1, 0.68, 1); }
            @media (prefers-reduced-motion: reduce) { .progress-ring-arc { transition: none; } }
        </style>
        <script>
            (function () {
                const arcs = document.querySelectorAll('.progress-ring-arc');
                const values = document.querySelectorAll('.progress-ring-value');
                const clamp = (n) => Math.max(0, Math.min(100, parseInt(n, 10) || 0));

                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    arcs.forEach((a) => a.setAttribute('stroke-dasharray', clamp(a.dataset.pct) + ' 100'));
                    values.forEach((e) => e.textContent = clamp(e.dataset.pct) + '%');
                    return;
                }

                // Sweep the arcs from 0 to their value.
                requestAnimationFrame(() => {
                    arcs.forEach((a) => a.setAttribute('stroke-dasharray', clamp(a.dataset.pct) + ' 100'));
                });

                // Count the numbers up in sync with the sweep.
                values.forEach((el) => {
                    const target = clamp(el.dataset.pct);
                    const duration = 2400;
                    const start = performance.now();
                    (function tick(now) {
                        const t = Math.min(1, (now - start) / duration);
                        el.textContent = Math.round(target * (1 - Math.pow(1 - t, 3))) + '%';
                        if (t < 1) requestAnimationFrame(tick);
                    })(start);
                });
            })();
        </script>
    </div>

        {{-- Right column: Notifications above Refer a Friend --}}
        <div class="space-y-6">
        {{-- Notifications recap --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <div class="flex items-center gap-2 mb-4">
                <h2 class="font-display text-lg font-bold text-navy dark:text-white">Notifications</h2>
                @if (($unreadNotificationCount ?? 0) > 0)
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-red-500 text-white">{{ $unreadNotificationCount }}</span>
                @endif
            </div>
            @forelse ($recentNotifications as $n)
                <div class="flex items-start gap-3 py-2.5 border-t border-gray-100 dark:border-gray-700 first:border-t-0">
                    <span class="w-8 h-8 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $n->created_at->diffForHumans() }}</p>
                        @if ($n->url)
                            <a href="{{ $n->url }}" class="text-sm font-medium text-navy dark:text-white hover:underline">{{ $n->title }}</a>
                        @else
                            <p class="text-sm font-medium text-navy dark:text-white">{{ $n->title }}</p>
                        @endif
                        @if ($n->description)
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $n->description }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 dark:text-gray-500 py-6 text-center">No updates yet — we'll let you know the moment something happens.</p>
            @endforelse
        </div>

        {{-- Refer-A-Friend --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 flex flex-col">
            <div class="flex items-center gap-2 mb-1">
                <h2 class="font-display text-lg font-bold text-navy dark:text-white">Refer a Friend</h2>
                @if ($referralCount > 0)
                    <span class="text-xs font-semibold text-teal-dark bg-teal/10 px-2 py-0.5 rounded-full">{{ $referralCount }} referred</span>
                @endif
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Know a business that needs a website? Share your link — we'll take great care of them.</p>

            <label class="block text-xs font-medium text-navy dark:text-white mb-1">Your referral link</label>
            <div class="flex items-center gap-2 mb-4">
                <input id="referral-link-input" type="text" readonly value="{{ $referralLink }}" onclick="this.select()"
                       class="flex-1 min-w-0 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 px-3 py-2 text-xs text-gray-600 dark:text-gray-300 cursor-pointer focus:outline-none">
                <button type="button" id="referral-copy-btn" class="shrink-0 min-w-[4.5rem] inline-flex items-center justify-center gap-1 px-3 py-2 rounded-lg text-xs font-semibold bg-gold/15 text-navy dark:text-white hover:bg-gold/25 transition-colors">Copy</button>
            </div>

            <a href="mailto:?subject={{ rawurlencode('A website recommendation for you') }}&body={{ rawurlencode("I've been working with VisionBridge Solutions on my website and thought you'd be a great fit too. You can get started here: ".$referralLink) }}"
               class="group relative mt-auto overflow-hidden text-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-navy dark:bg-gold text-white dark:text-navy transition-colors duration-300 hover:text-navy dark:hover:text-navy">
                <span class="absolute inset-0 bg-white origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></span>
                <span class="relative z-10">Refer your friend</span>
            </a>
        </div>
    </div>
    <style>
        @keyframes copiedPop { 0% { transform: scale(1); } 40% { transform: scale(1.1); } 100% { transform: scale(1); } }
        #referral-copy-btn.is-copied { background-color: #0D9488 !important; color: #fff !important; animation: copiedPop .3s ease; }
    </style>
    <script>
        (function () {
            const btn = document.getElementById('referral-copy-btn');
            const input = document.getElementById('referral-link-input');
            if (!btn || !input) return;

            const checkSvg = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>';
            let resetTimer;

            btn.addEventListener('click', function () {
                input.select();
                input.setSelectionRange(0, input.value.length); // iOS Safari
                const done = function () {
                    clearTimeout(resetTimer);
                    btn.classList.add('is-copied');
                    btn.innerHTML = checkSvg + 'Copied!';
                    resetTimer = setTimeout(function () {
                        btn.classList.remove('is-copied');
                        btn.textContent = 'Copy';
                    }, 1600);
                };
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(input.value).then(done).catch(done);
                } else {
                    document.execCommand('copy');
                    done();
                }
            });
        })();
    </script>
    </div>

    {{-- Project header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 sm:p-7 mb-6 sm:mb-8">

        {{-- Identity: title + status badge live together --}}
        <div class="mb-6">
            <div class="flex flex-wrap items-center gap-2.5">
                <h2 class="font-display text-xl sm:text-2xl font-bold text-navy dark:text-white">{{ $project->name }}</h2>
                <span class="inline-flex items-center text-[0.65rem] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-navy text-white shrink-0">
                    {{ $statusLabels[$project->status] ?? $project->status }}
                </span>
            </div>
            @if ($project->description)
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1.5">{{ $project->description }}</p>
            @endif
        </div>

        {{-- Progress: label promoted to an eyebrow, % promoted to a hero number.
             Sans-serif (not font-display) so it reads as data, not a headline,
             and sits on the label's baseline instead of floating above it. --}}
        @php
            $nextMilestone = $project->nextMilestone();
            // Only meaningful while work remains — once launched, the "site
            // is live" banner below already covers it. Silently skipped
            // (never a fallback string) if the final milestone has no due
            // date set, since a guessed date would be worse than none.
            $estimatedCompletion = $nextMilestone ? $project->estimatedCompletionDate() : null;
        @endphp
        <div class="mb-6">
            <div class="flex items-baseline justify-between gap-3 mb-2.5">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Project Progress</p>
                    @if ($project->milestones->isNotEmpty() && ! $project->isProgressOverridden())
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $project->milestones->where('status', 'completed')->count() }} of {{ $project->milestones->count() }} milestones</p>
                    @endif
                    @if ($estimatedCompletion)
                        <p class="text-xs {{ $estimatedCompletion->isPast() ? 'text-red-500 font-medium' : 'text-gray-400 dark:text-gray-500' }} mt-0.5">
                            {{ $estimatedCompletion->isPast() ? 'Behind schedule' : 'Est. completion' }} &middot; {{ $estimatedCompletion->format('M j, Y') }}
                        </p>
                    @endif
                </div>
                <span class="inline-flex items-baseline font-sans leading-none shrink-0" data-pct="{{ $project->progressPercent() }}">
                    <span class="progress-bar-value-num text-3xl font-extrabold tracking-tight text-navy dark:text-white">0</span><span class="text-base font-semibold text-gray-400 dark:text-gray-500 ml-0.5">%</span>
                </span>
            </div>
            <div class="w-full h-3 rounded-md bg-gray-100 dark:bg-gray-700 overflow-hidden">
                <div class="progress-bar-fill h-full bg-gold rounded-md" style="width: 0%" data-pct="{{ $project->progressPercent() }}"></div>
            </div>
        </div>
        <style>
            .progress-bar-fill { transition: width 2.4s cubic-bezier(0.33, 1, 0.68, 1); }
            @media (prefers-reduced-motion: reduce) { .progress-bar-fill { transition: none; } }
        </style>
        <script>
            (function () {
                const clamp = (n) => Math.max(0, Math.min(100, parseInt(n, 10) || 0));
                const fill = document.querySelector('.progress-bar-fill');
                const val = document.querySelector('.progress-bar-value-num');
                if (!fill) return;
                const target = clamp(fill.dataset.pct);

                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    fill.style.width = target + '%';
                    if (val) val.textContent = target;
                    return;
                }

                requestAnimationFrame(() => { fill.style.width = target + '%'; });

                if (val) {
                    const duration = 2400, start = performance.now();
                    (function tick(now) {
                        const t = Math.min(1, (now - start) / duration);
                        val.textContent = Math.round(target * (1 - Math.pow(1 - t, 3)));
                        if (t < 1) requestAnimationFrame(tick);
                    })(start);
                }
            })();
        </script>

        {{-- Contextual status + its action, grouped in one bar so the CTA
             always sits next to the text that justifies it. --}}
        @if ($nextMilestone)
            <div class="flex flex-wrap items-center justify-between gap-4 rounded-lg bg-gray-50 dark:bg-gray-900/40 px-5 py-4 mb-6">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-7 h-7 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                    <p class="text-sm text-gray-600 dark:text-gray-300 min-w-0">
                        <span class="font-semibold text-navy dark:text-white">Next: {{ $nextMilestone->title }}</span>
                        @if ($nextMilestone->due_date->isPast())
                            <span class="text-red-500 font-medium">&middot; Overdue since {{ $nextMilestone->due_date->format('M j, Y') }}</span>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">&middot; Due {{ $nextMilestone->due_date->format('M j, Y') }} ({{ $nextMilestone->due_date->diffForHumans() }})</span>
                        @endif
                    </p>
                </div>
                @if ($project->preview_url)
                    <a href="{{ $project->preview_url }}" target="_blank" class="group relative inline-flex items-center gap-1.5 text-sm font-semibold text-navy-dark bg-gold px-5 py-2.5 rounded-lg overflow-hidden shadow-sm hover:shadow-lg hover:shadow-gold/25 hover:-translate-y-0.5 transition-all duration-300 ease-out shrink-0">
                        <span class="absolute inset-0 bg-navy origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></span>
                        <span class="relative z-10 inline-flex items-center gap-1.5 group-hover:text-white transition-colors duration-300">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            View Live Preview
                        </span>
                    </a>
                @endif
            </div>
        @elseif (in_array($project->status, ['launched', 'maintenance'], true))
            {{-- Themed off the gold accent (not a separate green wash) so it
                 reads as one family with the card and its button; the teal
                 check badge alone carries the "success" cue, matching the
                 same badge used in the milestone list below. --}}
            <div class="flex flex-wrap items-center justify-between gap-4 rounded-lg bg-gold/8 border border-gold/25 px-5 py-4 mb-6">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-7 h-7 rounded-full bg-teal flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <p class="text-sm font-semibold text-navy dark:text-white">All milestones complete — your site is live!</p>
                </div>
                @if ($project->preview_url)
                    <a href="{{ $project->preview_url }}" target="_blank" class="group relative inline-flex items-center gap-1.5 text-sm font-semibold text-navy-dark bg-gold px-5 py-2.5 rounded-lg overflow-hidden shadow-sm hover:shadow-lg hover:shadow-gold/25 hover:-translate-y-0.5 transition-all duration-300 ease-out shrink-0">
                        <span class="absolute inset-0 bg-navy origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></span>
                        <span class="relative z-10 inline-flex items-center gap-1.5 group-hover:text-white transition-colors duration-300">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            View Live Preview
                        </span>
                    </a>
                @endif
            </div>
        @endif

        {{-- Milestones: collapsed by default — once progress is visible up
             top, the full history is reference material, not primary
             content. Completed state uses a filled check + muted color,
             never a literal strikethrough (reads as "cancelled", not "done"). --}}
        @if ($project->milestones->isNotEmpty())
            <div class="border-t border-gray-100 dark:border-gray-700 pt-5">
                <button type="button" id="milestones-toggle" aria-expanded="false" aria-controls="milestones-panel"
                        class="w-full flex items-center justify-between text-left group">
                    <span class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 group-hover:text-navy dark:group-hover:text-white transition-colors">
                        View Past Milestones <span class="text-gray-300 dark:text-gray-600 normal-case tracking-normal">({{ $project->milestones->count() }})</span>
                    </span>
                    <svg id="milestones-chevron" class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="milestones-panel" class="hidden mt-4">
                    <ul class="space-y-3">
                        @foreach ($project->milestones as $milestone)
                            <li class="flex items-start gap-2.5 text-sm">
                                @if ($milestone->status === 'completed')
                                    <span class="w-4 h-4 rounded-full bg-teal flex items-center justify-center shrink-0 mt-0.5">
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <div class="min-w-0">
                                        <span class="text-gray-400 dark:text-gray-500">{{ $milestone->title }}</span>
                                        @if ($milestone->completed_at)
                                            <span class="text-xs text-gray-400 dark:text-gray-500">&middot; Completed {{ $milestone->completed_at->format('M j, Y') }}</span>
                                        @endif
                                        @if ($milestone->description)
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $milestone->description }}</p>
                                        @endif
                                    </div>
                                @elseif ($milestone->status === 'in_progress')
                                    <span class="w-4 h-4 rounded-full border-2 border-gold shrink-0 mt-0.5"></span>
                                    <div class="min-w-0">
                                        <span class="text-navy dark:text-white font-medium">{{ $milestone->title }}</span>
                                        @if ($milestone->due_date)
                                            <span class="text-xs text-gray-400 dark:text-gray-500">&middot; Due {{ $milestone->due_date->format('M j, Y') }}</span>
                                        @endif
                                        @if ($milestone->description)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $milestone->description }}</p>
                                        @endif
                                    </div>
                                @else
                                    <span class="w-4 h-4 rounded-full border-2 border-gray-300 dark:border-gray-600 shrink-0 mt-0.5"></span>
                                    <div class="min-w-0">
                                        <span class="text-gray-500 dark:text-gray-400">{{ $milestone->title }}</span>
                                        @if ($milestone->due_date)
                                            <span class="text-xs text-gray-400 dark:text-gray-500">&middot; Due {{ $milestone->due_date->format('M j, Y') }}</span>
                                        @endif
                                        @if ($milestone->description)
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $milestone->description }}</p>
                                        @endif
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                <script>
                    (function () {
                        const btn = document.getElementById('milestones-toggle');
                        const panel = document.getElementById('milestones-panel');
                        const chevron = document.getElementById('milestones-chevron');
                        if (!btn || !panel) return;

                        btn.addEventListener('click', function () {
                            const open = panel.classList.contains('hidden');
                            panel.classList.toggle('hidden', !open);
                            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
                            if (chevron) chevron.style.transform = open ? 'rotate(180deg)' : '';
                        });
                    })();
                </script>
            </div>
        @endif
    </div>

    {{-- Growth Opportunities — improvement ideas the team has approved to share --}}
    @if ($recommendations->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6 sm:mb-8">
            <h3 class="font-display text-base font-bold text-navy dark:text-white mb-1">Growth Opportunities</h3>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Improvements our team thinks could help your website perform even better.</p>
            <div class="space-y-3">
                @foreach ($recommendations as $rec)
                    <div class="rounded-lg border border-gold/20 bg-gold/5 px-4 py-3.5">
                        <div class="flex items-center justify-between gap-3 mb-1">
                            <p class="text-sm font-semibold text-navy dark:text-white">{{ $rec->title }}</p>
                            <span class="text-xs font-semibold uppercase tracking-wide text-gold-dark shrink-0">{{ \App\Models\Recommendation::CATEGORIES[$rec->category] ?? $rec->category }}</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $rec->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Recent Activity --}}
    @if ($activity->isNotEmpty())
        @php
            $activityIcons = [
                'milestone' => ['bg' => 'bg-teal/10', 'text' => 'text-teal-dark', 'path' => 'M5 13l4 4L19 7'],
                'approved' => ['bg' => 'bg-teal/10', 'text' => 'text-teal-dark', 'path' => 'M5 13l4 4L19 7'],
                'reply' => ['bg' => 'bg-gold/15', 'text' => 'text-gold-dark', 'path' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                'payment' => ['bg' => 'bg-gold/15', 'text' => 'text-gold-dark', 'path' => 'M9 7h6m0 0v6m0-6L4 21'],
            ];
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6 sm:mb-8">
            <h3 class="font-display text-base font-bold text-navy dark:text-white mb-4">Recent Activity</h3>
            <div class="relative">
                {{-- Timeline track — icons "punch through" it via their own ring-4 ring-white matching the card background --}}
                <div class="absolute left-4 top-2 bottom-2 border-l-2 border-slate-100 dark:border-gray-700"></div>

                <ul class="relative space-y-1">
                    @foreach ($activity as $event)
                        @php $icon = $activityIcons[$event['icon']] ?? $activityIcons['milestone']; @endphp
                        <li>
                            <div class="flex items-start gap-3 rounded-lg -mx-2 px-2 py-2.5 transition-colors {{ $event['url'] ? 'hover:bg-slate-50/80 dark:hover:bg-gray-700/40 cursor-pointer' : '' }}"
                                 @if ($event['url']) onclick="window.location='{{ $event['url'] }}'" @endif>
                                <span class="relative z-10 w-8 h-8 rounded-full {{ $icon['bg'] }} ring-4 ring-white dark:ring-gray-800 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 {{ $icon['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon['path'] }}"/></svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <p class="text-sm font-medium text-navy dark:text-white">
                                            {{ $event['title'] }}
                                            @if ($event['highlight'])
                                                <span class="font-bold text-gold-dark">{{ $event['highlight'] }}</span>
                                            @endif
                                        </p>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">{{ $event['at']->diffForHumans() }}</span>
                                            @if ($event['url'])
                                                <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($event['description'])
                                        @if ($event['icon'] === 'reply')
                                            <blockquote class="mt-1.5 text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/50 border-l-2 border-gold/40 rounded-r-lg px-3 py-2 truncate">
                                                {{ $event['description'] }}
                                            </blockquote>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate mt-0.5">{{ $event['description'] }}</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Category tiles --}}
    <h3 class="font-display text-base font-bold text-navy dark:text-white mb-4">Project Sections</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($counts as $cat => $info)
            <a href="{{ route('portal.category', $cat) }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:border-gold/40 hover:shadow-sm transition-all group">
                <div class="w-10 h-10 rounded-lg bg-navy/5 group-hover:bg-gold/15 flex items-center justify-center mb-3 transition-colors">
                    <svg class="w-5 h-5 text-navy dark:text-white group-hover:text-gold-dark transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $categoryIcons[$cat] }}"/>
                    </svg>
                </div>
                <p class="font-semibold text-navy dark:text-white text-sm">{{ $info['label'] }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 mb-2">{{ $info['description'] }}</p>
                @if ($info['count'] > 0)
                    <p class="text-xs font-medium text-teal-dark">{{ $info['count'] }} item{{ $info['count'] === 1 ? '' : 's' }} uploaded</p>
                @else
                    <p class="text-xs text-gray-400 dark:text-gray-500 leading-snug">{{ $info['why'] }}</p>
                @endif
            </a>
        @endforeach
    </div>

@endif

@endsection
