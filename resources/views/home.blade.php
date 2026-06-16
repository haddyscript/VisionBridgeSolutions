@extends('layouts.app')

@section('title', 'VisionBridge Solutions – Building Websites. Expanding Reach.')

@section('content')

@php
$svgIcons = [
    'check'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
    'star'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
    'users'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
    'shield'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
    'sparkles'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>',
    'swatch'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>',
    'trending-up' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>',
    'chat'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>',
    'desktop'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
    'document'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
    'home'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
    'book-open'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
    'heart'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
    'building'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
    'refresh'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>',
    'cog'         => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
    'globe'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>',
    'cursor'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>',
    'lock'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>',
    'mobile'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>',
    'bolt'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
];
@endphp

{{-- ============================================================
     HERO SECTION
     ============================================================ --}}
<section id="hero" class="relative min-h-screen flex items-center overflow-hidden" style="background:#080F1C;">

    {{-- Layer 0 — Hero background video (autoplay, muted, loop) --}}
    <video autoplay muted loop playsinline
           style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center top;">
        <source src="{{ asset('videos/Web_development_company_hero_video.mp4') }}" type="video/mp4">
    </video>
    {{-- Dark overlay so text stays legible over any video frame --}}
    <div style="position:absolute;inset:0;background:rgba(6,11,22,0.68);pointer-events:none;"></div>

    {{-- Layer 1 — dot-grid texture --}}
    <div class="hero-grid-dots absolute inset-0 pointer-events-none" style="z-index:1;"></div>

    {{-- Layer 1 — atmospheric CSS orbs (GPU-composed, zero CPU) --}}
    <div class="hero-orb" style="width:580px;height:580px;top:-120px;right:-120px;z-index:1;
         background:radial-gradient(circle,rgba(42,157,143,.11) 0%,transparent 70%);
         animation:orb-drift 16s ease-in-out infinite;"></div>
    <div class="hero-orb" style="width:420px;height:420px;bottom:-80px;left:-80px;z-index:1;
         background:radial-gradient(circle,rgba(201,168,76,.08) 0%,transparent 70%);
         animation:orb-drift 20s ease-in-out infinite reverse 3s;"></div>
    <div class="hero-orb" style="width:260px;height:260px;top:55%;left:58%;z-index:1;
         background:radial-gradient(circle,rgba(42,157,143,.07) 0%,transparent 70%);
         animation:orb-drift 11s ease-in-out infinite 1.5s;"></div>

    {{-- Layer 2 — vignette to push eye to centre --}}
    <div class="absolute inset-0 pointer-events-none" style="z-index:2;
         background:radial-gradient(ellipse at 50% 46%,transparent 28%,rgba(5,10,20,.65) 100%);"></div>

    {{-- Layer 3 — floating glassmorphism cards (desktop only) --}}
    <div class="float-card float-card-1 hidden lg:flex items-center gap-3">
        <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0"
             style="background:rgba(42,157,143,.25);">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div>
            <p class="text-white text-xs font-semibold leading-none mb-0.5">Website Launched!</p>
            <p class="text-white/40 text-xs">Delivered on time</p>
        </div>
    </div>
    <div class="float-card float-card-2 hidden lg:flex items-center gap-3">
        <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0"
             style="background:rgba(201,168,76,.20);">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
        <div>
            <p class="text-white text-xs font-semibold leading-none mb-0.5">5-Star Support</p>
            <p class="text-white/40 text-xs">Always available</p>
        </div>
    </div>

    {{-- Layer 4 — content --}}
    <div class="relative w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-20 text-center" style="z-index:4;">

        {{-- Badge --}}
        <div id="hero-badge" class="inline-flex items-center text-xs font-semibold tracking-widest uppercase px-5 py-2 rounded-full mb-8 opacity-0"
             style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.75);">
            <span class="live-dot"></span>
            Website Development &amp; Management
        </div>

        {{-- Heading --}}
        <h1 id="hero-heading" class="font-display font-bold leading-tight mb-3"
            style="font-size:clamp(2.6rem,6vw,4.5rem);">
            <span class="word-wrap"><span class="hero-word text-white">Building</span></span><span class="word-wrap"><span class="hero-word text-white">Websites.</span></span><br>
            <span class="word-wrap"><span class="hero-word shimmer-gold">Expanding</span></span><span class="word-wrap"><span class="hero-word shimmer-gold">Reach.</span></span>
        </h1>

        {{-- Gold glow divider --}}
        <div id="hero-glow-line" class="glow-line opacity-0"></div>

        {{-- Subtext --}}
        <p id="hero-subtext" class="text-white/60 text-lg sm:text-xl max-w-2xl mx-auto mb-8 leading-relaxed opacity-0">
            Custom websites designed to strengthen your brand, expand your reach,<br class="hidden sm:block"> and protect your online presence.
        </p>

        {{-- Social proof row --}}
        <div id="hero-trust" class="flex items-center justify-center gap-3 mb-10 opacity-0">
            <div class="flex -space-x-2">
                <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold text-white" style="background:#2A9D8F;border-color:#080F1C;">J</div>
                <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold text-white" style="background:#243762;border-color:#080F1C;">M</div>
                <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold" style="background:#C9A84C;border-color:#080F1C;color:#111D33;">S</div>
                <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold text-white" style="background:#1E7268;border-color:#080F1C;">A</div>
            </div>
            <div class="h-4 w-px" style="background:rgba(255,255,255,.18);"></div>
            <p class="text-sm" style="color:rgba(255,255,255,.5);">
                Trusted by <span style="color:rgba(255,255,255,.85);font-weight:600;">20+ organizations</span>
            </p>
        </div>

        {{-- CTA buttons --}}
        <div id="hero-ctas" class="flex flex-col sm:flex-row gap-4 justify-center mb-20">
            <a href="#contact" class="hero-btn-primary opacity-0">
                Start Your Project
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            <a href="#contact" class="hero-btn-secondary opacity-0">
                <svg class="w-4 h-4 shrink-0 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Book A Consultation
            </a>
        </div>

        {{-- Stats --}}
        <div id="hero-stats" class="grid grid-cols-3 gap-6 max-w-xl mx-auto pt-8"
             style="border-top:1px solid rgba(255,255,255,.10);">
            <div class="stat-item text-center opacity-0">
                <div id="stat-pct" class="text-3xl font-bold text-gold">0%</div>
                <div class="text-xs mt-1 uppercase tracking-widest" style="color:rgba(255,255,255,.4);">Client Ownership</div>
            </div>
            <div class="stat-item text-center opacity-0" style="border-left:1px solid rgba(255,255,255,.10);border-right:1px solid rgba(255,255,255,.10);">
                <div class="text-3xl font-bold text-gold">Custom</div>
                <div class="text-xs mt-1 uppercase tracking-widest" style="color:rgba(255,255,255,.4);">Every Project</div>
            </div>
            <div class="stat-item text-center opacity-0">
                <div class="text-3xl font-bold text-gold">Long-Term</div>
                <div class="text-xs mt-1 uppercase tracking-widest" style="color:rgba(255,255,255,.4);">Support</div>
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div id="hero-scroll-cue" class="absolute bottom-6 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-0" style="z-index:4;">
        <span class="text-xs tracking-widest uppercase" style="color:rgba(255,255,255,.28);">Scroll</span>
        <div class="w-5 h-8 rounded-full flex items-start justify-center pt-1.5"
             style="border:1.5px solid rgba(255,255,255,.2);">
            <div class="w-1 h-2 rounded-full" style="background:rgba(201,168,76,.65);animation:scroll-dot 1.9s ease-in-out infinite;"></div>
        </div>
    </div>
