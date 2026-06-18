<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin – VisionBridge Solutions')</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'><rect width='20' height='20' rx='3' fill='%23C9A84C'/><path d='M10 2L2 7v11h5v-6h6v6h5V7L10 2z' fill='%23111D33'/></svg>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50 min-h-screen">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 flex flex-col -translate-x-full md:translate-x-0 transition-transform duration-200" style="background:#111D33;">
            <div class="flex items-center gap-2.5 px-6 h-16 border-b border-white/10 shrink-0">
                <div class="w-8 h-8 bg-gold rounded-md flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-navy" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L2 7v11h5v-6h6v6h5V7L10 2z"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-sm leading-tight">VisionBridge <span class="text-gold">Solutions</span></span>
            </div>

            <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-0.5">
                <p class="px-3 text-[0.65rem] font-semibold uppercase tracking-widest text-white/30 mb-2">FaithStack Team Portal</p>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    All Projects
                </a>
                <a href="{{ route('admin.contact-messages.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.contact-messages.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Contact Messages
                </a>
                <a href="{{ route('admin.intake-submissions.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.intake-submissions.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="flex-1">Intake Submissions</span>
                    @if ($newIntakeCount > 0)
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500 text-white">{{ $newIntakeCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.payments.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.payments.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h5M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                    </svg>
                    Payments
                </a>
                <a href="{{ route('admin.subscriptions.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.subscriptions.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Maintenance Plans
                </a>
                <a href="{{ route('admin.care-plans.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.care-plans.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Care Plan Pricing
                </a>
            </nav>

            <div class="border-t border-white/10 p-3 shrink-0">
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

        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden"></div>

        {{-- Main content --}}
        <div class="flex-1 md:ml-64 min-w-0">
            <header class="sticky top-0 z-20 bg-white border-b border-gray-200 h-16 flex items-center px-4 sm:px-6 lg:px-8 gap-4">
                <button id="sidebar-toggle" class="md:hidden text-gray-500 hover:text-navy">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="font-display text-lg font-bold text-navy">@yield('page-title', 'Admin')</h1>
            </header>

            <main class="px-4 sm:px-6 lg:px-8 py-8">
                @if (session('status'))
                    <div class="mb-6 text-sm text-teal-dark bg-teal/10 border border-teal/30 rounded-lg px-4 py-3">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
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
        const sidebar = document.getElementById('admin-sidebar');
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
    </script>

</body>
</html>
