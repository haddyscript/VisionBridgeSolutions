<!DOCTYPE html>
<html lang="en" class="{{ auth()->user()?->isDarkTheme() ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin – VisionBridge Solutions')</title>
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

    {{-- Gold sidebar scrollbar --}}
    <style>
        .gold-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #C9A84C transparent;
        }
        .gold-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .gold-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .gold-scrollbar::-webkit-scrollbar-thumb {
            background-color: #C9A84C;
            border-radius: 9999px;
        }
        .gold-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #DFC06A;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 dark:text-gray-100 bg-gray-50 dark:bg-navy-dark min-h-screen">

    {{-- Welcome-back greeting — session()->pull() reads AND clears it in one
         step, so this renders exactly once right after a genuine login, never
         again on subsequent page loads in the same session, and never at all
         during impersonation (see AuthenticatedSessionController::finishLogin()). --}}
    @php($adminGreeting = session()->pull('admin_greeting'))
    @if ($adminGreeting)
        <div id="admin-greeting-modal" class="fixed inset-0 z-[70] flex items-center justify-center px-4">
            <div id="admin-greeting-backdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>

            <div id="admin-greeting-panel" class="relative bg-white dark:bg-navy rounded-2xl w-full max-w-md overflow-hidden transform scale-90 opacity-0 translate-y-4 transition-all duration-500 ease-out admin-greeting-ring">
                <div class="relative overflow-hidden px-6 pt-7 pb-6" style="background:linear-gradient(135deg,#111D33,#1B2A4A 65%,#1B2A4A);">
                    {{-- Decorative glow + drifting sparkles — all purely cosmetic
                         and disabled under reduced-motion below. These keep
                         running for as long as the modal stays open (not just
                         during the 6s confetti burst), so it stays lively. --}}
                    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full pointer-events-none admin-greeting-glow" style="background:radial-gradient(circle,rgba(201,168,76,0.35) 0%,transparent 70%);"></div>
                    <div class="absolute -bottom-14 -left-10 w-44 h-44 rounded-full pointer-events-none admin-greeting-glow" style="background:radial-gradient(circle,rgba(42,157,143,0.22) 0%,transparent 70%); animation-delay:1.5s;"></div>
                    <svg class="absolute top-5 right-16 w-3.5 h-3.5 text-gold admin-greeting-sparkle" style="animation-delay:.2s" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l1.8 6.2L20 10l-6.2 1.8L12 18l-1.8-6.2L4 10l6.2-1.8z"/></svg>
                    <svg class="absolute top-12 right-6 w-2.5 h-2.5 text-white/70 admin-greeting-sparkle" style="animation-delay:.6s" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l1.8 6.2L20 10l-6.2 1.8L12 18l-1.8-6.2L4 10l6.2-1.8z"/></svg>
                    <svg class="absolute bottom-6 right-24 w-2 h-2 text-gold/80 admin-greeting-sparkle" style="animation-delay:1s" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l1.8 6.2L20 10l-6.2 1.8L12 18l-1.8-6.2L4 10l6.2-1.8z"/></svg>
                    <svg class="absolute top-20 right-28 w-2 h-2 text-teal-light admin-greeting-sparkle" style="animation-delay:1.4s" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l1.8 6.2L20 10l-6.2 1.8L12 18l-1.8-6.2L4 10l6.2-1.8z"/></svg>
                    <svg class="absolute bottom-10 right-8 w-3 h-3 text-white/50 admin-greeting-sparkle" style="animation-delay:1.8s" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l1.8 6.2L20 10l-6.2 1.8L12 18l-1.8-6.2L4 10l6.2-1.8z"/></svg>
                    <svg class="absolute top-8 left-8 w-2.5 h-2.5 text-gold/70 admin-greeting-sparkle" style="animation-delay:.9s" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l1.8 6.2L20 10l-6.2 1.8L12 18l-1.8-6.2L4 10l6.2-1.8z"/></svg>
                    <svg class="absolute bottom-4 left-16 w-2 h-2 text-teal-light/80 admin-greeting-sparkle" style="animation-delay:2.2s" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l1.8 6.2L20 10l-6.2 1.8L12 18l-1.8-6.2L4 10l6.2-1.8z"/></svg>

                    <div id="admin-greeting-header-text" class="relative opacity-0 transition-all duration-500 ease-out" style="transform:translateY(6px);">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-1">Welcome back</p>
                        <h2 class="font-display text-xl font-bold text-white">{{ auth()->user()->name }}</h2>
                    </div>
                </div>
                <div class="px-6 py-6">
                    <p id="admin-greeting-quote" class="text-base text-navy dark:text-white leading-relaxed italic opacity-0 transition-all duration-500 ease-out" style="transform:translateY(6px);">&ldquo;{{ $adminGreeting }}&rdquo;</p>
                    <button type="button" onclick="document.getElementById('admin-greeting-modal').remove(); document.getElementById('admin-greeting-confetti')?.remove();"
                            class="relative overflow-hidden mt-6 w-full bg-gold hover:bg-gold-dark text-navy font-bold text-sm py-2.5 rounded-lg transition-all duration-200 hover:scale-[1.02] hover:shadow-lg active:scale-[0.98]">
                        <span class="relative">Let's Get to Work</span>
                        <span class="admin-greeting-shimmer" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>

        <style>
            @keyframes admin-greeting-float {
                0%, 100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-8px) scale(1.05); }
            }
            .admin-greeting-glow { animation: admin-greeting-float 6s ease-in-out infinite; }

            @keyframes admin-greeting-twinkle {
                0%, 100% { opacity: 0.2; transform: scale(0.8) rotate(0deg); }
                50% { opacity: 1; transform: scale(1.2) rotate(20deg); }
            }
            .admin-greeting-sparkle { animation: admin-greeting-twinkle 2.2s ease-in-out infinite; }

            /* Continuous pulsing gold ring around the whole panel — runs for
               as long as the modal stays open, not just on entrance. */
            @keyframes admin-greeting-ring-pulse {
                0%, 100% { box-shadow: 0 0 0 0 rgba(201,168,76,0.45), 0 25px 50px -12px rgba(0,0,0,0.5); }
                50% { box-shadow: 0 0 0 8px rgba(201,168,76,0), 0 25px 50px -12px rgba(0,0,0,0.5); }
            }
            .admin-greeting-ring { animation: admin-greeting-ring-pulse 2.5s ease-out infinite; }

            /* Diagonal light sweep looping across the CTA button. */
            @keyframes admin-greeting-shimmer-sweep {
                0% { transform: translateX(-150%) skewX(-20deg); }
                100% { transform: translateX(250%) skewX(-20deg); }
            }
            .admin-greeting-shimmer {
                position: absolute;
                top: 0; left: 0;
                width: 35%; height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.55), transparent);
                animation: admin-greeting-shimmer-sweep 2.8s ease-in-out infinite;
            }

            @media (prefers-reduced-motion: reduce) {
                .admin-greeting-glow, .admin-greeting-sparkle, .admin-greeting-ring, .admin-greeting-shimmer {
                    animation: none;
                }
            }
        </style>

        <script>
            (function () {
                const backdrop = document.getElementById('admin-greeting-backdrop');
                const panel = document.getElementById('admin-greeting-panel');
                const headerText = document.getElementById('admin-greeting-header-text');
                const quote = document.getElementById('admin-greeting-quote');

                requestAnimationFrame(function () {
                    backdrop.classList.remove('opacity-0');
                    panel.classList.remove('scale-90', 'opacity-0', 'translate-y-4');
                });

                // Staggered two-stage reveal — header settles in first, then the
                // quote a beat later, instead of everything landing at once.
                setTimeout(function () {
                    headerText.classList.remove('opacity-0');
                    headerText.style.transform = 'translateY(0)';
                }, 200);
                setTimeout(function () {
                    quote.classList.remove('opacity-0');
                    quote.style.transform = 'translateY(0)';
                }, 380);

                // Hand-rolled canvas confetti — no external library, same as
                // the QR code and everything else this app generates itself.
                // Skipped entirely under prefers-reduced-motion.
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

                const canvas = document.createElement('canvas');
                canvas.id = 'admin-greeting-confetti';
                canvas.style.cssText = 'position:fixed;inset:0;width:100vw;height:100vh;z-index:75;pointer-events:none;';
                document.body.appendChild(canvas);

                const ctx = canvas.getContext('2d');
                function resizeCanvas() {
                    canvas.width = window.innerWidth;
                    canvas.height = window.innerHeight;
                }
                resizeCanvas();
                window.addEventListener('resize', resizeCanvas);

                const colors = ['#C9A84C', '#DFC06A', '#A8872E', '#2A9D8F', '#3DBFB0', '#FFFFFF'];
                const particles = Array.from({ length: 160 }, function () {
                    return {
                        x: Math.random() * canvas.width,
                        y: -20 - Math.random() * canvas.height * 0.6,
                        w: 5 + Math.random() * 6,
                        h: 8 + Math.random() * 8,
                        color: colors[Math.floor(Math.random() * colors.length)],
                        rotation: Math.random() * 360,
                        rotationSpeed: (Math.random() - 0.5) * 12,
                        speedY: 2 + Math.random() * 3,
                        speedX: (Math.random() - 0.5) * 2.5,
                        opacity: 1,
                    };
                });

                const durationMs = 6000;
                const startedAt = performance.now();

                function drawConfetti(now) {
                    const elapsed = now - startedAt;
                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    particles.forEach(function (p) {
                        p.y += p.speedY;
                        p.x += p.speedX;
                        p.rotation += p.rotationSpeed;

                        // Fade out over the last second instead of stopping abruptly.
                        if (elapsed > durationMs - 1000) {
                            p.opacity = Math.max(0, (durationMs - elapsed) / 1000);
                        }

                        ctx.save();
                        ctx.globalAlpha = p.opacity;
                        ctx.translate(p.x, p.y);
                        ctx.rotate((p.rotation * Math.PI) / 180);
                        ctx.fillStyle = p.color;
                        ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h);
                        ctx.restore();
                    });

                    if (elapsed < durationMs) {
                        requestAnimationFrame(drawConfetti);
                    } else {
                        window.removeEventListener('resize', resizeCanvas);
                        canvas.remove();
                    }
                }

                requestAnimationFrame(drawConfetti);
            })();
        </script>
    @endif

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 flex flex-col -translate-x-full md:translate-x-0 transition-transform duration-200" style="background:#111D33;">
            <div class="flex items-center justify-center py-6 border-b border-white/10 shrink-0">
                <img src="{{ asset('image/logo/vbs-logo-v3.jpeg') }}" alt="VisionBridge Solutions" class="h-28 w-auto object-contain rounded-md">
            </div>

            <p class="px-6 pt-4 pb-2 text-xs font-bold uppercase tracking-widest text-gold shrink-0 border-b border-white/10">{{ auth()->user()->name }} Portal</p>

            <nav id="admin-sidebar-nav" class="flex-1 overflow-y-auto gold-scrollbar py-5 px-3 space-y-0.5">
                @if (auth()->user()->canAccessAdminPage('dashboard'))
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        All Projects
                    </a>
                @endif
                @if (auth()->user()->isDeveloper() && auth()->user()->canAccessAdminPage('work-orders'))
                    <a href="{{ route('admin.work-orders.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.work-orders.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7a2 2 0 012-2h6.5L21 8.5V17a2 2 0 01-2 2H11a2 2 0 01-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h4m-4 3h4m-9-8v14"/>
                        </svg>
                        <span class="flex-1">My Work Orders</span>
                        @if ($myWorkOrderCount > 0)
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500 text-white">{{ $myWorkOrderCount }}</span>
                        @endif
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('developers'))
                    <a href="{{ route('admin.developers.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.developers.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 18.5v-1.25a3.25 3.25 0 00-3.25-3.25h-5.5A3.25 3.25 0 004 17.25v1.25M10.5 10.5a2.75 2.75 0 100-5.5 2.75 2.75 0 000 5.5zM20 18.5v-1a3 3 0 00-2.25-2.905M15.5 5.13a3 3 0 010 5.74"/>
                        </svg>
                        <span class="flex-1">Developers</span>
                        @if ($unassignedWorkOrderCount > 0)
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500 text-white">{{ $unassignedWorkOrderCount }}</span>
                        @endif
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('clients'))
                    <a href="{{ route('admin.clients.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.clients.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Clients
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('revisions'))
                    <a href="{{ route('admin.revisions.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.revisions.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Revision Management
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('calendar'))
                    <a href="{{ route('admin.calendar') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.calendar') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Calendar
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('contact-messages'))
                    <a href="{{ route('admin.contact-messages.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.contact-messages.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <span class="flex-1">Contact Messages</span>
                        @if ($unreadContactCount > 0)
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500 text-white">{{ $unreadContactCount }}</span>
                        @endif
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('consultations'))
                    <a href="{{ route('admin.consultations.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.consultations.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="flex-1">Consultations</span>
                        @if ($unreadConsultationCount > 0)
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500 text-white">{{ $unreadConsultationCount }}</span>
                        @endif
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('intake-submissions'))
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
                @endif
                @if (auth()->user()->canAccessAdminPage('project-requests'))
                    <a href="{{ route('admin.project-requests.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.project-requests.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="flex-1">Project Requests</span>
                        @if ($pendingProjectRequestCount > 0)
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500 text-white">{{ $pendingProjectRequestCount }}</span>
                        @endif
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('support-tickets'))
                    <a href="{{ route('admin.support-tickets.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.support-tickets.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="flex-1">Support Tickets</span>
                        @if ($openSupportTicketCount > 0)
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500 text-white">{{ $openSupportTicketCount }}</span>
                        @endif
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('recommendations'))
                    <a href="{{ route('admin.recommendations.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.recommendations.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span class="flex-1">Recommendations</span>
                        @if ($pendingRecommendationCount > 0)
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500 text-white">{{ $pendingRecommendationCount }}</span>
                        @endif
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('payments'))
                    <a href="{{ route('admin.payments.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.payments.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h5M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                        </svg>
                        Payments
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('refund-requests'))
                    <a href="{{ route('admin.refund-requests.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.refund-requests.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l-4-4 4-4m-4 4h11a4 4 0 010 8h-1"/>
                        </svg>
                        <span class="flex-1">Refund Requests</span>
                        @if ($pendingRefundRequestCount > 0)
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500 text-white">{{ $pendingRefundRequestCount }}</span>
                        @endif
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('subscriptions'))
                    <a href="{{ route('admin.subscriptions.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.subscriptions.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Care Plans
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('partner-payouts'))
                    <a href="{{ route('admin.partner-payouts.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.partner-payouts.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a4 4 0 00-8 0v2m-2 0h12a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2v-8a2 2 0 012-2z"/>
                        </svg>
                        FaithStack Payouts
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('care-plan-pricing'))
                    <a href="{{ route('admin.care-plans.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.care-plans.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Care Plan Pricing
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('service-agreement'))
                    <a href="{{ route('admin.service-agreement.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.service-agreement.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Service Agreement
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('email-templates'))
                    <a href="{{ route('admin.email-templates.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.email-templates.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Email Templates
                    </a>
                @endif
                @if (auth()->user()->canAccessAdminPage('satisfaction-surveys'))
                    <a href="{{ route('admin.satisfaction-surveys.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.satisfaction-surveys.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.062 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.958z"/>
                        </svg>
                        Satisfaction Surveys
                    </a>
                @endif
                @php($unreadAnnouncementCount = \App\Models\Announcement::unacknowledgedCountFor(auth()->user()))
                @if (auth()->user()->canAccessAdminPage('announcements'))
                    <a href="{{ route('admin.announcements.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.announcements.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span class="flex-1">Announcements</span>
                        @if ($unreadAnnouncementCount > 0)
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500 text-white">{{ $unreadAnnouncementCount }}</span>
                        @endif
                    </a>
                @else
                    {{-- Team members without the "Announcements" management
                         permission (Developer, PM, Sales Rep, CSR, Admin
                         Staff, etc.) still get a read-only history view. --}}
                    <a href="{{ route('admin.announcements.history') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.announcements.history') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span class="flex-1">Announcements</span>
                        @if ($unreadAnnouncementCount > 0)
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500 text-white">{{ $unreadAnnouncementCount }}</span>
                        @endif
                    </a>
                @endif
                <a href="{{ route('admin.team.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.team.*') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-3.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/>
                    </svg>
                    Team
                </a>
                <a href="{{ route('admin.faq') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.faq') ? 'bg-gold/15 text-gold' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    FAQ &amp; Help Guide
                </a>
            </nav>

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

        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden"></div>

        {{-- Main content --}}
        <div class="flex-1 md:ml-64 min-w-0">
            <header class="sticky top-0 z-20 bg-white dark:bg-navy border-b border-gray-200 dark:border-gray-700 h-16 flex items-center px-4 sm:px-6 lg:px-8 gap-4">
                <button id="sidebar-toggle" class="md:hidden text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="font-display text-lg font-bold text-navy dark:text-white flex-1">@yield('page-title', 'Admin')</h1>
                <div class="flex items-center gap-2.5 pl-3 pr-1.5 py-1.5 rounded-full bg-gray-50 dark:bg-navy-dark/50 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <span id="theme-toggle-label" class="hidden sm:inline text-xs font-bold uppercase tracking-wider text-navy dark:text-gold select-none transition-colors">Light Mode</span>
                    <button id="theme-toggle" type="button" title="Toggle dark mode" aria-label="Toggle light and dark mode"
                            class="relative inline-flex items-center h-8 w-16 shrink-0 rounded-full border-2 border-gold bg-gradient-to-r from-amber-200 via-amber-100 to-amber-50 dark:from-indigo-950 dark:via-navy-dark dark:to-navy shadow-inner hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gold focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-300">
                        {{-- Static day/night cues at each end of the track — always
                             present, just dimmed on the side that isn't active, so
                             the switch reads as "day ↔ night" at a glance even
                             before noticing the sliding knob. --}}
                        <svg class="absolute left-1.5 w-3.5 h-3.5 text-amber-500 opacity-90 dark:opacity-20 transition-opacity duration-300 pointer-events-none" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z"/>
                        </svg>
                        <svg class="absolute right-1.5 w-3.5 h-3.5 text-gold opacity-20 dark:opacity-90 transition-opacity duration-300 pointer-events-none" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z"/>
                        </svg>
                        <span class="absolute left-1 top-1 w-6 h-6 rounded-full bg-white dark:bg-navy-light shadow-md ring-1 ring-black/5 flex items-center justify-center transform transition-transform duration-300 dark:translate-x-8">
                            <svg id="theme-icon-light" class="w-4 h-4 text-amber-500 hidden" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z"/>
                            </svg>
                            <svg id="theme-icon-dark" class="w-4 h-4 text-gold-dark hidden" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z"/>
                            </svg>
                        </span>
                    </button>
                </div>
            </header>

            @if (session('impersonator_id'))
                <div class="sticky top-16 z-20 bg-gold text-navy-dark text-sm font-semibold px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between gap-3">
                    <span>👁️ Logged in as {{ auth()->user()->name }} — any changes you make here are real.</span>
                    <form method="POST" action="{{ route('impersonate.stop') }}">
                        @csrf
                        <button type="submit" class="underline hover:no-underline shrink-0">Return to My Account</button>
                    </form>
                </div>
            @endif

            <main class="px-4 sm:px-6 lg:px-8 py-8">
                @if (session('status'))
                    <div class="mb-6 text-sm text-teal-dark dark:text-teal-light bg-teal/10 border border-teal/30 rounded-lg px-4 py-3">
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

                {{-- Announcement banner for team/developer audiences (clients get
                     their own on the portal dashboard). Shows the most recent
                     active announcement targeting this admin, until dismissed. --}}
                @php($adminAnnouncement = \App\Models\Announcement::activeFor(auth()->user()))
                @if ($adminAnnouncement)
                    @include('partials.announcement-banner', [
                        'announcement' => $adminAnnouncement,
                        'dismissUrl' => route('admin.announcements.dismiss', $adminAnnouncement),
                        'domId' => 'admin-announcement',
                    ])
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Deliberately no backdrop-click or Escape dismissal here — the
        // greeting is meant to actually be read, so "Let's Get to Work" is
        // the only way out.

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

        // Sidebar scroll position — every nav link is a normal full-page
        // reload (this admin area isn't an SPA), which otherwise snaps a
        // scrolled sidebar back to the top on every click. Persisted per
        // browser so navigating to something further down the list (e.g.
        // "FAQ & Help Guide") doesn't force scrolling back down again after
        // the page loads.
        const sidebarNav = document.getElementById('admin-sidebar-nav');
        if (sidebarNav) {
            const savedScroll = parseInt(localStorage.getItem('admin-sidebar-scroll'), 10);
            if (!isNaN(savedScroll)) {
                sidebarNav.scrollTop = savedScroll;
            }
            sidebarNav.addEventListener('scroll', function () {
                localStorage.setItem('admin-sidebar-scroll', sidebarNav.scrollTop);
            });
        }

        // Theme toggle
        const themeToggle = document.getElementById('theme-toggle');
        const iconLight = document.getElementById('theme-icon-light');
        const iconDark = document.getElementById('theme-icon-dark');
        const themeLabel = document.getElementById('theme-toggle-label');

        function syncThemeIcon() {
            const isDark = document.documentElement.classList.contains('dark');
            iconLight.classList.toggle('hidden', !isDark);
            iconDark.classList.toggle('hidden', isDark);
            if (themeLabel) themeLabel.textContent = isDark ? 'Dark Mode' : 'Light Mode';
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
    </script>

</body>
</html>
