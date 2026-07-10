@extends('layouts.portal')

@section('title', 'Overview – Client Portal')
@section('page-title', 'Overview')

@section('content')

@if ($announcement)
    <div id="announcement-banner" class="relative flex items-start gap-3 rounded-xl border border-gold/30 bg-gold/10 px-5 py-4 mb-6">
        <svg class="w-5 h-5 text-gold-dark shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-bold text-navy dark:text-white">{{ $announcement->title }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-0.5">{{ $announcement->body }}</p>
        </div>
        <button type="button" id="announcement-dismiss" data-id="{{ $announcement->id }}" aria-label="Dismiss"
                class="text-gray-400 hover:text-navy dark:hover:text-white transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <script>
        document.getElementById('announcement-dismiss')?.addEventListener('click', function () {
            const banner = document.getElementById('announcement-banner');
            const id = this.dataset.id;
            banner.remove();
            fetch('/portal/announcements/' + id + '/dismiss', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            });
        });
    </script>
@endif

@if ($pendingSurvey)
    <div class="flex items-center justify-between gap-4 rounded-xl border border-teal/30 bg-teal/10 px-5 py-4 mb-6">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-teal-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.062 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.958z"/></svg>
            <p class="text-sm font-semibold text-navy dark:text-white">Your project launched — how did we do?</p>
        </div>
        <a href="{{ route('portal.survey.show') }}" class="shrink-0 text-sm font-semibold text-teal-dark hover:underline">Share Feedback →</a>
    </div>
@endif

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

            <div class="px-6 pt-7 pb-6" style="background:linear-gradient(135deg,#0F766E,#0D9488);">
                <div class="w-12 h-12 rounded-full bg-white/15 text-white flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.062 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.958z"/>
                    </svg>
                </div>
                <p class="text-xs font-semibold uppercase tracking-widest text-white/70 mb-1">Your Feedback</p>
                <h2 class="font-display text-xl font-bold text-white">Your project launched — how did we do?</h2>
            </div>

            <div class="px-6 py-6">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    We'd love to hear about your experience. It only takes a minute, and it helps us serve you better.
                </p>

                <div class="flex flex-col sm:flex-row sm:justify-end gap-2.5">
                    <button type="button" id="survey-dismiss" class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Maybe later
                    </button>
                    <a href="{{ route('portal.survey.show') }}" class="text-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-teal-dark text-white hover:bg-teal transition-colors">
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
        })();
    </script>
@endif