</section>

{{-- ============================================================
     WELCOME VIDEO SECTION
     ============================================================ --}}
<section id="welcome" class="py-28 relative overflow-hidden" style="background:radial-gradient(ellipse 90% 70% at 50% 55%,#132038 0%,#0b1525 48%,#07101c 100%);">
    <div id="welcome-glow" class="absolute pointer-events-none" style="width:820px;height:820px;top:50%;left:50%;transform:translate(-50%,-50%);border-radius:50%;background:radial-gradient(circle,rgba(201,168,76,.07) 0%,rgba(42,157,143,.05) 42%,transparent 70%);filter:blur(72px);will-change:transform;"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" style="z-index:2;">
        <span id="welcome-kicker" class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-5" style="opacity:0;">The VisionBridge Story</span>
        <h2 class="font-display font-bold text-white leading-tight mb-5" style="font-size:clamp(2rem,4.5vw,3.2rem);">
            <span class="welcome-word-wrap"><span class="welcome-word">Where</span></span>
            <span class="welcome-word-wrap"><span class="welcome-word">Vision</span></span>
            <span class="welcome-word-wrap"><span class="welcome-word">Meets</span></span>
            <span class="welcome-word-wrap"><span class="welcome-word">the</span></span>
            <span class="welcome-word-wrap"><span class="welcome-word">Digital</span></span>
            <span class="welcome-word-wrap"><span class="welcome-word">World</span></span>
        </h2>
        <p id="welcome-sub" class="text-white/50 text-lg max-w-2xl mx-auto mb-12 leading-relaxed" style="opacity:0;">We bridge the gap between your vision and a powerful online presence — connecting organizations to the digital opportunities that drive real, lasting growth.</p>

        <div id="welcome-video-wrap" class="relative rounded-3xl overflow-hidden" style="opacity:0;transform:scale(0.93);box-shadow:0 0 0 1px rgba(201,168,76,0.18),0 40px 100px rgba(0,0,0,0.75),0 12px 36px rgba(0,0,0,0.55);">
            <div class="aspect-video relative">
                <video id="welcome-video" autoplay muted loop playsinline preload="auto" class="w-full h-full object-cover block">
                    <source src="{{ asset('videos/VisionBridge_Solutions_welcome_v.mp4') }}" type="video/mp4">
                </video>

            </div>
        </div>

        <div id="welcome-credit" class="mt-8 flex items-center justify-center gap-4" style="opacity:0;">
            <div class="h-px w-20" style="background:linear-gradient(to right,transparent,rgba(201,168,76,0.45));"></div>
            <span class="text-xs tracking-widest uppercase font-medium" style="color:rgba(201,168,76,0.55);">VisionBridge Solutions — Building Websites. Expanding Reach.</span>
            <div class="h-px w-20" style="background:linear-gradient(to left,transparent,rgba(201,168,76,0.45));"></div>
        </div>
    </div>
