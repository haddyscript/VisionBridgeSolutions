<!DOCTYPE html>
<html lang="en" class="{{ auth()->user()?->isDarkTheme() ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Client Portal – VisionBridge Solutions')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('image/logo/vbs-logo-v3.jpeg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        navy:  { DEFAULT: '#1B2A4A', dark: '#111D33', light: '#243762' },
                        gold:  { DEFAULT: '#C9A84C', light: '#DFC06A', dark: '#A8872E' },
                        teal:  { DEFAULT: '#2A9D8F', light: '#3DBFB0', dark: '#1E7268' },
                    },
                    fontFamily: {
                        sans:    ['Inter', 'sans-serif'],
                        display: ['"Playfair Display"', 'serif'],
                    },
                }
            }
        }
    </script>

    @php
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

    {{-- Gold sidebar scrollbar --}}
    <style>
        .gold-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(201, 168, 76, 0.35) transparent;
        }
        .gold-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .gold-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .gold-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(201, 168, 76, 0.35);
            border-radius: 9999px;
        }
        .gold-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(201, 168, 76, 0.6);
        }

        /* ── Collapsible sidebar (desktop only) ───────────────────────── */
        #portal-sidebar { transition: transform .2s ease, width .2s ease; }

        @media (min-width: 768px) {
            body.sidebar-collapsed #portal-sidebar { width: 5rem; }
            body.sidebar-collapsed #portal-main { margin-left: 5rem; }

            /* Icons keep their fixed w/h; text collapses to zero width */
            body.sidebar-collapsed #portal-sidebar nav a,
            body.sidebar-collapsed #portal-sidebar .sidebar-signout {
                justify-content: center;
                gap: 0;
                font-size: 0;
            }
            /* Hide labels AND badges/dots (spans keep their own font size, so
               font-size:0 alone won't shrink them) — leaves just the icon. */
            body.sidebar-collapsed #portal-sidebar nav a span { display: none; }
            body.sidebar-collapsed #portal-sidebar nav > p { display: none; }
            body.sidebar-collapsed .sidebar-hide-collapsed { display: none; }
            body.sidebar-collapsed .sidebar-logo { height: 2.75rem; }
        }

        /* Floating tooltip for the collapsed rail */
        #sidebar-tooltip {
            position: fixed;
            z-index: 60;
            background: #1B2A4A;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.35rem 0.6rem;
            border-radius: 0.4rem;
            white-space: nowrap;
            pointer-events: none;
            box-shadow: 0 6px 16px rgba(0,0,0,.28);
            opacity: 0;
            transition: opacity .12s ease;
        }
        #sidebar-tooltip.visible { opacity: 1; }
        @media (max-width: 767px) { #sidebar-tooltip { display: none !important; } }
    </style>
</head>
@php
                    $hdrProject = auth()->user()->projects()->first();
                    $hdrStatusLabels = [
                        'onboarding' => 'Onboarding',
                        'in_progress' => 'In Development',
                        'review' => 'Under Review',
                        'launched' => 'Launched',
                        'maintenance' => 'Care Plan',
                    ];
                    $hdrStatusColors = [
                        'onboarding' => 'bg-gold/10 text-gold-dark',
                        'in_progress' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-300',
                        'review' => 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-300',
                        'launched' => 'bg-teal/10 text-teal-dark',
                        'maintenance' => 'bg-teal/10 text-teal-dark',
                    ];
                @endphp
<body class="font-sans antialiased text-gray-800 dark:text-gray-100 bg-gray-50 dark:bg-gray-900 min-h-screen">
<script>if (localStorage.getItem('portalSidebarCollapsed') === 'true') document.body.classList.add('sidebar-collapsed');</script>

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside id="portal-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 flex flex-col -translate-x-full md:translate-x-0 transition-transform duration-200" style="background:#111D33;">
            <div class="relative flex items-center justify-center py-6 border-b border-white/10 shrink-0">
                <img src="{{ asset('image/logo/vbs-logo-v3.jpeg') }}" alt="VisionBridge Solutions" class="sidebar-logo h-28 w-auto object-contain rounded-md transition-all duration-200">
                <button type="button" id="sidebar-collapse-toggle" aria-label="Collapse sidebar" title="Collapse sidebar"
                        class="hidden md:flex absolute top-3 -right-3 w-8 h-8 items-center justify-center rounded-full bg-gold text-navy-dark ring-2 ring-navy-dark shadow-lg hover:bg-gold-light hover:scale-110 transition-all duration-200">
                    <svg id="sidebar-collapse-icon" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto gold-scrollbar py-5 px-3 space-y-0.5">
                <p class="px-3 text-[0.65rem] font-semibold uppercase tracking-widest text-white/30 mb-2">Client Portal</p>
                <a href="{{ route('portal.dashboard') }}" data-tour="overview"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('portal.dashboard') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="flex-1">Overview</span>
                    @if (($unreadNotificationCount ?? 0) > 0)
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500 text-white">{{ $unreadNotificationCount }}</span>
                    @endif
                </a>
                <a href="{{ route('portal.documents.index') }}" data-tour="documents"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('portal.documents.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Documents
                </a>
                <a href="{{ route('portal.project-requests.show') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('portal.project-requests.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Request a New Project
                </a>
                <a href="{{ route('portal.consultation.create') }}" data-tour="consultation"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('portal.consultation.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="flex-1">Book a Consultation</span>
                    @if (($upcomingConsultationCount ?? 0) > 0)
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gold text-navy-dark">{{ $upcomingConsultationCount }}</span>
                    @endif
                </a>

                @php
                    $fileCategories = ['image', 'video', 'logo', 'document', 'marketing'];
                @endphp
                <p class="px-3 text-[0.65rem] font-semibold uppercase tracking-widest text-white/30 mt-5 mb-2">Project Files</p>
                <a href="{{ route('portal.category', 'image') }}" data-tour="files"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('portal.category') && in_array(request()->route('category'), $fileCategories, true) ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Project Files
                </a>

                <p class="px-3 text-[0.65rem] font-semibold uppercase tracking-widest text-white/30 mt-5 mb-2">Content &amp; Revisions</p>
                @foreach (['content' => 'Website Content', 'revision' => 'Revisions'] as $cat => $label)
                    <a href="{{ route('portal.category', $cat) }}" {{ $cat === 'content' ? 'data-tour=content-revisions' : '' }}
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('portal.category') && request()->route('category') === $cat ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $categoryIcons[$cat] }}"/>
                        </svg>
                        {{ $label }}
                    </a>
                @endforeach

                <p class="px-3 text-[0.65rem] font-semibold uppercase tracking-widest text-white/30 mt-5 mb-2">Billing</p>
                <a href="{{ route('portal.payments.index') }}" data-tour="payments"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('portal.payments.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h5M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                    </svg>
                    <span class="flex-1">Payments</span>
                    @if (auth()->user()->hasPendingPayment())
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    @endif
                </a>

                <p class="px-3 text-[0.65rem] font-semibold uppercase tracking-widest text-white/30 mt-5 mb-2">Account</p>
                <a href="{{ route('portal.account.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('portal.account.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Account Settings
                </a>
                @php($unreadAnnouncementCount = \App\Models\Announcement::unacknowledgedCountFor(auth()->user()))
                <a href="{{ route('portal.announcements.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('portal.announcements.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <span class="flex-1">Announcements</span>
                    @if ($unreadAnnouncementCount > 0)
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500 text-white">{{ $unreadAnnouncementCount }}</span>
                    @endif
                </a>
                <a href="{{ route('portal.faq') }}" data-tour="faq"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('portal.faq') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    FAQ &amp; Help Guide
                </a>
            </nav>

            <div class="px-3 pt-3 pb-3 mt-2 shrink-0 sidebar-hide-collapsed border-t border-white/10">
                <div class="rounded-lg bg-white/5 border border-white/10 px-3.5 py-3">
                    <button type="button" id="need-help-toggle" aria-expanded="false" aria-controls="need-help-content"
                            class="w-full flex items-center justify-between text-left group">
                        <span class="text-[0.65rem] font-semibold uppercase tracking-widest text-white/30 group-hover:text-white/50 transition-colors">Need Help?</span>
                        <svg id="need-help-chevron" class="w-3.5 h-3.5 text-white/30 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="need-help-content" class="hidden mt-2">
                        <a href="mailto:{{ config('mail.admin_address') }}" class="flex items-center gap-2 text-white/70 hover:text-gold transition-colors mb-2">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="text-[0.65rem] tracking-tight leading-snug whitespace-nowrap">{{ config('mail.admin_address') }}</span>
                        </a>
                        <a href="tel:5550000000" class="flex items-center gap-2 text-xs text-white/70 hover:text-gold transition-colors">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.517l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            (404) 426-2856
                        </a>
                    </div>
                </div>
                <script>
                    (function () {
                        const btn = document.getElementById('need-help-toggle');
                        const content = document.getElementById('need-help-content');
                        const chevron = document.getElementById('need-help-chevron');
                        if (!btn || !content) return;

                        function apply(open) {
                            content.classList.toggle('hidden', !open);
                            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
                            if (chevron) chevron.style.transform = open ? 'rotate(180deg)' : '';
                        }

                        // Remember the client's choice across page loads; collapsed by default.
                        apply(localStorage.getItem('needHelpOpen') === 'true');

                        btn.addEventListener('click', function () {
                            const open = content.classList.contains('hidden');
                            apply(open);
                            localStorage.setItem('needHelpOpen', open ? 'true' : 'false');
                        });
                    })();
                </script>
                <button type="button" id="tour-replay-trigger" class="w-full flex items-center gap-2 mt-2.5 text-xs text-white/70 hover:text-gold transition-colors">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Take a Tour
                </button>
            </div>

            <div class="border-t border-white/10 pt-3 shrink-0">
                <div class="sidebar-hide-collapsed">
                    @include('partials.getting-started')

                    <div class="flex items-center gap-3 px-3 py-2 mb-1">
                        <div class="w-8 h-8 rounded-full bg-gold/20 text-gold flex items-center justify-center text-sm font-semibold shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-white/40 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-signout w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/65 hover:bg-white/5 hover:text-white transition-colors">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <script>
            (function () {
                const toggle = document.getElementById('sidebar-collapse-toggle');
                const icon = document.getElementById('sidebar-collapse-icon');
                if (!toggle) return;

                function syncIcon() {
                    const collapsed = document.body.classList.contains('sidebar-collapsed');
                    if (icon) icon.style.transform = collapsed ? 'rotate(180deg)' : '';
                    const label = collapsed ? 'Expand sidebar' : 'Collapse sidebar';
                    toggle.setAttribute('aria-label', label);
                    toggle.setAttribute('title', label);
                }
                syncIcon();

                toggle.addEventListener('click', function () {
                    const collapsed = document.body.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('portalSidebarCollapsed', collapsed ? 'true' : 'false');
                    syncIcon();
                });

                // Hover tooltips — only meaningful while collapsed on desktop.
                const tip = document.createElement('div');
                tip.id = 'sidebar-tooltip';
                document.body.appendChild(tip);

                document.querySelectorAll('#portal-sidebar nav a, #portal-sidebar .sidebar-signout').forEach(function (el) {
                    el.addEventListener('mouseenter', function () {
                        if (!document.body.classList.contains('sidebar-collapsed') || window.innerWidth < 768) return;
                        const label = (el.querySelector('span')?.textContent || el.textContent || '').trim();
                        if (!label) return;
                        tip.textContent = label;
                        const rect = el.getBoundingClientRect();
                        tip.style.top = (rect.top + rect.height / 2) + 'px';
                        tip.style.left = (rect.right + 12) + 'px';
                        tip.style.transform = 'translateY(-50%)';
                        tip.classList.add('visible');
                    });
                    el.addEventListener('mouseleave', function () { tip.classList.remove('visible'); });
                });

                // The sidebar resets to the top on every full page reload — bring
                // the active nav item (uniquely marked with `text-gold`) into view
                // by centering it within the scrollable nav.
                const nav = document.querySelector('#portal-sidebar nav');
                const active = nav?.querySelector('a.text-gold');
                if (nav && active) {
                    const navRect = nav.getBoundingClientRect();
                    const aRect = active.getBoundingClientRect();
                    nav.scrollTop += (aRect.top - navRect.top) - (nav.clientHeight - active.clientHeight) / 2;
                }
            })();
        </script>

        {{-- Mobile overlay --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden"></div>

        {{-- Main content --}}
        <div id="portal-main" class="flex-1 md:ml-64 min-w-0 transition-[margin] duration-200">
            <header class="sticky top-0 z-20 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 h-16 flex items-center px-4 sm:px-6 lg:px-8 gap-4">
                <button id="sidebar-toggle" class="md:hidden text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                
                <div class="flex items-center gap-2.5 flex-1 min-w-0">
                    <h1 class="font-display text-lg font-bold text-navy dark:text-white truncate">@yield('page-title', 'Client Portal')</h1>
                    @if ($hdrProject)
                        <span class="hidden sm:inline-flex items-center gap-1.5 shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full {{ $hdrStatusColors[$hdrProject->status] ?? 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-300' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $hdrStatusLabels[$hdrProject->status] ?? ucfirst($hdrProject->status) }}
                        </span>
                    @endif
                </div>

                {{-- Quick Action --}}
                <div class="relative hidden md:block" id="quick-action-wrap">
                    <button type="button" id="quick-action-toggle"
                            class="inline-flex items-center gap-1.5 h-9 px-3 rounded-lg bg-gold hover:bg-gold-dark text-navy text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Quick Action
                    </button>
                    <div id="quick-action-menu" class="hidden absolute left-0 mt-2 w-52 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg z-30 py-1">
                        <a href="{{ route('portal.category', 'image') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-navy dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4 text-gold-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.9A5 5 0 1115.9 6 5 5 0 0117 15.9M12 12v9m0-9l-3 3m3-3l3 3"/></svg>
                            Upload Files
                        </a>
                        <a href="{{ route('portal.category', 'revision') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-navy dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4 text-gold-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Request a Revision
                        </a>
                        <a href="{{ route('portal.consultation.create') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-navy dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4 text-gold-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Book a Consultation
                        </a>
                    </div>
                </div>
                <script>
                    (function () {
                        const toggle = document.getElementById('quick-action-toggle');
                        const menu = document.getElementById('quick-action-menu');
                        if (!toggle || !menu) return;
                        toggle.addEventListener('click', function (e) {
                            e.stopPropagation();
                            menu.classList.toggle('hidden');
                        });
                        document.addEventListener('click', function () { menu.classList.add('hidden'); });
                    })();
                </script>

                <div class="relative hidden sm:block w-64 md:w-72 lg:w-80">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                    </svg>
                    <input type="text" id="portal-search-input" placeholder="Search your files, payments..." autocomplete="off"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">

                    <div id="portal-search-results" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg z-30 max-h-96 overflow-y-auto"></div>
                </div>

                <a href="{{ route('portal.faq') }}" title="Help &amp; Support" aria-label="Help &amp; Support"
                   class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </a>

                <div class="relative">
                    <button id="notification-bell-toggle" type="button" title="Notifications" data-tour="notification-bell"
                            class="relative w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if ($unreadNotificationCount > 0)
                            <span id="notification-bell-badge" class="absolute -top-1 -right-1 min-w-[1.1rem] h-[1.1rem] px-1 rounded-full bg-red-500 text-white text-[0.65rem] font-bold leading-none flex items-center justify-center">{{ $unreadNotificationCount > 9 ? '9+' : $unreadNotificationCount }}</span>
                        @endif
                    </button>

                    <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg z-30 flex flex-col max-h-[28rem]">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between shrink-0">
                            <p class="text-sm font-bold text-navy dark:text-white">Notifications</p>
                            @if ($unreadNotificationCount > 0)
                                <button type="button" id="notifications-mark-all-read" class="text-xs font-semibold text-gold-dark hover:underline">Mark all as read</button>
                            @endif
                        </div>
                        @if ($notifications->isEmpty())
                            <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-8 px-4">No updates yet.</p>
                        @else
                            @php
                                $notificationIcons = [
                                    'milestone_completed' => ['bg' => 'bg-teal/10', 'text' => 'text-teal-dark', 'path' => 'M5 13l4 4L19 7'],
                                    'file_approved' => ['bg' => 'bg-teal/10', 'text' => 'text-teal-dark', 'path' => 'M5 13l4 4L19 7'],
                                    'revision_reply' => ['bg' => 'bg-gold/15', 'text' => 'text-gold-dark', 'path' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                                    'quote_ready' => ['bg' => 'bg-gold/15', 'text' => 'text-gold-dark', 'path' => 'M9 7h6m0 0v6m0-6L4 21'],
                                    'consultation_update' => ['bg' => 'bg-teal/10', 'text' => 'text-teal-dark', 'path' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                                    'recommendation' => ['bg' => 'bg-gold/15', 'text' => 'text-gold-dark', 'path' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                                    'security' => ['bg' => 'bg-amber-50 dark:bg-amber-500/10', 'text' => 'text-amber-600 dark:text-amber-400', 'path' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                                ];
                            @endphp
                            <ul class="divide-y divide-gray-100 dark:divide-gray-700 overflow-y-auto">
                                @foreach ($notifications as $notification)
                                    @php $icon = $notificationIcons[$notification->type] ?? $notificationIcons['milestone_completed']; @endphp
                                    <li class="js-notification-item flex items-start gap-3 px-4 py-3 cursor-pointer {{ $notification->read_at ? '' : 'bg-gold/5' }}"
                                        data-id="{{ $notification->id }}" data-unread="{{ $notification->read_at ? '0' : '1' }}"
                                        data-mark-read-url="{{ route('portal.notifications.read-one', $notification) }}"
                                        @if ($notification->url) data-url="{{ $notification->url }}" @endif>
                                        <span class="relative w-8 h-8 rounded-full {{ $icon['bg'] }} flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 {{ $icon['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon['path'] }}"/></svg>
                                            @unless ($notification->read_at)
                                                <span class="notification-unread-dot absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-blue-600 ring-2 ring-white dark:ring-gray-800"></span>
                                            @endunless
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            @if ($notification->url)
                                                <a href="{{ $notification->url }}" class="text-sm font-medium text-navy dark:text-white hover:underline">{{ $notification->title }}</a>
                                            @else
                                                <p class="text-sm font-medium text-navy dark:text-white">{{ $notification->title }}</p>
                                            @endif
                                            @if ($notification->description)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-snug mt-0.5">{{ $notification->description }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <a href="{{ route('portal.notifications.index') }}" class="shrink-0 block text-center text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white border-t border-gray-100 dark:border-gray-700 px-4 py-2.5 transition-colors">
                            View all notifications
                        </a>
                    </div>
                </div>

                <button id="theme-toggle" type="button" title="Toggle dark mode"
                        class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg id="theme-icon-light" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg id="theme-icon-dark" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
            </header>

            @if (session('impersonator_id'))
                <div class="sticky top-16 z-20 bg-gold text-navy-dark text-sm font-semibold px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between gap-3">
                    <span>👁️ Viewing as {{ auth()->user()->name }} — any changes you make here are real.</span>
                    <form method="POST" action="{{ route('impersonate.stop') }}">
                        @csrf
                        <button type="submit" class="underline hover:no-underline shrink-0">Return to Admin</button>
                    </form>
                </div>
            @endif

            <main class="px-4 sm:px-6 lg:px-8 py-8">
                @if (session('status'))
                    <div id="flash-status-banner" class="mb-6 text-sm text-teal-dark dark:text-teal-light bg-teal/10 border border-teal/30 rounded-lg px-4 py-3">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-lg px-4 py-3">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('portal-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggle = document.getElementById('sidebar-toggle');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        toggle?.addEventListener('click', openSidebar);
        overlay?.addEventListener('click', closeSidebar);

        // Theme toggle
        const themeToggle = document.getElementById('theme-toggle');
        const iconLight = document.getElementById('theme-icon-light');
        const iconDark = document.getElementById('theme-icon-dark');

        function syncThemeIcon() {
            const isDark = document.documentElement.classList.contains('dark');
            iconLight.classList.toggle('hidden', !isDark);
            iconDark.classList.toggle('hidden', isDark);
        }
        syncThemeIcon();

        themeToggle?.addEventListener('click', function () {
            const isDark = document.documentElement.classList.toggle('dark');
            syncThemeIcon();

            fetch('{{ route('theme.update') }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ theme: isDark ? 'dark' : 'light' }),
            });
        });

        // Notification bell — a notification is only ever marked read when
        // the client actually clicks it (or explicitly hits "Mark all as
        // read"), never just by opening the dropdown or visiting a page.
        const bellToggle = document.getElementById('notification-bell-toggle');
        const bellDropdown = document.getElementById('notification-dropdown');
        let bellBadge = document.getElementById('notification-bell-badge');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let unreadCount = document.querySelectorAll('.js-notification-item[data-unread="1"]').length;

        function updateBadge() {
            if (!bellBadge) return;

            if (unreadCount <= 0) {
                bellBadge.remove();
                bellBadge = null;
            } else {
                bellBadge.textContent = unreadCount > 9 ? '9+' : String(unreadCount);
            }
        }

        bellToggle?.addEventListener('click', function (e) {
            e.stopPropagation();
            bellDropdown.classList.toggle('hidden');
        });

        document.querySelectorAll('.js-notification-item').forEach(function (item) {
            item.addEventListener('click', function () {
                if (item.dataset.unread === '1') {
                    item.dataset.unread = '0';
                    item.classList.remove('bg-gold/5');
                    item.querySelector('.notification-unread-dot')?.remove();
                    unreadCount = Math.max(0, unreadCount - 1);
                    updateBadge();

                    fetch(item.dataset.markReadUrl, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        keepalive: true,
                    });
                }

                if (item.dataset.url) {
                    window.location.href = item.dataset.url;
                }
            });
        });

        document.getElementById('notifications-mark-all-read')?.addEventListener('click', function (e) {
            e.stopPropagation();

            document.querySelectorAll('.js-notification-item[data-unread="1"]').forEach(function (item) {
                item.dataset.unread = '0';
                item.classList.remove('bg-gold/5');
                item.querySelector('.notification-unread-dot')?.remove();
            });
            unreadCount = 0;
            updateBadge();
            this.remove();

            fetch('{{ route('portal.notifications.read') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                keepalive: true,
            });
        });

        document.addEventListener('click', function (e) {
            if (bellDropdown && !bellDropdown.classList.contains('hidden') && !bellDropdown.contains(e.target) && e.target !== bellToggle) {
                bellDropdown.classList.add('hidden');
            }
        });

        // Global search
        const searchInput = document.getElementById('portal-search-input');
        const searchResults = document.getElementById('portal-search-results');
        let searchDebounce = null;
        let searchRequestId = 0;

        const SEARCH_GROUPS = [
            { key: 'files', label: 'Project Files' },
            { key: 'content', label: 'Website Content & Revisions' },
            { key: 'documents', label: 'Documents' },
            { key: 'payments', label: 'Payments' },
        ];

        function renderSearchResults(data, query) {
            const groupsWithResults = SEARCH_GROUPS.filter(g => (data[g.key] || []).length > 0);

            if (groupsWithResults.length === 0) {
                searchResults.innerHTML = '<p class="text-sm text-gray-400 dark:text-gray-500 text-center py-6 px-4">No matches for &quot;' +
                    query.replace(/</g, '&lt;') + '&quot;.</p>';
                return;
            }

            searchResults.innerHTML = groupsWithResults.map(function (group) {
                const items = data[group.key].map(function (item) {
                    return '<a href="' + item.url + '" class="block px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">' +
                        '<p class="text-sm font-medium text-navy dark:text-white truncate">' + item.title + '</p>' +
                        '<p class="text-xs text-gray-400 dark:text-gray-500 truncate">' + item.subtitle + '</p>' +
                        '</a>';
                }).join('');

                return '<div class="border-b border-gray-100 dark:border-gray-700 last:border-0">' +
                    '<p class="px-4 pt-3 pb-1 text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">' + group.label + '</p>' +
                    items +
                    '</div>';
            }).join('');
        }

        searchInput?.addEventListener('input', function () {
            const query = this.value.trim();
            clearTimeout(searchDebounce);

            if (query.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }

            searchDebounce = setTimeout(function () {
                const requestId = ++searchRequestId;

                fetch('{{ route('portal.search') }}?q=' + encodeURIComponent(query), {
                    headers: { 'Accept': 'application/json' },
                })
                    .then(function (response) { return response.json(); })
                    .then(function (data) {
                        if (requestId !== searchRequestId) return; // stale response
                        renderSearchResults(data, query);
                        searchResults.classList.remove('hidden');
                    });
            }, 250);
        });

        document.addEventListener('click', function (e) {
            if (searchResults && !searchResults.classList.contains('hidden') && !searchResults.contains(e.target) && e.target !== searchInput) {
                searchResults.classList.add('hidden');
            }
        });
    </script>

    {{-- Generic confirm modal, used for delete actions instead of the native browser confirm() --}}
    <div id="confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div id="confirm-modal-backdrop" class="absolute inset-0 bg-navy-dark/60 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>

        <div id="confirm-modal-panel" class="relative w-full max-w-sm transform scale-95 opacity-0 transition-all duration-200">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6">
                <div class="w-11 h-11 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-8.18 14.18A1 1 0 003 19.5h18a1 1 0 00.86-1.46L13.71 3.86a1 1 0 00-1.72 0z"/></svg>
                </div>
                <h2 class="font-display text-lg font-bold text-navy dark:text-white mb-2">Are you sure?</h2>
                <p id="confirm-modal-message" class="text-sm text-gray-500 dark:text-gray-400 mb-6"></p>
                <div class="flex justify-end gap-2.5">
                    <button type="button" id="confirm-modal-cancel" class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button type="button" id="confirm-modal-confirm" class="px-4 py-2.5 rounded-lg text-sm font-semibold bg-red-500 hover:bg-red-600 text-white transition-colors">
                        Confirm
                    </button>
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

            // Generic confirm-before-submit: any form with data-confirm shows the
            // modal instead of the native confirm() before submitting normally.
            function bindConfirmForms(root) {
                root.querySelectorAll('form[data-confirm]').forEach(function (form) {
                    if (form.dataset.confirmBound) return;
                    form.dataset.confirmBound = '1';

                    form.addEventListener('submit', function (e) {
                        if (form.dataset.confirmAccepted) return;
                        e.preventDefault();

                        window.confirmAction(form.dataset.confirm).then(function (ok) {
                            if (ok) {
                                form.dataset.confirmAccepted = '1';
                                form.requestSubmit();
                            }
                        });
                    });
                });
            }

            bindConfirmForms(document);
            window.bindConfirmForms = bindConfirmForms;
        })();
    </script>

    {{-- Interactive portal tour — see specs/INTERACTIVE_PRODUCT_TOUR.md --}}
    <div id="tour-backdrop" class="hidden fixed inset-0 z-50"></div>
    <div id="tour-card" class="hidden fixed z-50 w-72 bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-5">
        <p id="tour-step-count" class="text-xs font-semibold uppercase tracking-widest text-gold-dark mb-1.5"></p>
        <h3 id="tour-title" class="font-display text-base font-bold text-navy dark:text-white mb-1.5"></h3>
        <p id="tour-description" class="text-sm text-gray-500 dark:text-gray-400 mb-4"></p>
        <div class="flex items-center justify-between">
            <button type="button" id="tour-skip" class="text-xs font-medium text-gray-400 hover:text-navy dark:hover:text-white transition-colors">Skip Tour</button>
            <div class="flex items-center gap-2">
                <button type="button" id="tour-back" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Back</button>
                <button type="button" id="tour-next" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-gold hover:bg-gold-dark text-navy transition-colors">Next</button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const TOUR_STEPS = [
                { key: 'overview', title: 'Project Overview', description: "Your project status, progress, and recent activity — all in one place." },
                { key: 'notification-bell', title: 'Notifications', description: "Updates from our team — replies, approvals, milestones — land here." },
                { key: 'files', title: 'Project Files', description: "Upload your logo, photos, videos, and documents." },
                { key: 'content-revisions', title: 'Website Content', description: "Submit your site copy or request changes as a running conversation with us." },
                { key: 'payments', title: 'Payments', description: "See what's owed, pay securely, and download receipts." },
                { key: 'consultation', title: 'Book a Consultation', description: "Grab time on our calendar whenever you want to talk." },
                { key: 'documents', title: 'Documents', description: "Re-download any signed agreement, anytime." },
                { key: 'faq', title: 'FAQ & Help Guide', description: "Quick answers to common questions." },
            ];

            const backdrop = document.getElementById('tour-backdrop');
            const card = document.getElementById('tour-card');
            const stepCount = document.getElementById('tour-step-count');
            const titleEl = document.getElementById('tour-title');
            const descriptionEl = document.getElementById('tour-description');
            const backBtn = document.getElementById('tour-back');
            const nextBtn = document.getElementById('tour-next');
            const skipBtn = document.getElementById('tour-skip');

            let currentStep = 0;
            let sidebarWasOpen = false;

            function target(key) {
                return document.querySelector('[data-tour="' + key + '"]');
            }

            function positionCard(el) {
                const rect = el.getBoundingClientRect();
                const cardWidth = 288;
                let left = rect.right + 16;
                if (left + cardWidth > window.innerWidth - 16) {
                    left = Math.max(16, rect.left - cardWidth - 16);
                }
                let top = Math.min(rect.top, window.innerHeight - 220);
                top = Math.max(16, top);

                card.style.left = left + 'px';
                card.style.top = top + 'px';
            }

            function paintBackdrop(el) {
                const rect = el.getBoundingClientRect();
                const pad = 6;
                backdrop.style.background =
                    'rgba(17,29,51,0.6)';
                backdrop.style.clipPath =
                    'polygon(0% 0%, 0% 100%, ' + (rect.left - pad) + 'px 100%, ' + (rect.left - pad) + 'px ' + (rect.top - pad) + 'px, ' +
                    (rect.right + pad) + 'px ' + (rect.top - pad) + 'px, ' + (rect.right + pad) + 'px ' + (rect.bottom + pad) + 'px, ' +
                    (rect.left - pad) + 'px ' + (rect.bottom + pad) + 'px, ' + (rect.left - pad) + 'px 100%, 100% 100%, 100% 0%)';
            }

            function renderStep() {
                const step = TOUR_STEPS[currentStep];
                const el = target(step.key);

                if (!el) {
                    // Target isn't in the DOM (shouldn't happen for fixed sidebar
                    // items, but fail safe rather than getting stuck).
                    currentStep < TOUR_STEPS.length - 1 ? nextStep() : endTour(true);
                    return;
                }

                stepCount.textContent = 'Step ' + (currentStep + 1) + ' of ' + TOUR_STEPS.length;
                titleEl.textContent = step.title;
                descriptionEl.textContent = step.description;
                backBtn.classList.toggle('invisible', currentStep === 0);
                nextBtn.textContent = currentStep === TOUR_STEPS.length - 1 ? 'Finish' : 'Next';

                paintBackdrop(el);
                positionCard(el);
            }

            function nextStep() {
                if (currentStep < TOUR_STEPS.length - 1) {
                    currentStep++;
                    renderStep();
                } else {
                    endTour(true);
                }
            }

            function prevStep() {
                if (currentStep > 0) {
                    currentStep--;
                    renderStep();
                }
            }

            function startTour() {
                currentStep = 0;

                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebarWasOpen = false;
                    openSidebar();
                } else {
                    sidebarWasOpen = true;
                }

                backdrop.classList.remove('hidden');
                card.classList.remove('hidden');
                renderStep();
            }

            function endTour(markComplete) {
                backdrop.classList.add('hidden');
                card.classList.add('hidden');

                if (!sidebarWasOpen) {
                    closeSidebar();
                }

                if (markComplete) {
                    fetch('{{ route('portal.tour.complete') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    });
                }
            }

            nextBtn.addEventListener('click', nextStep);
            backBtn.addEventListener('click', prevStep);
            skipBtn.addEventListener('click', function () { endTour(true); });
            window.addEventListener('resize', function () {
                if (!card.classList.contains('hidden')) renderStep();
            });

            document.getElementById('tour-replay-trigger')?.addEventListener('click', startTour);

            if (window.autoStartTour) {
                startTour();
            }
        })();
    </script>

    {{-- Full-screen loading overlay --}}
    <div id="page-loading-overlay" class="fixed inset-0 z-[9999] hidden flex-col items-center justify-center gap-3 bg-white/70 dark:bg-gray-900/80 backdrop-blur-sm">
        <svg class="w-10 h-10 animate-spin text-gold" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        <p id="page-loading-text" class="text-sm font-medium text-navy dark:text-white">Processing your request…</p>
    </div>

    <script>
        // Every onboarding/portal form here is a plain server round-trip
        // (no AJAX) — without feedback, the page just sits still after a
        // click until the next page loads, which reads as "hanging"
        // especially on a slower connection. Shows a full-screen spinner the
        // instant any form on the page is submitted. A form can opt out with
        // data-no-loading-overlay if it already handles its own submit
        // feedback (e.g. an AJAX-driven form). Sidebar navigation links get
        // the same treatment below — those are also plain page navigations.
        (function () {
            const overlay = document.getElementById('page-loading-overlay');
            const overlayText = document.getElementById('page-loading-text');
            if (!overlay) return;

            const DEFAULT_TEXT = 'Processing your request…';
            const SLOW_TEXT = "Still working on it — this can take a little longer than usual. Please don't close or refresh this page.";
            let slowTimer = null;

            function showOverlay() {
                overlayText.textContent = DEFAULT_TEXT;
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');

                clearTimeout(slowTimer);
                slowTimer = setTimeout(function () {
                    overlayText.textContent = SLOW_TEXT;
                }, 10000);
            }

            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (form.tagName !== 'FORM' || form.dataset.noLoadingOverlay !== undefined) return;

                showOverlay();
            }, true);

            // Sidebar nav links are plain <a href> page navigations too —
            // show the same overlay, but only for an actual same-tab
            // navigation: skip modifier/middle clicks (new tab), target="_blank",
            // mailto:/tel: links, and same-page hash anchors.
            document.addEventListener('click', function (e) {
                const link = e.target.closest('#portal-sidebar nav a[href]');
                if (!link || link.dataset.noLoadingOverlay !== undefined) return;
                if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                if (link.target === '_blank') return;

                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) return;

                showOverlay();
            });

            // If the user navigates back to a page that was mid-submit
            // (bfcache), don't leave the overlay stuck showing.
            window.addEventListener('pageshow', function () {
                clearTimeout(slowTimer);
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            });
        })();
    </script>

    @include('portal.partials.assistant-widget')

</body>
</html>
