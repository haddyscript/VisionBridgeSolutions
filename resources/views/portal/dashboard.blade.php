@extends('layouts.portal')

@section('title', 'Overview – Client Portal')
@section('page-title', 'Overview')

@section('content')

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

    {{-- Project header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div>
                <h2 class="font-display text-2xl font-bold text-navy dark:text-white">{{ $project->name }}</h2>
                @if ($project->description)
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ $project->description }}</p>
                @endif
            </div>
            <span class="inline-block text-xs font-semibold uppercase tracking-wide px-3 py-1.5 rounded-full bg-gold/15 text-gold-dark">
                {{ $statusLabels[$project->status] ?? $project->status }}
            </span>
        </div>

        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-2">
            <span>Project Progress</span>
            <span class="font-semibold text-navy dark:text-white">{{ $project->progressPercent() }}%</span>
        </div>
        <div class="w-full h-2 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
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
