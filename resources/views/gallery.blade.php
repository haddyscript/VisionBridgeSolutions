@extends('layouts.app')

@section('title', 'Our Work — VisionBridge Solutions')
@section('description', 'A scroll-driven visual journey through the websites, hosting, and care plans we\'ve built for our clients.')

@section('content')

@php
    $projects = config('gallery.projects', []);
    $count    = count($projects);
    // First 6 projects only — the opening is meant to feel curated, not
    // exhaustive. The featured slide (index 0) is deliberately the same
    // photo as the first pinned scene below, so the opening's climax and
    // the real gallery's first chapter show the same image.
    $openingSlides = array_slice($projects, 0, 6);
@endphp

<link rel="stylesheet" href="@assetv('cinematic-gallery.css')">

<div id="cine-gallery">

    {{-- ── Cinematic opening — plays once on load, on top of the page
         below (which renders normally underneath it the whole time).
         See cinematic-gallery.js's initCineOpening() for the timeline. ── --}}
    @if(count($openingSlides))
        <div id="cine-opening" aria-hidden="true">
            <div class="cine-opening-bg"></div>
            <div class="cine-opening-stars"></div>
            <div class="cine-opening-field">
                @foreach($openingSlides as $i => $slide)
                    <div class="cine-opening-frame cine-opening-frame-{{ $i + 1 }} @if($i === 0) is-featured @endif">
                        <img src="@assetv($slide['image'])" alt="" loading="eager">
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ── Intro ── --}}
    <section class="cine-intro" aria-label="Our Work — introduction">
        <span class="cine-kicker opacity-0" data-reveal>Selected Work</span>
        <h1 class="cine-intro-title opacity-0" data-reveal>A Visual Journey<br>Through Our Craft</h1>
        <p class="cine-intro-sub opacity-0" data-reveal>
            Scroll to walk through {{ $count }} projects — each one a chapter in how we build, host, and care for the websites our clients depend on.
        </p>
        <div class="cine-scroll-cue opacity-0" data-reveal aria-hidden="true">
            <span>Scroll</span>
            <div class="cine-scroll-cue-track"><span></span></div>
        </div>
    </section>

    @if($count)
        {{-- ── Progress rail ── --}}
        <div class="cine-progress" aria-hidden="true">
            @foreach($projects as $i => $project)
                <span class="cine-dot @if($i === 0) is-active @endif"></span>
            @endforeach
            <span class="cine-progress-count">01 / {{ str_pad($count, 2, '0', STR_PAD_LEFT) }}</span>
        </div>

        {{-- ── Pinned camera through each project ── --}}
        <div class="cine-pin-track" style="height:{{ $count * 90 }}vh;">
            <div class="cine-stage">
                <div class="cine-stage-bg" aria-hidden="true"></div>
                <div class="cine-stage-vignette" aria-hidden="true"></div>
                <div class="hero-orb" style="width:480px;height:480px;top:-100px;right:-90px;z-index:0;
                     background:radial-gradient(circle,rgba(201,168,76,.14) 0%,transparent 70%);
                     animation:orb-drift 18s ease-in-out infinite;"></div>
                <div class="hero-orb" style="width:380px;height:380px;bottom:-80px;left:-70px;z-index:0;
                     background:radial-gradient(circle,rgba(44,166,164,.12) 0%,transparent 70%);
                     animation:orb-drift 22s ease-in-out infinite reverse 3s;"></div>

                @php
                    // Five named entrance choreographies, cycling by index —
                    // see PRESETS in cinematic-gallery.js for what each one
                    // actually animates. Independent of cine-variant-{0,1,2}
                    // (glow color, index % 3) below — with a 5-cycle and a
                    // 3-cycle running side by side, no two of these 11 scenes
                    // share the exact same (preset, variant) combination.
                    $cinePresets = ['A', 'B', 'C', 'D', 'E'];
                @endphp
                @foreach($projects as $i => $project)
                    <div class="cine-scene cine-variant-{{ $i % 3 }}" data-scene="{{ $i }}" data-preset="{{ $cinePresets[$i % 5] }}">
                        <div class="cine-frame-wrap">
                            <div class="cine-frame-glow" aria-hidden="true"></div>
                            {{-- Ghost cards — only animated for Preset E ("stacked
                                 cards separating into depth"); sit inert/invisible
                                 behind the frame for every other preset, same as
                                 the sweep/border below sit inert outside their own
                                 preset. --}}
                            <div class="cine-frame-ghost cine-frame-ghost-1" aria-hidden="true"></div>
                            <div class="cine-frame-ghost cine-frame-ghost-2" aria-hidden="true"></div>
                            <div class="cine-frame">
                                <img src="@assetv($project['image'])" alt="{{ $project['title'] }}" loading="lazy">
                                {{-- One-shot light sweep + animated border draw —
                                     Preset D only ("light sweep with layered
                                     parallax"); played once by JS when a Preset D
                                     scene becomes active, not a repeating loop. The
                                     rect's pathLength="100" normalizes stroke-dasharray/
                                     dashoffset to a simple 0–100 range regardless of the
                                     frame's actual rendered size. --}}
                                <div class="cine-frame-sweep" aria-hidden="true"></div>
                                <svg class="cine-frame-border" viewBox="0 0 100 75" preserveAspectRatio="none" aria-hidden="true">
                                    <rect x="1" y="1" width="98" height="73" rx="6" pathLength="100"></rect>
                                </svg>
                            </div>
                        </div>
                        <div class="cine-info">
                            <span class="cine-index" data-reveal>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }} / {{ str_pad($count, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="cine-category" data-reveal>{{ $project['category'] }}</span>
                            <h2 class="cine-title" data-reveal>{{ $project['title'] }}</h2>
                            <div class="cine-rule" data-reveal></div>
                            <p class="cine-desc" data-reveal>{{ $project['description'] }}</p>
                            <a href="{{ route('consultation.create') }}" class="cine-cta" data-reveal>
                                <span class="cine-cta-fill" aria-hidden="true"></span>
                                <span class="cine-cta-content">
                                    Start a Project Like This
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ── Finale / CTA ── --}}
    <section class="cine-finale">
        <img class="cine-finale-img opacity-0" data-reveal src="@assetv('image/whats-next.png')" alt="" aria-hidden="true" loading="lazy">
        <h2 class="cine-finale-title opacity-0" data-reveal>Ready for what's next?</h2>
        <p class="cine-finale-sub opacity-0" data-reveal>Let's talk about the project you have in mind — and build something worth scrolling through.</p>
        <div class="cine-finale-ctas opacity-0" data-reveal>
            <a href="{{ route('consultation.create') }}" class="hero-btn-primary">
                <span class="hero-btn-fill" aria-hidden="true"></span>
                <span class="hero-btn-content">Book A Consultation</span>
            </a>
            <a href="{{ route('register') }}" class="hero-btn-secondary">
                <span class="hero-btn-fill" aria-hidden="true"></span>
                <span class="hero-btn-content">Let's Build Your Website</span>
            </a>
        </div>
    </section>

</div>

@endsection

@section('scripts')
<script src="@assetv('cinematic-gallery.js')" defer></script>
@endsection