</section>

{{-- ============================================================
     ABOUT SECTION
     ============================================================ --}}
<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span id="about-kicker" class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">Who We Are</span>
            <h2 id="about-heading" class="section-title mt-1">About VisionBridge Solutions</h2>
        </div>

        <!-- Mosaic image grid + Mission / Vision side-by-side -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-20 items-stretch">

            {{-- Left: 3×2 mosaic with parallax wrapper --}}
            <div id="about-mosaic-wrap">
                <div id="about-mosaic" class="relative rounded-2xl overflow-hidden shadow-2xl"
                     style="min-height:460px; --img:url('{{ asset('image/VisionBridge_Solutions_1.jpeg') }}');">

                    {{-- 3 columns × 2 rows — each cell reveals a slice of the image --}}
                    <div class="absolute inset-0 grid grid-cols-3 grid-rows-2" style="gap:3px;background:#08101e;">
                        <div class="mosaic-panel" style="background-size:300% 200%;background-position:0%   0%;"></div>
                        <div class="mosaic-panel" style="background-size:300% 200%;background-position:50%  0%;"></div>
                        <div class="mosaic-panel" style="background-size:300% 200%;background-position:100% 0%;"></div>
                        <div class="mosaic-panel" style="background-size:300% 200%;background-position:0%   100%;"></div>
                        <div class="mosaic-panel" style="background-size:300% 200%;background-position:50%  100%;"></div>
                        <div class="mosaic-panel" style="background-size:300% 200%;background-position:100% 100%;"></div>
                    </div>

                    {{-- Bottom gradient overlay --}}
                    <div class="absolute inset-0 pointer-events-none" style="z-index:2;
                         background:linear-gradient(to top, rgba(17,29,51,0.94) 0%, rgba(17,29,51,0.22) 52%, transparent 100%);"></div>

                    {{-- Caption --}}
                    <div class="absolute bottom-0 left-0 right-0 p-8" style="z-index:3;">
                        <p id="about-mosaic-quote" class="font-display font-bold text-lg leading-snug mb-2"
                           style="color:#C9A84C;">
                            "We don't just build websites — we bridge the gap between vision and digital presence."
                        </p>
                        <p class="text-white/50 text-sm tracking-wide">— VisionBridge Solutions</p>
                    </div>
                </div>
            </div>

            {{-- Right: Mission & Vision with tilt + glow --}}
            <div class="about-cards flex flex-col gap-6">

                <div class="about-card bg-navy rounded-2xl p-8 text-white flex-1">
                    <div class="card-icon w-12 h-12 bg-gold/20 rounded-xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="card-title text-xl font-bold text-gold mb-3">Our Mission</h3>
                    <p class="card-body text-white/70 leading-relaxed">To help ministries, churches, nonprofits, entrepreneurs, and businesses establish a professional online presence through custom website development, ongoing support, and long-term website stability.</p>
                </div>

                <div class="about-card bg-teal rounded-2xl p-8 text-white flex-1">
                    <div class="card-icon w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h3 class="card-title text-xl font-bold text-white mb-3">Our Vision</h3>
                    <p class="card-body text-white/80 leading-relaxed">To become a trusted website solutions company that bridges the gap between vision and digital presence while helping clients maintain ownership, security, and confidence in their online future.</p>
                </div>

            </div>
        </div>

        <!-- Core Values -->
        <div class="text-center mb-10">
            <h3 class="section-title">Our Core Values</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['icon'=>'users','title'=>'Client Ownership','desc'=>'Your website, your brand, your data — always. We ensure you retain full ownership of every digital asset we create for you.'],
                ['icon'=>'shield','title'=>'Long-Term Stability','desc'=>'We don\'t just build and disappear. We provide ongoing support to keep your website secure, updated, and performing.'],
                ['icon'=>'sparkles','title'=>'Faith-Based Values','desc'=>'Rooted in integrity and service, we bring faith-based principles to every client relationship and project we undertake.'],
                ['icon'=>'swatch','title'=>'Custom Solutions','desc'=>'No templates, no shortcuts. Every website is custom-designed to reflect your unique brand and mission.'],
                ['icon'=>'trending-up','title'=>'Growth Focused','desc'=>'We design with your audience growth in mind — clear calls to action, strong messaging, and mobile-first delivery.'],
                ['icon'=>'chat','title'=>'Professional Support','desc'=>'From first inquiry to launch and beyond, you\'ll always have a dedicated team ready to support your online presence.'],
            ] as $value)
            <div class="bg-gray-50 rounded-xl p-6 hover:shadow-md transition-shadow duration-200">
                <div class="w-12 h-12 bg-navy/10 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$value['icon']] !!}</svg>
                </div>
                <h4 class="font-bold text-navy text-lg mb-2">{{ $value['title'] }}</h4>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $value['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     SERVICES SECTION
     ============================================================ --}}
