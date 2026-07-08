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
</head>
<body class="font-sans antialiased text-gray-800 dark:text-gray-100 bg-gray-50 dark:bg-gray-900 min-h-screen">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside id="portal-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 flex flex-col -translate-x-full md:translate-x-0 transition-transform duration-200" style="background:#111D33;">
            <div class="flex items-center justify-center py-6 border-b border-white/10 shrink-0">
                <img src="{{ asset('image/logo/vbs-logo-v3.jpeg') }}" alt="VisionBridge Solutions" class="h-28 w-auto object-contain rounded-md">
            </div>

            <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-0.5">
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
                <a href="{{ route('portal.faq') }}" data-tour="faq"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('portal.faq') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    FAQ &amp; Help Guide
                </a>
            </nav>

            <div class="px-3 pb-3 shrink-0">
                <div class="rounded-lg bg-white/5 border border-white/10 px-3.5 py-3">
                    <p class="text-[0.65rem] font-semibold uppercase tracking-widest text-white/30 mb-2">Need Help?</p>
                    <a href="mailto:{{ config('mail.admin_address') }}" class="flex items-start gap-2 text-xs text-white/70 hover:text-gold transition-colors mb-2">
                        <svg class="w-3.5 h-3.5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="break-all leading-snug">{{ config('mail.admin_address') }}</span>
                    </a>
                    <a href="tel:5550000000" class="flex items-center gap-2 text-xs text-white/70 hover:text-gold transition-colors">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.517l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        (404) 426-2856
                    </a>
                </div>
                <button type="button" id="tour-replay-trigger" class="w-full flex items-center gap-2 mt-2.5 text-xs text-white/70 hover:text-gold transition-colors">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Take a Tour
                </button>
            </div>

            <div class="border-t border-white/10 pt-3 shrink-0">
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
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/65 hover:bg-white/5 hover:text-white transition-colors">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile overlay --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden"></div>

        {{-- Main content --}}
        <div class="flex-1 md:ml-64 min-w-0">
            <header class="sticky top-0 z-20 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 h-16 flex items-center px-4 sm:px-6 lg:px-8 gap-4">
                <button id="sidebar-toggle" class="md:hidden text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="font-display text-lg font-bold text-navy dark:text-white flex-1">@yield('page-title', 'Client Portal')</h1>

                <div class="relative hidden sm:block w-64">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                    </svg>
                    <input type="text" id="portal-search-input" placeholder="Search your files, payments..." autocomplete="off"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">

                    <div id="portal-search-results" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg z-30 max-h-96 overflow-y-auto"></div>
                </div>

                <div class="relative">
                    <button id="notification-bell-toggle" type="button" title="Notifications" data-tour="notification-bell"
                            class="relative w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if ($unreadNotificationCount > 0)
                            <span id="notification-bell-badge" class="absolute top-1 right-1 w-2 h-2 rounded-full bg-red-500"></span>
                        @endif
                    </button>

                    <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg z-30 max-h-96 overflow-y-auto">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-sm font-bold text-navy dark:text-white">Notifications</p>
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
                                ];
                            @endphp
                            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($notifications as $notification)
                                    @php $icon = $notificationIcons[$notification->type] ?? $notificationIcons['milestone_completed']; @endphp
                                    <li class="flex items-start gap-3 px-4 py-3 {{ $notification->read_at ? '' : 'bg-gold/5' }}">
                                        <span class="w-8 h-8 rounded-full {{ $icon['bg'] }} flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 {{ $icon['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon['path'] }}"/></svg>
                                        </span>
                                        <div class="min-w-0">
                                            @if ($notification->url)
                                                <a href="{{ $notification->url }}" class="text-sm font-medium text-navy dark:text-white hover:underline">{{ $notification->title }}</a>
                                            @else
                                                <p class="text-sm font-medium text-navy dark:text-white">{{ $notification->title }}</p>
                                            @endif
                                            @if ($notification->description)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $notification->description }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
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

        // Notification bell
        const bellToggle = document.getElementById('notification-bell-toggle');
        const bellDropdown = document.getElementById('notification-dropdown');
        const bellBadge = document.getElementById('notification-bell-badge');

        bellToggle?.addEventListener('click', function (e) {
            e.stopPropagation();
            const opening = bellDropdown.classList.contains('hidden');
            bellDropdown.classList.toggle('hidden');

            if (opening && bellBadge) {
                bellBadge.remove();
                fetch('{{ route('portal.notifications.read') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
            }
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
        // feedback (e.g. an AJAX-driven form).
        (function () {
            const overlay = document.getElementById('page-loading-overlay');
            const overlayText = document.getElementById('page-loading-text');
            if (!overlay) return;

            const DEFAULT_TEXT = 'Processing your request…';
            const SLOW_TEXT = "Still working on it — this can take a little longer than usual. Please don't close or refresh this page.";
            let slowTimer = null;

            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (form.tagName !== 'FORM' || form.dataset.noLoadingOverlay !== undefined) return;

                overlayText.textContent = DEFAULT_TEXT;
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');

                clearTimeout(slowTimer);
                slowTimer = setTimeout(function () {
                    overlayText.textContent = SLOW_TEXT;
                }, 10000);
            }, true);

            // If the user navigates back to a page that was mid-submit
            // (bfcache), don't leave the overlay stuck showing.
            window.addEventListener('pageshow', function () {
                clearTimeout(slowTimer);
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            });
        })();
    </script>

</body>
</html>
