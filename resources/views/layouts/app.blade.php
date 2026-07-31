<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VisionBridge Solutions</title>
    <meta name="description" content="@yield('description', 'Custom websites designed to strengthen your brand, expand your reach, and protect your online presence.')">

    <!-- Favicon — VisionBridge logo mark, matching the navbar logo -->
    <link rel="icon" type="image/jpeg" href="@assetv('image/logo/vbs-logo-v3.jpeg')">

    <!-- Mobile-only design enhancements — loaded after the inline styles below so its rules can override them on small screens -->
    <link rel="stylesheet" href="@assetv('mobile-design.css')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&family=Orbitron:wght@700;800;900&family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    {{-- Start fetching GSAP early — these load via a deferred script tag
         near the bottom of body, so without a preload hint the browser
         doesn't even discover the URLs until the parser reaches that far
         down, delaying every animation init function's first successful
         retry. --}}
    <link rel="preload" as="script" href="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js">
    <link rel="preload" as="script" href="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js">

    {{-- Every entrance animation on this site (hero, nav, story overture,
         founder section, About mosaic) starts life as opacity:0 in the raw
         HTML/CSS and is only ever revealed by a GSAP timeline. If the GSAP
         CDN above is blocked, slow, or fails, every one of those sections —
         including the nav bar itself — previously stayed invisible forever,
         with no way to recover. This watchdog gives GSAP a fair 6s window;
         if it hasn't loaded by then (or the visitor has prefers-reduced-motion
         set, which never needs GSAP at all), it force-reveals everything via
         plain CSS instead. Deliberately a plain (non type="text/tailwindcss")
         <style> tag below, so both this script and its CSS work even if the
         Tailwind Play CDN itself is what's blocked. --}}
    <script>
    (function () {
        var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var deadline = Date.now() + (reducedMotion ? 0 : 6000);

        (function poll() {
            var ready = reducedMotion || typeof gsap !== 'undefined' || Date.now() >= deadline;
            if (!ready) { return setTimeout(poll, 150); }
            if (!reducedMotion && typeof gsap !== 'undefined') { return; } // GSAP loaded fine — nothing to do.

            document.documentElement.classList.add('motion-fallback');
            // #story-overture reuses its own existing .story-reduced static
            // layout (already handles the pinned-scroll-track collapse
            // correctly) rather than duplicating that CSS here.
            var overture = document.getElementById('story-overture');
            if (overture) { overture.classList.add('story-reduced'); }
            else { setTimeout(poll, 50); } // body not parsed yet this early — retry briefly.
        })();
    })();
    </script>
    <style>
        html.motion-fallback #hero-bridge-left,
        html.motion-fallback #hero-bridge-mobile,
        html.motion-fallback #hero-badge,
        html.motion-fallback #hero-glow-line,
        html.motion-fallback #hero-subtext,
        html.motion-fallback .hero-btn-primary,
        html.motion-fallback .hero-btn-secondary,
        html.motion-fallback #hero-trust,
        html.motion-fallback #hero-halo-mobile,
        html.motion-fallback #hero-halo-mobile-ring,
        html.motion-fallback #hero-trail-mobile,
        html.motion-fallback #hero-device-mobile,
        html.motion-fallback #hero-halo,
        html.motion-fallback #hero-orbit,
        html.motion-fallback #hero-device,
        html.motion-fallback #hero-support-card,
        html.motion-fallback .hero-rating-card,
        html.motion-fallback #hero-scroll-cue,
        html.motion-fallback #nav-logo,
        html.motion-fallback .nav-link-3d,
        html.motion-fallback #nav-login,
        html.motion-fallback .nav-cta-btn,
        html.motion-fallback #founder-photo-frame,
        html.motion-fallback [data-reveal-mobile-photo],
        html.motion-fallback [data-reveal-content],
        html.motion-fallback [data-reveal-stats],
        html.motion-fallback .mosaic-panel {
            opacity: 1 !important;
        }
    </style>

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
                        lightgray: { DEFAULT: '#FFFFFF' },
                    },
                    fontFamily: {
                        sans:    ['Inter', 'sans-serif'],
                        display: ['Orbitron', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    {{-- type="text/tailwindcss" lets the Play CDN process @apply directives --}}
    <style type="text/tailwindcss">
        html { scroll-behavior: smooth; overflow-x: hidden; }
        body { overflow-x: hidden; }

        /* ─── Nav ─── */
        /* ─── Nav link (base) ─── */
        .nav-link { @apply text-sm font-medium transition-colors duration-200; color:rgba(47,58,69,0.75); }
        .nav-link:hover { color:#C9A84C; }
        .nav-link.is-active { color:#C9A84C !important; }

        /* ── Premium 3D nav items (Vision Pro / Linear feel) ──
           Each link is a small 3D card: a perspective stage (<a>) holds an
           inner object that tilts to the cursor, with layered glass, a
           cursor-following glow, a gradient border, and a light sweep. All
           transform/opacity/filter only → GPU-composited, holds 60fps. */
        .nav-link-3d { display:inline-block; perspective:700px; }
        .nav-link-3d .nav-link-inner {
            position:relative; display:flex; align-items:center; justify-content:center;
            padding:8px 16px; border-radius:12px; transform-style:preserve-3d;
            will-change:transform; transition:box-shadow .35s cubic-bezier(.22,1,.36,1); }
        .nav-link-3d.is-hover .nav-link-inner {
            box-shadow:0 18px 38px rgba(0,0,0,.30), 0 5px 14px rgba(0,0,0,.22); }
        /* glass panel + cursor-following glow */
        .nav-link-glass {
            position:absolute; inset:0; border-radius:12px; opacity:0; pointer-events:none;
            background:
                radial-gradient(140px circle at var(--mx,50%) var(--my,50%), rgba(201,168,76,.22), transparent 62%),
                linear-gradient(155deg, rgba(255,255,255,.12), rgba(255,255,255,.03));
            -webkit-backdrop-filter:blur(9px); backdrop-filter:blur(9px);
            transition:opacity .32s cubic-bezier(.22,1,.36,1); }
        /* premium gradient border (masked so only the 1px ring shows) */
        .nav-link-glass::before {
            content:""; position:absolute; inset:0; border-radius:12px; padding:1px;
            background:linear-gradient(130deg, rgba(201,168,76,.75), rgba(63,189,187,.55), rgba(201,168,76,0) 72%);
            -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite:xor; mask-composite:exclude; }
        .nav-link-3d.is-hover .nav-link-glass { opacity:1; }
        /* cinematic light sweep */
        .nav-link-sweep { position:absolute; inset:0; border-radius:12px; overflow:hidden; opacity:0; pointer-events:none; }
        .nav-link-sweep::before {
            content:""; position:absolute; top:-20%; left:0; width:36%; height:140%;
            background:linear-gradient(105deg, transparent, rgba(255,255,255,.42), transparent);
            transform:translateX(-260%) skewX(-16deg); }
        .nav-link-3d.is-hover .nav-link-sweep { opacity:1; }
        .nav-link-3d.is-hover .nav-link-sweep::before { animation:nav-sweep .72s cubic-bezier(.22,1,.36,1) forwards; }
        .nav-link-label { position:relative; z-index:2; display:inline-block; transform:translateZ(16px); }
        @keyframes nav-sweep { to { transform:translateX(320%) skewX(-16deg); } }
        /* reduced motion: drop the 3D layers, keep the simple gold-text hover */
        @media (prefers-reduced-motion: reduce) {
            .nav-link-3d .nav-link-inner { transform:none !important; }
            .nav-link-glass, .nav-link-sweep { display:none; }
        }

        /* ─── "Login" — a teal outlined pill, not a plain text link ───
             Renamed from "Client Login": existing clients scanning the nav
             for "how do I get to my account" now get a button that visually
             announces itself, instead of blending in with the marketing
             links (About/Services/etc.) or reading like a second "Get
             Started". Teal (not gold) keeps it clearly distinct from the
             primary gold CTA next to it. */
        #nav-login {
            font-size: 0.85rem;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 999px;
            border: 1.5px solid rgba(42,157,143,0.45);
            background: rgba(42,157,143,0.08);
            color: #1F7A6E;
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }
        #nav-login:hover {
            background: rgba(42,157,143,0.18);
            border-color: rgba(42,157,143,0.75);
            color: #17635A;
        }
        .nav-on-dark-hero #nav-inner:not(.nav-pill) #nav-login {
            border-color: rgba(111,216,203,0.55);
            background: rgba(111,216,203,0.12);
        }
        .nav-on-dark-hero #nav-inner:not(.nav-pill) #nav-login:hover {
            background: rgba(111,216,203,0.22);
            border-color: rgba(111,216,203,0.85);
        }

        /* ─── Nav over a dark hero (homepage only, pre-scroll) ───
             Once #nav-inner gets .nav-pill (solid white pill on scroll) the
             default navy .nav-link color already reads fine again, so these
             overrides are scoped to :not(.nav-pill). */
        .nav-on-dark-hero #nav-inner:not(.nav-pill) .nav-link,
        .nav-on-dark-hero #nav-inner:not(.nav-pill) #nav-login,
        .nav-on-dark-hero #nav-inner:not(.nav-pill) #menu-btn {
            color: rgba(255,255,255,.85);
        }
        .nav-on-dark-hero #nav-inner:not(.nav-pill) .hamburger-bar {
            background-color: rgba(255,255,255,.85) !important;
        }
        .nav-on-dark-hero #nav-inner:not(.nav-pill) #nav-cursor {
            background: rgba(255,255,255,.12) !important;
        }

        /* ─── Desktop full-screen menu trigger ───
             Circular badge container (matches the #nav-login pill and the
             logo badge next to it — everything else in the nav has a defined
             shape/background, this used to be three bare lines floating with
             nothing around them). Bar sizing/position math is centered within
             the button now instead of stretching edge-to-edge, since the
             button itself grew from a bare 24×18 hitbox to a 42×42 circle. */
        #desktop-menu-btn {
            width: 42px;
            height: 42px;
            border-radius: 999px;
            position: relative;
            background: rgba(21,32,44,0.06);
            border: 1.5px solid rgba(21,32,44,0.14);
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }
        #desktop-menu-btn:hover {
            background: rgba(201,168,76,0.14);
            border-color: rgba(201,168,76,0.45);
        }
        .nav-on-dark-hero #nav-inner:not(.nav-pill) #desktop-menu-btn {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.22);
        }
        .nav-on-dark-hero #nav-inner:not(.nav-pill) #desktop-menu-btn:hover {
            background: rgba(201,168,76,0.22);
            border-color: rgba(201,168,76,0.55);
        }
        #desktop-menu-btn .hamburger-bar {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 18px;
            height: 2px;
            background-color: #2F3A45;
            border-radius: 2px;
            transition: transform 0.3s ease, opacity 0.3s ease, top 0.3s ease;
        }
        #desktop-menu-btn .hamburger-bar:nth-child(1) { top: 15px; }
        #desktop-menu-btn .hamburger-bar:nth-child(2) { top: 20px; }
        #desktop-menu-btn .hamburger-bar:nth-child(3) { top: 25px; }
        #desktop-menu-btn.is-open .hamburger-bar:nth-child(1) { top: 20px; transform: translateX(-50%) rotate(45deg); }
        #desktop-menu-btn.is-open .hamburger-bar:nth-child(2) { opacity: 0; }
        #desktop-menu-btn.is-open .hamburger-bar:nth-child(3) { top: 20px; transform: translateX(-50%) rotate(-45deg); }

        /* ─── Desktop full-screen menu ───
             Reference-matched layout: brand + contact top-left/middle, CLOSE
             top-right, giant stacked links filling the space, tagline
             bottom-left, a soft gold/teal glow bottom-right. Desktop-only —
             toggled by #desktop-menu-btn, entirely separate from mobile's
             own #mobile-menu. */
        /* Opacity/visibility here are just the static hidden/shown end
           states — no CSS transition, since the open/close JS drives a
           GSAP timeline directly on these (and child) elements. A plain
           CSS transition running alongside GSAP's own per-frame inline
           styles on the same property fights it and reads as stutter. */
        #desktop-menu {
            position: fixed;
            inset: 0;
            z-index: 60;
            background: #05070B;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        #desktop-menu.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
        /* Safety net — this menu is desktop-only (only #desktop-menu-btn,
           itself hidden below md, ever toggles it), but this guarantees it
           can never show on a narrow screen even if resized while open. */
        @media (max-width: 767px) {
            #desktop-menu { display: none !important; }
        }
        #desktop-menu-glow {
            position: absolute;
            right: -10%;
            bottom: -20%;
            width: 46%;
            height: 70%;
            background: radial-gradient(ellipse at bottom right, rgba(201,168,76,0.30) 0%, rgba(44,166,164,0.14) 45%, transparent 75%);
            filter: blur(40px);
            pointer-events: none;
        }
        #desktop-menu-close {
            font-family: 'Orbitron', sans-serif;
            color: rgba(255,255,255,.8);
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            transition: color 0.2s ease;
        }
        #desktop-menu-close {
            transform-origin: right center;
        }
        #desktop-menu-close:hover {
            color: #DFC06A;
            transform: scale(1.15);
        }
        .desktop-menu-link {
            display: block;
            font-family: 'Orbitron', sans-serif;
            font-weight: 800;
            font-size: clamp(2.6rem, 6.4vw, 5.4rem);
            line-height: 1.04;
            letter-spacing: -0.01em;
            color: rgba(255,255,255,.92);
            text-align: right;
            transform-origin: right center;
            /* Slowed/enlarged to match the same text zoom-on-hover
               treatment as the Contact page and footer (see contact.blade.php
               and the footer CSS above) — color/position keep their
               original transition here, only the growth is new. */
            transition: color .3s ease, transform .65s cubic-bezier(.16,1,.3,1);
        }
        .desktop-menu-link:hover {
            color: #DFC06A;
            transform: translateX(-6px) scale(1.1);
        }

        /* ─── Desktop menu: brand name + contact links also get the slow
             text zoom (same values as .desktop-menu-link above) ─── */
        #desktop-menu-brand-name,
        .desktop-menu-contact-link,
        #desktop-menu-tagline {
            font-family: 'Orbitron', sans-serif;
            display: inline-block;
            transition: transform .65s cubic-bezier(.16,1,.3,1);
            transform-origin: left center;
        }
        #desktop-menu-brand-name:hover,
        .desktop-menu-contact-link:hover,
        #desktop-menu-tagline:hover {
            transform: scale(1.15);
        }
        @media (prefers-reduced-motion: reduce) {
            #desktop-menu-close, .desktop-menu-link,
            #desktop-menu-brand-name, .desktop-menu-contact-link, #desktop-menu-tagline {
                transition: none;
            }
        }

        /* ─── Desktop menu: custom trailing "signal lock" cursor — same
             lag-stretch technique as Contact/footer. #desktop-menu itself
             carries no transform/filter/will-change:transform, so unlike
             the footer these are safe to nest directly inside it (still
             correctly resolves position:fixed against the viewport). Also
             needs no extra open/closed gating: #desktop-menu already has
             pointer-events:none while closed, so mousemove simply never
             reaches it until the menu is actually open. ─── */
        #desktop-menu-cursor-dot, #desktop-menu-cursor-ring {
            position: fixed;
            top: 0; left: 0;
            pointer-events: none;
            z-index: 200;
            opacity: 0;
            transform: translate(-50%, -50%);
        }
        #desktop-menu-cursor-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #C9A84C;
            box-shadow: 0 0 10px rgba(201,168,76,.85);
        }
        /* A fixed large-px radius (not 50%) so the same rule reads as a
           circle at the default square size, and as a pill/stadium shape
           once the script below grows it into an oblong that spans a nav
           link's full text width — border-radius simply clamps to the
           tightest curve the current box shape allows either way. */
        #desktop-menu-cursor-ring {
            width: 46px; height: 46px;
            border-radius: 999px;
            border: 1.5px solid rgba(201,168,76,.55);
            /* width/height are no longer here — the script below now
               GSAP-tweens both (for the plain circle-grow AND the oblong
               nav-link morph) instead of toggling a CSS class, since a CSS
               transition racing GSAP's own per-frame inline styles on the
               same property fights it and reads as stutter (same reason
               noted on #desktop-menu's own open/close styles above). */
            transition: border-color .3s ease, background-color .3s ease;
        }
        #desktop-menu-cursor-dot.is-visible, #desktop-menu-cursor-ring.is-visible { opacity: 1; }
        #desktop-menu-cursor-ring.is-hovering {
            background: rgba(201,168,76,.12);
            border-color: rgba(201,168,76,.85);
        }
        #desktop-menu.has-custom-cursor,
        #desktop-menu.has-custom-cursor a,
        #desktop-menu.has-custom-cursor button {
            cursor: none;
        }
        @media (hover: none), (pointer: coarse) {
            #desktop-menu-cursor-dot, #desktop-menu-cursor-ring { display: none; }
        }

        /* ─── Re-usable buttons (outside hero) ─── */
        .btn-gold    { @apply inline-block bg-gold hover:bg-gold-dark text-navy font-bold text-lg px-9 py-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg; }
        .btn-outline { @apply inline-block border-2 border-navy text-navy hover:bg-navy hover:text-white font-bold text-lg px-9 py-4 rounded-lg transition-all duration-200; }

        /* ─── Typography ─── */
        .section-title    { @apply font-display text-4xl md:text-5xl lg:text-6xl font-extrabold text-navy leading-tight; }
        .section-subtitle { @apply text-gray-700 text-xl mt-3 max-w-2xl mx-auto leading-relaxed; }

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
            position:relative; display:inline-flex; align-items:center; gap:8px;
            border:1.5px solid rgba(47,58,69,.28); color:rgba(47,58,69,.85);
            font-weight:600; padding:15px 34px; border-radius:10px; font-size:1rem;
            background:rgba(255,255,255,.92);
            backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px);
            overflow:hidden;
            transition: transform .22s, box-shadow .22s, border-color .22s, background .22s;
            will-change: transform;
        }
        .hero-btn-secondary:hover { border-color:rgba(47,58,69,.5); transform:translateY(-3px); box-shadow:0 8px 28px rgba(47,58,69,.14); }

        /* Hover fill sweep — desktop/laptop only (devices with real hover),
           so it never gets stuck mid-sweep on a touch tap. .hero-btn-content
           sits above the sweep layer so text/icon never get covered. */
        @media (hover: hover) and (pointer: fine) {
            .hero-btn-content { position:relative; z-index:2; display:inline-flex; align-items:center; gap:8px; }
            .hero-btn-fill {
                position:absolute; inset:0; z-index:1;
                transform:scaleX(0); transition:transform .4s cubic-bezier(.65,0,.35,1);
            }
            .hero-btn-primary .hero-btn-fill {
                background:#fff; transform-origin:left;
            }
            .hero-btn-primary:hover .hero-btn-fill { transform:scaleX(1); }
            .hero-btn-primary:hover { color:#15202C; }

            .hero-btn-secondary .hero-btn-fill {
                background:linear-gradient(135deg,#C9A84C 0%,#E6C878 50%,#C9A84C 100%);
                transform-origin:right;
            }
            .hero-btn-secondary:hover .hero-btn-fill { transform:scaleX(1); }
            .hero-btn-secondary:hover { color:#15202C; border-color:transparent; }

            /* Same lift + white fill-sweep treatment on the gold CTA buttons
               inside the parallax dividers ("See Why VisionBridge", "View Plans"). */
            .parallax-cta-btn { position:relative; overflow:hidden; }
            .parallax-cta-btn .hero-btn-fill { background:#fff; transform-origin:left; }
            .parallax-cta-btn:hover .hero-btn-fill { transform:scaleX(1); }
            .parallax-cta-btn:hover {
                color:#15202C; transform:translateY(-3px);
                box-shadow:0 0 38px rgba(201,168,76,.48),0 8px 28px rgba(0,0,0,.35);
            }
        }

        /* ─── "Why VisionBridge" feature cards — hover polish ───
           Circular icon badges already get a gradient tint + scale-up via
           Tailwind utilities in the markup; this adds the gold glow ring
           and stronger card shadow/border on hover. */
        .why-feature-card:hover {
            border-color: rgba(201,168,76,0.35) !important;
            box-shadow: 0 18px 40px rgba(201,168,76,0.16), 0 4px 14px rgba(17,29,51,0.06) !important;
        }
        .why-feature-card:hover .why-feature-icon {
            box-shadow: 0 0 0 5px rgba(201,168,76,0.14), 0 6px 18px rgba(201,168,76,0.28);
        }

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

        /* ─── Portfolio project cards — premium agency showcase ─── */

        .portfolio-card-inner {
            display:flex; flex-direction:column; height:100%;
            background:#FFFFFF; border-radius:22px; overflow:hidden;
            border:1px solid rgba(21,32,44,0.08);
            box-shadow:0 4px 20px rgba(21,32,44,0.06);
            transition:transform .4s cubic-bezier(.34,1.56,.64,1), box-shadow .4s ease, border-color .4s ease;
        }
        .portfolio-card:hover .portfolio-card-inner {
            transform:translateY(-8px);
            box-shadow:0 28px 60px rgba(21,32,44,0.16), 0 8px 24px rgba(201,168,76,0.12);
            border-color:rgba(201,168,76,0.35);
        }
        .portfolio-card-inner-cta { background:linear-gradient(155deg,#15202C 0%,#2F3A45 100%); border-color:rgba(201,168,76,0.30); }

        .portfolio-card-media { position:relative; aspect-ratio:16/10; overflow:hidden; background:#EEF2F5; flex-shrink:0; }
        .portfolio-card-media img {
            width:100%; height:100%; object-fit:cover; object-position:top center;
            transition:transform .6s cubic-bezier(.22,1,.36,1);
        }
        .portfolio-card:hover .portfolio-card-media img { transform:scale(1.06); }
        .portfolio-card-placeholder { width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:linear-gradient(155deg,#F7F3EA 0%,#EDE6D4 100%); }
        .portfolio-card-placeholder-cta { background:linear-gradient(155deg,#1B2530 0%,#2F3A45 100%); }

        .portfolio-industry-badge {
            position:absolute; top:14px; left:14px; z-index:2;
            background:rgba(255,255,255,0.94); color:#15202C;
            font-size:0.68rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase;
            padding:6px 14px; border-radius:9999px; box-shadow:0 4px 14px rgba(21,32,44,0.16);
        }
        .portfolio-status-pill {
            position:absolute; top:14px; right:14px; z-index:2;
            background:#C9A84C; color:#15202C;
            font-size:0.68rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase;
            padding:6px 14px; border-radius:9999px; box-shadow:0 4px 14px rgba(201,168,76,0.38);
        }

        .portfolio-card-body { display:flex; flex-direction:column; flex:1; padding:26px 26px 24px; position:relative; }
        .portfolio-card-num {
            position:absolute; top:14px; right:22px; font-family:'Playfair Display',serif;
            font-size:2.4rem; font-weight:800; color:rgba(21,32,44,0.05); line-height:1; user-select:none; z-index:0;
        }
        .portfolio-card-inner-cta .portfolio-card-num { color:rgba(255,255,255,0.06); }
        .portfolio-card-title { position:relative; z-index:1; font-size:1.25rem; font-weight:800; color:#15202C; margin-bottom:5px; line-height:1.28; }
        .portfolio-card-inner-cta .portfolio-card-title { color:#FFFFFF; }
        .portfolio-card-tagline { position:relative; z-index:1; font-size:0.9rem; font-weight:700; color:#A8872E; margin-bottom:12px; line-height:1.4; }
        .portfolio-card-inner-cta .portfolio-card-tagline { color:#C9A84C; }
        .portfolio-card-desc { position:relative; z-index:1; font-size:0.94rem; font-weight:500; color:rgba(21,32,44,0.72); line-height:1.65; margin-bottom:16px; }
        .portfolio-card-inner-cta .portfolio-card-desc { color:rgba(255,255,255,0.78); }

        .portfolio-card-features { position:relative; z-index:1; display:flex; flex-wrap:wrap; gap:8px; margin:0 0 8px; padding:0; }
        .portfolio-card-features li {
            list-style:none; font-size:0.72rem; font-weight:600; color:#1F7A78;
            background:rgba(42,157,143,0.09); border:1px solid rgba(42,157,143,0.20);
            padding:5px 12px; border-radius:9999px;
        }

        .portfolio-card-btn-wrap { position:relative; z-index:1; margin-top:auto; padding-top:18px; }
        .portfolio-card-btn {
            display:inline-flex; align-items:center; gap:8px; font-size:0.88rem; font-weight:700;
            color:#15202C; padding:12px 22px; border-radius:9999px;
            background:transparent; border:1.5px solid rgba(21,32,44,0.16);
            transition:background .3s ease, color .3s ease, border-color .3s ease;
        }
        .portfolio-card-btn svg { transition:transform .3s ease; }
        .portfolio-card-btn:hover { background:#15202C; color:#C9A84C; border-color:#15202C; }
        .portfolio-card-btn:hover svg { transform:translateX(3px); }
        .portfolio-card-btn-gold { background:#C9A84C; color:#15202C; border-color:#C9A84C; }
        .portfolio-card-btn-gold:hover { background:#FFFFFF; color:#15202C; border-color:#FFFFFF; }
        .portfolio-card-btn-disabled { opacity:0.55; cursor:default; pointer-events:none; border-style:dashed; }

        /* ─── Atmospheric orbs ─── */
        .hero-orb { position:absolute; border-radius:50%; pointer-events:none; will-change:transform; }
        @keyframes orb-drift {
            0%,100% { transform:translate(0,0) scale(1); }
            33%      { transform:translate(28px,-22px) scale(1.05); }
            66%      { transform:translate(-18px,14px) scale(.96); }
        }

        /* ─── Hero background — ambient gradient drift ───
             Large oversized gradient, background-position animated slowly;
             low-contrast + slow (24s) enough that the paint cost stays cheap. */
        .hero-gradient-shift {
            background: linear-gradient(120deg,
                rgba(44,166,164,.05) 0%,
                rgba(201,168,76,.07) 35%,
                rgba(47,58,69,.03) 60%,
                rgba(44,166,164,.05) 100%);
            background-size: 220% 220%;
            /* Two independent animations on different properties — position
               drift (existing) plus a slow opacity "breathe" pulse (new) —
               run simultaneously without conflicting. */
            animation: hero-gradient-drift 24s ease-in-out infinite,
                       hero-bg-breathe 8s ease-in-out infinite;
        }
        @keyframes hero-gradient-drift {
            0%,100% { background-position: 0% 30%; }
            50%      { background-position: 100% 70%; }
        }
        @keyframes hero-bg-breathe {
            0%,100% { opacity: 1; }
            50%      { opacity: 0.7; }
        }
        @media (prefers-reduced-motion: reduce) {
            .hero-gradient-shift { animation: none; }
        }

        /* ─── Hero background — mouse-following ambient glow ───
             Position driven by --mx/--my custom properties, animated via
             GSAP on mousemove (see home.blade.php) rather than a CSS
             transition — custom-property transitions need @property
             registration for reliable cross-browser interpolation, which
             GSAP's JS-driven approach doesn't need. */
        #hero-mouse-glow {
            --mx: 50%;
            --my: 40%;
            background: radial-gradient(560px circle at var(--mx) var(--my), rgba(223,192,106,.16), transparent 62%);
            opacity: 0;
        }

        /* ─── Hero background — very soft light rays ─── */
        .hero-ray {
            position:absolute; top:-30%; left:-20%; width:150%; height:200%;
            background:linear-gradient(100deg, transparent 44%, rgba(255,255,255,.09) 50%, transparent 56%);
            filter: blur(28px);
            will-change: transform;
        }
        .hero-ray-1 { animation: hero-ray-sway 20s ease-in-out infinite; }
        .hero-ray-2 { opacity:.6; animation: hero-ray-sway 26s ease-in-out infinite reverse; animation-delay:-6s; }
        @keyframes hero-ray-sway {
            0%,100% { transform: rotate(-14deg) translateX(0); }
            50%      { transform: rotate(-9deg) translateX(26px); }
        }

        /* ─── Hero background — frosted glass depth pane behind the heading ─── */
        .hero-glass-depth {
            border-radius: 50%;
            background: radial-gradient(ellipse at center, rgba(255,255,255,.07) 0%, transparent 72%);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        /* ─── Animated grain/noise texture — shared keyframe, used by
             .page-noise below (the sitewide overlay). feTurbulence data-URI
             tile, translated in discrete steps (not background-position) so
             the browser can composite it on the GPU instead of repainting
             the gradient/turbulence each frame. */
        @keyframes hero-noise-drift {
            0%   { transform: translate3d(0,0,0); }
            100% { transform: translate3d(-40px,-30px,0); }
        }

        /* ─── Site-wide film grain — one fixed overlay covering the whole
             viewport, not a per-section copy. Scrolling from the hero into
             every lighter section below reads as one continuous cinematic
             texture instead of the grain stopping at the hero's edge.
             mix-blend-mode:overlay adapts to both the hero's dark bg and the
             lighter sections below without needing a different opacity per
             section. ─── */
        .page-noise {
            position: fixed;
            inset: 0;
            z-index: 9999;
            pointer-events: none;
            opacity: .09;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-repeat: repeat;
            mix-blend-mode: overlay;
            animation: hero-noise-drift 7s steps(8) infinite alternate;
            will-change: transform;
        }
        @media (max-width: 639px) {
            .page-noise { display: none; }
        }
        @media (prefers-reduced-motion: reduce) {
            .page-noise { animation: none; }
        }

        /* ─── Hero background — floating gold particles (positioned/animated via GSAP in home.blade.php) ─── */
        .hero-particle {
            position:absolute; border-radius:50%; pointer-events:none;
            background: radial-gradient(circle, #FFF6DC 0%, rgba(223,192,106,.9) 45%, transparent 75%);
            filter: drop-shadow(0 0 4px rgba(223,192,106,.85));
            will-change: transform, opacity;
        }

        /* Respect reduced-motion: the new hero layers go static instead of animating */
        @media (prefers-reduced-motion: reduce) {
            .hero-gradient-shift, .hero-ray { animation: none !important; }
        }

        /* ════════════════════════════════════════════════════════════
           HERO — dark theme redesign (homepage only, scoped under #hero)
           ════════════════════════════════════════════════════════════ */

        /* Starfield — same dot-grid technique as .hero-grid-dots, recolored
           white/sparse so it reads as stars against the near-black hero bg */
        #hero.hero-dark .hero-grid-dots {
            background-image: radial-gradient(circle, rgba(255,255,255,.14) 1px, transparent 1px);
            background-size: 3px 3px, 46px 46px;
        }

        /* Left-edge bridge photo — positioning/sizing lives inline on the
           element itself (Tailwind utilities + style attribute) since it's a
           plain <img>, not the old rotated SVG this rule used to style.
           opacity driven entirely by the GSAP entrance timeline. */
        @media (max-width: 767px) {
            #hero-bridge-left { display: none; }
        }

        /* Hero badge's live-dot — hot gold instead of the shared teal
           default, scoped to #hero-badge so .live-dot elsewhere (e.g.
           online-status indicators) keeps its original color. */
        #hero-badge .live-dot { background: #FFB627; }
        #hero-badge .live-dot::after { border-color: rgba(255,182,39,.65); }

        /* Hero heading glow — a brighter, wider radiant version of the
           shared .glow-line divider, scoped by id so the base .glow-line
           class (reused elsewhere on the page) is untouched. */
        #hero-glow-line {
            width: 220px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #FFB627 50%, transparent);
        }
        #hero-glow-line::after {
            content: '';
            position: absolute;
            inset: -16px -32px;
            background: radial-gradient(ellipse 60% 100% at center, rgba(255,152,20,.70) 0%, rgba(255,152,20,.25) 45%, transparent 75%);
            filter: blur(11px);
            opacity: 1;
        }
        #hero-glow-line::before {
            content: '';
            position: absolute;
            left: 50%; top: -2px; transform: translateX(-50%);
            width: 66px; height: 7px; border-radius: 50%;
            background: #FFEFC2;
            filter: blur(7px);
            opacity: 1;
        }

        /* Orbit ring — a sparkling dashed arc continuously circling the
           laptop mockup. Animates stroke-dashoffset along the fixed ellipse
           path (NOT a CSS transform:rotate on the shape itself — rotating a
           non-circular ellipse rigidly tilts/warps it through the spin
           instead of sliding smoothly around a stationary ring). The
           dasharray's dash+gap (110 + 1319 = 1429) matches the ellipse's
           Ramanujan-approximated circumference (rx:272, ry:178) so the loop
           repeats with no visible seam/jump. */
        #hero-orbit-bloom, #hero-orbit-mid, #hero-orbit-glow {
            animation: hero-orbit-travel 9s linear infinite;
        }
        /* Three stacked layers — wide soft amber bloom, mid gold glow, thin
           white-hot core — read together as one bright burning beam (like
           the light streak baked into the laptop photo) instead of a single
           flat-colored line. All three share the same dash animation so
           they move as one cohesive segment. */
        #hero-orbit-bloom {
            opacity: .6;
            filter: blur(11px) drop-shadow(0 0 18px rgba(255,140,20,.6));
        }
        #hero-orbit-mid {
            opacity: .9;
            filter: blur(2px) drop-shadow(0 0 10px rgba(255,201,77,.75));
        }
        #hero-orbit-glow {
            filter: drop-shadow(0 0 6px rgba(255,255,255,.95)) drop-shadow(0 0 16px rgba(255,180,60,.8));
        }
        @keyframes hero-orbit-travel {
            from { stroke-dashoffset: 0; }
            to   { stroke-dashoffset: -1429; }
        }

        /* Inner ring — smaller (rx:190, ry:124; dasharray 70+927=997 matches
           its own circumference), spins the OPPOSITE direction at a
           different speed than the outer ring. Two rings counter-rotating
           at different depths/rates is what reads as a spiral/vortex rather
           than a single flat circling line. */
        #hero-orbit-inner-mid, #hero-orbit-inner-glow {
            animation: hero-orbit-travel-reverse 6.5s linear infinite;
        }
        #hero-orbit-inner-mid {
            opacity: .85;
            filter: blur(1.5px) drop-shadow(0 0 8px rgba(255,157,46,.7));
        }
        #hero-orbit-inner-glow {
            filter: drop-shadow(0 0 5px rgba(255,255,255,.9)) drop-shadow(0 0 12px rgba(255,157,46,.75));
        }
        @keyframes hero-orbit-travel-reverse {
            from { stroke-dashoffset: 0; }
            to   { stroke-dashoffset: 997; }
        }
        @media (prefers-reduced-motion: reduce) {
            #hero-orbit-bloom, #hero-orbit-mid, #hero-orbit-glow,
            #hero-orbit-inner-mid, #hero-orbit-inner-glow { animation: none; }
        }

        /* Halo — soft diffuse glow disc slowly rotating behind the laptop,
           sitting further back than the thin sparkling orbit rings above it.
           A safe use of transform:rotate() (unlike the flat-ellipse orbit
           rings) since it's radially symmetric — no non-circular shape to
           visibly warp/tilt through the spin. */
        #hero-halo {
            animation: hero-halo-spin 55s linear infinite;
        }
        @keyframes hero-halo-spin {
            from { transform: translate(-50%,-50%) rotate(0deg); }
            to   { transform: translate(-50%,-50%) rotate(360deg); }
        }
        @media (prefers-reduced-motion: reduce) {
            #hero-halo { animation: none; }
        }

        /* Device mockup — a real image asset (public/image/laptop-tillted.png),
           masked at the edges with a radial gradient so its own baked-in dark
           background blends into the hero instead of showing a hard rectangle. */
        #hero-device { width:100%; }

        /* Idle floating motion for the hero laptop — applied to the outer
           #hero-device-frame (not #hero-device itself), since GSAP already
           owns #hero-device's transform for its scale/slide entrance
           animation; a CSS keyframe animation on the same element would
           override that inline transform for the animation's duration and
           kill the entrance easing. The frame is never touched by GSAP, so
           it's free to carry a continuous bob + gentle tilt from page load
           with no conflict. Keeps the element's own base scale(1.12) in
           every keyframe step so the animation doesn't reset it. */
        #hero-device-frame {
            animation: hero-laptop-float 8s ease-in-out infinite;
        }
        @keyframes hero-laptop-float {
            0%, 100% { transform: scale(1.12) translateY(0) rotate(-0.6deg); }
            50%      { transform: scale(1.12) translateY(-12px) rotate(0.6deg); }
        }
        @media (prefers-reduced-motion: reduce) {
            #hero-device-frame { animation: none; }
        }

        /* Dark-glass modifier for a .float-card sitting on the dark hero —
           overrides the base opaque-white glass treatment with a translucent
           tinted-dark version so it reads as glass against a dark backdrop
           instead of a stray white box. */
        .hero-glass-card {
            background: rgba(20,26,36,.42) !important;
            border-color: rgba(255,255,255,.16) !important;
            box-shadow: 0 12px 32px rgba(0,0,0,.40) !important;
        }

        /* Rating cards — a horizontal row sitting just under the laptop */
        .hero-rating-card {
            flex:1 1 0%; pointer-events:none;
            background:rgba(20,26,36,.85); border:1px solid rgba(201,168,76,.20);
            box-shadow:0 12px 32px rgba(0,0,0,.45);
            backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);
            border-radius:14px; padding:14px 16px;
            will-change:transform;
        }
        .hero-rating-stars { color:#DFC06A; font-size:.72rem; letter-spacing:1px; margin-bottom:4px; }
        .hero-rating-quote { color:rgba(255,255,255,.88); font-size:.76rem; line-height:1.35; margin-bottom:4px; }
        .hero-rating-attr { color:rgba(255,255,255,.45); font-size:.68rem; }

        /* Independent floating motion — each rating card bobs on its own
           duration/delay/amplitude so the row reads as three cards drifting
           on their own rather than one row moving in lockstep. Transform-only
           (translateY/rotate), so it never affects flex layout/flow. */
        #hero-rating-1 { animation: rating-float-1 6s ease-in-out infinite; }
        #hero-rating-2 { animation: rating-float-2 7.5s ease-in-out infinite; animation-delay: -2.2s; }
        #hero-rating-3 { animation: rating-float-3 5.2s ease-in-out infinite; animation-delay: -3.6s; }
        @keyframes rating-float-1 {
            0%,100% { transform:translateY(0) rotate(-0.6deg); }
            50%     { transform:translateY(-9px) rotate(0.4deg); }
        }
        @keyframes rating-float-2 {
            0%,100% { transform:translateY(-3px) rotate(0.5deg); }
            50%     { transform:translateY(-14px) rotate(-0.5deg); }
        }
        @keyframes rating-float-3 {
            0%,100% { transform:translateY(-1px) rotate(-0.4deg); }
            50%     { transform:translateY(-11px) rotate(0.6deg); }
        }
        @media (prefers-reduced-motion: reduce) {
            #hero-rating-1, #hero-rating-2, #hero-rating-3 { animation:none; }
        }

        /* Hero CTA buttons — dark-hero variant of the secondary button
           (transparent + light border instead of the frosted-white pill,
           which would look like a stray white box on a near-black hero) */
        #hero.hero-dark .hero-btn-secondary {
            background: transparent;
            border-color: rgba(255,255,255,.30);
            color: rgba(255,255,255,.90);
        }
        #hero.hero-dark .hero-btn-secondary:hover {
            border-color: rgba(255,255,255,.55);
            box-shadow: 0 8px 28px rgba(0,0,0,.35);
        }
        @media (hover: hover) and (pointer: fine) {
            #hero.hero-dark .hero-btn-secondary .hero-btn-fill {
                background: rgba(255,255,255,.10);
            }
        }

        /* ─── Off-screen animation pause (perf) ───
           Toggled by JS via IntersectionObserver on the always-running
           "infinite" CSS animations scattered around the page (orb drift,
           shimmer, pulse, wave glide) so they stop burning CPU/GPU cycles
           while their section isn't visible. */
        .anim-paused { animation-play-state: paused !important; }
        .live-dot.anim-paused::after { animation-play-state: paused !important; }

        /* ─── Dot-grid texture ─── */
        .hero-grid-dots {
            background-image: radial-gradient(circle,rgba(47,58,69,.06) 1px,transparent 1px);
            background-size: 28px 28px;
        }

        /* ─── Contact form: custom service dropdown ─── */
        .service-option:hover { background: rgba(201,168,76,0.10); }
        .service-option.is-selected { background: rgba(201,168,76,0.07); font-weight: 600; }
        .service-option.is-selected .service-option-check { opacity: 1 !important; }
        #service-select-trigger.is-open { border-color: #C9A84C !important; background: #ffffff !important; }
        #service-select-panel.is-open { opacity: 1 !important; transform: scaleY(1) translateY(0) !important; visibility: visible !important; }
        #service-select-chevron.is-open { transform: rotate(180deg); }
        #service-select-list::-webkit-scrollbar { width: 6px; }
        #service-select-list::-webkit-scrollbar-thumb { background: rgba(201,168,76,0.45); border-radius: 3px; }


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
        /* Connecting track behind the dots, plus a gold fill that grows
           to the active dot's position — reads as a page progress bar */
        #rail-track {
            position: absolute;
            left: 50%;
            top: 4px;
            bottom: 4px;
            width: 3px;
            transform: translateX(-50%);
            background: rgba(47,58,69,0.12);
            border-radius: 3px;
        }
        #rail-progress {
            position: absolute;
            left: 50%;
            top: 4px;
            width: 3px;
            height: 0;
            transform: translateX(-50%);
            background: linear-gradient(180deg, #C9A84C, #DFC06A);
            border-radius: 3px;
            transition: height 0.35s ease;
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
            opacity: 1;
            transition: background 0.25s ease, transform 0.25s ease, width 0.25s ease, height 0.25s ease, opacity 0.3s ease;
        }
        .rail-dot:hover { transform: scale(1.3); background: rgba(201,168,76,0.55); }
        .rail-dot.is-active {
            width: 13px;
            height: 13px;
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
        /* Small triangle connecting the label to its dot */
        .rail-dot-label::after {
            content: '';
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-left-color: rgba(255,255,255,0.92);
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

        /* Once scrolled, blur (no solid fill) the outer #navbar wrapper too —
           otherwise content scrolled up behind the 12px gap around the pill
           peeks through and visually collides with it. Blur-only keeps the
           floating/glassy look instead of reading as a hard white box. */
        #navbar:has(#nav-inner.nav-pill) {
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
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

        /* ─── Contact form submit: gold gradient + shimmer + glow ─── */
        .contact-submit-btn {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #C9A84C 0%, #E6C878 50%, #C9A84C 100%);
            color: #15202C;
            box-shadow: 0 10px 28px rgba(201,168,76,0.28);
            will-change: transform;
        }
        .contact-submit-btn::before {
            content: '';
            position: absolute;
            top: -50%; left: -80%;
            width: 48%; height: 200%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
            transform: skewX(-18deg);
            pointer-events: none;
            animation: btn-shine 3s ease-in-out infinite 1s;
            z-index: 1;
        }
        .contact-submit-btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: #ffffff;
            transform: translateX(-100%);
            transition: transform 0.4s ease;
            z-index: 0;
        }
        .contact-submit-btn:hover {
            box-shadow: 0 16px 38px rgba(201,168,76,0.5);
        }
        .contact-submit-btn:hover::after {
            transform: translateX(0);
        }
        .contact-submit-btn:disabled {
            box-shadow: none;
        }

        /* ─── Plan card CTAs: lift + white slide-fill ─── */
        .plan-cta-btn {
            position: relative;
            overflow: hidden;
        }
        .plan-cta-btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: #ffffff;
            transform: translateX(-100%);
            transition: transform 0.4s ease;
            z-index: 0;
        }
        .plan-cta-btn:hover::after {
            transform: translateX(0);
        }
        .plan-cta-btn:hover {
            color: #15202C !important;
            box-shadow: inset 0 0 0 2px rgba(21,32,44,0.18), 0 16px 32px rgba(21,32,44,0.16) !important;
        }
        .plan-cta-btn .plan-cta-content {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
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
        /* .page-noise is also excluded here, same as #footer-spacer — it's a
           direct child of #page-wrapper too (see home.blade.php), and without
           this exclusion this rule's #page-wrapper ID selector outranks
           .page-noise's own `pointer-events: none` (a plain class selector),
           silently re-enabling pointer events on that fixed, full-viewport,
           z-index:9999 decorative overlay and blocking every click on the
           page underneath it. #home-cursor-dot/#home-cursor-ring (also direct
           children of #page-wrapper, see home.blade.php) need the same
           exclusion for the same reason — an ID selector here would otherwise
           outrank their own `pointer-events: none` and turn the trailing
           cursor into a small fixed hitbox that follows the mouse and steals
           every click on the page underneath it. */
        #page-wrapper > *:not(#footer-spacer):not(.page-noise):not(#home-cursor-dot):not(#home-cursor-ring) {
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
            font-family: "Chakra Petch", "Chakra Petch Placeholder", sans-serif;
        }
        /* Column headings (Quick Links / Company / Others) get the same
           display treatment as headings elsewhere on the site, standing out
           from the footer's Chakra Petch body copy around them. */
        #footer-col-2 h4, #footer-col-3 h4, #footer-col-4 h4 {
            font-family: 'Orbitron', sans-serif;
        }

        /* ─── Footer: giant bleeding wordmark ─── */
        .footer-wordmark-wrap {
            position: relative;
            overflow: hidden;
            height: clamp(70px, 13vw, 150px);
            pointer-events: none;
        }
        .footer-wordmark {
            display: block;
            text-align: center;
            white-space: nowrap;
            line-height: 1;
            font-family: 'Orbitron', sans-serif;
            font-size: clamp(5.5rem, 17vw, 12rem);
            letter-spacing: -0.01em;
            background: linear-gradient(180deg, #DFC06A 0%, #3FBDBB 130%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            -webkit-mask-image: linear-gradient(to right, transparent 0%, black 14%, black 86%, transparent 100%);
            mask-image: linear-gradient(to right, transparent 0%, black 14%, black 86%, transparent 100%);
        }
        /* Below 640px the bleed effect would cut the word off entirely rather
           than just fading its edges, so it shrinks to fit fully on-screen
           instead — the edge fade mask is dropped too, since there's no
           actual off-screen bleed left to soften. */
        @media (max-width: 640px) {
            .footer-wordmark {
                font-size: clamp(2rem, 9vw, 3.2rem);
                -webkit-mask-image: none;
                mask-image: none;
            }
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

        /* ─── Footer: custom trailing "signal lock" cursor — same lag-
             stretch technique as the Contact page's own cursor (see
             contact.blade.php), replicated here since the footer is one
             shared component rendered on every page, not something a
             single page's own script can reach. The dot/ring elements
             themselves live outside <footer> in the markup (right after it
             closes), not nested inside it — #site-footer has
             will-change:transform, which per spec makes it a containing
             block for position:fixed descendants exactly like an actual
             transform would (the same class of bug this codebase already
             hit and fixed once before for #desktop-menu/#mobile-menu, by
             reparenting them to <body>). Nesting the cursor inside the
             footer would silently offset it from the real mouse position
             by however far the footer sits from the viewport's own
             top-left corner. ─── */
        #footer-cursor-dot, #footer-cursor-ring {
            position: fixed;
            top: 0; left: 0;
            border-radius: 50%;
            pointer-events: none;
            z-index: 200;
            opacity: 0;
            transform: translate(-50%, -50%);
        }
        #footer-cursor-dot {
            width: 6px; height: 6px;
            background: #C9A84C;
            box-shadow: 0 0 10px rgba(201,168,76,.85);
        }
        #footer-cursor-ring {
            width: 46px; height: 46px;
            border: 1.5px solid rgba(201,168,76,.55);
            transition: width .3s cubic-bezier(.22,1,.36,1), height .3s cubic-bezier(.22,1,.36,1),
                        border-color .3s ease, background-color .3s ease;
        }
        #footer-cursor-dot.is-visible, #footer-cursor-ring.is-visible { opacity: 1; }
        #footer-cursor-ring.is-hovering {
            width: 68px; height: 68px;
            background: rgba(201,168,76,.12);
            border-color: rgba(201,168,76,.85);
        }
        #site-footer.has-custom-cursor,
        #site-footer.has-custom-cursor a,
        #site-footer.has-custom-cursor button {
            cursor: none;
        }
        @media (hover: none), (pointer: coarse) {
            #footer-cursor-dot, #footer-cursor-ring { display: none; }
        }

        /* ─── Footer: text zoom-on-hover — same slow/enlarged treatment as
             the Contact page. .footer-link already has its own GSAP hover
             tween (the underline draw + x nudge, see initFooter() below) —
             that tween is extended to add `scale` directly rather than
             layering a competing CSS :hover rule on top of it, since
             GSAP's inline transform would just override a plain CSS
             transform on every hover anyway. ─── */
        .footer-link { transform-origin: left center; }
        #footer-col-2 h4, #footer-col-3 h4, #footer-col-4 h4,
        #footer-bottom p {
            display: inline-block;
            transition: transform .65s cubic-bezier(.16,1,.3,1);
            transform-origin: left center;
        }
        #footer-col-2 h4:hover, #footer-col-3 h4:hover, #footer-col-4 h4:hover,
        #footer-bottom p:hover {
            transform: scale(1.2);
        }
        @media (prefers-reduced-motion: reduce) {
            #footer-col-2 h4, #footer-col-3 h4, #footer-col-4 h4, #footer-bottom p {
                transition: none;
            }
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
            padding: 32px;
            height: 100%;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(17,29,51,0.07);
            box-shadow: 0 2px 12px rgba(17,29,51,0.05), 0 1px 3px rgba(17,29,51,0.03);
            transition: box-shadow 0.32s ease, border-color 0.32s ease;
        }
        .value-card-outer:hover .value-card {
            box-shadow: 0 20px 52px rgba(201,168,76,0.14), 0 6px 18px rgba(17,29,51,0.06);
            border-color: rgba(201,168,76,0.32);
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

        /* Big background watermark number — sits behind the icon/title,
           not a small corner label anymore */
        .value-card > .value-number {
            position: absolute;
            top: -8px;
            right: 14px;
            font-family: 'Playfair Display', serif;
            font-size: 4.2rem;
            font-weight: 800;
            letter-spacing: 0;
            line-height: 1;
            color: rgba(17,29,51,0.045);
            user-select: none;
            pointer-events: none;
            z-index: 0;
            transition: color 0.35s ease;
        }
        .value-card-outer:hover .value-card > .value-number { color: rgba(201,168,76,0.16); }

        .value-card-divider {
            width: 28px;
            height: 1px;
            transition: width 0.40s cubic-bezier(0.34,1.56,0.64,1);
            background: linear-gradient(90deg, rgba(201,168,76,0.38), transparent);
            margin-bottom: 14px;
        }
        .value-card-outer:hover .value-card-divider { width: 48px; }

        /* ─── Plans carousel ─── */
        .plans-card {
            transform: scale(0.85);
            opacity: 0.55;
            cursor: pointer;
            transition: transform 0.45s cubic-bezier(0.34,1.56,0.64,1), opacity 0.45s ease;
        }
        .plans-card.is-center {
            transform: scale(1);
            opacity: 1;
            cursor: default;
        }
        .plans-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #C9A84C;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(201,168,76,0.35);
            transition: background 0.25s ease, transform 0.25s ease;
        }
        .plans-arrow:hover { background: #DFC06A; transform: translateY(-50%) scale(1.08); }

        /* Care plan card header cap — gentle scoop at the bottom edge */
        .plan-header-cap {
            border-bottom-left-radius: 50% 18px;
            border-bottom-right-radius: 50% 18px;
        }

        .value-title {
            font-weight: 800;
            font-size: 1.15rem;
            color: #15202C;
            margin-bottom: 10px;
            line-height: 1.30;
            transition: color 0.26s ease;
        }
        .value-card-outer:hover .value-title { color: #A8872E; }

        .value-card-outer:hover .value-card-photo { transform: scale(1.07); }

        .value-desc {
            font-size: 1rem;
            font-weight: 500;
            line-height: 1.7;
            color: rgba(17,29,51,0.82);
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

        /* ── Portfolio section: featured project cards ── */
        .portfolio-card { transition: transform 0.32s ease; }
        .portfolio-card:hover { transform: translateY(-4px); }

        /* ── Marketing Spotlight section ── */
        .spotlight-frame {
            transition: transform 0.45s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.45s ease;
            will-change: transform;
        }
        .spotlight-frame:hover {
            transform: translateY(-6px) rotate(-0.6deg);
            box-shadow: 0 0 0 1px rgba(201,168,76,0.55), 0 48px 110px rgba(0,0,0,0.6), 0 16px 40px rgba(0,0,0,0.45) !important;
        }
        .spotlight-cta-primary {
            transition: transform 0.24s ease, box-shadow 0.24s ease, background 0.24s ease;
            box-shadow: 0 10px 28px rgba(201,168,76,0.28);
            will-change: transform;
        }
        .spotlight-cta-primary:hover {
            background: #DFC06A;
            transform: translateY(-3px);
            box-shadow: 0 16px 38px rgba(201,168,76,0.5);
        }
        .spotlight-cta-outline {
            transition: transform 0.24s ease, border-color 0.24s ease, background 0.24s ease;
            will-change: transform;
        }
        .spotlight-cta-outline:hover {
            transform: translateY(-3px);
            border-color: rgba(201,168,76,0.65) !important;
            background: rgba(201,168,76,0.10);
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
        /* Mobile active link — a visible gold pill (matching the desktop
           nav's own sliding gold capsule), not just a faint tint. Previously
           this set `color` directly on the <a>, but the title/description
           text now lives in nested spans with their own explicit colors,
           which override inherited color from the ancestor — so this had
           become fully invisible against the new dark theme regardless of
           the specificity issue: the title needs its own targeted rule
           (.menu-item-title) since a color set on the <a> can't reach it. */
        #mobile-menu a.is-active {
            background: linear-gradient(135deg, rgba(201,168,76,.28) 0%, rgba(223,192,106,.14) 100%) !important;
            border: 1px solid rgba(201,168,76,.4);
        }
        #mobile-menu a.is-active .menu-item-title {
            color: #FFE9B0 !important;
        }
        #mobile-menu a.is-active .menu-icon-badge {
            background: linear-gradient(135deg, rgba(201,168,76,.7) 0%, rgba(223,192,106,.4) 100%);
            border-color: rgba(255,255,255,.4);
        }

        /* ════════════════════════════════════════════════════════════
           OUR WORK / IN THE SPOTLIGHT — hover-tilt depth only, homepage
           only. No invented content, and no duplicate entrance animation
           either: both sections already had their own well-tuned reveal
           timelines (runPortfolioAnimation() and the Spotlight reveal,
           both in home.blade.php) before any of this was added — an
           earlier pass here also added a second, scroll-scrubbed
           entrance on the same cards/panel/frame, and the two systems
           fighting over the same transforms is what made the section
           look jumbled. That duplicate entrance was removed; only the
           mouse-tilt hover (initSpotlightTilt() in home.blade.php; the
           portfolio cards' own equivalent tilt was later removed as dead
           code once they moved into the story overture's pinned scenes —
           see home.blade.php) remains, since it drives rotationX/rotationY —
           a different transform axis than what the existing entrances
           already animate — so it layers on top safely instead of
           competing. `perspective`/`preserve-3d` below exist for that
           hover tilt to render with real depth. Deliberately no
           filter:blur() scrubbing here either — blur is one of the most
           expensive properties to repaint, and animating it on several
           elements at once (Hero, panel, every card, the Spotlight
           frame) added real paint cost for no benefit scale/rotation/
           opacity don't already give more cheaply. (A genuinely broken
           multi-second scroll lag reported around this same change
           turned out to be from a smooth-scroll library tried alongside
           this work, since reverted — this blur removal
           is kept anyway as good practice, not as that fix.) */
        .portfolio-card {
            transform-style: preserve-3d;
            will-change: transform, opacity;
        }
        .spotlight-frame {
            transform-style: preserve-3d;
            will-change: transform, opacity;
        }
        @media (prefers-reduced-motion: reduce) {
            .portfolio-card, .spotlight-frame { opacity: 1 !important; transform: none !important; filter: none !important; }
        }
    </style>
    {{-- Optional per-page extra <head> tags (e.g. a page-specific display
         font) — empty by default, so this is a no-op on every page that
         doesn't @push('head') anything. --}}
    @stack('head')
</head>
<body class="font-sans antialiased text-gray-800 bg-white">

    {{-- Full-page video intro — homepage only. Plays once, then shrinks
         away (gravity-pull) to reveal the site. See #intro-overlay script
         further down for behavior. --}}
    @if (request()->routeIs('home'))
        <div id="intro-overlay" style="position:fixed;inset:0;z-index:9999;background:#000;overflow:hidden;">
            <video id="intro-video" autoplay muted playsinline preload="metadata"
                   style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                <source src="@assetv('videos/Web_development_company_hero_video.mp4')" type="video/mp4">
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
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 @if(request()->routeIs('home')) nav-on-dark-hero @endif" style="padding:12px 16px 0;will-change:transform;">

        {{-- Floating pill inner wrapper --}}
        <div id="nav-inner" class="mx-auto flex items-center justify-between px-5 sm:px-7" style="height:60px;">

            {{-- Logo — always a full navigation to the homepage (not just
                 "#hero"), so clicking it replays the intro video even when
                 already on the homepage, matching the intro's own
                 route('home')-only trigger further down. --}}
            {{-- The source logo file has its own dark navy background baked
                 in (not transparent), so sitting directly in the white pill
                 it read as a mismatched rectangle pasted on top rather than
                 part of the design. Framing it in a matching dark badge with
                 a thin gold border turns that into an intentional look
                 instead of an accidental one. --}}
            <a id="nav-logo" href="{{ route('home') }}#hero" class="flex items-center shrink-0 opacity-0">
                <div class="rounded-xl" style="background:#0B0F17;padding:5px 9px;border:1px solid rgba(201,168,76,0.35);box-shadow:0 3px 10px rgba(0,0,0,0.20);">
                    <img src="@assetv('image/logo/vbs-logo-v3.jpeg')" alt="VisionBridge Solutions" class="h-9 w-auto object-contain block">
                </div>
            </a>

            {{-- Desktop CTA + full-screen menu trigger. The inline capsule
                 nav links (About/Services/Plans/Portfolio/Our Work) that used
                 to sit here on desktop are gone — those links now live inside
                 #desktop-menu, the full-screen takeover opened by
                 #desktop-menu-btn below. Login/Get Started stay put, exactly
                 as before. Desktop only (hidden md:flex) — mobile keeps its
                 own separate #mobile-menu/#menu-btn, untouched. --}}
            <div class="hidden md:flex items-center gap-4">
                <a id="nav-login" href="{{ route('login') }}" class="relative z-10 opacity-0 inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                    Login
                </a>
                <a id="nav-cta" href="{{ route('intake.create') }}"
                   class="nav-cta-btn inline-flex items-center gap-2 bg-gold hover:bg-gold-light text-navy font-bold text-base px-6 py-2.5 rounded-lg opacity-0 transition-colors duration-200">
                    Get Started
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                <button id="desktop-menu-btn" class="relative z-10 opacity-0 shrink-0" aria-label="Toggle menu" aria-expanded="false" aria-controls="desktop-menu">
                    <span class="hamburger-bar"></span>
                    <span class="hamburger-bar"></span>
                    <span class="hamburger-bar"></span>
                </button>
            </div>

            {{-- Mobile hamburger --}}
            <button id="menu-btn" class="md:hidden relative text-navy/70 hover:text-navy focus:outline-none transition-colors" aria-label="Toggle menu">
                <span class="hamburger-bar"></span>
                <span class="hamburger-bar"></span>
                <span class="hamburger-bar"></span>
            </button>
        </div>

        {{-- Mobile menu — full-screen immersive takeover, not a floating
             dropdown card. #mobile-menu's actual position/sizing is governed
             by the ID-selector rules in mobile-design.css (an ID selector
             always outranks a Tailwind utility class on the same element,
             regardless of what's written here), so the `fixed inset-0`
             classes below are for readability — the real positioning livesx
             there. Structure mirrors the reference layout: logo + close
             button, a divider, the nav links (still animate in staggered via
             mobile-design.css, unchanged), then Get Started pinned to the
             bottom of the screen via `mt-auto`. --}}
        <div id="mobile-menu" class="hidden md:hidden fixed inset-0 z-50 overflow-y-auto"
             style="background:rgba(10,18,30,.55);backdrop-filter:blur(30px);-webkit-backdrop-filter:blur(30px);">
            {{-- Frosted-glass depth layer — reuses .hero-gradient-shift's
                 existing keyframes (already defined globally in this file's
                 own <style> block, not scoped to #hero) for the animated
                 gradient, plus a small drifting-particle field lazily
                 created on first open by mobile-design.js (see the comment
                 there) — so the dark glass reads as alive rather than a flat
                 tinted pane, matching the iOS-style animated-gradient plus
                 moving-particles ask. --}}
            <div class="hero-gradient-shift absolute inset-0 pointer-events-none" aria-hidden="true"></div>
            <div id="mobile-menu-particles" class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true"></div>

            <div class="relative flex flex-col min-h-full px-6 pt-6 pb-8">
                {{-- Premium header — brand wordmark + tagline instead of just
                     the logo mark, so the full-screen takeover itself carries
                     branding rather than reading as a bare settings sheet. --}}
                <div id="mobile-menu-header" class="flex items-start justify-between mb-6">
                    <div>
                        <p class="font-display text-2xl font-bold text-white leading-tight">VisionBridge</p>
                        <p class="text-sm mt-1 leading-snug" style="color:rgba(255,255,255,.55);">Building Websites.<br>Expanding Reach.</p>
                    </div>
                    {{-- Just simulates a click on the hamburger — reuses every
                         bit of existing open/close logic (both the basic
                         `.hidden` toggle below and mobile-design.js's
                         backdrop/stagger cleanup) instead of duplicating it. --}}
                    <button id="mobile-menu-close" type="button" aria-label="Close menu" class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 transition-colors" style="color:rgba(255,255,255,.7);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                {{-- Draws left-to-right on open (mobile-design.js's
                     initMobileMenu, a scaleX tween) instead of just being
                     instantly there — transform-origin:left is what makes a
                     scaleX tween read as "growing rightward" rather than
                     growing from the center. --}}
                <div id="mobile-menu-divider-header" class="h-px" style="background:rgba(255,255,255,.15);transform-origin:left center;"></div>

                {{-- Bigger, scannable menu items — title + short description
                     stacked, instead of one plain line each (which read as a
                     settings list, per the boss's own comparison). Layout
                     (flex-column, no per-link icon) is finished in
                     mobile-design.css, not here — see that file's comment
                     on #mobile-menu-links .mobile-menu-link for why the old
                     icon treatment was dropped rather than kept alongside. --}}
                <div id="mobile-menu-links" class="flex flex-col flex-1 py-4 gap-2">
                    {{-- Icon badge (rounded, gold-gradient glass background)
                         + title/description pair. Same 5 icon shapes as the
                         old dropdown-card design (info/grid/document/image/
                         person) — reused, not reinvented, just given the
                         premium glass-badge treatment instead of a small
                         inline outline glyph. .menu-icon-badge is defined
                         once in mobile-design.css. --}}
                    <a href="{{ $homeAnchor }}#about" class="mobile-menu-link px-4 py-4 rounded-xl transition-all duration-200">
                        <span class="menu-icon-badge shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="#FFE9B0" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><line x1="12" y1="11" x2="12" y2="16"/><circle cx="12" cy="8" r="1" fill="#FFE9B0" stroke="none"/></svg>
                        </span>
                        <span class="flex flex-col">
                            <span class="menu-item-title block text-lg font-bold uppercase tracking-wide text-white">About</span>
                            <span class="menu-item-desc block mt-1">Learn who we are and why businesses trust us.</span>
                        </span>
                    </a>
                    <a href="{{ $homeAnchor }}#services" class="mobile-menu-link px-4 py-4 rounded-xl transition-all duration-200">
                        <span class="menu-icon-badge shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="#FFE9B0" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                        </span>
                        <span class="flex flex-col">
                            <span class="menu-item-title block text-lg font-bold uppercase tracking-wide text-white">Services</span>
                            <span class="menu-item-desc block mt-1">Website Design, Development &amp; Maintenance</span>
                        </span>
                    </a>
                    <a href="{{ $homeAnchor }}#plans" class="mobile-menu-link px-4 py-4 rounded-xl transition-all duration-200">
                        <span class="menu-icon-badge shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="#FFE9B0" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="3" width="16" height="18" rx="2"/><line x1="8" y1="8" x2="16" y2="8"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="8" y1="16" x2="13" y2="16"/></svg>
                        </span>
                        <span class="flex flex-col">
                            <span class="menu-item-title block text-lg font-bold uppercase tracking-wide text-white">Plans</span>
                            <span class="menu-item-desc block mt-1">Choose the right care plan.</span>
                        </span>
                    </a>
                    <a href="{{ $homeAnchor }}#portfolio" class="mobile-menu-link px-4 py-4 rounded-xl transition-all duration-200">
                        <span class="menu-icon-badge shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="#FFE9B0" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5" fill="#FFE9B0" stroke="none"/><path d="M21 15l-5-5L5 21"/></svg>
                        </span>
                        <span class="flex flex-col">
                            <span class="menu-item-title block text-lg font-bold uppercase tracking-wide text-white">Portfolio</span>
                            <span class="menu-item-desc block mt-1">Explore our latest projects.</span>
                        </span>
                    </a>
                    <a href="{{ route('gallery') }}" class="mobile-menu-link px-4 py-4 rounded-xl transition-all duration-200">
                        <span class="menu-icon-badge shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="#FFE9B0" stroke-width="2" viewBox="0 0 24 24"><rect x="7" y="7" width="14" height="14" rx="2"/><path d="M17 7V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2"/></svg>
                        </span>
                        <span class="flex flex-col">
                            <span class="menu-item-title block text-lg font-bold uppercase tracking-wide text-white">Our Work</span>
                            <span class="menu-item-desc block mt-1">Walk through our project gallery.</span>
                        </span>
                    </a>
                    {{-- Teal accent (not the shared gold icon badge) so this
                         reads as "your account" rather than another
                         marketing link, same distinction made on desktop's
                         #nav-login pill. --}}
                    <a href="{{ route('login') }}" class="mobile-menu-link mobile-menu-link--login px-4 py-4 rounded-xl transition-all duration-200">
                        <span class="menu-icon-badge menu-icon-badge--login shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="#8FE8DB" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                        </span>
                        <span class="flex flex-col">
                            <span class="menu-item-title block text-lg font-bold uppercase tracking-wide text-white">Login</span>
                            <span class="menu-item-desc block mt-1">Access your project dashboard.</span>
                        </span>
                    </a>
                    {{-- Real element now, not a ::before — GSAP can't tween a
                         pseudo-element directly, and this needs to draw in
                         with the rest of the open sequence. Used to be a
                         ::before specifically to avoid disturbing an
                         nth-child-based stagger that no longer exists (see
                         mobile-design.js's initMobileMenu), so there's no
                         longer a reason to keep it as one. --}}
                    <div id="mobile-menu-divider-cta" class="h-px mt-auto" style="background:rgba(255,255,255,.15);transform-origin:left center;"></div>
                    <a id="mobile-menu-cta" href="{{ route('intake.create') }}" class="bg-gold text-navy font-bold text-base text-center px-4 py-4 rounded-xl inline-flex items-center justify-center gap-2">
                        Get Started
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Desktop full-screen menu — separate from #mobile-menu above,
             opened by #desktop-menu-btn. Reference-matched layout: brand +
             contact top-left, CLOSE top-right, giant stacked links, tagline
             bottom-left, glow accent bottom-right. --}}
        <div id="desktop-menu" aria-hidden="true">
            {{-- Custom trailing cursor — see #desktop-menu-cursor-dot/ring
                 in the <style> block above; safe to nest here since
                 #desktop-menu itself has no transform/filter/will-change
                 that would offset position:fixed descendants (unlike the
                 footer, where these had to live outside it instead). --}}
            <div id="desktop-menu-cursor-dot" aria-hidden="true"></div>
            <div id="desktop-menu-cursor-ring" aria-hidden="true"></div>

            {{-- Ambient galaxy-particles GIF backdrop — mix-blend-mode:screen
                 against this menu's near-black background so only the bright
                 particles read (same technique used elsewhere on the site
                 for GIFs over dark sections). The file is 4.7MB, so `src`
                 is intentionally left unset here — the open/close controller
                 script below only assigns it from data-src the first time
                 the menu is actually opened, so desktop visitors who never
                 open the menu never download it. --}}
            <img id="desktop-menu-gif-bg" data-src="@assetv('image/galaxy-particles.gif')" alt="" aria-hidden="true"
                 class="absolute inset-0 w-full h-full object-cover pointer-events-none"
                 style="opacity:0;mix-blend-mode:screen;">
            <div id="desktop-menu-glow" aria-hidden="true"></div>
            <div class="relative h-full max-w-7xl mx-auto px-10 lg:px-16 py-10 flex flex-col">
                <div class="flex items-start justify-between">
                    <div id="desktop-menu-brand">
                        <p id="desktop-menu-brand-name" class="font-display text-2xl font-bold text-white leading-tight mb-4">VisionBridge</p>
                        <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,.55);">
                            <a href="mailto:support@visionbridgesolutions.com" class="hover:text-gold transition-colors desktop-menu-contact-link">support@visionbridgesolutions.com</a><br>
                            <a href="tel:5550000000" class="hover:text-gold transition-colors desktop-menu-contact-link">(404) 426-2856</a>
                        </p>
                    </div>
                    <button id="desktop-menu-close" type="button" aria-label="Close menu">Close</button>
                </div>

                <nav class="flex-1 flex flex-col items-end justify-center gap-1" aria-label="Main">
                    <a href="{{ route('home') }}#hero" class="desktop-menu-link">Home</a>
                    <a href="{{ $homeAnchor }}#about" class="desktop-menu-link">About</a>
                    <a href="{{ $homeAnchor }}#services" class="desktop-menu-link">Services</a>
                    <a href="{{ $homeAnchor }}#plans" class="desktop-menu-link">Plans</a>
                    <a href="{{ $homeAnchor }}#portfolio" class="desktop-menu-link">Portfolio</a>
                    <a href="{{ route('gallery') }}" class="desktop-menu-link">Our Work</a>
                    <a href="{{ route('contact') }}" class="desktop-menu-link">Contact</a>
                </nav>

                <p id="desktop-menu-tagline" class="text-xs tracking-widest uppercase" style="color:rgba(201,168,76,0.7);">Building Websites. Expanding Reach.</p>
            </div>
        </div>
    </nav>

    {{-- Section progress rail — homepage only (targets homepage section IDs) --}}
    @if (request()->routeIs('home'))
        <nav id="section-rail" aria-label="Page sections">
            <div id="rail-track"></div>
            <div id="rail-progress"></div>
            @foreach ([
                ['id' => 'hero',        'label' => 'Home'],
                ['id' => 'portfolio',   'label' => 'Portfolio'],
                ['id' => 'about',       'label' => 'About'],
                ['id' => 'services',    'label' => 'Services'],
                ['id' => 'why',         'label' => 'Why Us'],
                ['id' => 'plans',       'label' => 'Plans'],
                ['id' => 'partnership', 'label' => 'Partnership'],
            ] as $rail)
                <button type="button" class="rail-dot" data-rail-target="{{ $rail['id'] }}" aria-label="Jump to {{ $rail['label'] }}">
                    <span class="rail-dot-label">{{ $rail['label'] }}</span>
                </button>
            @endforeach
        </nav>

        {{-- GIF page transition — covers the screen, jumps scroll position
             invisibly behind it, then reveals. Replaces the previous
             hand-drawn "flying plane over bridge" SVG/GSAP animation with
             website-animation-transition.gif. That file is 13.6MB, so it
             isn't preloaded here directly — the script below fetches it in
             the background shortly after the page settles (not blocking
             initial render, but very likely cached before the first click),
             rather than only fetching on first trigger, since a first-ever
             transition on a slow connection would otherwise show a blank
             overlay for however long the download takes. --}}
        <div id="flight-transition" style="position:fixed;inset:0;z-index:9990;opacity:0;pointer-events:none;background:#05070B;overflow:hidden;">
            <img id="flight-transition-gif" data-src="@assetv('image/website-animation-transition.gif')" alt=""
                 style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
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
    <footer id="site-footer" class="text-white" style="background:#0B0F17;">

        {{-- Ambient rotating-icosahedron GIF — centered behind the whole
             footer, both desktop and mobile. Black background in the
             source + mix-blend-mode:screen keeps only the glowing wireframe
             visible against the footer's own near-black background (same
             technique used for GIFs over dark sections elsewhere on the
             site). Low opacity + centered so it reads as ambient depth
             behind the columns/wordmark, not a distraction from the text. --}}
        <img src="@assetv('image/times-keep-turning.gif')" alt="" aria-hidden="true"
             class="absolute pointer-events-none" style="
             top:50%;left:50%;transform:translate(-50%,-50%);
             width:min(70vw,700px);height:auto;z-index:0;
             opacity:.22;mix-blend-mode:screen;">

        {{-- ── Main columns ── --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[1.3fr_1fr_1fr_1fr] gap-x-10 gap-y-8 md:gap-y-10 mb-8 md:mb-12">

                {{-- Column 1: Brand --}}
                <div id="footer-col-1" class="footer-col">
                    {{-- Sized up from h-9 (36px) to h-20 (80px) so it actually
                         reads as the brand mark for this whole footer, not a
                         small thumbnail — plus a soft gold glow behind it
                         (same radial-glow technique used elsewhere on the
                         site, e.g. the medallion/halo treatments) so it lifts
                         off the dark background instead of just being bigger. --}}
                    <div class="relative flex items-center mb-6" style="width:fit-content;">
                        <div class="absolute pointer-events-none" style="width:220px;height:220px;top:50%;left:50%;transform:translate(-50%,-50%);border-radius:50%;background:radial-gradient(circle,rgba(201,168,76,0.22) 0%,transparent 70%);filter:blur(20px);"></div>
                        <img src="@assetv('image/logo/vbs-logo-v3.jpeg')" alt="VisionBridge Solutions" class="relative h-20 w-auto object-contain" style="filter:drop-shadow(0 8px 20px rgba(0,0,0,0.35));">
                    </div>
                    <p class="text-white/70 text-base font-medium leading-relaxed mb-5">Building Websites. Expanding Reach.<br>Helping organizations establish a professional online presence.</p>
                    <ul class="space-y-3 text-base font-medium text-white/70">
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
                                (404) 426-2856<span class="footer-link-bar"></span>
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Column 2: Quick Links --}}
                <div id="footer-col-2" class="footer-col">
                    <h4 class="font-semibold text-gold mb-4">Quick Links</h4>
                    <ul class="space-y-3 text-base font-medium text-white/70">
                        <li><a href="{{ $homeAnchor }}#hero"      class="footer-link hover:text-gold">Home<span class="footer-link-bar"></span></a></li>
                        <li><a href="{{ $homeAnchor }}#about"     class="footer-link hover:text-gold">About Us<span class="footer-link-bar"></span></a></li>
                        <li><a href="{{ $homeAnchor }}#services"  class="footer-link hover:text-gold">Services<span class="footer-link-bar"></span></a></li>
                        <li><a href="{{ $homeAnchor }}#portfolio" class="footer-link hover:text-gold">Portfolio<span class="footer-link-bar"></span></a></li>
                        <li><a href="{{ route('contact') }}"      class="footer-link hover:text-gold">Contact<span class="footer-link-bar"></span></a></li>
                    </ul>
                </div>

                {{-- Column 3: Company --}}
                <div id="footer-col-3" class="footer-col">
                    <h4 class="font-semibold text-gold mb-4">Company</h4>
                    <ul class="space-y-3 text-base font-medium text-white/70">
                        <li><a href="{{ $homeAnchor }}#founder"     class="footer-link hover:text-gold">Meet the Founder<span class="footer-link-bar"></span></a></li>
                        <li><a href="{{ $homeAnchor }}#partnership" class="footer-link hover:text-gold">Our Team<span class="footer-link-bar"></span></a></li>
                        <li><a href="{{ $homeAnchor }}#plans"       class="footer-link hover:text-gold">Care Plans<span class="footer-link-bar"></span></a></li>
                        <li><a href="{{ route('intake.create') }}"  class="footer-link hover:text-gold">Get Started<span class="footer-link-bar"></span></a></li>
                    </ul>
                </div>

                {{-- Column 4: Others --}}
                <div id="footer-col-4" class="footer-col">
                    <h4 class="font-semibold text-gold mb-4">Others</h4>
                    <ul class="space-y-3 text-base font-medium text-white/70">
                        <li><a href="{{ route('login') }}" class="footer-link hover:text-gold">Login<span class="footer-link-bar"></span></a></li>
                        <li><a href="#" class="footer-link hover:text-gold">Privacy Policy<span class="footer-link-bar"></span></a></li>
                        <li><a href="#" class="footer-link hover:text-gold">Terms of Service<span class="footer-link-bar"></span></a></li>
                    </ul>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div id="footer-bottom" class="footer-bottom-bar border-t border-white/10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm font-medium text-white/50">
                <p>&copy; {{ date('Y') }} VisionBridge Solutions. All rights reserved.</p>
                <p class="text-xs tracking-widest uppercase" style="color:rgba(201,168,76,0.55);">Building Websites. Expanding Reach.</p>
            </div>
        </div>

        {{-- ── Giant bleeding wordmark ── --}}
        <div class="footer-wordmark-wrap" aria-hidden="true">
            <span class="footer-wordmark font-display font-extrabold">VISIONBRIDGE</span>
        </div>
    </footer>

    {{-- Footer cursor elements — deliberately outside <footer> itself, see
         the comment on #footer-cursor-dot/#footer-cursor-ring in the
         <style> block above for why. --}}
    <div id="footer-cursor-dot" aria-hidden="true"></div>
    <div id="footer-cursor-ring" aria-hidden="true"></div>

    <script>
        // Re-parent the full-screen menu to the very end of the body element,
        // outside the navbar entirely. It needs to, not just could: the
        // navbar wrapper picks up its own backdrop-filter once the page is
        // scrolled and the pill nav condenses, and any ancestor with a
        // filter, backdrop-filter, or transform becomes the containing block
        // for a fixed-position descendant. That meant the menu's full-screen
        // inset was resolving against the navbar's own short box instead of
        // the viewport, collapsing the whole panel down to just its header
        // row. Moving it out from under that ancestor is the actual fix; no
        // CSS on the menu itself can override an ancestor's containing-block
        // effect. Moving the element like this does not affect any of its
        // existing attributes, children, or the listeners added below.
        document.body.appendChild(document.getElementById('mobile-menu'));

        // Same containing-block issue, same fix — #desktop-menu was nested
        // inside <nav>, whose will-change:transform made it the containing
        // block for #desktop-menu's position:fixed;inset:0, resolving that
        // against the nav bar's own ~72px height instead of the viewport.
        // The dark background was genuinely filling that tiny box; the
        // giant link text just overflowed past it with nothing behind it,
        // exposing the real page underneath.
        document.body.appendChild(document.getElementById('desktop-menu'));

        // Opening/closing itself (the .hidden toggle, the entrance/exit
        // animation, the backdrop, body/button state classes) is owned
        // entirely by mobile-design.js's initMobileMenu controller, which
        // attaches its own click listener directly to #menu-btn — a genuine
        // animated close needs to control exactly when `.hidden` gets added
        // (only after the exit animation finishes), which isn't possible
        // from a plain instant class-toggle running here. The ✕ button
        // still just simulates a real click on the hamburger, so it goes
        // through that same controller rather than a second close path.
        document.getElementById('mobile-menu-close')?.addEventListener('click', () => {
            document.getElementById('menu-btn').click();
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
            const menuBtn  = document.getElementById('desktop-menu-btn');
            const navLinks = document.getElementById('nav-links');
            const cursor   = document.getElementById('nav-cursor');
            const linkEls  = navLinks ? Array.from(navLinks.querySelectorAll('a')) : [];

            // ── Entry: logo → Login/Get Started/menu button ──────────────
            gsap.timeline({ delay: 0.15 })
                .fromTo(logo,               { opacity:0, y:-14 }, { opacity:1, y:0, duration:0.55, ease:'power3.out' })
                .fromTo([login, cta, menuBtn], { opacity:0, y:-10 }, { opacity:1, y:0, duration:0.40, stagger:0.08, ease:'power2.out' }, '-=0.20');

            // ── Transparent → pill on scroll ────────────────────────────
            ScrollTrigger.create({
                start:       'top -50',
                onEnter:     () => inner && inner.classList.add('nav-pill'),
                onLeaveBack: () => {
                    inner && inner.classList.remove('nav-pill');
                    gsap.to(navbar, { y:0, duration:0.35, ease:'power3.out', overwrite:true });
                },
            });


            // ── Premium 3D hover on desktop nav items ────────────────────
            // Two motion channels kept separate so they never fight:
            //  1) state (enter/leave): spring lift + scale + glass/sweep/shadow
            //  2) cursor tilt: pointermove → rotateX/rotateY via GSAP quickTo
            //     (a pre-made setter — the cheapest way to push 60fps values).
            // Gated to fine pointers + no reduced-motion; touch just navigates.
            const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
            const noMotion    = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (navLinks && finePointer && !noMotion) {
                linkEls.forEach(link => {
                    const inner = link.querySelector('.nav-link-inner');
                    const glass = link.querySelector('.nav-link-glass');
                    if (!inner) return;

                    // quickTo = reusable tweened setters; no new tween per frame.
                    const setRotX = gsap.quickTo(inner, 'rotationX', { duration: 0.4, ease: 'power3.out' });
                    const setRotY = gsap.quickTo(inner, 'rotationY', { duration: 0.4, ease: 'power3.out' });

                    link.addEventListener('mouseenter', () => {
                        link.classList.add('is-hover');
                        // lift toward the viewer + subtle scale, spring-eased
                        gsap.to(inner, { y: -4, z: 24, scale: 1.04, duration: 0.5, ease: 'back.out(2.2)', overwrite: 'auto' });
                        // dim the other items to focus the hovered one
                        linkEls.forEach(other => { if (other !== link) gsap.to(other, { opacity: 0.5, duration: 0.3, ease: 'power2.out' }); });
                    });

                    link.addEventListener('mousemove', e => {
                        const r  = inner.getBoundingClientRect();
                        const px = (e.clientX - r.left) / r.width  - 0.5;  // -0.5 … +0.5
                        const py = (e.clientY - r.top)  / r.height - 0.5;
                        setRotY(px * 12);   // ≈ ±6° — subtle, never exaggerated
                        setRotX(-py * 12);
                        if (glass) {
                            glass.style.setProperty('--mx', ((px + 0.5) * 100).toFixed(1) + '%');
                            glass.style.setProperty('--my', ((py + 0.5) * 100).toFixed(1) + '%');
                        }
                    });

                    link.addEventListener('mouseleave', () => {
                        link.classList.remove('is-hover');
                        setRotX(0); setRotY(0);
                        gsap.to(inner, { y: 0, z: 0, scale: 1, duration: 0.6, ease: 'elastic.out(1, 0.55)', overwrite: 'auto' });
                        linkEls.forEach(other => gsap.to(other, { opacity: 1, duration: 0.35, ease: 'power2.out' }));
                    });
                });
            }

            // ── Magnetic pull on CTA ─────────────────────────────────────
            // Skipped on touch — a tap fires mousemove with no mouseleave,
            // which can leave the button stuck nudged off its resting spot.
            if (cta && !window.matchMedia('(hover: none), (pointer: coarse)').matches) {
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

    <!-- Desktop full-screen menu — open/close controller, entirely separate
         from mobile's #mobile-menu/#menu-btn logic further up. Same overall
         shape as that mobile controller (animating lock, gsap.set for the
         hidden start state, a staggered timeline in, a faster un-staggered
         fade out, graceful instant show/hide if GSAP hasn't loaded). -->
    <script defer>
    (function () {
        var btn      = document.getElementById('desktop-menu-btn');
        var menu     = document.getElementById('desktop-menu');
        var closeBtn = document.getElementById('desktop-menu-close');
        if (!btn || !menu) return;

        var brand   = document.getElementById('desktop-menu-brand');
        var tagline = document.getElementById('desktop-menu-tagline');
        var glow    = document.getElementById('desktop-menu-glow');
        var gifBg   = document.getElementById('desktop-menu-gif-bg');
        var links   = Array.prototype.slice.call(menu.querySelectorAll('.desktop-menu-link'));

        var isOpen = false;
        var animating = false;

        // Preload + decode the 4.7MB galaxy-particles GIF during idle time
        // shortly after page load, instead of only assigning `src` the
        // first time the menu opens. Assigning it at click time meant the
        // download + decode of a file that size happened synchronously
        // right as the GSAP entrance timeline started, which was blocking
        // the render pipeline just long enough for the Hero behind the
        // (fixed, full-screen) menu to flash through between frames —
        // same idea as the flight-transition GIF preload further down.
        function preloadGifBg() {
            if (gifBg && gifBg.dataset.src) {
                gifBg.src = gifBg.dataset.src;
                gifBg.removeAttribute('data-src');
            }
        }
        if ('requestIdleCallback' in window) requestIdleCallback(preloadGifBg, { timeout: 4000 });
        else setTimeout(preloadGifBg, 2000);

        function openMenu() {
            if (isOpen || animating) return;

            preloadGifBg(); // no-op if already loaded; covers opening before idle callback fires

            isOpen = true;
            animating = true;

            menu.classList.add('is-visible');
            btn.classList.add('is-open');
            btn.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';

            // Tells the Hero's continuously-running decorative animations
            // (particles, orbit rings, gradient drift — see the offscreen
            // anim-pause script below and initHeroParticles in home.blade.php)
            // to pause while this opaque full-screen menu covers them. Left
            // running, those layers kept compositing every frame underneath
            // the menu's own open animation with nothing on screen to show
            // for it — the GPU contention from both running at once is what
            // was showing up as the Hero flickering (and the machine heating
            // up) specifically while the menu was open.
            window.dispatchEvent(new CustomEvent('desktopmenu:open'));

            if (typeof gsap === 'undefined') { animating = false; return; }

            gsap.set(menu, { opacity: 1 });
            gsap.set(brand, { opacity: 0, y: -16 });
            gsap.set(closeBtn, { opacity: 0, x: 16 });
            gsap.set(links, { opacity: 0, y: 44 });
            gsap.set(tagline, { opacity: 0 });
            gsap.set(glow, { opacity: 0 });
            gsap.set(gifBg, { opacity: 0 });

            gsap.timeline({ onComplete: function () { animating = false; } })
                // Brand + Close settle in first, together
                .to(brand,    { opacity: 1, y: 0, duration: 0.5, ease: 'power3.out' }, 0.05)
                .to(closeBtn, { opacity: 1, x: 0, duration: 0.4, ease: 'power3.out' }, 0.05)
                // Giant links rise in one after another — the main event
                .to(links,    { opacity: 1, y: 0, duration: 0.65, stagger: 0.07, ease: 'power3.out' }, 0.15)
                // Tagline + ambient glow/GIF backdrop settle in last, overlapping the tail of the links
                .to(tagline,  { opacity: 1, duration: 0.4, ease: 'power2.out' }, '-=0.25')
                .to(glow,     { opacity: 1, duration: 0.8, ease: 'power2.out' }, '-=0.6')
                .to(gifBg,    { opacity: 0.35, duration: 0.8, ease: 'power2.out' }, '-=0.8');
        }

        function closeMenu() {
            if (!isOpen || animating) return;
            isOpen = false;
            animating = true;

            function finish() {
                menu.classList.remove('is-visible');
                btn.classList.remove('is-open');
                btn.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
                animating = false;
                // Resume Hero's decorative animations only once the menu has
                // actually finished fading out, not while it's still
                // covering the screen — see the matching dispatch in
                // openMenu() above for why these get paused in the first place.
                window.dispatchEvent(new CustomEvent('desktopmenu:close'));
            }

            if (typeof gsap === 'undefined') { finish(); return; }

            // Close is deliberately quick and un-staggered (everything fades
            // up together, same convention as the mobile menu's own close) —
            // a staggered close reads as sluggish, not premium.
            var everything = [brand, closeBtn].concat(links, [tagline, glow, gifBg]).filter(Boolean);
            gsap.timeline({ onComplete: finish })
                .to(everything, { opacity: 0, y: -14, duration: 0.25, ease: 'power1.in' })
                .to(menu, { opacity: 0, duration: 0.3, ease: 'power1.in' }, '-=0.1');
        }

        btn.addEventListener('click', function () {
            if (isOpen) closeMenu(); else openMenu();
        });
        if (closeBtn) closeBtn.addEventListener('click', closeMenu);

        links.forEach(function (link) {
            link.addEventListener('click', closeMenu);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && isOpen) closeMenu();
        });

        // The button itself is hidden below md, so this can only ever be
        // opened on desktop — but if the window is resized down to mobile
        // while it's open, this still closes it rather than leaving body
        // scroll locked with no visible way to close.
        window.addEventListener('resize', function () {
            if (isOpen && window.innerWidth < 768) closeMenu();
        });
    })();
    </script>

    {{-- Desktop full-screen menu — custom trailing cursor. Same lag-stretch
         technique as Contact/footer (see contact.blade.php for the
         original and its inline reasoning). No open/closed gating needed:
         #desktop-menu has pointer-events:none while closed, so mousemove
         simply can't reach it until the menu is actually open. --}}
    <script defer>
    (function () {
        function initDesktopMenuCursor() {
            if (typeof gsap === 'undefined') { setTimeout(initDesktopMenuCursor, 80); return; }

            var menu = document.getElementById('desktop-menu');
            var dot  = document.getElementById('desktop-menu-cursor-dot');
            var ring = document.getElementById('desktop-menu-cursor-ring');
            if (!menu || !dot || !ring) return;
            if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            var moveDotX = gsap.quickTo(dot, 'x', { duration: 0.05, ease: 'power3.out' });
            var moveDotY = gsap.quickTo(dot, 'y', { duration: 0.05, ease: 'power3.out' });

            var mouseX = 0, mouseY = 0, ringX = 0, ringY = 0;
            var ringReady = false, pressed = false, hovering = false, visible = false;
            // The giant nav link currently being "morphed" onto, if any —
            // while set, the ticker below hands the ring's position over to
            // the morph tween entirely instead of fighting it with the
            // lag-follow loop.
            var morphedLink = null;

            function growRing(w, h) {
                gsap.to(ring, { width: w, height: h, duration: 0.35, ease: 'power3.out', overwrite: 'auto' });
            }

            menu.addEventListener('mousemove', function (e) {
                mouseX = e.clientX; mouseY = e.clientY;
                moveDotX(mouseX); moveDotY(mouseY);
                if (!ringReady) { ringX = mouseX; ringY = mouseY; ringReady = true; }
                if (!visible) {
                    visible = true;
                    menu.classList.add('has-custom-cursor');
                    dot.classList.add('is-visible');
                    ring.classList.add('is-visible');
                }
            });

            menu.addEventListener('mouseleave', function () {
                visible = false;
                menu.classList.remove('has-custom-cursor');
                dot.classList.remove('is-visible');
                ring.classList.remove('is-visible');
            });

            gsap.ticker.add(function () {
                if (!visible || morphedLink) return;
                ringX += (mouseX - ringX) * 0.1;
                ringY += (mouseY - ringY) * 0.1;
                var dist = Math.hypot(mouseX - ringX, mouseY - ringY);
                var stretch = pressed ? 0.8 : gsap.utils.clamp(1, 1.7, 1 + dist / 130);
                gsap.set(ring, { x: ringX, y: ringY, scale: hovering ? 1 : stretch });
            });

            // Giant nav links (Home/About/Services/…) — the ring morphs into
            // an oblong pill spanning the text's full width, left edge to
            // right edge, instead of just growing into a bigger circle
            // (these words are far wider than the ring's default size). It
            // also locks onto the link's own center instead of the raw
            // mouse position, so it reads as a highlight sliding under the
            // word rather than a circle that just happens to be big enough
            // to cover it.
            menu.querySelectorAll('.desktop-menu-link').forEach(function (link) {
                link.addEventListener('mouseenter', function () {
                    hovering = true;
                    morphedLink = link;
                    ring.classList.add('is-hovering');
                    var r = link.getBoundingClientRect();
                    var padX = 32, padY = 16;
                    gsap.to(ring, {
                        x: r.left + r.width / 2,
                        y: r.top + r.height / 2,
                        width: r.width + padX * 2,
                        height: r.height + padY * 2,
                        scale: 1,
                        duration: 0.45,
                        ease: 'power3.out',
                        overwrite: 'auto',
                    });
                });
                link.addEventListener('mouseleave', function () {
                    hovering = false;
                    morphedLink = null;
                    ring.classList.remove('is-hovering');
                    // Resume the lag-follow from wherever the mouse actually
                    // is now, not from the link's center the ring was just
                    // morphed onto — avoids a visible jump back.
                    ringX = mouseX; ringY = mouseY;
                    growRing(46, 46);
                });
            });

            // Everything else clickable in the menu (Close button, email/
            // phone contact links) keeps the original, simpler circle-grow
            // acquire treatment.
            menu.querySelectorAll('a, button').forEach(function (el) {
                if (el.classList.contains('desktop-menu-link')) return;
                el.addEventListener('mouseenter', function () { hovering = true; ring.classList.add('is-hovering'); growRing(68, 68); });
                el.addEventListener('mouseleave', function () { hovering = false; ring.classList.remove('is-hovering'); growRing(46, 46); });
            });

            menu.addEventListener('mousedown', function () { pressed = true; });
            menu.addEventListener('mouseup', function () { pressed = false; });
        }
        if (document.readyState !== 'loading') { initDesktopMenuCursor(); }
        else { window.addEventListener('DOMContentLoaded', initDesktopMenuCursor); }
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

    {{-- GIF page transition — covers the jump between sections instead of a
         visible fast-scroll. Triggers on every in-page anchor click (nav,
         mobile menu, footer Quick Links) plus the section-rail dots below. --}}
    <script defer>
    (function () {
        function initFlightTransition() {
            const overlay = document.getElementById('flight-transition');
            if (!overlay) return; // not on the homepage

            if (typeof gsap === 'undefined') { setTimeout(initFlightTransition, 80); return; }

            const gif = document.getElementById('flight-transition-gif');
            let flying = false;

            // Fetch the 13.6MB GIF in the background shortly after the page
            // settles — not blocking initial render, but almost certainly
            // cached before anyone actually clicks a nav link, unlike
            // fetching it only on the first transition (which would show a
            // blank overlay for however long that download takes).
            function preloadGif() {
                if (gif && gif.dataset.src) {
                    gif.src = gif.dataset.src;
                    gif.removeAttribute('data-src');
                }
            }
            if ('requestIdleCallback' in window) requestIdleCallback(preloadGif, { timeout: 4000 });
            else setTimeout(preloadGif, 2000);

            window.flyTransition = function (targetEl) {
                if (!targetEl) return;
                // Below the same breakpoint the horizontal-wipe and section-rail
                // already use as "desktop-only flourish" — on mobile this
                // full-screen takeover just reads as the page freezing, so fall
                // back to a plain smooth scroll instead.
                if (!gif || flying || window.innerWidth < 1024) {
                    targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    return;
                }
                flying = true;
                overlay.style.pointerEvents = 'all';
                preloadGif(); // no-op if already loaded; covers the rare case it wasn't yet

                const tl = gsap.timeline({ onComplete() { overlay.style.pointerEvents = 'none'; flying = false; } });
                tl.to(overlay, { opacity: 1, duration: 0.25, ease: 'power2.out' })
                    // Scroll jump happens while the GIF is holding at full
                    // opacity, fully hiding it, same as the old plane/wave version.
                    .call(() => targetEl.scrollIntoView({ behavior: 'auto', block: 'start' }), null, 0.9)
                    .to(overlay, { opacity: 0, duration: 0.4, ease: 'power2.in' }, 1.3);
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

            const progress = document.getElementById('rail-progress');

            function setActive(id) {
                dots.forEach(dot => dot.classList.toggle('is-active', dot.dataset.railTarget === id));

                const activeIndex = dots.findIndex(dot => dot.dataset.railTarget === id);

                // Fade dots further from the active one — gives a sense of
                // depth/focus rather than every dot reading at equal weight
                dots.forEach((dot, i) => {
                    if (i === activeIndex) { dot.style.opacity = '1'; return; }
                    const distance = Math.abs(i - activeIndex);
                    dot.style.opacity = String(Math.max(0.3, 1 - distance * 0.2));
                });

                const activeDot = dots[activeIndex];
                if (progress && activeDot) {
                    const dotRect  = activeDot.getBoundingClientRect();
                    const railRect = rail.getBoundingClientRect();
                    progress.style.height = (dotRect.top - railRect.top + dotRect.height / 2 - 4) + 'px';
                }
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

            const video = document.getElementById('intro-video');
            const skip  = document.getElementById('intro-skip');
            let revealed = false;

            // Skip the video entirely for visitors who've asked for less
            // motion — avoids the decode/playback cost altogether instead
            // of just not animating it.
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                overlay.style.display = 'none';
                window.dispatchEvent(new CustomEvent('intro:complete'));
                return;
            }

            document.body.style.overflow = 'hidden';

            function revealSite() {
                if (revealed) return;
                revealed = true;

                // Falls back to a plain CSS fade if GSAP hasn't loaded yet
                // (e.g. the CDN script was blocked or slow) — every dismiss
                // path below (video end/error, skip click, the safety-net
                // timeout) has to be able to reach this regardless of GSAP's
                // load state, or a stuck overlay (z-index:9999, no
                // pointer-events:none) blocks every click on the page with
                // no way out. This used to be impossible: the whole function
                // wouldn't even set up the skip button or the safety net
                // until `gsap` existed, so a failed/slow GSAP load could trap
                // visitors on the intro screen permanently.
                if (typeof gsap === 'undefined') {
                    overlay.style.transition = 'opacity .4s ease';
                    overlay.style.opacity = '0';
                    setTimeout(() => {
                        overlay.style.display = 'none';
                        document.body.style.overflow = '';
                        window.dispatchEvent(new CustomEvent('intro:complete'));
                    }, 400);
                    return;
                }

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

            // Safety net: never trap a visitor on the intro. Registered
            // immediately (not gated behind GSAP being loaded), since this
            // is the last line of defense against a stuck full-page overlay.
            setTimeout(revealSite, 12000);

            // Clicking the logo always replays the intro from the start —
            // only reachable here since the overlay (and this whole
            // function) only exists on the homepage; from other pages the
            // logo's href just navigates back to the homepage, where the
            // intro already autoplays on load.
            const logo = document.getElementById('nav-logo');
            if (logo) {
                logo.addEventListener('click', function (e) {
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'instant' });
                    revealed = false;
                    document.body.style.overflow = 'hidden';
                    if (typeof gsap !== 'undefined') {
                        gsap.set(overlay, { scale: 1, opacity: 1, display: 'block' });
                    } else {
                        overlay.style.transform = '';
                        overlay.style.opacity = '1';
                        overlay.style.display = 'block';
                    }
                    if (video) {
                        video.currentTime = 0;
                        video.play().catch(revealSite);
                    } else {
                        revealSite();
                    }
                });
            }
        }
        initIntro();
    })();
    </script>

    {{-- Pause always-running "infinite" CSS animations (orb drift, shimmer,
         pulse, wave glide) while their element is off-screen — pure CSS
         animations, no GSAP dependency, so this can run immediately. --}}
    <script defer>
    (function () {
        function initOffscreenAnimPause() {
            const selectors = [
                '.hero-orb', '#svc-toggle-btn',
                '.shimmer-gold', '.live-dot', '.float-card-1', '.float-card-2',
                '#hscroll-edge-arrow',
                '.hero-gradient-shift', '.hero-ray', '#hero-orbit-glow', '#hero-orbit-bloom', '#hero-orbit-mid',
                '#hero-orbit-inner-mid', '#hero-orbit-inner-glow', '#hero-halo',
                // Desktop laptop idle float + rating-card floats — same
                // always-on infinite CSS animations as the ones above, just
                // missed when this list was first written.
                '#hero-device-frame', '#hero-rating-1', '#hero-rating-2', '#hero-rating-3',
                // Mobile equivalents of the desktop halo/orbit/device-frame
                // animations above (see the matching comments in
                // home.blade.php — mobile gets its own idle float, rotating
                // halo ring, and orbit trail instead of reusing the desktop
                // elements directly).
                '#hero-device-mobile-frame', '#hero-halo-mobile-ring',
                '#hero-trail-mobile-bloom', '#hero-trail-mobile-core',
                '#hero-scroll-dot',
                // Security banner's focal-shield orbit ring (home.blade.php,
                // "Why VisionBridge" section) — same always-on decorative
                // spin as the ones above.
                '#security-glow-ring',
            ];
            const els = document.querySelectorAll(selectors.join(','));
            if (!els.length) return;

            // Real on-screen state per element, tracked separately from
            // `menuOpen` below so that closing the desktop full-screen menu
            // restores each element to whatever state it actually had
            // (still off-screen vs. genuinely back in view) instead of
            // blindly resuming everything.
            const intersecting = new WeakMap();
            let menuOpen = false;

            const io = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    intersecting.set(entry.target, entry.isIntersecting);
                    entry.target.classList.toggle('anim-paused', menuOpen || !entry.isIntersecting);
                });
            }, { rootMargin: '150px 0px' });

            els.forEach(el => io.observe(el));

            // Force-pause everything while the desktop full-screen menu is
            // open (it sits on an opaque layer directly over the Hero, so
            // these animations keep compositing for nothing underneath it —
            // see the dispatch in the menu open/close controller above for
            // why), then restore each element's real on-screen state on
            // close rather than assuming it's back in view.
            window.addEventListener('desktopmenu:open', () => {
                menuOpen = true;
                els.forEach(el => el.classList.add('anim-paused'));
            });
            window.addEventListener('desktopmenu:close', () => {
                menuOpen = false;
                els.forEach(el => {
                    el.classList.toggle('anim-paused', !(intersecting.get(el) ?? true));
                });
            });
        }
        initOffscreenAnimPause();
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
            const cols    = ['footer-col-1', 'footer-col-2', 'footer-col-3', 'footer-col-4'].map(id => document.getElementById(id));
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
                    // Horizontal nudge + zoom on the text (slowed/enlarged
                    // to match the Contact page's own text zoom-on-hover),
                    // underline draws in
                    gsap.to(link, { x: 5, scale: 1.12, duration: 0.6, ease: 'power3.out' });
                    gsap.to(bar,  { scaleX: 1, duration: 0.34, ease: 'power3.out' });
                });

                link.addEventListener('mouseleave', () => {
                    gsap.killTweensOf([link, bar]);
                    gsap.to(link, { x: 0, scale: 1, duration: 0.6, ease: 'power3.out' });
                    gsap.to(bar,  { scaleX: 0, duration: 0.28, ease: 'power2.in' });
                });
            });

            // ── 4. Custom trailing cursor — same lag-stretch technique as
            //    the Contact page (see contact.blade.php for the original
            //    and its inline reasoning). Desktop/fine-pointer only.
            const footerCursorDot  = document.getElementById('footer-cursor-dot');
            const footerCursorRing = document.getElementById('footer-cursor-ring');
            const reduceMotion     = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const finePointer      = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;

            if (footer && footerCursorDot && footerCursorRing && finePointer && !reduceMotion) {
                const moveDotX = gsap.quickTo(footerCursorDot, 'x', { duration: 0.05, ease: 'power3.out' });
                const moveDotY = gsap.quickTo(footerCursorDot, 'y', { duration: 0.05, ease: 'power3.out' });

                let mouseX = 0, mouseY = 0, ringX = 0, ringY = 0;
                let ringReady = false, pressed = false, hovering = false, visible = false;

                footer.addEventListener('mousemove', (e) => {
                    mouseX = e.clientX; mouseY = e.clientY;
                    moveDotX(mouseX); moveDotY(mouseY);
                    if (!ringReady) { ringX = mouseX; ringY = mouseY; ringReady = true; }
                    if (!visible) {
                        visible = true;
                        footer.classList.add('has-custom-cursor');
                        footerCursorDot.classList.add('is-visible');
                        footerCursorRing.classList.add('is-visible');
                    }
                });

                footer.addEventListener('mouseleave', () => {
                    visible = false;
                    footer.classList.remove('has-custom-cursor');
                    footerCursorDot.classList.remove('is-visible');
                    footerCursorRing.classList.remove('is-visible');
                });

                gsap.ticker.add(() => {
                    if (!visible) return;
                    ringX += (mouseX - ringX) * 0.1;
                    ringY += (mouseY - ringY) * 0.1;
                    const dist = Math.hypot(mouseX - ringX, mouseY - ringY);
                    const stretch = pressed ? 0.8 : gsap.utils.clamp(1, 1.7, 1 + dist / 130);
                    gsap.set(footerCursorRing, { x: ringX, y: ringY, scale: hovering ? 1 : stretch });
                });

                footer.querySelectorAll('a, button').forEach((el) => {
                    el.addEventListener('mouseenter', () => { hovering = true; footerCursorRing.classList.add('is-hovering'); });
                    el.addEventListener('mouseleave', () => { hovering = false; footerCursorRing.classList.remove('is-hovering'); });
                });

                footer.addEventListener('mousedown', () => { pressed = true; });
                footer.addEventListener('mouseup', () => { pressed = false; });
            }
        }
        initFooter();
    })();
    </script>

    @yield('scripts')

    <script src="@assetv('mobile-design.js')"></script>

</body>
</html>