<section id="services" class="py-20" style="background: linear-gradient(180deg, #F8F9FA 0%, #EEF2F7 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">What We Offer</span>
            <h2 class="section-title">Our Services</h2>
            <p class="section-subtitle">From initial design to long-term maintenance — we cover everything your online presence needs.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['icon'=>'desktop','title'=>'Custom Website Development','desc'=>'Fully custom websites built to reflect your unique brand identity and business goals.'],
                ['icon'=>'document','title'=>'Landing Page Development','desc'=>'High-converting landing pages designed to capture leads and drive specific actions.'],
                ['icon'=>'home','title'=>'Church Website Development','desc'=>'Professional church websites that connect congregations and communicate your ministry\'s heart.'],
                ['icon'=>'book-open','title'=>'Ministry Website Development','desc'=>'Websites crafted to expand the reach of ministries and share your message with the world.'],
                ['icon'=>'heart','title'=>'Nonprofit Website Development','desc'=>'Compelling nonprofit websites that tell your story and inspire support for your cause.'],
                ['icon'=>'building','title'=>'Small Business Website Development','desc'=>'Affordable, professional websites that help small businesses compete and grow online.'],
                ['icon'=>'refresh','title'=>'Website Redesign Services','desc'=>'Breathe new life into an outdated website with a modern, performance-focused redesign.'],
                ['icon'=>'cog','title'=>'Website Maintenance Services','desc'=>'Regular updates, monitoring, and care to keep your website running at peak performance.'],
                ['icon'=>'globe','title'=>'Hosting Management','desc'=>'We manage your hosting environment so you can focus on running your organization.'],
                ['icon'=>'cursor','title'=>'Website Consulting','desc'=>'Strategic guidance on your website\'s direction, technology, and digital growth potential.'],
            ] as $service)
            <div class="bg-white rounded-xl p-6 border border-gray-100 hover:border-teal/30 hover:shadow-lg transition-all duration-200 group">
                <div class="w-12 h-12 bg-teal/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-teal/20 transition-colors">
                    <svg class="w-6 h-6 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$service['icon']] !!}</svg>
                </div>
                <h4 class="font-bold text-navy text-base mb-2 group-hover:text-teal transition-colors">{{ $service['title'] }}</h4>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $service['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     WHY CHOOSE US SECTION
     ============================================================ --}}
