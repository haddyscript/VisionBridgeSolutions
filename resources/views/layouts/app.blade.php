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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">

    {{-- Start fetching GSAP early — these load via a deferred script tag
         near the bottom of body, so without a preload hint the browser
         doesn't even discover the URLs until the parser reaches that far
         down, delaying every animation init function's first successful
         retry. --}}
    <link rel="preload" as="script" href="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js">
    <link rel="preload" as="script" href="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js">

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
                        display: ['"Playfair Display"', 'serif'],
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

        /* ─── Portfolio floating service badges — same glassmorphism
             pill treatment as .float-card, reused for the 4 corner
             badges around the Our Work video panel. ─── */
        .portfolio-badge {
            position:absolute; pointer-events:none; z-index:3;
            background:rgba(255,255,255,.92); border:1px solid rgba(47,58,69,.08);
            box-shadow:0 8px 28px rgba(47,58,69,.10);
            backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px);
            border-radius:9999px; padding:10px 20px;
            will-change:transform;
        }
        .portfolio-badge-1 { top:6%;    left:2%;  animation:float-a 5s   ease-in-out infinite; }
        .portfolio-badge-2 { top:8%;    right:2%; animation:float-b 6.5s ease-in-out infinite 0.6s; }
        .portfolio-badge-3 { bottom:8%; left:3%;  animation:float-b 6s   ease-in-out infinite 1.2s; }
        .portfolio-badge-4 { bottom:6%; right:3%; animation:float-a 5.5s ease-in-out infinite 1.8s; }

        /* ─── Portfolio project cards — premium agency showcase ─── */
        .portfolio-filter-btn {
            padding:10px 22px; border-radius:9999px; font-size:0.85rem; font-weight:600;
            letter-spacing:0.02em; color:rgba(21,32,44,0.62);
            background:rgba(255,255,255,0.7); border:1.5px solid rgba(21,32,44,0.10);
            cursor:pointer; transition:background .28s ease, color .28s ease, border-color .28s ease;
        }
        .portfolio-filter-btn:hover { border-color:rgba(201,168,76,0.45); color:#15202C; }
        .portfolio-filter-btn.is-active {
            background:#15202C; color:#C9A84C; border-color:#15202C;
            box-shadow:0 8px 20px rgba(21,32,44,0.18);
        }

        .portfolio-hidden { display:none !important; }

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

        /* ─── Hero background — animated grain/noise texture ───
             feTurbulence data-URI tile, translated in discrete steps (not
             background-position) so the browser can composite it on the GPU
             instead of repainting the gradient/turbulence each frame. */
        .hero-noise {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-repeat: repeat;
            mix-blend-mode: overlay;
            animation: hero-noise-drift 7s steps(8) infinite alternate;
            will-change: transform;
        }
        @keyframes hero-noise-drift {
            0%   { transform: translate3d(0,0,0); }
            100% { transform: translate3d(-40px,-30px,0); }
        }

        /* ─── Site-wide film grain — one fixed overlay covering the whole
             viewport, not a per-section copy. Scrolling from the hero into
             every lighter section below reads as one continuous cinematic
             texture instead of the grain stopping at the hero's edge. Same
             feTurbulence tile + drift as .hero-noise; mix-blend-mode:overlay
             adapts to both the hero's dark bg and the lighter sections below
             without needing a different opacity per section. ─── */
        .page-noise {
            position: fixed;
            inset: 0;
            z-index: 9999;
            pointer-events: none;
            opacity: .035;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
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
            .hero-gradient-shift, .hero-ray, .hero-noise { animation: none !important; }
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

        /* ─── Our Team medallion: rotating gloss sweep ─── */
        .medallion-sweep { animation: medallion-spin 6s linear infinite; }
        @keyframes medallion-spin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

        /* ─── Footer: peeking mascot ─── */
        #footer-mascot {
            position: absolute;
            /* Relative to #footer-col-3 (Contact column) now, not the whole
               footer — sits just above the "Contact" heading. Stays within
               that column's own box (not poking above the footer entirely)
               to avoid being clipped by the previous section's content,
               which sits at a higher stacking order. */
            top: -52px;
            left: 0;
            width: 60px;
            z-index: 2;
            pointer-events: none;
        }
        #footer-mascot img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: auto;
            transform: rotate(180deg);
        }
        #footer-mascot .mascot-smile { opacity: 0; }
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
            <a id="nav-logo" href="{{ route('home') }}#hero" class="flex items-center shrink-0 opacity-0">
                <img src="@assetv('image/logo/vbs-logo-v3.jpeg')" alt="VisionBridge Solutions" class="h-9 w-auto object-contain">
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
                <a id="nav-cta" href="{{ route('intake.create') }}"
                   class="nav-cta-btn inline-flex items-center gap-2 bg-gold hover:bg-gold-light text-navy font-bold text-base px-6 py-2.5 rounded-lg opacity-0 transition-colors duration-200">
                    Get Started
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            {{-- Mobile hamburger --}}
            <button id="menu-btn" class="md:hidden relative text-navy/70 hover:text-navy focus:outline-none transition-colors" aria-label="Toggle menu">
                <span class="hamburger-bar"></span>
                <span class="hamburger-bar"></span>
                <span class="hamburger-bar"></span>
            </button>
        </div>

        {{-- Mobile dropdown (glassmorphism, outside pill) --}}
        <div id="mobile-menu" class="hidden md:hidden mt-2 mx-2 rounded-2xl overflow-hidden"
             style="background:rgba(255,255,255,0.96);backdrop-filter:blur(20px);border:1px solid rgba(47,58,69,0.08);box-shadow:0 8px 32px rgba(47,58,69,0.14);">
            <div id="mobile-menu-links" class="flex flex-col p-4 gap-1">
                <a href="{{ $homeAnchor }}#about"     class="mobile-menu-link text-navy/75 hover:text-gold text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-navy/5 transition-all duration-200">About</a>
                <a href="{{ $homeAnchor }}#services"  class="mobile-menu-link text-navy/75 hover:text-gold text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-navy/5 transition-all duration-200">Services</a>
                <a href="{{ $homeAnchor }}#plans"     class="mobile-menu-link text-navy/75 hover:text-gold text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-navy/5 transition-all duration-200">Plans</a>
                <a href="{{ $homeAnchor }}#portfolio" class="mobile-menu-link text-navy/75 hover:text-gold text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-navy/5 transition-all duration-200">Portfolio</a>
                <a href="{{ route('login') }}" class="mobile-menu-link text-navy/75 hover:text-gold text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-navy/5 transition-all duration-200">Client Login</a>
                <a id="mobile-menu-cta" href="{{ route('intake.create') }}"   class="mt-2 bg-gold text-navy font-bold text-base text-center px-4 py-3 rounded-xl">Get Started</a>
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
        <div id="flight-transition" style="position:fixed;inset:0;z-index:9990;opacity:0;pointer-events:none;background:#FFFFFF;overflow:hidden;">
            <svg viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" style="position:absolute;inset:0;width:100%;height:100%;">
                <defs>
                    <linearGradient id="bridgeMetal" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#E6E9ED"/>
                        <stop offset="100%" stop-color="#B9C1C9"/>
                    </linearGradient>
                    <linearGradient id="planeBody" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0%" stop-color="#1F2730"/>
                        <stop offset="100%" stop-color="#3F4C59"/>
                    </linearGradient>
                    <linearGradient id="trailFadeStrong" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0%" stop-color="#2CA6A4" stop-opacity="0"/>
                        <stop offset="100%" stop-color="#2CA6A4" stop-opacity="0.85"/>
                    </linearGradient>
                    <linearGradient id="trailFadeLight" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0%" stop-color="#7FD9D6" stop-opacity="0"/>
                        <stop offset="100%" stop-color="#7FD9D6" stop-opacity="0.7"/>
                    </linearGradient>
                    <linearGradient id="skyGradient" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#EAF3F8"/>
                        <stop offset="55%" stop-color="#F7FAFC"/>
                        <stop offset="100%" stop-color="#FFFFFF"/>
                    </linearGradient>
                    <radialGradient id="sunGlow" cx="50%" cy="50%" r="50%">
                        <stop offset="0%" stop-color="#FFE9B0" stop-opacity="0.55"/>
                        <stop offset="55%" stop-color="#C9A84C" stop-opacity="0.18"/>
                        <stop offset="100%" stop-color="#C9A84C" stop-opacity="0"/>
                    </radialGradient>
                </defs>
                {{-- Soft sky backdrop + a warm gold glow the plane climbs
                     toward — echoes the golden-hour bridge photo used
                     elsewhere on the site, instead of a flat white void --}}
                <rect width="1600" height="900" fill="url(#skyGradient)"/>
                <circle cx="1320" cy="200" r="280" fill="url(#sunGlow)"/>
                {{-- Soft clouds — each one drifts/parts away once the plane
                     flies past it (synced in the JS timeline below). Varied
                     opacity reads as atmospheric depth (farther = fainter). --}}
                <g id="cloud-1" fill="#DCE3E9" opacity="0.65">
                    <ellipse cx="260" cy="400" rx="55" ry="26"/>
                    <ellipse cx="305" cy="388" rx="38" ry="22"/>
                    <ellipse cx="215" cy="392" rx="34" ry="20"/>
                </g>
                <g id="cloud-2" fill="#DCE3E9" opacity="0.85">
                    <ellipse cx="660" cy="370" rx="62" ry="28"/>
                    <ellipse cx="712" cy="358" rx="40" ry="22"/>
                    <ellipse cx="608" cy="362" rx="36" ry="20"/>
                </g>
                <g id="cloud-3" fill="#DCE3E9" opacity="0.72">
                    <ellipse cx="1080" cy="360" rx="58" ry="26"/>
                    <ellipse cx="1128" cy="348" rx="38" ry="20"/>
                    <ellipse cx="1030" cy="352" rx="34" ry="19"/>
                </g>
                <g id="cloud-4" fill="#DCE3E9" opacity="0.92">
                    <ellipse cx="1430" cy="340" rx="60" ry="27"/>
                    <ellipse cx="1480" cy="328" rx="38" ry="21"/>
                    <ellipse cx="1378" cy="332" rx="35" ry="19"/>
                </g>
                {{-- Minimalist bridge with twin-leg A-frame pylons (matches the
                     hero/about bridge silhouette motif) and smooth sagging cables --}}
                <g opacity="0.95">
                    <rect x="40" y="616" width="1520" height="14" rx="7" fill="url(#bridgeMetal)"/>
                    {{-- Tower 1: A-frame twin legs --}}
                    <path d="M533,452 L505,628" stroke="url(#bridgeMetal)" stroke-width="15" stroke-linecap="round" fill="none"/>
                    <path d="M533,452 L561,628" stroke="url(#bridgeMetal)" stroke-width="15" stroke-linecap="round" fill="none"/>
                    <circle cx="533" cy="452" r="14" fill="#CCD2D8"/>
                    {{-- Tower 2: A-frame twin legs --}}
                    <path d="M1063,452 L1035,628" stroke="url(#bridgeMetal)" stroke-width="15" stroke-linecap="round" fill="none"/>
                    <path d="M1063,452 L1091,628" stroke="url(#bridgeMetal)" stroke-width="15" stroke-linecap="round" fill="none"/>
                    <circle cx="1063" cy="452" r="14" fill="#CCD2D8"/>
                    {{-- Smooth cubic-bezier cables — each tower only sweeps toward
                         its own nearer side, so they no longer cross mid-span --}}
                    <path d="M533,462 C420,496 180,560 70,622"  fill="none" stroke="#C7CDD3" stroke-width="4"   stroke-linecap="round"/>
                    <path d="M533,462 C450,490 260,538 160,608" fill="none" stroke="#D2D7DC" stroke-width="3"   stroke-linecap="round" opacity="0.7"/>
                    <path d="M533,462 C580,486 650,548 700,612" fill="none" stroke="#C7CDD3" stroke-width="4"   stroke-linecap="round"/>
                    <path d="M533,462 C570,484 600,530 620,600" fill="none" stroke="#D2D7DC" stroke-width="3"   stroke-linecap="round" opacity="0.7"/>
                    <path d="M1063,462 C1016,486 950,548 900,612" fill="none" stroke="#C7CDD3" stroke-width="4" stroke-linecap="round"/>
                    <path d="M1063,462 C1026,484 996,530 980,600" fill="none" stroke="#D2D7DC" stroke-width="3" stroke-linecap="round" opacity="0.7"/>
                    <path d="M1063,462 C1150,490 1320,560 1530,622" fill="none" stroke="#C7CDD3" stroke-width="4" stroke-linecap="round"/>
                    <path d="M1063,462 C1146,490 1306,538 1450,608" fill="none" stroke="#D2D7DC" stroke-width="3" stroke-linecap="round" opacity="0.7"/>
                </g>
                {{-- Plane group — GSAP animates x/y/rotation on this <g> --}}
                <g id="flight-plane">
                    {{-- Teal turbo streaks (smooth gradient fade, brand-consistent) + motion particles --}}
                    <rect x="-175" y="591" width="190" height="9" rx="4.5" fill="url(#trailFadeStrong)"/>
                    <rect x="-120" y="605" width="135" height="5" rx="2.5" fill="url(#trailFadeLight)"/>
                    <rect x="-120" y="577" width="135" height="5" rx="2.5" fill="url(#trailFadeLight)"/>
                    <circle cx="-190" cy="595" r="4" fill="#2CA6A4" opacity="0.5"/>
                    <circle cx="-212" cy="585" r="3" fill="#7FD9D6" opacity="0.4"/>
                    <circle cx="-212" cy="605" r="3" fill="#7FD9D6" opacity="0.4"/>
                    {{-- Bigger airplane with softly rounded wing/tail tips (no sharp points) --}}
                    <path d="M14,588 Q-18,560 -10,548 Q16,562 52,588 Z" fill="url(#planeBody)"/>
                    <path d="M78,614 Q46,644 42,652 Q70,630 124,614 Z" fill="url(#planeBody)"/>
                    {{-- Tail fin + horizontal stabilizer — gives the silhouette
                         a recognizable nose/tail instead of reading as a
                         plain capsule from a distance --}}
                    <path d="M16,589 L4,565 L26,586 Z" fill="url(#planeBody)" opacity="0.95"/>
                    <path d="M10,597 Q-12,593 -22,598 Q-12,603 10,601 Z" fill="url(#planeBody)" opacity="0.9"/>
                    <rect x="10" y="588" width="135" height="24" rx="12" fill="url(#planeBody)"/>
                    {{-- Top-edge highlight — reads as cylindrical shading on the fuselage --}}
                    <rect x="14" y="590" width="120" height="2.5" rx="1.2" fill="#6B7986" opacity="0.45"/>
                    <ellipse cx="155" cy="600" rx="15" ry="13" fill="url(#planeBody)"/>
                    <rect x="14" y="598" width="125" height="4" rx="2" fill="#C9A84C" opacity="0.85"/>
                    <ellipse cx="115" cy="596" rx="11" ry="8" fill="#EAF3F8" opacity="0.92"/>
                    <ellipse cx="119" cy="593" rx="4" ry="2.4" fill="#FFFFFF" opacity="0.6"/>
                </g>
            </svg>
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
    <footer id="site-footer" class="text-navy" style="background:#FFFFFF;">

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
                    fill="#FFFFFF"
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
                    <div class="flex items-center mb-4">
                        <img src="@assetv('image/logo/vbs-logo-v3.jpeg')" alt="VisionBridge Solutions" class="h-9 w-auto object-contain">
                    </div>
                    <p class="text-navy/80 text-base font-medium leading-relaxed">Building Websites. Expanding Reach.<br>Helping organizations establish a professional online presence.</p>
                </div>

                {{-- Column 2: Quick Links --}}
                <div id="footer-col-2" class="footer-col">
                    <h4 class="font-semibold text-gold mb-4">Quick Links</h4>
                    <ul class="space-y-3 text-base font-medium text-navy/80">
                        <li><a href="{{ $homeAnchor }}#about"     class="footer-link hover:text-gold">About Us<span class="footer-link-bar"></span></a></li>
                        <li><a href="{{ $homeAnchor }}#services"  class="footer-link hover:text-gold">Services<span class="footer-link-bar"></span></a></li>
                        <li><a href="{{ $homeAnchor }}#plans"     class="footer-link hover:text-gold">Care Plans<span class="footer-link-bar"></span></a></li>
                        <li><a href="{{ $homeAnchor }}#portfolio" class="footer-link hover:text-gold">Portfolio<span class="footer-link-bar"></span></a></li>
                        <li><a href="{{ $homeAnchor }}#contact"   class="footer-link hover:text-gold">Contact<span class="footer-link-bar"></span></a></li>
                    </ul>
                </div>

                {{-- Column 3: Contact --}}
                <div id="footer-col-3" class="footer-col" style="position:relative;">
                    {{-- ── Peeking mascot — hides shyly until the footer is
                         scrolled into view, then smiles. Source images are
                         authored upside-down, hence rotate(180deg). ── --}}
                    <div id="footer-mascot" aria-hidden="true">
                        <img src="@assetv('image/mascut-hide.png')" alt="" class="mascot-hide">
                        <img src="@assetv('image/mascut-smile.png')" alt="" class="mascot-smile">
                    </div>
                    <h4 class="font-semibold text-gold mb-4">Contact</h4>
                    <ul class="space-y-3 text-base font-medium text-navy/80">
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
            </div>

            {{-- Bottom bar --}}
            <div id="footer-bottom" class="footer-bottom-bar border-t border-navy/10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm font-medium text-navy/60">
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

            const plane  = document.getElementById('flight-plane');
            const clouds = ['cloud-1', 'cloud-2', 'cloud-3', 'cloud-4'].map(id => document.getElementById(id));
            let flying = false;

            window.flyTransition = function (targetEl) {
                if (!targetEl) return;
                // Below the same breakpoint the horizontal-wipe and section-rail
                // already use as "desktop-only flourish" — on mobile this ~2.4s
                // full-screen takeover just reads as the page freezing, so fall
                // back to a plain smooth scroll instead.
                if (!plane || flying || window.innerWidth < 1024) {
                    targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    return;
                }
                flying = true;
                overlay.style.pointerEvents = 'all';
                gsap.set(plane, { x: 0, y: 0, rotation: 0 });
                gsap.set(clouds, { x: 0, y: 0, opacity: 0.85 });

                // Cloud reaction times approximate when the plane's x position
                // (computed from each stage's easing curve) reaches that cloud.
                const tl = gsap.timeline({ onComplete() { overlay.style.pointerEvents = 'none'; flying = false; } });
                tl.to(overlay, { opacity: 1, duration: 0.25, ease: 'power2.out' })
                    // Stage 1: accelerates along the bridge deck
                    .to(plane, { x: 850, duration: 1.3, ease: 'power2.in' }, 0.1)
                    // Stage 2: lifts off and climbs up into the sky
                    .to(plane, { x: 1550, y: -750, rotation: -22, duration: 1.1, ease: 'power2.out' })
                    .call(() => targetEl.scrollIntoView({ behavior: 'auto', block: 'start' }), null, 0.7)
                    .to(overlay, { opacity: 0, duration: 0.4, ease: 'power2.in' }, '-=0.2');

                // Each cloud drifts/parts away and fades as the plane flies past it
                tl.to('#cloud-1', { x: 40,  y: -25, opacity: 0.3, duration: 0.5, ease: 'power2.out' }, 0.80)
                  .to('#cloud-2', { x: 45,  y: -20, opacity: 0.3, duration: 0.5, ease: 'power2.out' }, 1.24)
                  .to('#cloud-3', { x: 35,  y: -22, opacity: 0.3, duration: 0.5, ease: 'power2.out' }, 1.59)
                  .to('#cloud-4', { x: 40,  y: -18, opacity: 0.3, duration: 0.5, ease: 'power2.out' }, 2.03);
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

            if (typeof gsap === 'undefined') { setTimeout(initIntro, 80); return; }

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
                    gsap.set(overlay, { scale: 1, opacity: 1, display: 'block' });
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
                '.hero-orb', '#svc-toggle-btn', '.wave-teal', '.wave-main',
                '.shimmer-gold', '.live-dot', '.float-card-1', '.float-card-2',
                '.portfolio-badge', '#hscroll-edge-arrow', '.medallion-sweep',
                '.hero-gradient-shift', '.hero-ray', '.hero-noise', '#hero-orbit-glow', '#hero-orbit-bloom', '#hero-orbit-mid',
                '#hero-orbit-inner-mid', '#hero-orbit-inner-glow', '#hero-halo',
            ];
            const els = document.querySelectorAll(selectors.join(','));
            if (!els.length) return;

            const io = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    entry.target.classList.toggle('anim-paused', !entry.isIntersecting);
                });
            }, { rootMargin: '150px 0px' });

            els.forEach(el => io.observe(el));
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
                        const mascotSmile = document.querySelector('#footer-mascot .mascot-smile');
                        const mascotHide  = document.querySelector('#footer-mascot .mascot-hide');

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
                            }, '-=0.20')
                            // Mascot stops hiding and smiles once the footer is revealed
                            .to(mascotSmile, { opacity: 1, duration: 0.45 }, '-=0.65')
                            .to(mascotHide,  { opacity: 0, duration: 0.45 }, '<');
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

    <script src="@assetv('mobile-design.js')"></script>

</body>
</html>