@if ($firstVisit)
    <div id="welcome-banner" class="relative overflow-hidden rounded-2xl mb-8 shadow-lg">
        <div class="px-8 py-8 md:py-10" style="background:linear-gradient(135deg,#111D33,#1B2A4A);">

            <button type="button" id="welcome-banner-close" aria-label="Dismiss"
                class="absolute top-4 right-4 text-white/40 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex items-start gap-5">
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
        <div class="rounded-xl p-6 mb-8 border border-gold/20 dark:border-gold/10" style="background:linear-gradient(135deg,rgba(201,168,76,0.10),rgba(42,157,143,0.08));">
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
        <div class="rounded-xl p-6 mb-8 border border-gold/20 dark:border-gold/10" style="background:linear-gradient(135deg,rgba(201,168,76,0.10),rgba(42,157,143,0.08));">
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
        <a href="{{ route('portal.payments.index') }}" class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-red-200 dark:border-red-500/30 bg-red-50 dark:bg-red-500/10 px-5 py-4 mb-8 hover:border-red-300 dark:hover:border-red-500/50 transition-colors">
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
        <div class="rounded-xl p-6 mb-8 border border-teal/20 dark:border-teal/10" style="background:linear-gradient(135deg,rgba(42,157,143,0.10),rgba(201,168,76,0.08));">
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

    {{-- What's Next — the single highest-priority thing to do or expect --}}
    @if ($whatsNext)
        <div class="flex items-start gap-3.5 rounded-xl border {{ $whatsNext['actionable'] ? 'border-gold/30 bg-gold/5' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800' }} px-5 py-4 mb-8">
            <span class="w-9 h-9 rounded-full {{ $whatsNext['actionable'] ? 'bg-gold/15 text-gold-dark' : 'bg-teal/10 text-teal-dark' }} flex items-center justify-center shrink-0">
                @if ($whatsNext['actionable'])
                    <svg class="w-[1.125rem] h-[1.125rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                @else
                    <svg class="w-[1.125rem] h-[1.125rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @endif
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold uppercase tracking-widest {{ $whatsNext['actionable'] ? 'text-gold-dark' : 'text-teal-dark' }} mb-0.5">What's Next</p>
                <p class="text-sm font-semibold text-navy dark:text-white">{{ $whatsNext['title'] }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $whatsNext['description'] }}</p>
            </div>
            @if ($whatsNext['url'])
                <a href="{{ $whatsNext['url'] }}" class="shrink-0 text-sm font-semibold text-navy dark:text-white bg-gold/15 hover:bg-gold/25 px-4 py-2 rounded-lg transition-colors">
                    {{ $whatsNext['actionLabel'] }}
                </a>
            @endif
        </div>
    @endif

    {{-- Project header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div>
                <h2 class="font-display text-2xl font-bold text-navy dark:text-white">{{ $project->name }}</h2>
                @if ($project->description)
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ $project->description }}</p>
                @endif
            </div>
            <div class="flex items-center gap-2 shrink-0">
                @if ($project->preview_url)
                    <a href="{{ $project->preview_url }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy dark:text-white bg-gold/15 hover:bg-gold/25 px-4 py-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4 text-gold-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        View Live Preview
                    </a>
                @endif
                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full bg-gold/15 text-gold-dark">
                    {{ $statusLabels[$project->status] ?? $project->status }}
                </span>
            </div>
        </div>

        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-2">
            <span>
                Project Progress
                @if ($project->milestones->isNotEmpty() && ! $project->isProgressOverridden())
                    <span class="text-xs text-gray-400 dark:text-gray-500">({{ $project->milestones->where('status', 'completed')->count() }} of {{ $project->milestones->count() }} milestones)</span>
                @endif
            </span>
            <span class="font-semibold text-navy dark:text-white">{{ $project->progressPercent() }}%</span>
        </div>
        <div class="w-full h-2 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
            <div class="h-full bg-gold rounded-full" style="width: {{ $project->progressPercent() }}%"></div>
        </div>

        @php $nextMilestone = $project->nextMilestone(); @endphp
        @if ($nextMilestone)
            <div class="flex items-center gap-2.5 mt-4 text-sm">
                <span class="w-7 h-7 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <p class="text-gray-600 dark:text-gray-300">
                    <span class="font-semibold text-navy dark:text-white">Next: {{ $nextMilestone->title }}</span>
                    @if ($nextMilestone->due_date->isPast())
                        <span class="text-red-500 font-medium">&middot; Overdue since {{ $nextMilestone->due_date->format('M j, Y') }}</span>
                    @else
                        <span class="text-gray-400 dark:text-gray-500">&middot; Due {{ $nextMilestone->due_date->format('M j, Y') }} ({{ $nextMilestone->due_date->diffForHumans() }})</span>
                    @endif
                </p>
            </div>
        @elseif (in_array($project->status, ['launched', 'maintenance'], true))
            <p class="text-sm text-teal-dark mt-4 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                All milestones complete — your site is live!
            </p>
        @endif

        @if ($project->milestones->isNotEmpty())
            <ul class="mt-5 space-y-2">
                @foreach ($project->milestones as $milestone)
                    <li class="flex items-center gap-2.5 text-sm">
                        @if ($milestone->status === 'completed')
                            <span class="w-4 h-4 rounded-full bg-teal flex items-center justify-center shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-gray-400 dark:text-gray-500 line-through">{{ $milestone->title }}</span>
                            @if ($milestone->completed_at)
                                <span class="text-xs text-gray-400 dark:text-gray-500">&middot; Completed {{ $milestone->completed_at->format('M j, Y') }}</span>
                            @endif
                        @elseif ($milestone->status === 'in_progress')
                            <span class="w-4 h-4 rounded-full border-2 border-gold shrink-0"></span>
                            <span class="text-navy dark:text-white font-medium">{{ $milestone->title }}</span>
                            @if ($milestone->due_date)
                                <span class="text-xs text-gray-400 dark:text-gray-500">&middot; Due {{ $milestone->due_date->format('M j, Y') }}</span>
                            @endif
                        @else
                            <span class="w-4 h-4 rounded-full border-2 border-gray-300 dark:border-gray-600 shrink-0"></span>
                            <span class="text-gray-500 dark:text-gray-400">{{ $milestone->title }}</span>
                            @if ($milestone->due_date)
                                <span class="text-xs text-gray-400 dark:text-gray-500">&middot; Due {{ $milestone->due_date->format('M j, Y') }}</span>
                            @endif
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Growth Opportunities — improvement ideas the team has approved to share --}}
    @if ($recommendations->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-8">
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
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <h3 class="font-display text-base font-bold text-navy dark:text-white mb-4">Recent Activity</h3>
            <ul class="space-y-4">
                @foreach ($activity as $event)
                    @php $icon = $activityIcons[$event['icon']] ?? $activityIcons['milestone']; @endphp
                    <li class="flex items-start gap-3">
                        <span class="w-8 h-8 rounded-full {{ $icon['bg'] }} flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 {{ $icon['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon['path'] }}"/></svg>
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-navy dark:text-white">{{ $event['title'] }}</p>
                            @if ($event['description'])
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $event['description'] }}</p>
                            @endif
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $event['at']->diffForHumans() }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
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