<section id="why" class="py-20 bg-navy text-white relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none opacity-5">
        <svg class="w-full h-full" viewBox="0 0 800 400" preserveAspectRatio="xMidYMid slice">
            <path d="M0,200 Q200,50 400,200 Q600,350 800,200" stroke="#C9A84C" stroke-width="80" fill="none"/>
        </svg>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block text-gold text-sm font-semibold tracking-widest uppercase mb-3">Why VisionBridge</span>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-white mb-6">Why Choose VisionBridge Solutions?</h2>
            <div class="max-w-3xl mx-auto bg-white/5 border border-gold/30 rounded-2xl p-8">
                <p class="text-gold text-xl md:text-2xl font-display font-bold leading-relaxed">
                    "We don't just build custom websites — we help protect the long-term stability of our clients' online presence."
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon'=>'lock','title'=>'Ownership First','desc'=>'You own everything — domain, content, hosting, data. Always.'],
                ['icon'=>'mobile','title'=>'Mobile-First Design','desc'=>'Every site is built to perform beautifully on any device.'],
                ['icon'=>'users','title'=>'Partnership Approach','desc'=>'We work with you, not just for you, through every stage.'],
                ['icon'=>'bolt','title'=>'Fast & Reliable','desc'=>'Optimized for speed, uptime, and a seamless user experience.'],
            ] as $point)
            <div class="text-center p-6">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mb-4 mx-auto">
                    <svg class="w-7 h-7 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$point['icon']] !!}</svg>
                </div>
                <h4 class="font-bold text-gold text-lg mb-2">{{ $point['title'] }}</h4>
                <p class="text-white/60 text-sm leading-relaxed">{{ $point['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     MAINTENANCE PLANS SECTION
     ============================================================ --}}
<section id="plans" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">Ongoing Care</span>
            <h2 class="section-title">Website Maintenance Plans</h2>
            <p class="section-subtitle">Keep your website secure, updated, and performing — month after month.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Essential Care -->
            <div class="relative rounded-2xl border-2 border-gold shadow-xl overflow-hidden">
                <div class="bg-gold px-6 py-4 text-navy text-center">
                    <span class="text-xs font-bold tracking-widest uppercase">Most Popular</span>
                </div>
                <div class="bg-white p-8 text-center">
                    <h3 class="font-bold text-navy text-xl mb-1">Essential Care Plan</h3>
                    <div class="my-6">
                        <span class="text-5xl font-bold text-navy">$59</span>
                        <span class="text-gray-400 text-sm">/month</span>
                    </div>
                    <ul class="text-left space-y-3 mb-8">
                        @foreach(['Website Updates','Security Monitoring','Monthly Backups','Content Changes','Email Support','Basic Website Maintenance'] as $item)
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-teal shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="#contact" class="btn-gold w-full text-center block">Get Started</a>
                </div>
            </div>

            <!-- Growth Care -->
            <div class="rounded-2xl border-2 border-gray-100 overflow-hidden opacity-70">
                <div class="bg-gray-100 px-6 py-4 text-gray-500 text-center">
                    <span class="text-xs font-bold tracking-widest uppercase">Coming Soon</span>
                </div>
                <div class="bg-white p-8 text-center">
                    <h3 class="font-bold text-navy text-xl mb-1">Growth Care Plan</h3>
                    <div class="my-6">
                        <span class="text-3xl font-bold text-gray-300">Coming Soon</span>
                    </div>
                    <ul class="text-left space-y-3 mb-8">
                        @foreach(['Everything in Essential','Priority Support','SEO Monitoring','Performance Reports','Additional Content Changes'] as $item)
                        <li class="flex items-center gap-3 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                    <button disabled class="w-full bg-gray-100 text-gray-400 font-semibold px-7 py-3 rounded-lg cursor-not-allowed">Coming Soon</button>
                </div>
            </div>

            <!-- Premium Care -->
            <div class="rounded-2xl border-2 border-gray-100 overflow-hidden opacity-70">
                <div class="bg-gray-100 px-6 py-4 text-gray-500 text-center">
                    <span class="text-xs font-bold tracking-widest uppercase">Coming Soon</span>
                </div>
                <div class="bg-white p-8 text-center">
                    <h3 class="font-bold text-navy text-xl mb-1">Premium Care Plan</h3>
                    <div class="my-6">
                        <span class="text-3xl font-bold text-gray-300">Coming Soon</span>
                    </div>
                    <ul class="text-left space-y-3 mb-8">
                        @foreach(['Everything in Growth','Dedicated Account Manager','Monthly Strategy Call','Advanced Analytics','Custom Integrations'] as $item)
                        <li class="flex items-center gap-3 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                    <button disabled class="w-full bg-gray-100 text-gray-400 font-semibold px-7 py-3 rounded-lg cursor-not-allowed">Coming Soon</button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     PORTFOLIO SECTION
     ============================================================ --}}
<section id="portfolio" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">Our Work</span>
            <h2 class="section-title">Featured Projects</h2>
            <p class="section-subtitle">A selection of websites we've built for ministries, churches, and organizations.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['title'=>'Johnny Davis Global Missions','category'=>'Ministry','color'=>'from-navy to-teal','icon'=>'globe'],
                ['title'=>'Johnny Davis Ministries','category'=>'Ministry','color'=>'from-navy-dark to-navy','icon'=>'book-open'],
                ['title'=>'Mercy City Eleven 22 Church','category'=>'Church','color'=>'from-teal to-teal-dark','icon'=>'home'],
                ['title'=>'Future VisionBridge Projects','category'=>'Coming Soon','color'=>'from-gold-dark to-gold','icon'=>'sparkles'],
            ] as $project)
            <div class="group rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer">
                <div class="h-48 bg-gradient-to-br {{ $project['color'] }} flex items-center justify-center relative">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$project['icon']] !!}</svg>
                    </div>
                    <div class="absolute inset-0 bg-navy/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <span class="text-white font-semibold text-sm bg-white/20 px-4 py-2 rounded-full">View Project</span>
                    </div>
                </div>
                <div class="bg-white p-5">
                    <span class="text-teal text-xs font-semibold tracking-widest uppercase">{{ $project['category'] }}</span>
                    <h4 class="font-bold text-navy mt-1 text-base leading-snug">{{ $project['title'] }}</h4>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     FAITHSTACK PARTNERSHIP SECTION
     ============================================================ --}}
