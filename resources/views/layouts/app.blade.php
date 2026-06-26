<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VisionBridge Solutions</title>
    <meta name="description" content="@yield('description', 'Custom websites designed to strengthen your brand, expand your reach, and protect your online presence.')">

    <!-- Favicon — gold square with navy house icon, matching the navbar logo -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'><rect width='20' height='20' rx='3' fill='%23C9A84C'/><path d='M10 2L2 7v11h5v-6h6v6h5V7L10 2z' fill='%232F3A45'/></svg>">

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
                        navy:  { DEFAULT: '#2F3A45', dark: '#1F2730', light: '#465360' },
                        gold:  { DEFAULT: '#C9A84C', light: '#DFC06A', dark: '#A8872E' },
                        teal:  { DEFAULT: '#2CA6A4', light: '#3FBDBB', dark: '#1F7A78' },
                        sky:   { DEFAULT: '#EAF3F8' },
                        lightgray: { DEFAULT: '#F8F9FA' },
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
        .nav-link { @apply text-sm font-medium transition-colors duration-200; color:rgba(47,58,69,0.75); }
        .nav-link:hover { color:#C9A84C; }
        .nav-link.is-active { color:#C9A84C !important; }

        /* ─── Re-usable buttons (outside hero) ─── */
        .btn-gold    { @apply inline-block bg-gold hover:bg-gold-dark text-navy font-semibold px-7 py-3 rounded-lg transition-all duration-200 shadow hover:shadow-lg; }
        .btn-outline { @apply inline-block border-2 border-navy text-navy hover:bg-navy hover:text-white font-semibold px-7 py-3 rounded-lg transition-all duration-200; }

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
            background:#C9A84C; color:#2F3A45; font-weight:700;
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
            border:1.5px solid rgba(47,58,69,.28); color:rgba(47,58,69,.85);
            font-weight:600; padding:15px 34px; border-radius:10px; font-size:1rem;
            background:rgba(255,255,255,.55);
            backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px);
            transition: transform .22s, box-shadow .22s, border-color .22s, background .22s;
            will-change: transform;
        }
        .hero-btn-secondary:hover { border-color:rgba(47,58,69,.5); background:rgba(255,255,255,.80); transform:translateY(-3px); box-shadow:0 8px 28px rgba(47,58,69,.14); }

        /* ─── Floating glassmorphism cards ─── */
        .float-card {
            position:absolute; pointer-events:none; z-index:3;
            background:rgba(255,255,255,.85); border:1px solid rgba(47,58,69,.08);
            box-shadow:0 8px 28px rgba(47,58,69,.10);
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
            background-image: radial-gradient(circle,rgba(47,58,69,.06) 1px,transparent 1px);
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

        /* ─── Video intro skip button ─── */
        #intro-skip:hover { background:rgba(255,255,255,0.16); border-color:rgba(201,168,76,0.75); }

        /* ─── Bridge cable divider — signature motif between sections ─── */
        .bridge-cable-divider {
            width:100%; max-width:640px; height:34px;
            margin:0 auto; color:#C9A84C; opacity:.55;
            pointer-events:none;
        }

        /* ─── Section progress rail — jump to any section, see how far's left ─── */
        #section-rail {
            position: fixed;
            right: 22px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 40;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
        }
        .rail-dot {
            position: relative;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: rgba(47,58,69,0.22);
            border: none;
            padding: 0;
            cursor: pointer;
            transition: background 0.25s ease, transform 0.25s ease;
        }
        .rail-dot:hover { transform: scale(1.3); background: rgba(201,168,76,0.55); }
        .rail-dot.is-active {
            background: #C9A84C;
            box-shadow: 0 0 0 4px rgba(201,168,76,0.18);
        }
        .rail-dot-label {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%) translateX(6px);
            white-space: nowrap;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            color: #2F3A45;
            background: rgba(255,255,255,0.92);
            padding: 4px 10px;
            border-radius: 6px;
            box-shadow: 0 4px 14px rgba(47,58,69,0.12);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .rail-dot:hover .rail-dot-label,
        .rail-dot.is-active .rail-dot-label {
            opacity: 1;
            transform: translateY(-50%) translateX(0);
        }
        @media (max-width: 1023px) {
            #section-rail { display: none; }
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
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);
            border-color: rgba(47,58,69,0.08);
            border-radius: 50px;
            box-shadow: 0 8px 36px rgba(47,58,69,0.16), 0 0 0 1px rgba(201,168,76,0.10);
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
            background: rgba(47,58,69,0.07);
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
            /* pointer-events:none here + auto on every real child (below) — without
               this, #page-wrapper's own empty box still intercepts clicks meant for
               the fixed footer underneath, even with the spacer itself passthrough. */
            pointer-events: none;
        }
        #page-wrapper > *:not(#footer-spacer) {
            pointer-events: auto;
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
            will-change: transform;
            opacity: 1;
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
            color: #2F3A45;
            margin-bottom: 10px;
            line-height: 1.30;
            transition: color 0.26s ease;
        }
        .value-card-outer:hover .value-title { color: #C9A84C; }

        .value-card-outer:hover .value-card-photo { transform: scale(1.07); }

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
        /* Gliding arrow beside the edge label */
        #hscroll-edge-arrow {
            display: flex;
            align-items: center;
            animation: edge-arrow-glide 2.4s cubic-bezier(0.45, 0, 0.55, 1) infinite;
            opacity: 0.55;
        }
        #hscroll-edge-arrow svg {
            filter: drop-shadow(0 0 4px rgba(201,168,76,0.45));
        }
        @keyframes edge-arrow-glide {
            0%   { transform: translateX(-6px); opacity: 0; }
            20%  { opacity: 0.55; }
            80%  { opacity: 0.55; }
            100% { transform: translateX(10px); opacity: 0; }
        }

        /* Track + bar are fixed to the viewport — must live outside overflow:hidden container */
        #hscroll-track {
            position: fixed;
            bottom: 24px;
            left: 0;
            right: 0;
            height: 3px;
            background: rgba(201,168,76,0.18);
            z-index: 9998;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.35s ease;
        }
        #hscroll-progress {
            position: fixed;
            bottom: 24px;
            left: 0;
            height: 3px;
            width: 0%;
            background: #C9A84C;
            z-index: 9999;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.35s ease;
            box-shadow: 0 0 8px 2px rgba(201,168,76,0.55);
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
        @media (max-width: 767px) {
            #hscroll-hint, #hscroll-track, #hscroll-progress { display: none; }
        }

        /* ── Portfolio section ── */
        .portfolio-card {
            border-radius: 18px;
            overflow: hidden;
            background: #FFFFFF;
            border: 1px solid rgba(17,29,51,0.07);
            box-shadow: 0 4px 20px rgba(17,29,51,0.06), 0 1px 4px rgba(17,29,51,0.04);
            transition: border-color 0.28s ease;
            will-change: transform;
            transform-style: preserve-3d;
            position: relative;
        }
        .portfolio-card:hover { border-color: rgba(201,168,76,0.30); }
        /* Browser chrome bar */
        .pf-chrome {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0 10px;
            height: 30px;
            background: rgba(17,29,51,0.88);
            backdrop-filter: blur(8px);
            flex-shrink: 0;
        }
        .pf-dots { display:flex; align-items:center; gap:4px; flex-shrink:0; }
        .pf-dots span { width:7px; height:7px; border-radius:50%; display:block; }
        .pf-urlbar {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 5px;
            height: 16px;
            background: rgba(255,255,255,0.08);
            border-radius: 4px;
            padding: 0 7px;
            font-size: 0.58rem;
            color: rgba(255,255,255,0.50);
            font-family: 'Inter', monospace;
            overflow: hidden;
            white-space: nowrap;
        }
        .pf-live-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #28C840;
            flex-shrink: 0;
            box-shadow: 0 0 5px rgba(40,200,64,0.70);
            animation: pf-pulse 2s ease-out infinite;
        }
        @keyframes pf-pulse {
            0%,100% { box-shadow: 0 0 4px rgba(40,200,64,0.70); }
            50%      { box-shadow: 0 0 10px rgba(40,200,64,0.90); }
        }
        /* "Visit Live Site" CTA button inside hover overlay */
        .pf-cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 0.78rem;
            font-weight: 700;
            color: #2F3A45;
            background: #C9A84C;
            padding: 9px 20px;
            border-radius: 30px;
            letter-spacing: 0.02em;
            transform: translateY(10px) scale(0.92);
            opacity: 0;
            transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1),
                        opacity 0.28s ease;
            transition-delay: 0.05s;
        }
        .portfolio-card:hover .pf-cta-btn {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
        /* Card footer */
        .pf-footer {
            padding: 16px 18px 18px;
            background: #FFFFFF;
        }
        .pf-title {
            font-weight: 700;
            font-size: 0.95rem;
            color: #2F3A45;
            line-height: 1.35;
            transition: color 0.22s ease;
        }
        .portfolio-card:hover .pf-title { color: #2CA6A4; }
        /* Live badge in footer */
        .pf-live-badge {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #28C840;
            box-shadow: 0 0 5px rgba(40,200,64,0.65);
            animation: pf-pulse 2s ease-out infinite;
        }
        /* Coming soon shimmer sweep */
        .pf-shimmer-sweep {
            position: absolute;
            inset: 0;
            background: linear-gradient(105deg,
                transparent 30%,
                rgba(255,255,255,0.18) 50%,
                transparent 70%);
            background-size: 200% 100%;
            animation: pf-shimmer 2.8s linear infinite;
        }
        @keyframes pf-shimmer {
            0%   { background-position: 200% center; }
            100% { background-position: -200% center; }
        }

        /* ── Portfolio card enhancements ── */
        /* Gold top-line drawn in on hover */
        .portfolio-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, #C9A84C 0%, rgba(42,157,143,0.75) 60%, #C9A84C 100%);
            background-size: 200% 100%;
            border-radius: 2px 2px 0 0;
            transform: scaleX(0); transform-origin: left;
            transition: transform 0.40s cubic-bezier(0.34,1.56,0.64,1); z-index: 2;
        }
        .portfolio-card:hover::before { transform: scaleX(1); }
        /* Cursor-following spotlight glow */
        .portfolio-card::after {
            content: ''; position: absolute; inset: 0; border-radius: inherit;
            background: radial-gradient(circle 200px at var(--mx,50%) var(--my,50%), rgba(201,168,76,0.09) 0%, transparent 70%);
            opacity: 0; transition: opacity 0.40s ease; pointer-events: none; z-index: 1;
        }
        .portfolio-card:hover::after { opacity: 1; }
        /* One-shot shimmer sweep on mouseenter */
        .pf-shimmer {
            position: absolute; inset: 0;
            background: linear-gradient(110deg, transparent 20%, rgba(255,255,255,0.26) 48%, rgba(201,168,76,0.09) 52%, transparent 80%);
            transform: translateX(-120%); pointer-events: none; z-index: 3; border-radius: inherit;
        }
        .portfolio-card.pf-shimmering .pf-shimmer { animation: pf-card-shine 0.72s cubic-bezier(0.4,0,0.2,1) forwards; }
        @keyframes pf-card-shine { to { transform: translateX(140%); } }

        /* ── Featured hero card (01) ── */
        .portfolio-card-featured {
            animation: pf-featured-border 5s ease-in-out infinite;
        }
        @keyframes pf-featured-border {
            0%,100% { border-color: rgba(201,168,76,0.18); }
            50%      { border-color: rgba(201,168,76,0.45); box-shadow: 0 8px 40px rgba(201,168,76,0.10), 0 4px 16px rgba(17,29,51,0.05); }
        }
        .pf-featured-body { display: flex; }
        .pf-featured-img  { flex: 0 0 58%; position: relative; overflow: hidden; }
        .pf-featured-img img {
            width: 100%; height: 100%;
            object-fit: cover; object-position: top;
            transition: transform 0.70s cubic-bezier(0.25,0.46,0.45,0.94);
        }
        .portfolio-card-featured:hover .pf-featured-img img { transform: scale(1.07); }
        .pf-featured-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(17,29,51,0.72) 0%, rgba(17,29,51,0.28) 100%);
            backdrop-filter: blur(2px);
            opacity: 0; transition: opacity 0.35s ease;
            display: flex; align-items: center; justify-content: center;
        }
        .portfolio-card-featured:hover .pf-featured-overlay { opacity: 1; }
        .pf-featured-details {
            flex: 1; padding: 32px 36px;
            display: flex; flex-direction: column; justify-content: space-between;
            background: #FFFFFF;
        }
        .pf-featured-num {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 0.22em;
            color: rgba(201,168,76,0.68); margin-bottom: 8px;
        }
        .pf-featured-separator {
            width: 32px; height: 1.5px;
            background: linear-gradient(90deg, #C9A84C, rgba(201,168,76,0.15));
            border-radius: 2px; margin-bottom: 14px;
            transition: width 0.40s cubic-bezier(0.34,1.56,0.64,1);
        }
        .portfolio-card-featured:hover .pf-featured-separator { width: 68px; }
        .pf-featured-title {
            font-size: 1.40rem; font-weight: 800; color: #2F3A45;
            line-height: 1.25; margin-bottom: 10px;
            transition: color 0.22s ease;
        }
        .portfolio-card-featured:hover .pf-featured-title { color: #2CA6A4; }
        .pf-featured-desc {
            font-size: 0.86rem; color: rgba(17,29,51,0.50);
            line-height: 1.65; margin-bottom: 18px;
        }
        /* Tag chips (used in featured and regular card footers) */
        .pf-tags { display: flex; flex-wrap: wrap; gap: 6px; }
        .pf-tag {
            font-size: 0.59rem; font-weight: 700; letter-spacing: 0.13em;
            text-transform: uppercase; color: rgba(17,29,51,0.52);
            background: rgba(17,29,51,0.05); border: 1px solid rgba(17,29,51,0.09);
            padding: 3px 9px; border-radius: 20px;
            transition: background 0.22s ease, border-color 0.22s ease, color 0.22s ease;
        }
        .portfolio-card:hover .pf-tag,
        .portfolio-card-featured:hover .pf-tag {
            background: rgba(201,168,76,0.08);
            border-color: rgba(201,168,76,0.30);
            color: rgba(201,168,76,0.90);
        }
        .pf-featured-footer {
            display: flex; align-items: center; justify-content: space-between;
            padding-top: 16px; border-top: 1px solid rgba(17,29,51,0.07); margin-top: 18px;
        }
        .pf-live-text {
            font-size: 0.60rem; font-weight: 700; letter-spacing: 0.14em;
            text-transform: uppercase; color: rgba(42,157,143,0.82);
        }
        .pf-visit-hint {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 0.72rem; font-weight: 700; color: rgba(201,168,76,0.80);
            opacity: 0; transform: translateX(-10px);
            transition: opacity 0.30s ease 0.08s, transform 0.30s ease 0.08s;
        }
        .portfolio-card-featured:hover .pf-visit-hint { opacity: 1; transform: translateX(0); }
        /* Stats bar */
        #pf-stats-bar { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; }
        .pf-stat-item { text-align: center; padding: 0 44px; }
        .pf-stats-divider { width: 1px; height: 38px; background: linear-gradient(180deg, transparent, rgba(17,29,51,0.12), transparent); }
        .pf-stat-num { font-size: 2.20rem; font-weight: 800; color: #2F3A45; line-height: 1; margin-bottom: 5px; }
        .pf-stat-label { font-size: 0.69rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(17,29,51,0.38); }
        @media (max-width: 767px) {
            .pf-featured-body  { flex-direction: column; }
            .pf-featured-img   { flex: 0 0 200px; }
            .pf-featured-details { padding: 22px; }
            .pf-featured-title { font-size: 1.10rem; }
            .pf-stat-item  { padding: 0 20px; }
            .pf-stat-num   { font-size: 1.65rem; }
        }

        /* ── Portfolio Detail Modal ── */
        #pf-modal-backdrop {
            position: fixed; inset: 0; z-index: 10000;
            background: rgba(10,18,38,0.74);
            backdrop-filter: blur(12px) saturate(0.80);
            display: flex; align-items: center; justify-content: center;
            padding: 24px;
            opacity: 0; pointer-events: none;
        }
        #pf-modal {
            position: relative; width: 100%; max-width: 940px;
            border-radius: 22px; overflow: hidden;
            background: #FFFFFF;
            box-shadow: 0 48px 120px rgba(17,29,51,0.30), 0 12px 40px rgba(17,29,51,0.14), 0 0 0 1px rgba(201,168,76,0.14);
            opacity: 0;
        }
        #pf-modal-close {
            position: absolute; top: 42px; right: 16px; z-index: 20;
            width: 36px; height: 36px; border-radius: 50%;
            background: rgba(255,255,255,0.14); border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.75);
            transition: background 0.22s ease, color 0.22s ease, transform 0.22s ease;
        }
        #pf-modal-close:hover { background: rgba(255,255,255,0.24); color: #fff; transform: rotate(90deg); }
        .pf-modal-chrome {
            height: 36px; padding: 0 14px;
            background: rgba(17,29,51,0.92);
            display: flex; align-items: center; gap: 10px; flex-shrink: 0;
        }
        .pf-modal-body { display: flex; height: 460px; }
        .pf-modal-img-panel {
            flex: 0 0 56%; position: relative; overflow: hidden;
            background: linear-gradient(135deg, #111D33 0%, #1A2A42 100%);
        }
        .pf-modal-img-panel img { width: 100%; height: 100%; object-fit: cover; object-position: top; }
        .pf-modal-no-image {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.72rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase;
            color: rgba(255,255,255,0.28);
        }
        .pf-modal-details {
            flex: 1; padding: 34px 36px 28px;
            display: flex; flex-direction: column; overflow-y: auto;
        }
        .pf-modal-num {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 0.22em;
            color: rgba(201,168,76,0.68); margin-bottom: 6px;
        }
        .pf-modal-category-badge {
            display: inline-block;
            font-size: 0.58rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase;
            color: rgba(17,29,51,0.60); background: rgba(17,29,51,0.07);
            padding: 3px 10px; border-radius: 20px; border: 1px solid rgba(17,29,51,0.10);
            margin-bottom: 14px; width: fit-content;
        }
        .pf-modal-separator {
            width: 36px; height: 1.5px;
            background: linear-gradient(90deg, #C9A84C, rgba(201,168,76,0.15));
            border-radius: 2px; margin-bottom: 14px;
        }
        .pf-modal-title { font-size: 1.42rem; font-weight: 800; color: #2F3A45; line-height: 1.22; margin-bottom: 11px; }
        .pf-modal-desc  { font-size: 0.85rem; color: rgba(17,29,51,0.50); line-height: 1.70; margin-bottom: 16px; }
        .pf-modal-domain {
            font-size: 0.68rem; color: rgba(17,29,51,0.32);
            font-family: 'Inter', monospace; margin-bottom: 20px;
            display: flex; align-items: center; gap: 6px;
        }
        .pf-modal-cta {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 0.82rem; font-weight: 700; color: #2F3A45;
            background: #C9A84C; padding: 11px 24px; border-radius: 30px;
            text-decoration: none; letter-spacing: 0.02em; align-self: flex-start; margin-top: auto;
            transition: background 0.22s ease, transform 0.22s ease;
        }
        .pf-modal-cta:hover { background: #B8962E; transform: translateY(-2px); }
        .pf-modal-cta-soon {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 0.82rem; font-weight: 700; color: rgba(17,29,51,0.30);
            background: rgba(17,29,51,0.06); padding: 11px 24px; border-radius: 30px;
            letter-spacing: 0.02em; cursor: default; align-self: flex-start; margin-top: auto;
        }
        @media (max-width: 640px) {
            .pf-modal-body { flex-direction: column; height: auto; max-height: 82vh; overflow-y: auto; }
            .pf-modal-img-panel { flex: 0 0 180px; }
            .pf-modal-details { padding: 22px; }
            .pf-modal-title { font-size: 1.10rem; }
        }

        /* ── Services section ── */
        /* Gold accent line drawn by GSAP on scroll-in */
        #services-accent-line {
            height: 2px;
            width: 56px;
            margin: 16px auto 20px;
            background: linear-gradient(90deg, #C9A84C, rgba(201,168,76,0.18));
            border-radius: 2px;
            transform-origin: left center;
        }
        /* Card base — GSAP controls transform + shadow; CSS handles border & decorative */
        .services-card {
            transition: border-color 0.30s ease;
            will-change: transform;
            transform-style: preserve-3d;
        }
        .services-card:hover {
            border-color: rgba(201,168,76,0.42) !important;
        }
        /* Gold top-line accent that draws in on hover */
        .services-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2.5px;
            background: linear-gradient(90deg, #C9A84C 0%, rgba(42,157,143,0.75) 60%, #C9A84C 100%);
            background-size: 200% 100%;
            border-radius: 2px 2px 0 0;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.40s cubic-bezier(0.34,1.56,0.64,1);
            animation: none;
            z-index: 2;
        }
        .services-card:hover::before {
            transform: scaleX(1);
            animation: svc-line-shimmer 1.8s linear 0.4s infinite;
        }
        @keyframes svc-line-shimmer {
            0%   { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        /* Mouse spotlight that follows cursor */
        .services-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: radial-gradient(circle 160px at var(--mx,50%) var(--my,50%), rgba(201,168,76,0.10) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.40s ease;
            pointer-events: none;
            z-index: 1;
        }
        .services-card:hover::after { opacity: 1; }
        /* Single shimmer sweep on hover entry */
        .svc-shimmer {
            position: absolute;
            inset: 0;
            background: linear-gradient(110deg,
                transparent 20%,
                rgba(255,255,255,0.28) 48%,
                rgba(201,168,76,0.10) 52%,
                transparent 80%);
            transform: translateX(-120%);
            pointer-events: none;
            z-index: 3;
            border-radius: inherit;
        }
        .services-card.svc-shimmering .svc-shimmer {
            animation: svc-card-shine 0.70s cubic-bezier(0.4,0,0.2,1) forwards;
        }
        @keyframes svc-card-shine {
            to { transform: translateX(140%); }
        }
        /* Gold underline that draws under the title */
        .svc-title-line {
            display: block;
            height: 1.5px;
            width: 100%;
            background: linear-gradient(90deg, #C9A84C, rgba(42,157,143,0.65));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.38s cubic-bezier(0.34,1.56,0.64,1) 0.05s;
            border-radius: 1px;
            margin-top: 5px;
            margin-bottom: 2px;
        }
        .services-card:hover .svc-title-line { transform: scaleX(1); }
        /* Image overlay: richer gradient + gold arrow */
        .svc-img-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top,
                rgba(17,29,51,0.72) 0%,
                rgba(17,29,51,0.18) 50%,
                transparent 100%);
            opacity: 0;
            transition: opacity 0.38s ease;
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;
            padding: 12px;
        }
        .services-card:hover .svc-img-overlay { opacity: 1; }
        /* Arrow circle + pulsing ring */
        .svc-arrow {
            position: relative;
            width: 34px; height: 34px;
            border-radius: 50%;
            background: #C9A84C;
            display: flex; align-items: center; justify-content: center;
            transform: translate(10px, 10px) scale(0.65);
            opacity: 0;
            transition: transform 0.36s cubic-bezier(0.34,1.56,0.64,1),
                        opacity 0.28s ease;
            transition-delay: 0.08s;
            flex-shrink: 0;
        }
        .services-card:hover .svc-arrow {
            transform: translate(0,0) scale(1);
            opacity: 1;
        }
        .svc-arrow-ring {
            position: absolute;
            inset: -5px;
            border-radius: 50%;
            border: 1.5px solid rgba(201,168,76,0.55);
            transform: scale(0.6);
            opacity: 0;
            transition: transform 0.40s cubic-bezier(0.34,1.56,0.64,1) 0.18s,
                        opacity 0.30s ease 0.18s;
        }
        .services-card:hover .svc-arrow-ring {
            transform: scale(1);
            opacity: 1;
            animation: svc-ring-pulse 1.6s ease-in-out 0.45s infinite;
        }
        @keyframes svc-ring-pulse {
            0%, 100% { transform: scale(1);    opacity: 0.80; }
            55%       { transform: scale(1.55); opacity: 0; }
        }

        /* ── Services toggle button ── */
        #svc-toggle-btn {
            position: relative;
            overflow: hidden;
            transition: transform 0.32s cubic-bezier(0.34,1.56,0.64,1),
                        box-shadow 0.32s ease,
                        border-color 0.32s ease;
            will-change: transform;
            animation: svc-btn-glow 3.2s ease-in-out infinite;
        }
        /* Shimmer sweep on hover */
        #svc-toggle-btn::before {
            content: '';
            position: absolute;
            top: -50%; left: -80%;
            width: 48%; height: 200%;
            background: linear-gradient(90deg, transparent, rgba(201,168,76,0.30), transparent);
            transform: skewX(-18deg);
            pointer-events: none;
        }
        #svc-toggle-btn:hover::before {
            animation: svc-shine 0.55s ease forwards;
        }
        @keyframes svc-shine {
            0%   { left: -80%; opacity: 0; }
            6%   { opacity: 1; }
            100% { left: 160%; opacity: 0; }
        }
        #svc-toggle-btn:hover {
            transform: translateY(-4px) scale(1.04);
            box-shadow: 0 0 32px rgba(201,168,76,0.25), 0 10px 28px rgba(17,29,51,0.30);
            border-color: rgba(201,168,76,0.60) !important;
        }
        #svc-toggle-btn:active {
            transform: translateY(0) scale(0.97);
            transition-duration: 0.12s;
        }
        /* Idle border-glow pulse */
        @keyframes svc-btn-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(201,168,76,0); }
            50%       { box-shadow: 0 0 20px 4px rgba(201,168,76,0.16); }
        }
        #svc-toggle-btn #svc-toggle-icon {
            transition: transform 0.40s cubic-bezier(0.34,1.56,0.64,1);
        }

        /* ── Nav scroll-spy active dot ── */
        #nav-active-dot {
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #C9A84C;
            box-shadow: 0 0 7px rgba(201,168,76,0.75), 0 0 14px rgba(201,168,76,0.30);
            opacity: 0;
            pointer-events: none;
            will-change: transform, opacity;
        }
        /* Mobile active link */
        #mobile-menu a.is-active {
            color: #C9A84C !important;
            background: rgba(201,168,76,0.08);
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-white">

    {{-- Full-page video intro — homepage only. Plays once, then shrinks
         away (gravity-pull) to reveal the site. See #intro-overlay script
         further down for behavior. --}}
    @if (request()->routeIs('home'))
        <div id="intro-overlay" style="position:fixed;inset:0;z-index:9999;background:#000;overflow:hidden;">
            <video id="intro-video" autoplay muted playsinline preload="auto"
                   style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                <source src="{{ asset('videos/Web_development_company_hero_video.mp4') }}" type="video/mp4">
            </video>
            <button id="intro-skip" type="button"
                    style="position:fixed;bottom:24px;right:24px;z-index:10000;display:inline-flex;align-items:center;gap:6px;
                           background:rgba(255,255,255,0.08);border:1px solid rgba(201,168,76,0.45);color:#DFC06A;
                           font-size:0.8rem;font-weight:600;letter-spacing:0.04em;padding:10px 18px;border-radius:30px;
                           backdrop-filter:blur(10px);cursor:pointer;transition:background 0.2s ease,border-color 0.2s ease;">
                Skip Intro
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    @endif

    {{-- Section anchors only exist on the homepage; from other pages, link back home first --}}
    @php $homeAnchor = request()->routeIs('home') ? '' : route('home'); @endphp

    <!-- Navigation -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50" style="padding:12px 16px 0;will-change:transform;">

        {{-- Floating pill inner wrapper --}}
        <div id="nav-inner" class="mx-auto flex items-center justify-between px-5 sm:px-7" style="height:60px;">

            {{-- Logo --}}
            <a id="nav-logo" href="{{ $homeAnchor }}#hero" class="flex items-center gap-2.5 shrink-0 opacity-0">
                <div class="w-8 h-8 bg-gold rounded-md flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-navy" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L2 7v11h5v-6h6v6h5V7L10 2z"/>
                    </svg>
                </div>
                <span class="text-navy font-bold text-lg leading-tight">VisionBridge<br>
                    <span class="text-gold text-xs font-medium tracking-widest uppercase">Solutions</span>
                </span>
            </a>

            {{-- Desktop links with sliding capsule --}}
            <div id="nav-links" class="hidden md:flex items-center gap-0.5 relative">
                <div id="nav-cursor"></div>
                <div id="nav-active-dot"></div>
                <a href="{{ $homeAnchor }}#about"     class="nav-link relative z-10 px-4 py-2 opacity-0">About</a>
                <a href="{{ $homeAnchor }}#services"  class="nav-link relative z-10 px-4 py-2 opacity-0">Services</a>
                <a href="{{ $homeAnchor }}#plans"     class="nav-link relative z-10 px-4 py-2 opacity-0">Plans</a>
                <a href="{{ $homeAnchor }}#portfolio" class="nav-link relative z-10 px-4 py-2 opacity-0">Portfolio</a>
            </div>

            {{-- Desktop CTA --}}
            <div class="hidden md:flex items-center gap-4">
                <a id="nav-login" href="{{ route('login') }}" class="nav-link relative z-10 opacity-0">Client Login</a>
                <a id="nav-cta" href="{{ $homeAnchor }}#contact"
                   class="nav-cta-btn inline-flex items-center gap-2 bg-gold hover:bg-gold-light text-navy font-bold text-sm px-5 py-2.5 rounded-lg opacity-0 transition-colors duration-200">
                    Get Started
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            {{-- Mobile hamburger --}}
            <button id="menu-btn" class="md:hidden text-navy/70 hover:text-navy focus:outline-none transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Mobile dropdown (glassmorphism, outside pill) --}}
        <div id="mobile-menu" class="hidden md:hidden mt-2 mx-2 rounded-2xl overflow-hidden"
             style="background:rgba(255,255,255,0.96);backdrop-filter:blur(20px);border:1px solid rgba(47,58,69,0.08);box-shadow:0 8px 32px rgba(47,58,69,0.14);">
            <div class="flex flex-col p-4 gap-1">
                <a href="{{ $homeAnchor }}#about"     class="text-navy/75 hover:text-gold text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-navy/5 transition-all duration-200">About</a>
                <a href="{{ $homeAnchor }}#services"  class="text-navy/75 hover:text-gold text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-navy/5 transition-all duration-200">Services</a>
                <a href="{{ $homeAnchor }}#plans"     class="text-navy/75 hover:text-gold text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-navy/5 transition-all duration-200">Plans</a>
                <a href="{{ $homeAnchor }}#portfolio" class="text-navy/75 hover:text-gold text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-navy/5 transition-all duration-200">Portfolio</a>
                <a href="{{ route('login') }}" class="text-navy/75 hover:text-gold text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-navy/5 transition-all duration-200">Client Login</a>
                <a href="{{ $homeAnchor }}#contact"   class="mt-2 bg-gold text-navy font-bold text-sm text-center px-4 py-2.5 rounded-xl">Get Started</a>
            </div>
        </div>
    </nav>

    {{-- Section progress rail — homepage only (targets homepage section IDs) --}}
    @if (request()->routeIs('home'))
        <nav id="section-rail" aria-label="Page sections">
            @foreach ([
                ['id' => 'hero',        'label' => 'Home'],
                ['id' => 'about',       'label' => 'About'],
                ['id' => 'services',    'label' => 'Services'],
                ['id' => 'why',         'label' => 'Why Us'],
                ['id' => 'plans',       'label' => 'Plans'],
                ['id' => 'portfolio',   'label' => 'Portfolio'],
                ['id' => 'partnership', 'label' => 'Partnership'],
                ['id' => 'contact',     'label' => 'Contact'],
            ] as $rail)
                <button type="button" class="rail-dot" data-rail-target="{{ $rail['id'] }}" aria-label="Jump to {{ $rail['label'] }}">
                    <span class="rail-dot-label">{{ $rail['label'] }}</span>
                </button>
            @endforeach
        </nav>

        {{-- Flying plane-over-bridge page transition — covers the screen,
             jumps scroll position invisibly behind it, then reveals. --}}
        <div id="flight-transition" style="position:fixed;inset:0;z-index:9990;opacity:0;pointer-events:none;background:#EAF3F8;overflow:hidden;">
            <div style="position:absolute;bottom:8%;left:0;right:0;height:140px;color:#2F3A45;opacity:0.5;">
                <svg viewBox="0 0 1200 220" preserveAspectRatio="none" width="100%" height="100%" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <line x1="60" y1="170" x2="1140" y2="170" stroke-width="3" stroke-linecap="round"/>
                    <line x1="350" y1="20" x2="320" y2="170" stroke-width="4" stroke-linecap="round"/>
                    <line x1="350" y1="20" x2="380" y2="170" stroke-width="4" stroke-linecap="round"/>
                    <line x1="850" y1="20" x2="820" y2="170" stroke-width="4" stroke-linecap="round"/>
                    <line x1="850" y1="20" x2="880" y2="170" stroke-width="4" stroke-linecap="round"/>
                    <line x1="350" y1="20" x2="70"  y2="170" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="350" y1="20" x2="150" y2="170" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="350" y1="20" x2="230" y2="170" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="350" y1="20" x2="300" y2="170" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="350" y1="20" x2="400" y2="170" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="350" y1="20" x2="470" y2="170" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="350" y1="20" x2="540" y2="170" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="350" y1="20" x2="610" y2="170" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="850" y1="20" x2="660" y2="170" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="850" y1="20" x2="730" y2="170" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="850" y1="20" x2="800" y2="170" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="850" y1="20" x2="900" y2="170" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="850" y1="20" x2="970" y2="170" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="850" y1="20" x2="1040" y2="170" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="850" y1="20" x2="1110" y2="170" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
            <div id="flight-plane" style="position:absolute;top:38%;left:0;width:64px;height:64px;color:#C9A84C;">
                <svg viewBox="0 0 24 24" width="100%" height="100%" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                </svg>
            </div>
        </div>
    @endif

    <!-- Page Content -->
    <div id="page-wrapper">
        @yield('content')
        {{-- Spacer so fixed footer doesn't overlap last section content.
             Height is set dynamically by footer-reveal.js once footer renders.
             pointer-events:none so clicks pass through to the fixed footer
             underneath it instead of being swallowed by this invisible div. --}}
        <div id="footer-spacer" style="pointer-events:none;"></div>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         FOOTER — fixed behind page content (unpeel reveal)
         ═══════════════════════════════════════════════════════ -->
    <footer id="site-footer" class="text-navy" style="background-color:#F8F9FA;">

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
                    fill="rgba(44,166,164,0.22)"
                    d="M0,42
                       C180,14 360,66 540,42 C720,14 900,66 1080,42 C1260,14 1380,56 1440,42
                       C1620,14 1800,66 1980,42 C2160,14 2340,66 2520,42 C2700,14 2820,56 2880,42
                       C3060,14 3240,66 3420,42 C3600,14 3780,66 3960,42 C4140,14 4260,56 4320,42
                       L4320,72 L0,72 Z"/>
                {{-- Main footer-color wave (front, fills footer bg upward) --}}
                <path class="wave-main"
                    fill="#F8F9FA"
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
                    <p class="text-navy/60 text-sm leading-relaxed">Building Websites. Expanding Reach.<br>Helping organizations establish a professional online presence.</p>
                </div>

                {{-- Column 2: Quick Links --}}
                <div id="footer-col-2" class="footer-col">
                    <h4 class="font-semibold text-gold mb-4">Quick Links</h4>
                    <ul class="space-y-3 text-sm text-navy/60">
                        <li><a href="{{ $homeAnchor }}#about"     class="footer-link hover:text-gold">About Us<span class="footer-link-bar"></span></a></li>
                        <li><a href="{{ $homeAnchor }}#services"  class="footer-link hover:text-gold">Services<span class="footer-link-bar"></span></a></li>
                        <li><a href="{{ $homeAnchor }}#plans"     class="footer-link hover:text-gold">Maintenance Plans<span class="footer-link-bar"></span></a></li>
                        <li><a href="{{ $homeAnchor }}#portfolio" class="footer-link hover:text-gold">Portfolio<span class="footer-link-bar"></span></a></li>
                        <li><a href="{{ $homeAnchor }}#contact"   class="footer-link hover:text-gold">Contact<span class="footer-link-bar"></span></a></li>
                    </ul>
                </div>

                {{-- Column 3: Contact --}}
                <div id="footer-col-3" class="footer-col">
                    <h4 class="font-semibold text-gold mb-4">Contact</h4>
                    <ul class="space-y-3 text-sm text-navy/60">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:support@visionbridgesolutions.com" class="footer-link hover:text-gold">
                                support@visionbridgesolutions.com<span class="footer-link-bar"></span>
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
            <div id="footer-bottom" class="footer-bottom-bar border-t border-navy/10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-navy/40">
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
            const login    = document.getElementById('nav-login');
            const navLinks = document.getElementById('nav-links');
            const cursor   = document.getElementById('nav-cursor');
            const linkEls  = navLinks ? Array.from(navLinks.querySelectorAll('a')) : [];

            // ── Entry: logo → links stagger → CTA ──────────────────────
            gsap.timeline({ delay: 0.15 })
                .fromTo(logo,        { opacity:0, y:-14 }, { opacity:1, y:0, duration:0.55, ease:'power3.out' })
                .fromTo(linkEls,     { opacity:0, y:-10 }, { opacity:1, y:0, duration:0.45, stagger:0.08, ease:'power2.out' }, '-=0.28')
                .fromTo([login, cta],{ opacity:0, y:-10 }, { opacity:1, y:0, duration:0.40, stagger:0.08, ease:'power2.out' }, '-=0.20');

            // ── Transparent → pill on scroll ────────────────────────────
            ScrollTrigger.create({
                start:       'top -50',
                onEnter:     () => inner && inner.classList.add('nav-pill'),
                onLeaveBack: () => {
                    inner && inner.classList.remove('nav-pill');
                    gsap.to(navbar, { y:0, duration:0.35, ease:'power3.out', overwrite:true });
                },
            });


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

    <!-- Scroll-spy: highlights the active nav link as the user scrolls -->
    <script defer>
    (function () {
        function initScrollSpy() {
            if (typeof gsap === 'undefined') { setTimeout(initScrollSpy, 80); return; }

            const navLinksWrap = document.getElementById('nav-links');
            const dot          = document.getElementById('nav-active-dot');

            // Map section IDs → the nav href that should become active.
            // Sections without a dedicated nav link inherit from the nearest section.
            const idToHref = {
                hero:        null,          // above the fold — no link lit
                welcome:     '#about',
                about:       '#about',
                services:    '#services',
                why:         '#services',   // "Why VisionBridge" lives in the services area
                plans:       '#plans',
                portfolio:   '#portfolio',
                partnership: '#portfolio',
                contact:     null,          // CTA button, not a nav-link
            };

            // Collect all trackable elements that exist in the DOM
            const trackedEls = Object.keys(idToHref)
                .map(id => document.getElementById(id))
                .filter(Boolean);

            // Desktop links keyed by href
            const desktopLinks = {};
            if (navLinksWrap) {
                navLinksWrap.querySelectorAll('a.nav-link').forEach(a => {
                    desktopLinks[a.getAttribute('href')] = a;
                });
            }

            // Mobile links keyed by href
            const mobileLinks = {};
            const mobileMenu  = document.getElementById('mobile-menu');
            if (mobileMenu) {
                mobileMenu.querySelectorAll('a').forEach(a => {
                    mobileLinks[a.getAttribute('href')] = a;
                });
            }

            let currentHref = null;

            function moveDot(linkEl) {
                if (!dot || !navLinksWrap || !linkEl) return;
                const lr      = linkEl.getBoundingClientRect();
                const nr      = navLinksWrap.getBoundingClientRect();
                const centerX = lr.left - nr.left + lr.width / 2 - 2; // center minus half-dot
                gsap.to(dot, { x: centerX, opacity: 1, duration: 0.38, ease: 'power2.out', overwrite: true });
            }

            function setActive(href) {
                if (href === currentHref) return;
                currentHref = href;

                Object.values(desktopLinks).forEach(a => a.classList.remove('is-active'));
                Object.values(mobileLinks).forEach(a  => a.classList.remove('is-active'));

                if (!href) {
                    if (dot) gsap.to(dot, { opacity: 0, duration: 0.22, overwrite: true });
                    return;
                }

                const dLink = desktopLinks[href];
                if (dLink) { dLink.classList.add('is-active'); moveDot(dLink); }
                else if (dot) gsap.to(dot, { opacity: 0, duration: 0.22, overwrite: true });

                const mLink = mobileLinks[href];
                if (mLink) mLink.classList.add('is-active');
            }

            // IntersectionObserver with a detection band in the upper-middle viewport.
            // rootMargin '-28% 0px -52% 0px' means a section is "active" when its top
            // edge is between 28% and 48% down from the viewport top.
            const observer = new IntersectionObserver(entries => {
                let best = null;
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        if (!best || entry.intersectionRatio > best.intersectionRatio) best = entry;
                    }
                });
                if (best) setActive(idToHref[best.target.id] ?? null);
            }, {
                rootMargin: '-28% 0px -52% 0px',
                threshold:  [0, 0.1, 0.25, 0.5],
            });

            trackedEls.forEach(el => observer.observe(el));

            // Clicking a link immediately marks it active before scroll settles
            [...Object.values(desktopLinks), ...Object.values(mobileLinks)].forEach(a => {
                a.addEventListener('click', () => {
                    const href = a.getAttribute('href');
                    if (href && href.startsWith('#')) setActive(href);
                });
            });
        }
        initScrollSpy();
    })();
    </script>

    {{-- Flying plane-over-bridge transition — covers the jump between
         sections instead of a visible fast-scroll. Triggers on every
         in-page anchor click (nav, mobile menu, footer Quick Links) plus
         the section-rail dots below. --}}
    <script defer>
    (function () {
        function initFlightTransition() {
            const overlay = document.getElementById('flight-transition');
            if (!overlay) return; // not on the homepage

            if (typeof gsap === 'undefined') { setTimeout(initFlightTransition, 80); return; }

            const plane = document.getElementById('flight-plane');
            let flying = false;

            window.flyTransition = function (targetEl) {
                if (!targetEl) return;
                if (!plane || flying) { targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' }); return; }
                flying = true;
                overlay.style.pointerEvents = 'all';

                gsap.timeline({ onComplete() { overlay.style.pointerEvents = 'none'; flying = false; } })
                    .to(overlay, { opacity: 1, duration: 0.25, ease: 'power2.out' })
                    .fromTo(plane, { x: '-20vw', y: 0 }, { x: '120vw', y: -50, duration: 2.2, ease: 'power1.inOut' }, 0.15)
                    .call(() => targetEl.scrollIntoView({ behavior: 'auto', block: 'start' }), null, 1.3)
                    .to(overlay, { opacity: 0, duration: 0.4, ease: 'power2.in' }, 2.1);
            };

            // Intercept every in-page anchor click site-wide (nav, mobile
            // menu, footer Quick Links) — only acts when the hash target
            // actually exists on the current page; otherwise the link
            // proceeds normally (e.g. navigating to the home page first).
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a[href*="#"]');
                if (!link) return;
                const hash = link.getAttribute('href').split('#')[1];
                if (!hash) return;
                const target = document.getElementById(hash);
                if (!target) return;
                e.preventDefault();
                window.flyTransition(target);
            });
        }
        initFlightTransition();
    })();
    </script>

    {{-- Section progress rail — click to jump, highlights as you scroll --}}
    <script defer>
    (function () {
        function initSectionRail() {
            const rail = document.getElementById('section-rail');
            if (!rail) return;

            const dots = Array.from(rail.querySelectorAll('.rail-dot'));
            const sections = dots
                .map(dot => ({ dot, el: document.getElementById(dot.dataset.railTarget) }))
                .filter(item => item.el);

            dots.forEach(dot => {
                dot.addEventListener('click', () => {
                    const target = document.getElementById(dot.dataset.railTarget);
                    if (target) {
                        if (window.flyTransition) window.flyTransition(target);
                        else target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });

            function setActive(id) {
                dots.forEach(dot => dot.classList.toggle('is-active', dot.dataset.railTarget === id));
            }

            const observer = new IntersectionObserver(entries => {
                let best = null;
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        if (!best || entry.intersectionRatio > best.intersectionRatio) best = entry;
                    }
                });
                if (best) setActive(best.target.id);
            }, {
                rootMargin: '-20% 0px -60% 0px',
                threshold: [0, 0.1, 0.25, 0.5],
            });

            sections.forEach(({ el }) => observer.observe(el));
        }
        initSectionRail();
    })();
    </script>

    {{-- Video intro — plays once, skip button, safety timeout, then
         shrinks away (gravity-pull) and signals home.blade.php's Hero
         entrance via a custom event so it animates in right after. --}}
    <script defer>
    (function () {
        function initIntro() {
            const overlay = document.getElementById('intro-overlay');
            if (!overlay) return; // not on the homepage

            if (typeof gsap === 'undefined') { setTimeout(initIntro, 80); return; }

            const video = document.getElementById('intro-video');
            const skip  = document.getElementById('intro-skip');
            let revealed = false;

            document.body.style.overflow = 'hidden';

            function revealSite() {
                if (revealed) return;
                revealed = true;
                gsap.to(overlay, {
                    scale: 0.06, opacity: 0, duration: 1, ease: 'power3.in',
                    onComplete() {
                        overlay.style.display = 'none';
                        document.body.style.overflow = '';
                        window.dispatchEvent(new CustomEvent('intro:complete'));
                    },
                });
            }

            if (video) {
                video.addEventListener('ended', revealSite);
                video.addEventListener('error', revealSite);
                video.play().catch(revealSite); // autoplay blocked → reveal immediately
            }
            if (skip) skip.addEventListener('click', revealSite);

            // Safety net: never trap a visitor on the intro
            setTimeout(revealSite, 12000);
        }
        initIntro();
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
