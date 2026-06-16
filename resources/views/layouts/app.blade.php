<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VisionBridge Solutions – Building Websites. Expanding Reach.')</title>
    <meta name="description" content="@yield('description', 'Custom websites designed to strengthen your brand, expand your reach, and protect your online presence.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CDN with custom config -->
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

    {{-- type="text/tailwindcss" lets the Play CDN process @apply directives --}}
    <style type="text/tailwindcss">
        html { scroll-behavior: smooth; }

        /* ─── Nav ─── */
        .nav-link { @apply text-white/80 hover:text-gold transition-colors duration-200 text-sm font-medium; }

        /* ─── Re-usable buttons (outside hero) ─── */
        .btn-gold    { @apply inline-block bg-gold hover:bg-gold-dark text-navy font-semibold px-7 py-3 rounded-lg transition-all duration-200 shadow hover:shadow-lg; }
        .btn-outline { @apply inline-block border-2 border-white text-white hover:bg-white hover:text-navy font-semibold px-7 py-3 rounded-lg transition-all duration-200; }

        /* ─── Typography ─── */
        .section-title    { @apply font-display text-3xl md:text-4xl font-bold text-navy leading-tight; }
        .section-subtitle { @apply text-gray-500 text-lg mt-3 max-w-2xl mx-auto; }

        /* ─── Hero canvas ─── */
        #hero-canvas { position:absolute; inset:0; width:100%; height:100%; display:block; }

        /* ─── Word-mask reveal ─── */
        .word-wrap  { display:inline-block; overflow:hidden; vertical-align:bottom; margin-right:0.26em; line-height:1.12; padding-bottom:0.06em; }
        .word-wrap:last-child { margin-right:0; }
        .hero-word  { display:inline-block; will-change:transform,opacity; }

        /* ─── Gold shimmer text ─── */
        .shimmer-gold {
            background: linear-gradient(100deg,#C9A84C 0%,#FFF2A8 38%,#E8C96A 52%,#C9A84C 100%);
            background-size: 240% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: text-shimmer 3.5s linear infinite;
        }
        @keyframes text-shimmer {
            0%   { background-position: 220% center; }
            100% { background-position: -220% center; }
        }

        /* ─── Pulsing "live" dot ─── */
        .live-dot {
            display:inline-block; width:7px; height:7px;
            background:#2A9D8F; border-radius:50%;
            margin-right:8px; vertical-align:middle; position:relative;
        }
        .live-dot::after {
            content:''; position:absolute; inset:-4px; border-radius:50%;
            border:1.5px solid rgba(42,157,143,.55);
            animation: pulse-ring 2s ease-out infinite;
        }
        @keyframes pulse-ring {
            0%   { transform:scale(1);   opacity:1; }
            100% { transform:scale(2.4); opacity:0; }
        }

        /* ─── Hero CTA buttons ─── */
        .hero-btn-primary {
            position:relative; display:inline-flex; align-items:center; gap:8px;
            background:#C9A84C; color:#111D33; font-weight:700;
            padding:15px 34px; border-radius:10px; font-size:1rem;
            overflow:hidden; letter-spacing:.01em;
            transition: transform .22s, box-shadow .22s, background .22s;
            will-change: transform;
        }
        .hero-btn-primary::after {
            content:''; position:absolute; inset:0;
            background:linear-gradient(135deg,rgba(255,255,255,.22) 0%,transparent 60%);
            opacity:0; transition:opacity .22s;
        }
        .hero-btn-primary:hover { background:#DFC06A; transform:translateY(-3px); box-shadow:0 0 38px rgba(201,168,76,.48),0 8px 28px rgba(0,0,0,.35); }
        .hero-btn-primary:hover::after { opacity:1; }

        .hero-btn-secondary {
            display:inline-flex; align-items:center; gap:8px;
            border:1.5px solid rgba(255,255,255,.32); color:rgba(255,255,255,.88);
            font-weight:600; padding:15px 34px; border-radius:10px; font-size:1rem;
            background:rgba(255,255,255,.04);
            backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px);
            transition: transform .22s, box-shadow .22s, border-color .22s, background .22s;
            will-change: transform;
        }
        .hero-btn-secondary:hover { border-color:rgba(255,255,255,.7); background:rgba(255,255,255,.10); transform:translateY(-3px); box-shadow:0 8px 28px rgba(0,0,0,.3); }

        /* ─── Floating glassmorphism cards ─── */
        .float-card {
            position:absolute; pointer-events:none; z-index:3;
            background:rgba(255,255,255,.065); border:1px solid rgba(255,255,255,.12);
            backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px);
            border-radius:16px; padding:12px 18px;
            will-change:transform;
        }
        .float-card-1 { bottom:24%; left:3.5%; animation:float-a 5s ease-in-out infinite; }
        .float-card-2 { top:20%;   right:3.5%; animation:float-b 6.5s ease-in-out infinite; }
        @keyframes float-a {
            0%,100% { transform:translateY(0) rotate(-1deg); }
            50%      { transform:translateY(-10px) rotate(0deg); }
        }
        @keyframes float-b {
            0%,100% { transform:translateY(0) rotate(1deg); }
            50%      { transform:translateY(-13px) rotate(0deg); }
        }

        /* ─── Atmospheric orbs ─── */
        .hero-orb { position:absolute; border-radius:50%; pointer-events:none; will-change:transform; }
        @keyframes orb-drift {
            0%,100% { transform:translate(0,0) scale(1); }
            33%      { transform:translate(28px,-22px) scale(1.05); }
            66%      { transform:translate(-18px,14px) scale(.96); }
        }

        /* ─── Dot-grid texture ─── */
        .hero-grid-dots {
            background-image: radial-gradient(circle,rgba(255,255,255,.055) 1px,transparent 1px);
            background-size: 28px 28px;
        }

        /* ─── Glowing gold divider ─── */
        .glow-line {
            width:72px; height:2px; margin:18px auto;
            background:linear-gradient(90deg,transparent,#C9A84C,transparent);
            position:relative;
        }
        .glow-line::after {
            content:''; position:absolute; inset:-2px;
            background:inherit; filter:blur(5px); opacity:.65;
        }

        /* ─── Mouse-scroll indicator ─── */
        @keyframes scroll-dot {
            0%,100% { transform:translateY(0);   opacity:1; }
            60%      { transform:translateY(9px); opacity:.25; }
        }

        /* ─── Welcome section ─── */
        .welcome-word-wrap {
            display:inline-block; overflow:hidden; vertical-align:bottom;
            margin-right:0.22em; line-height:1.18; padding-bottom:0.05em;
        }
        .welcome-word-wrap:last-child { margin-right:0; }
        .welcome-word { display:inline-block; will-change:transform,opacity; }
        @keyframes play-pulse {
            0%   { transform:scale(1);   opacity:0.85; }
            100% { transform:scale(2.5); opacity:0; }
        }

        /* ─── About section ─── */
        .about-card {
            position: relative;
            will-change: transform;
            transition: box-shadow 0.35s ease;
            --mx: 50%; --my: 50%;
        }
        /* Cursor-tracking radial glow */
        .about-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: radial-gradient(200px circle at var(--mx) var(--my), rgba(255,255,255,0.09), transparent 70%);
            opacity: 0;
            transition: opacity 0.30s;
            pointer-events: none;
            z-index: 1;
        }
        .about-card:hover {
            box-shadow: 0 22px 60px rgba(0,0,0,0.50), 0 0 0 1px rgba(201,168,76,0.22) inset;
        }
        .about-card:hover::after { opacity: 1; }
        /* Lift card content above ::after overlay */
        .about-card > * { position: relative; z-index: 2; }

        /* Mosaic panels — start hidden; GSAP reveals each one */
        .mosaic-panel {
            background-image: var(--img);
            will-change: transform, opacity;
            opacity: 0;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-white">

    <!-- Navigation -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-navy/95 backdrop-blur-sm shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="#hero" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gold rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-navy" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L2 7v11h5v-6h6v6h5V7L10 2z"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-lg leading-tight">VisionBridge<br><span class="text-gold text-xs font-medium tracking-widest uppercase">Solutions</span></span>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="#about" class="nav-link">About</a>
                    <a href="#services" class="nav-link">Services</a>
                    <a href="#plans" class="nav-link">Plans</a>
                    <a href="#portfolio" class="nav-link">Portfolio</a>
                    <a href="#contact" class="btn-gold text-sm py-2 px-5">Get Started</a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="menu-btn" class="md:hidden text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 flex flex-col gap-3">
                <a href="#about" class="nav-link py-1">About</a>
                <a href="#services" class="nav-link py-1">Services</a>
                <a href="#plans" class="nav-link py-1">Plans</a>
                <a href="#portfolio" class="nav-link py-1">Portfolio</a>
                <a href="#contact" class="btn-gold text-center mt-2">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-navy-dark text-white pt-16 pb-8" style="background-color:#111D33">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-12">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gold rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-navy" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2L2 7v11h5v-6h6v6h5V7L10 2z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-lg">VisionBridge <span class="text-gold">Solutions</span></span>
                    </div>
                    <p class="text-white/60 text-sm leading-relaxed">Building Websites. Expanding Reach.<br>Helping organizations establish a professional online presence.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-white/60">
                        <li><a href="#about" class="hover:text-gold transition-colors">About Us</a></li>
                        <li><a href="#services" class="hover:text-gold transition-colors">Services</a></li>
                        <li><a href="#plans" class="hover:text-gold transition-colors">Maintenance Plans</a></li>
                        <li><a href="#portfolio" class="hover:text-gold transition-colors">Portfolio</a></li>
                        <li><a href="#contact" class="hover:text-gold transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm text-white/60">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            info@visionbridgesolutions.com
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            (555) 000-0000
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-white/40">
                <p>&copy; {{ date('Y') }} VisionBridge Solutions. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-gold transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-gold transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('menu-btn').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.add('hidden');
            });
        });
    </script>

    <!-- GSAP + ScrollTrigger -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>

    @yield('scripts')

</body>
</html>