<section id="partnership" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">Our Partnership</span>
            <h2 class="section-title">VisionBridge & FaithStack</h2>
            <p class="section-subtitle">A strategic partnership delivering seamless website solutions from concept to long-term care.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <div class="rounded-2xl border border-navy/10 p-8 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-navy rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-navy text-xl mb-4">VisionBridge Solutions</h3>
                <ul class="space-y-3">
                    @foreach(['Client Acquisition','Marketing','Billing & Project Management','Customer Support','Hosting Ownership'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-600">
                        <div class="w-2 h-2 rounded-full bg-gold shrink-0"></div>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="rounded-2xl border border-teal/20 bg-teal/5 p-8 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-teal rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-teal text-xl mb-4">FaithStack</h3>
                <ul class="space-y-3">
                    @foreach(['Website Development','Technical Support','Website Updates','Website Maintenance'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-600">
                        <div class="w-2 h-2 rounded-full bg-teal shrink-0"></div>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Ownership note -->
        <div class="mt-10 max-w-3xl mx-auto bg-navy/5 border border-navy/10 rounded-xl p-6 text-center">
            <svg class="w-8 h-8 text-gold mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <p class="text-navy font-semibold text-sm">VisionBridge Solutions retains full ownership of all client websites, branding, hosting accounts, and associated assets.</p>
        </div>
    </div>
</section>

{{-- ============================================================
     CONTACT SECTION
     ============================================================ --}}
<section id="contact" class="py-20" style="background: linear-gradient(135deg, #1B2A4A 0%, #111D33 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block text-gold text-sm font-semibold tracking-widest uppercase mb-3">Let's Connect</span>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-white mb-4">Ready to Start Your Project?</h2>
            <p class="text-white/60 text-lg max-w-xl mx-auto">Fill out the form below and we'll get back to you within 24 hours to discuss your vision.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-5xl mx-auto">
            <!-- Contact Info -->
            <div class="text-white">
                <h3 class="font-bold text-xl text-gold mb-6">Get In Touch</h3>
                <div class="space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white/40 text-xs uppercase tracking-widest mb-1">Phone</p>
                            <p class="text-white font-medium">(555) 000-0000</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white/40 text-xs uppercase tracking-widest mb-1">Email</p>
                            <p class="text-white font-medium">info@visionbridgesolutions.com</p>
                        </div>
                    </div>
                </div>

                <!-- Book Consultation CTA -->
                <div class="mt-10 bg-white/5 border border-gold/20 rounded-xl p-6">
                    <h4 class="font-bold text-gold mb-2">Prefer to talk first?</h4>
                    <p class="text-white/60 text-sm mb-4">Book a free 30-minute consultation and let's discuss your project goals.</p>
                    <a href="#" class="btn-gold text-sm inline-block">Book A Consultation</a>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-white rounded-2xl p-8 shadow-2xl">
                <form action="#" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input type="text" name="first_name" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal transition-colors" placeholder="Johnny">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input type="text" name="last_name" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal transition-colors" placeholder="Davis">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal transition-colors" placeholder="you@example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Organization / Business</label>
                        <input type="text" name="organization" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal transition-colors" placeholder="Your Church, Ministry, or Business">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service You're Interested In</label>
                        <select name="service" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal transition-colors text-gray-600">
                            <option value="">Select a service...</option>
                            <option>Custom Website Development</option>
                            <option>Church Website Development</option>
                            <option>Ministry Website Development</option>
                            <option>Nonprofit Website Development</option>
                            <option>Small Business Website Development</option>
                            <option>Landing Page Development</option>
                            <option>Website Redesign</option>
                            <option>Website Maintenance</option>
                            <option>Hosting Management</option>
                            <option>Website Consulting</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tell Us About Your Project</label>
                        <textarea name="message" rows="4" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal transition-colors resize-none" placeholder="Share a little about your vision and what you need..."></textarea>
                    </div>
                    <button type="submit" class="btn-gold w-full text-center text-base">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
(function () {
    'use strict';

    // ─────────────────────────────────────────────────────────────────
    //  ANIMATION CONSTANTS
    //
    //  TOGGLE — 'play none none reverse'
    //    onEnter      → play   : elements reveal as section enters from below
    //    onLeave      → none   : elements stay visible as you scroll past them
    //    onEnterBack  → none   : still visible when scrolling back up into view
    //    onLeaveBack  → reverse: elements elegantly un-reveal when scrolling
    //                            back above the trigger point, ready to play again
    //
    //  SCRUB triggers bypass TOGGLE entirely — they're tied directly to the
    //  scrollbar position so they naturally go forward and back with scroll.
    // ─────────────────────────────────────────────────────────────────
    const TOGGLE = 'play none none reverse';

    // Reusable ScrollTrigger config for parallax scrub animations
    function scrubST(trigger, scrub) {
        return { trigger, start: 'top bottom', end: 'bottom top', scrub: scrub || 1.8 };
    }

    // ─────────────────────────────────────────────────────────────────
    //  GENERIC .reveal-section SYSTEM
    //
    //  Drop class="reveal-section" on any section wrapper to get a free
    //  fade+rise entrance. Optionally add data-stagger on child elements
    //  to have them animate as an orchestrated group instead.
    //
    //  Usage:
    //    <section class="reveal-section">…</section>
    //    <section class="reveal-section">
    //      <div data-stagger>card 1</div>
    //      <div data-stagger>card 2</div>
    //    </section>
    // ─────────────────────────────────────────────────────────────────
    function initRevealSections() {
        document.querySelectorAll('.reveal-section').forEach(section => {
            const staggerChildren = section.querySelectorAll('[data-stagger]');
            if (staggerChildren.length) {
                gsap.fromTo(staggerChildren,
                    { opacity: 0, y: 40 },
                    { opacity: 1, y: 0, duration: 0.72, stagger: 0.13, ease: 'power3.out',
                      scrollTrigger: { trigger: section, start: 'top 82%', toggleActions: TOGGLE } }
                );
            } else {
                gsap.fromTo(section,
                    { opacity: 0, y: 30 },
                    { opacity: 1, y: 0, duration: 0.80, ease: 'power3.out',
                      scrollTrigger: { trigger: section, start: 'top 82%', toggleActions: TOGGLE } }
                );
            }
        });
    }

    function initGSAP() {
        if (typeof gsap === 'undefined') { setTimeout(initGSAP, 80); return; }
        gsap.registerPlugin(ScrollTrigger);

        // Run the generic reveal system first so section-specific tweens
        // that share the same trigger don't double-fire on the same element
        initRevealSections();

        // ============================================================
        //  HERO — page-load entrance timeline (no ScrollTrigger needed:
        //  hero is always the first thing visible on load)
        // ============================================================
        const heroTl = gsap.timeline({ defaults: { ease: 'power3.out' }, delay: 0.3 });

        heroTl
            .fromTo('#hero-badge',      { opacity:0, y:22  }, { opacity:1, y:0, duration:0.65 })
            .from ('.hero-word',        { y:'110%', opacity:0, duration:0.75, stagger:0.09 }, '-=0.30')
            .fromTo('#hero-glow-line',  { opacity:0, scaleX:0 }, { opacity:1, scaleX:1, duration:0.70, ease:'power2.out' }, '-=0.15')
            .fromTo('#hero-subtext',    { opacity:0, y:26  }, { opacity:1, y:0, duration:0.60 }, '-=0.35')
            .fromTo('#hero-trust',      { opacity:0, y:18  }, { opacity:1, y:0, duration:0.50 }, '-=0.30')
            .fromTo('#hero-ctas > a',   { opacity:0, y:22  }, { opacity:1, y:0, duration:0.50, stagger:0.13 }, '-=0.28')
            .fromTo('.stat-item',       { opacity:0, y:20  }, { opacity:1, y:0, duration:0.50, stagger:0.10 }, '-=0.20')
            // Counter is driven by a side-effect; it lives inside the hero so
            // no ScrollTrigger — fire once as part of the page-load sequence
            .call(() => {
                const el = document.getElementById('stat-pct');
                if (!el) return;
                const o = { v: 0 };
                gsap.to(o, { v:100, duration:2.5, ease:'power2.out',
                    onUpdate() { el.textContent = Math.round(o.v) + '%'; }
                });
            })
            .fromTo('#hero-scroll-cue', { opacity:0 }, { opacity:1, duration:0.70 }, '-=1.90');

        // ============================================================
        //  WELCOME / FOUNDER'S MESSAGE — bi-directional timeline
        //
        //  TOGGLE on the parent ScrollTrigger means the entire timeline
        //  plays forward on entry and reverses cleanly on scroll-back.
        // ============================================================
        gsap.timeline({
            scrollTrigger: { trigger:'#welcome', start:'top 78%', toggleActions: TOGGLE }
        })
        .fromTo('#welcome-kicker',
            { opacity:0, y:14 }, { opacity:1, y:0, duration:0.60, ease:'power3.out' })
        .from('.welcome-word',
            { y:'105%', opacity:0, duration:0.72, stagger:0.08, ease:'power3.out' }, '-=0.28')
        .fromTo('#welcome-sub',
            { opacity:0, y:22 }, { opacity:1, y:0, duration:0.60, ease:'power2.out' }, '-=0.28')
        .fromTo('#welcome-video-wrap',
            { opacity:0, scale:0.93 }, { opacity:1, scale:1, duration:0.95, ease:'power2.out' }, '-=0.32')
        .fromTo('#welcome-credit',
            { opacity:0, y:12 }, { opacity:1, y:0, duration:0.55, ease:'power2.out' }, '-=0.50');

        // Ambient glow scrub — naturally reverses with scroll direction
        gsap.to('#welcome-glow', { y:-55, ease:'none', scrollTrigger: scrubST('#welcome', 3) });

        // Video: play/pause via IntersectionObserver (independent of GSAP)
        const wVideo = document.getElementById('welcome-video');
        if (wVideo) {
            new IntersectionObserver(entries => {
                entries[0].isIntersecting ? wVideo.play().catch(() => {}) : wVideo.pause();
            }, { threshold: 0.25 }).observe(wVideo);
        }

        // ============================================================
        //  ABOUT — header, mosaic, cards — all bi-directional
        // ============================================================

        // ── Section header: kicker + heading in a single timeline ──
        gsap.timeline({
            scrollTrigger: { trigger:'#about', start:'top 82%', toggleActions: TOGGLE }
        })
        .fromTo('#about-kicker',  { opacity:0, y:16 }, { opacity:1, y:0, duration:0.65, ease:'power3.out' })
        .fromTo('#about-heading', { opacity:0, y:30 }, { opacity:1, y:0, duration:0.80, ease:'power3.out' }, '-=0.35');

        // ── Mosaic panels: staggered scale-reveal ──
        gsap.fromTo('.mosaic-panel',
            { opacity:0, scale:1.08 },
            { opacity:1, scale:1, duration:0.90, stagger:{ amount:0.55, from:'start' }, ease:'power2.out',
              scrollTrigger: { trigger:'#about-mosaic', start:'top 80%', toggleActions: TOGGLE } }
        );

        // ── Mosaic caption ──
        gsap.fromTo('#about-mosaic-quote',
            { opacity:0, y:20 },
            { opacity:1, y:0, duration:0.70, ease:'power3.out',
              scrollTrigger: { trigger:'#about-mosaic', start:'top 76%', toggleActions: TOGGLE } }
        );

        // ── Mosaic parallax scrub (bi-directional by nature) ──
        gsap.to('#about-mosaic-wrap', { y:-38, ease:'none', scrollTrigger: scrubST('#about', 2) });

        // ── Mission / Vision cards: stagger entrance ──
        gsap.fromTo('.about-card',
            { opacity:0, y:46 },
            { opacity:1, y:0, duration:0.80, stagger:0.18, ease:'power3.out',
              scrollTrigger: { trigger:'.about-cards', start:'top 82%', toggleActions: TOGGLE } }
        );

        // ── Card interior cascade: icon → title → body ──
        document.querySelectorAll('.about-card').forEach(card => {
            gsap.fromTo(
                card.querySelectorAll('.card-icon, .card-title, .card-body'),
                { opacity:0, y:18 },
                { opacity:1, y:0, duration:0.55, stagger:0.11, ease:'power2.out',
                  scrollTrigger: { trigger:card, start:'top 85%', toggleActions: TOGGLE } }
            );
        });

        // ── 3D tilt + cursor-glow (hover; no ScrollTrigger) ──
        document.querySelectorAll('.about-card').forEach(card => {
            card.addEventListener('mousemove', e => {
                const r  = card.getBoundingClientRect();
                const cx = e.clientX - r.left - r.width  / 2;
                const cy = e.clientY - r.top  - r.height / 2;
                gsap.to(card, {
                    rotateX: (-cy / r.height) * 7,
                    rotateY: ( cx / r.width)  * 7,
                    transformPerspective: 900,
                    duration: 0.40, ease: 'power2.out',
                });
                card.style.setProperty('--mx', ((e.clientX - r.left) / r.width  * 100) + '%');
                card.style.setProperty('--my', ((e.clientY - r.top)  / r.height * 100) + '%');
            }, { passive: true });

            card.addEventListener('mouseleave', () => {
                gsap.to(card, { rotateX:0, rotateY:0, duration:0.65, ease:'power3.out' });
            });
        });

        // ============================================================
        //  GENERIC BELOW-FOLD CARD REVEALS
        //
        //  Targets all white cards, service tiles, plan cards, portfolio
        //  cards, and bordered panels across the page. Each gets its own
        //  ScrollTrigger so they stagger naturally as the user scrolls.
        //  .about-card elements are excluded (handled with finer control
        //  above). scrub-based parallax parents are also untouched.
        // ============================================================
        document.querySelectorAll(
            '.bg-white.rounded-xl, .bg-white.rounded-2xl, .rounded-2xl.border, .bg-gray-50.rounded-xl'
        ).forEach(el => {
            if (el.closest('.about-cards')) return; // about-cards use bespoke stagger above
            gsap.fromTo(el,
                { opacity:0, y:36 },
                { opacity:1, y:0, duration:0.65, ease:'power2.out',
                  scrollTrigger: { trigger:el, start:'top 92%', toggleActions: TOGGLE } }
            );
        });
    }

    initGSAP();

})();
</script>
@endsection
