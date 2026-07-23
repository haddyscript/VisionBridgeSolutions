@extends('layouts.app')

@section('title', 'Our Work — VisionBridge Solutions')
@section('description', 'A scroll-driven visual journey through the websites, hosting, and care plans we\'ve built for our clients.')

@section('content')

@php
    $projects = config('gallery.projects', []);
    $count    = count($projects);
@endphp

<link rel="stylesheet" href="@assetv('cinematic-gallery.css')">

<div id="cine-gallery">

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

                @foreach($projects as $i => $project)
                    <div class="cine-scene" data-scene="{{ $i }}">
                        <div class="cine-frame">
                            <img src="@assetv($project['image'])" alt="{{ $project['title'] }}" loading="lazy">
                        </div>
                        <div class="cine-info">
                            <span class="cine-index">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }} / {{ str_pad($count, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="cine-category">{{ $project['category'] }}</span>
                            <h2 class="cine-title">{{ $project['title'] }}</h2>
                            <div class="cine-rule"></div>
                            <p class="cine-desc">{{ $project['description'] }}</p>
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
