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
        /* ─── Nav link (base) ─── */
        .nav-link { @apply text-sm font-medium transition-colors duration-200; color:rgba(255,255,255,0.75); }
        .nav-link:hover { color:#C9A84C; }

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

        /* ─── Floating pill nav ─── */
        #nav-inner {
            transition: background 0.50s ease, box-shadow 0.50s ease,
                        border-radius 0.50s ease, max-width 0.55s ease,
                        border-color 0.50s ease, height 0.40s ease, padding 0.40s ease;
            border: 1px solid transparent;
            max-width: 100%;
        }
        #nav-inner.nav-pill {
            background: rgba(8,15,28,0.80);
            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);
            border-color: rgba(255,255,255,0.08);
            border-radius: 50px;
            box-shadow: 0 8px 36px rgba(0,0,0,0.45), 0 0 0 1px rgba(201,168,76,0.07);
            max-width: 940px;
            height: 54px !important;
        }

        /* ─── Sliding hover capsule ─── */
        #nav-cursor {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            height: 34px;
            width: 80px;
            background: rgba(255,255,255,0.07);
            border-radius: 8px;
            pointer-events: none;
            opacity: 0;
            will-change: transform, width, opacity;
        }

        /* ─── CTA shimmer sweep ─── */
        .nav-cta-btn {
            position: relative;
            overflow: hidden;
            will-change: transform;
        }
        .nav-cta-btn::before {
            content: '';
            position: absolute;
            top: -50%; left: -80%;
            width: 48%; height: 200%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.32), transparent);
            transform: skewX(-18deg);
            pointer-events: none;
            animation: btn-shine 4.5s ease-in-out infinite 2.5s;
        }
        @keyframes btn-shine {
            0%, 28%  { left: -80%; opacity: 0; }
            30%      { opacity: 1; }
            58%, 100%{ left: 155%; opacity: 0; }
        }

        /* ─── Footer: unpeel / reveal ─── */
        #page-wrapper {
            position: relative;
            z-index: 2;
            /* No background set here — each page section carries its own solid bg.
               The transparent #footer-spacer at the bottom lets the fixed footer show through. */
        }
        #site-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1;
            will-change: transform;
            overflow: visible;
        }

        /* ─── Footer: flowing SVG wave ─── */
        .footer-wave-top {
            position: relative;
            height: 72px;
            overflow: hidden;
            margin-bottom: -2px; /* seal gap to footer bg */
            pointer-events: none;
        }
        .footer-wave-svg {
            width: 300%;
            height: 100%;
            display: block;
            position: relative;
            will-change: transform;
        }
        .footer-wave-svg .wave-teal {
            animation: wave-glide-teal 18s linear infinite;
        }
        .footer-wave-svg .wave-main {
            animation: wave-glide-main 12s linear infinite;
        }
        @keyframes wave-glide-main {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-33.333%); }
        }
        @keyframes wave-glide-teal {
            0%   { transform: translateX(-8%); }
            100% { transform: translateX(-41.333%); }
        }

        /* ─── Footer: link hover underline ─── */
        .footer-link {
            position: relative;
            display: inline-block;
        }
        .footer-link-bar {
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 1px;
            background: #C9A84C;
            transform: scaleX(0);
            transform-origin: center;
            will-change: transform;
        }

        /* ─── Footer: column entrance (GSAP sets from) ─── */
        .footer-col {
            will-change: transform, opacity;
        }
        .footer-bottom-bar {
            will-change: opacity;
        }

        /* ════════════════════════════════════════════════════════════
           CORE VALUES — Light, smooth, welcoming cards
           ════════════════════════════════════════════════════════════ */

        .value-card-outer {
            border-radius: 20px;
            will-change: transform, opacity;
            opacity: 0;
            cursor: default;
            transition: transform 0.36s cubic-bezier(0.34,1.56,0.64,1);
        }
        .value-card-outer:hover { transform: translateY(-6px); }

        .value-card {
            border-radius: 20px;
            background: #FFFFFF;
            padding: 28px;
            height: 100%;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(17,29,51,0.07);
            box-shadow: 0 2px 12px rgba(17,29,51,0.05), 0 1px 3px rgba(17,29,51,0.03);
            transition: box-shadow 0.32s ease, border-color 0.32s ease;
        }
        .value-card-outer:hover .value-card {
            box-shadow: 0 20px 52px rgba(17,29,51,0.09), 0 6px 18px rgba(17,29,51,0.05);
            border-color: rgba(201,168,76,0.24);
        }

        .value-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(
                380px circle at var(--cx, -9999px) var(--cy, -9999px),
                rgba(201,168,76,0.055),
                rgba(42,157,143,0.03) 52%,
                transparent 72%
            );
            opacity: 0;
            transition: opacity 0.45s ease;
            pointer-events: none;
            z-index: 0;
        }
        .value-card-outer:hover .value-card::before { opacity: 1; }

        .value-card > * { position: relative; z-index: 1; }

        .value-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .value-icon-wrap {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            background: rgba(201,168,76,0.08);
            border: 1px solid rgba(201,168,76,0.16);
            display: flex;
            align-items: center;
            justify-content: center;
            will-change: transform;
            flex-shrink: 0;
            transition: background 0.30s ease, border-color 0.30s ease;
        }
        .value-card-outer:hover .value-icon-wrap {
            background: rgba(201,168,76,0.14);
            border-color: rgba(201,168,76,0.30);
        }

        .value-number {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            color: rgba(17,29,51,0.13);
            user-select: none;
            padding-top: 5px;
            transition: color 0.30s ease;
        }
        .value-card-outer:hover .value-number { color: rgba(201,168,76,0.65); }

        .value-card-divider {
            width: 28px;
            height: 1px;
            background: linear-gradient(90deg, rgba(201,168,76,0.38), transparent);
            margin-bottom: 14px;
        }

        .value-title {
            font-weight: 700;
            font-size: 1rem;
            color: #111D33;
            margin-bottom: 10px;
            line-height: 1.30;
            transition: color 0.26s ease;
        }
        .value-card-outer:hover .value-title { color: #C9A84C; }

        .value-desc {
            font-size: 0.875rem;
            line-height: 1.72;
            color: rgba(17,29,51,0.50);
        }

        /* ── Horizontal Wipe: Services → Why VisionBridge ── */
        #hscroll-outer {
            position: relative;
            overflow: hidden;
        }

        /* Backdrop — fills the outer behind #why while it slides in */
        #hscroll-backdrop {
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }

        /* Centered wipe indicator */
        #hscroll-indicator {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        #hscroll-ring-wrap {
            position: relative;
            width: 88px;
            height: 88px;
        }
        #hscroll-ring-svg {
            width: 88px;
            height: 88px;
        }
        #hscroll-ring-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #hscroll-pct {
            font-family: 'Playfair Display', serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: #C9A84C;
            letter-spacing: 0.04em;
        }
        #hscroll-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            text-align: center;
        }
        #hscroll-label span {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(17,29,51,0.35);
            line-height: 1.4;
        }

        /* Left-edge "WHY VISIONBRIDGE" peek label */
        #hscroll-edge-label {
            position: absolute;
            left: 40px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            gap: 12px;
            opacity: 0;
        }
        #hscroll-edge-label span {
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: rgba(17,29,51,0.30);
            writing-mode: vertical-rl;
        }

        /* Progress bar — gold line at bottom of the pinned container */
        #hscroll-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 0%;
            background: linear-gradient(90deg, #C9A84C 0%, rgba(42,157,143,0.70) 100%);
            z-index: 99;
            pointer-events: none;
            box-shadow: 0 0 10px rgba(201,168,76,0.40);
        }
        /* "Scroll to continue" hint — fades out once wipe starts */
        #hscroll-hint {
            position: absolute;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            z-index: 10;
            pointer-events: none;
        }
        #hscroll-hint span {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(17,29,51,0.38);
        }
        #hscroll-hint-arrow {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1.5px solid rgba(201,168,76,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media (max-width: 1023px) {
            #hscroll-hint, #hscroll-progress { display: none; }
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-white">

    <!-- Navigation -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50" style="padding:12px 16px 0;will-change:transform;">

        {{-- Floating pill inner wrapper --}}
        <div id="nav-inner" class="mx-auto flex items-center justify-between px-5 sm:px-7" style="height:60px;">

            {{-- Logo --}}
            <a id="nav-logo" href="#hero" class="flex items-center gap-2.5 shrink-0 opacity-0">
                <div class="w-8 h-8 bg-gold rounded-md flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-navy" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L2 7v11h5v-6h6v6h5V7L10 2z"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-lg leading-tight">VisionBridge<br>
                    <span class="text-gold text-xs font-medium tracking-widest uppercase">Solutions</span>
                </span>
            </a>

            {{-- Desktop links with sliding capsule --}}
            <div id="nav-links" class="hidden md:flex items-center gap-0.5 relative">
                <div id="nav-cursor"></div>
                <a href="#about"     class="nav-link relative z-10 px-4 py-2 opacity-0">About</a>
                <a href="#services"  class="nav-link relative z-10 px-4 py-2 opacity-0">Services</a>
                <a href="#plans"     class="nav-link relative z-10 px-4 py-2 opacity-0">Plans</a>
                <a href="#portfolio" class="nav-link relative z-10 px-4 py-2 opacity-0">Portfolio</a>
            </div>

            {{-- Desktop CTA --}}
            <a id="nav-cta" href="#contact"
               class="nav-cta-btn hidden md:inline-flex items-center gap-2 bg-gold hover:bg-gold-light text-navy font-bold text-sm px-5 py-2.5 rounded-lg opacity-0 transition-colors duration-200">
                Get Started
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>

            {{-- Mobile hamburger --}}
            <button id="menu-btn" class="md:hidden text-white/80 hover:text-white focus:outline-none transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Mobile dropdown (glassmorphism, outside pill) --}}
        <div id="mobile-menu" class="hidden md:hidden mt-2 mx-2 rounded-2xl overflow-hidden"
             style="background:rgba(8,15,28,0.92);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.08);">
            <div class="flex flex-col p-4 gap-1">
                <a href="#about"     class="text-white/75 hover:text-gold text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-white/5 transition-all duration-200">About</a>
                <a href="#services"  class="text-white/75 hover:text-gold text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-white/5 transition-all duration-200">Services</a>
                <a href="#plans"     class="text-white/75 hover:text-gold text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-white/5 transition-all duration-200">Plans</a>
                <a href="#portfolio" class="text-white/75 hover:text-gold text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-white/5 transition-all duration-200">Portfolio</a>
                <a href="#contact"   class="mt-2 bg-gold text-navy font-bold text-sm text-center px-4 py-2.5 rounded-xl">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div id="page-wrapper">
        @yield('content')
        {{-- Spacer so fixed footer doesn't overlap last section content.
             Height is set dynamically by footer-reveal.js once footer renders. --}}
        <div id="footer-spacer"></div>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         FOOTER — fixed behind page content (unpeel reveal)
         ═══════════════════════════════════════════════════════ -->
    <footer id="site-footer" class="text-white" style="background-color:#111D33;">

        {{-- ── Flowing organic wave top border ── --}}
        <div class="footer-wave-top" aria-hidden="true">
            {{--
                The SVG is 300% wide (3× tiled wave cycle).
                The CSS translateX animations shift each layer by -33.333%
                for a seamless, infinite horizontal glide.
            --}}
            <svg class="footer-wave-svg" viewBox="0 0 4320 72"
                 preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                {{-- Teal ambient glow layer (behind) --}}
                <path class="wave-teal"
                    fill="rgba(42,157,143,0.22)"
                    d="M0,42
                       C180,14 360,66 540,42 C720,14 900,66 1080,42 C1260,14 1380,56 1440,42
                       C1620,14 1800,66 1980,42 C2160,14 2340,66 2520,42 C2700,14 2820,56 2880,42
                       C3060,14 3240,66 3420,42 C3600,14 3780,66 3960,42 C4140,14 4260,56 4320,42
                       L4320,72 L0,72 Z"/>
                {{-- Main footer-color wave (front, fills footer bg upward) --}}
                <path class="wave-main"
                    fill="#111D33"
                    d="M0,36
                       C200,8  400,60 600,32 C800,5  1000,58 1200,32 C1340,14 1400,46 1440,36
                       C1640,8  1840,60 2040,32 C2240,5  2440,58 2640,32 C2780,14 2840,46 2880,36
                       C3080,8  3280,60 3480,32 C3680,5  3880,58 4080,32 C4220,14 4280,46 4320,36
                       L4320,72 L0,72 Z"/>
            </svg>
        </div>

        {{-- ── Main columns ── --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-12">

                {{-- Column 1: Brand --}}
                <div id="footer-col-1" class="footer-col">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gold rounded-md flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-navy" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2L2 7v11h5v-6h6v6h5V7L10 2z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-lg">VisionBridge <span class="text-gold">Solutions</span></span>
                    </div>
                    <p class="text-white/60 text-sm leading-relaxed">Building Websites. Expanding Reach.<br>Helping organizations establish a professional online presence.</p>
                </div>

                {{-- Column 2: Quick Links --}}
                <div id="footer-col-2" class="footer-col">
                    <h4 class="font-semibold text-gold mb-4">Quick Links</h4>
                    <ul class="space-y-3 text-sm text-white/60">
                        <li><a href="#about"     class="footer-link hover:text-gold">About Us<span class="footer-link-bar"></span></a></li>
                        <li><a href="#services"  class="footer-link hover:text-gold">Services<span class="footer-link-bar"></span></a></li>
                        <li><a href="#plans"     class="footer-link hover:text-gold">Maintenance Plans<span class="footer-link-bar"></span></a></li>
                        <li><a href="#portfolio" class="footer-link hover:text-gold">Portfolio<span class="footer-link-bar"></span></a></li>
                        <li><a href="#contact"   class="footer-link hover:text-gold">Contact<span class="footer-link-bar"></span></a></li>
                    </ul>
                </div>

                {{-- Column 3: Contact --}}
                <div id="footer-col-3" class="footer-col">
                    <h4 class="font-semibold text-gold mb-4">Contact</h4>
                    <ul class="space-y-3 text-sm text-white/60">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:info@visionbridgesolutions.com" class="footer-link hover:text-gold">
                                info@visionbridgesolutions.com<span class="footer-link-bar"></span>
                            </a>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <a href="tel:5550000000" class="footer-link hover:text-gold">
                                (555) 000-0000<span class="footer-link-bar"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div id="footer-bottom" class="footer-bottom-bar border-t border-white/10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-white/40">
                <p>&copy; {{ date('Y') }} VisionBridge Solutions. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="footer-link hover:text-gold">Privacy Policy<span class="footer-link-bar"></span></a>
                    <a href="#" class="footer-link hover:text-gold">Terms of Service<span class="footer-link-bar"></span></a>
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

    <!-- Nav Interactions (floating pill, hide/reveal, capsule hover, magnetic CTA) -->
    <script defer>
    (function () {
        function initNav() {
            if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
                setTimeout(initNav, 80); return;
            }

            const navbar   = document.getElementById('navbar');
            const inner    = document.getElementById('nav-inner');
            const logo     = document.getElementById('nav-logo');
            const cta      = document.getElementById('nav-cta');
            const navLinks = document.getElementById('nav-links');
            const cursor   = document.getElementById('nav-cursor');
            const linkEls  = navLinks ? Array.from(navLinks.querySelectorAll('a')) : [];

            // ── Entry: logo → links stagger → CTA ──────────────────────
            gsap.timeline({ delay: 0.15 })
                .fromTo(logo,    { opacity:0, y:-14 }, { opacity:1, y:0, duration:0.55, ease:'power3.out' })
                .fromTo(linkEls, { opacity:0, y:-10 }, { opacity:1, y:0, duration:0.45, stagger:0.08, ease:'power2.out' }, '-=0.28')
                .fromTo(cta,     { opacity:0, y:-10 }, { opacity:1, y:0, duration:0.40, ease:'power2.out' }, '-=0.20');

            // ── Transparent → pill on scroll ────────────────────────────
            ScrollTrigger.create({
                start:       'top -50',
                onEnter:     () => inner && inner.classList.add('nav-pill'),
                onLeaveBack: () => {
                    inner && inner.classList.remove('nav-pill');
                    gsap.to(navbar, { y:0, duration:0.35, ease:'power3.out', overwrite:true });
                },
            });

            // ── Smart hide/reveal on scroll direction ────────────────────
            let lastY = 0, ticking = false;
            window.addEventListener('scroll', () => {
                if (ticking) return;
                ticking = true;
                requestAnimationFrame(() => {
                    const y = window.scrollY;
                    if (y < 80) {
                        gsap.to(navbar, { y:0, duration:0.35, ease:'power3.out', overwrite:true });
                    } else if (y > lastY + 6) {
                        gsap.to(navbar, { y:-110, duration:0.40, ease:'power2.in', overwrite:true });
                    } else if (y < lastY - 4) {
                        gsap.to(navbar, { y:0, duration:0.40, ease:'power3.out', overwrite:true });
                    }
                    lastY   = y;
                    ticking = false;
                });
            }, { passive: true });

            // ── Sliding capsule across desktop links ─────────────────────
            if (navLinks && cursor) {
                linkEls.forEach(link => {
                    link.addEventListener('mouseenter', () => {
                        const lr = link.getBoundingClientRect();
                        const nr = navLinks.getBoundingClientRect();
                        gsap.to(cursor, {
                            x: lr.left - nr.left - 8,
                            width: lr.width + 16,
                            opacity: 1,
                            duration: 0.26,
                            ease: 'power2.out',
                        });
                    });
                });
                navLinks.addEventListener('mouseleave', () => {
                    gsap.to(cursor, { opacity:0, duration:0.20 });
                });
            }

            // ── Magnetic pull on CTA ─────────────────────────────────────
            if (cta) {
                cta.addEventListener('mousemove', e => {
                    const r  = cta.getBoundingClientRect();
                    const cx = (e.clientX - r.left  - r.width  / 2) * 0.24;
                    const cy = (e.clientY - r.top   - r.height / 2) * 0.24;
                    gsap.to(cta, { x:cx, y:cy, duration:0.35, ease:'power2.out' });
                });
                cta.addEventListener('mouseleave', () => {
                    gsap.to(cta, { x:0, y:0, duration:0.60, ease:'elastic.out(1,0.5)' });
                });
            }
        }
        initNav();
    })();
    </script>

        <!-- GSAP + ScrollTrigger -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>

    <!-- ═══════════════════════════════════════════════════════
         FOOTER ANIMATION — unpeel spacer + stagger entrance
         + GSAP underline micro-hovers on all footer links
         ═══════════════════════════════════════════════════════ -->
    <script defer>
    (function () {
        function initFooter() {
            if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
                setTimeout(initFooter, 80); return;
            }

            const footer  = document.getElementById('site-footer');
            const spacer  = document.getElementById('footer-spacer');
            const cols    = ['footer-col-1', 'footer-col-2', 'footer-col-3'].map(id => document.getElementById(id));
            const bottom  = document.getElementById('footer-bottom');

            // ── 1. Spacer: keep bottom of page-wrapper = footer height ──
            function syncSpacer() {
                if (footer && spacer) {
                    // Include the wave overhang so content fully clears footer
                    spacer.style.height = footer.offsetHeight + 'px';
                    ScrollTrigger.refresh();
                }
            }
            syncSpacer();
            window.addEventListener('resize', syncSpacer, { passive: true });

            // ── 2. Staggered column entrance (trigger on spacer entering viewport) ──
            if (spacer && cols.every(Boolean) && bottom) {
                // Set initial hidden state in JS (keeps CSS clean of layout-affecting props)
                gsap.set(cols,   { opacity: 0, y: 38 });
                gsap.set(bottom, { opacity: 0 });

                ScrollTrigger.create({
                    trigger: spacer,
                    start:   'top 88%',
                    once:    true,
                    onEnter: () => {
                        gsap.timeline({ defaults: { ease: 'power3.out' } })
                            .to(cols, {
                                opacity:  1,
                                y:        0,
                                duration: 0.80,
                                stagger:  0.16,
                            })
                            .to(bottom, {
                                opacity:  1,
                                duration: 0.55,
                            }, '-=0.20');
                    },
                });
            }

            // ── 3. GSAP underline micro-hovers (center-outward draw) ──
            document.querySelectorAll('.footer-link').forEach(link => {
                const bar = link.querySelector('.footer-link-bar');
                if (!bar) return;

                link.addEventListener('mouseenter', () => {
                    gsap.killTweensOf([link, bar]);
                    // Slight horizontal nudge on the text + underline draws in
                    gsap.to(link, { x: 5, duration: 0.30, ease: 'power3.out' });
                    gsap.to(bar,  { scaleX: 1, duration: 0.34, ease: 'power3.out' });
                });

                link.addEventListener('mouseleave', () => {
                    gsap.killTweensOf([link, bar]);
                    gsap.to(link, { x: 0, duration: 0.45, ease: 'power3.out' });
                    gsap.to(bar,  { scaleX: 0, duration: 0.28, ease: 'power2.in' });
                });
            });
        }
        initFooter();
    })();
    </script>

    @yield('scripts')

</body>
</html>
