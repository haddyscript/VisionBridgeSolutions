@extends('layouts.app')

@section('title', 'Website Redesign & Rescue – VisionBridge Solutions')
@section('description', 'Already have a website? We redesign, rebuild, and rescue outdated, underperforming, or poorly-hosted websites for churches, ministries, nonprofits, and businesses.')

@section('content')

@php
    $svgIcons = [
        'refresh'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>',
        'mobile'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>',
        'chat'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>',
        'bolt'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
        'compass'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M12 3a9 9 0 100 18 9 9 0 000-18z"/>',
        'search'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>',
        'shield'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
        'clipboard'=> '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
        'swatch'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>',
        'rocket'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
    ];

    $painPoints = [
        ['icon' => 'refresh', 'title' => 'Looks Outdated', 'desc' => "Built years ago and it shows — visitors judge credibility in seconds, and an old design costs you trust before anyone reads a word."],
        ['icon' => 'mobile',  'title' => 'Not Mobile-Friendly', 'desc' => 'Most visitors are on a phone. If your site is hard to use there, you\'re losing the majority of your traffic before it converts.'],
        ['icon' => 'chat',    'title' => 'No One To Call', 'desc' => 'Your old developer went dark, or your host offers zero support — you\'re stuck when something breaks.'],
        ['icon' => 'bolt',    'title' => 'Slow To Load', 'desc' => 'A slow site loses visitors and ranks worse in search — every extra second costs you real people.'],
        ['icon' => 'compass', 'title' => 'Confusing Navigation', 'desc' => "Visitors can't find what they need — service info, giving, contact — and leave instead of taking action."],
        ['icon' => 'search',  'title' => 'Invisible On Google', 'desc' => "If your site isn't built with search in mind, people looking for exactly what you offer never find you at all."],
    ];

    $processSteps = [
        ['num' => '01', 'icon' => 'clipboard', 'title' => 'Audit', 'desc' => "We review your current site, hosting, and goals — what's working, what's holding you back, and what to fix first."],
        ['num' => '02', 'icon' => 'swatch',    'title' => 'Plan',   'desc' => 'A clear design direction and scope, built around your brand and what your visitors actually need to do.'],
        ['num' => '03', 'icon' => 'refresh',   'title' => 'Rebuild','desc' => 'A modern, mobile-first site — rebuilt from the ground up, or refreshed where it makes sense — with real milestones you can track.'],
        ['num' => '04', 'icon' => 'rocket',    'title' => 'Launch & Support', 'desc' => "We migrate your domain, launch on reliable hosting, and stay on as your ongoing support team — for good."],
    ];

    $faqs = [
        ['q' => "Will I lose my content?", 'a' => "No — we start by reviewing everything already on your site (copy, images, logos) and carry over what's still working, so nothing gets lost in the rebuild."],
        ['q' => "Can I keep my domain name?", 'a' => "Yes. Your domain stays yours and we handle pointing it to your new site during launch, with no downtime you'll notice."],
        ['q' => "What if I'm on a host I don't like?", 'a' => "We can migrate you to reliable, managed hosting as part of the rebuild — or work with your existing host if you'd rather stay put."],
        ['q' => "How long does a redesign take?", 'a' => "It depends on scope, but every project moves through the same clear path — audit, design, build, and launch — with real milestones you can follow the whole way through."],
        ['q' => "Do I need a whole new site, or can you just refresh what I have?", 'a' => "Either — the audit tells us which makes more sense. Sometimes a targeted refresh solves the real problem; sometimes a full rebuild is the better investment. We'll be straight with you about which one you actually need."],
    ];
@endphp

<style>
    #redesign-hero {
        --vb-gold: #C9A84C;
        --vb-gold-light: #DFC06A;
        --vb-teal: #2CA6A4;
        background: #0A0A0A;
        position: relative;
        overflow: hidden;
        padding-top: clamp(120px, 14vw, 160px);
        padding-bottom: 90px;
        font-family: "Chakra Petch", "Chakra Petch Placeholder", sans-serif;
    }
    #redesign-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
        background-image: repeating-linear-gradient(135deg, rgba(255,255,255,.025) 0px, rgba(255,255,255,.025) 1px, transparent 1px, transparent 14px);
    }
    #redesign-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
        background: radial-gradient(ellipse 70% 60% at 30% 15%, rgba(201,168,76,.10), transparent 60%);
    }
    #redesign-page { font-family: "Chakra Petch", "Chakra Petch Placeholder", sans-serif; }

    .rd-tag {
        position: relative;
        display: inline-flex;
        align-items: center;
        padding: 8px 18px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .18em;
        text-transform: uppercase;
        color: rgba(255,255,255,.85);
        border: 1px solid rgba(255,255,255,.22);
    }
    .rd-tag-light {
        color: rgba(21,32,44,.78);
        border-color: rgba(21,32,44,.18);
    }
    .rd-tag::before, .rd-tag::after {
        content: '';
        position: absolute;
        top: -1px; bottom: -1px;
        width: 6px;
        border-top: 1px solid #C9A84C;
        border-bottom: 1px solid #C9A84C;
    }
    .rd-tag::before { left: -6px; border-left: 1px solid #C9A84C; }
    .rd-tag::after  { right: -6px; border-right: 1px solid #C9A84C; }

    .rd-headline {
        font-family: 'Orbitron', sans-serif;
        text-transform: uppercase;
        line-height: 1.02;
        letter-spacing: -.01em;
        font-size: clamp(2.4rem, 5.4vw, 4.4rem);
        color: #FFFFFF;
    }
    .rd-headline .accent { color: #C9A84C; }
    .rd-headline-light { color: #15202C; }
    .rd-headline-light .accent { color: #A8872E; }

    /* Pain-point cards */
    .rd-pain-card {
        position: relative;
        background: rgba(255,255,255,.02);
        border: 1px solid rgba(255,255,255,.10);
        padding: 26px 24px;
        clip-path: polygon(0 0, calc(100% - 20px) 0, 100% 20px, 100% 100%, 0 100%);
        transition: border-color .3s ease, background .3s ease;
    }
    .rd-pain-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 20px; height: 20px;
        background: linear-gradient(135deg, transparent 49%, #C9A84C 50%, transparent 51%);
        opacity: .9;
    }
    .rd-pain-card:hover { border-color: rgba(201,168,76,.4); background: rgba(201,168,76,.03); }
    .rd-pain-icon {
        width: 42px; height: 42px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid rgba(201,168,76,.4);
        color: #C9A84C;
        margin-bottom: 16px;
    }

    /* Process steps */
    .rd-step-card {
        position: relative;
        background: #FFFFFF;
        border: 1px solid rgba(21,32,44,.08);
        padding: 28px 24px;
        clip-path: polygon(0 0, calc(100% - 22px) 0, 100% 22px, 100% 100%, 0 100%);
        box-shadow: 0 4px 20px rgba(21,32,44,.06);
    }
    .rd-step-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 22px; height: 22px;
        background: linear-gradient(135deg, transparent 49%, #C9A84C 50%, transparent 51%);
    }
    .rd-step-num {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        font-weight: 800;
        color: rgba(21,32,44,.08);
        line-height: 1;
    }
    .rd-step-icon {
        width: 42px; height: 42px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        background: rgba(201,168,76,.10);
        color: #A8872E;
        margin: -34px 0 14px;
    }

    /* FAQ (mirrors contact.blade.php's accordion) */
    .rd-faq-item {
        position: relative;
        border: 1px solid rgba(21,32,44,.12);
        background: rgba(21,32,44,.015);
        clip-path: polygon(0 0, calc(100% - 20px) 0, 100% 20px, 100% 100%, 0 100%);
        transition: border-color .3s ease, background .3s ease;
    }
    .rd-faq-item::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 20px; height: 20px;
        background: linear-gradient(135deg, transparent 49%, rgba(21,32,44,.14) 50%, transparent 51%);
        transition: background .3s ease;
        pointer-events: none;
    }
    .rd-faq-item.is-open, .rd-faq-item:hover {
        border-color: rgba(201,168,76,.45);
        background: rgba(201,168,76,.03);
    }
    .rd-faq-item.is-open::before, .rd-faq-item:hover::before {
        background: linear-gradient(135deg, transparent 49%, #C9A84C 50%, transparent 51%);
    }
    .rd-faq-question-btn {
        width: 100%;
        display: flex; align-items: center; gap: 18px;
        padding: 20px 24px;
        background: none; border: none;
        cursor: pointer;
        text-align: left;
    }
    .rd-faq-number {
        font-family: 'Orbitron', sans-serif;
        font-size: 1rem;
        color: rgba(21,32,44,.28);
        flex-shrink: 0;
        width: 30px;
    }
    .rd-faq-question-text {
        flex: 1;
        font-weight: 700;
        font-size: .92rem;
        letter-spacing: .02em;
        color: rgba(21,32,44,.88);
    }
    .rd-faq-item.is-open .rd-faq-question-text { color: #A8872E; }
    .rd-faq-toggle-icon {
        width: 20px; height: 20px;
        flex-shrink: 0;
        color: #C9A84C;
        transition: transform .35s cubic-bezier(.22,1,.36,1);
    }
    .rd-faq-item.is-open .rd-faq-toggle-icon { transform: rotate(45deg); }
    .rd-faq-answer-wrap { max-height: 0; overflow: hidden; transition: max-height .4s cubic-bezier(.22,1,.36,1); }
    .rd-faq-answer { padding: 0 24px 22px 72px; font-size: .88rem; line-height: 1.65; color: rgba(21,32,44,.68); }
    @media (max-width: 640px) { .rd-faq-answer { padding-left: 24px; } }

    .rd-final-cta {
        background: linear-gradient(155deg,#0A0D11 0%,#171B21 45%,#0A0D11 100%);
        position: relative;
        overflow: hidden;
    }

    @media (max-width: 767px) {
        .rd-pain-card, .rd-step-card, .rd-faq-item { clip-path: none; border-radius: 16px; }
        .rd-pain-card::before, .rd-step-card::before, .rd-faq-item::before { display: none; }
    }
</style>

<div id="redesign-page">

    {{-- ── Hero ── --}}
    <section id="redesign-hero">
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="rd-tag" data-rd-reveal>Website Redesign &amp; Rescue</div>

            <h1 class="rd-headline mt-6" data-rd-reveal>
                Your Website Shouldn't Be<br><span class="accent">Working Against You.</span>
            </h1>

            <p class="mt-6 text-base sm:text-lg leading-relaxed max-w-2xl mx-auto" style="color:rgba(255,255,255,.62);" data-rd-reveal>
                Outdated design. Unreliable hosting. No one to call when something breaks. If your current website is holding your organization back instead of moving it forward, we'll fix that — without starting from zero.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-10" data-rd-reveal>
                <a href="{{ route('consultation.create') }}" class="hero-btn-primary">
                    <span class="hero-btn-fill" aria-hidden="true"></span>
                    <span class="hero-btn-content">
                        Book A Free Consultation
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </a>
                <a href="{{ route('gallery') }}" class="hero-btn-secondary" style="background:transparent;border-color:rgba(255,255,255,.30);color:rgba(255,255,255,.90);">
                    <span class="hero-btn-fill" aria-hidden="true" style="background:rgba(255,255,255,.10);"></span>
                    <span class="hero-btn-content">See Our Work</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ── Signs it's time — pain points ── --}}
    <section id="redesign-signs" class="py-24" style="background:#0A0A0A;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <div class="rd-tag" data-rd-reveal>Sound Familiar?</div>
                <h2 class="rd-headline mt-5" style="font-size:clamp(2rem,4.2vw,3.2rem);" data-rd-reveal>
                    Signs It's Time For<br><span class="accent">A Redesign.</span>
                </h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5" data-rd-reveal>
                @foreach ($painPoints as $point)
                    <div class="rd-pain-card">
                        <div class="rd-pain-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$point['icon']] !!}</svg>
                        </div>
                        <p class="text-base font-bold mb-2" style="color:#fff;">{{ $point['title'] }}</p>
                        <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,.58);">{{ $point['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Process ── --}}
    <section id="redesign-process" class="py-24" style="background:#F7F8FA;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <div class="rd-tag rd-tag-light" data-rd-reveal>How It Works</div>
                <h2 class="rd-headline rd-headline-light mt-5" style="font-size:clamp(2rem,4.2vw,3.2rem);" data-rd-reveal>
                    From Outdated To<br><span class="accent">On-Brand.</span>
                </h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" data-rd-reveal>
                @foreach ($processSteps as $step)
                    <div class="rd-step-card">
                        <span class="rd-step-num">{{ $step['num'] }}</span>
                        <div class="rd-step-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$step['icon']] !!}</svg>
                        </div>
                        <p class="text-base font-bold mb-2" style="color:#15202C;">{{ $step['title'] }}</p>
                        <p class="text-sm leading-relaxed" style="color:rgba(21,32,44,.62);">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── FAQ ── --}}
    <section id="redesign-faq" class="py-24" style="background:#FFFFFF;">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="rd-tag rd-tag-light" data-rd-reveal>Common Questions</div>
                <h2 class="rd-headline rd-headline-light mt-5" style="font-size:clamp(1.9rem,3.8vw,2.8rem);" data-rd-reveal>
                    Before You <span class="accent">Switch.</span>
                </h2>
            </div>
            <div class="flex flex-col gap-3" data-rd-reveal>
                @foreach ($faqs as $index => $faq)
                    <div class="rd-faq-item">
                        <button type="button" class="rd-faq-question-btn" aria-expanded="false">
                            <span class="rd-faq-number">{{ sprintf('%02d', $index + 1) }}</span>
                            <span class="rd-faq-question-text">{{ $faq['q'] }}</span>
                            <svg class="rd-faq-toggle-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                        </button>
                        <div class="rd-faq-answer-wrap">
                            <p class="rd-faq-answer">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Final CTA ── --}}
    <section class="rd-final-cta py-24">
        <div class="hero-orb" style="width:520px;height:520px;top:-140px;right:-100px;background:radial-gradient(circle,rgba(201,168,76,0.14) 0%,transparent 70%);filter:blur(60px);animation:orb-drift 20s ease-in-out infinite;"></div>
        <div class="hero-orb" style="width:420px;height:420px;bottom:-100px;left:-90px;background:radial-gradient(circle,rgba(42,157,143,0.12) 0%,transparent 70%);filter:blur(55px);animation:orb-drift 24s ease-in-out infinite reverse 3s;"></div>
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center" style="z-index:1;">
            <h2 class="rd-headline" style="font-size:clamp(2rem,4.4vw,3.4rem);" data-rd-reveal>
                Ready To Give Your Website<br>The Upgrade It <span class="accent">Deserves?</span>
            </h2>
            <p class="mt-5 text-base leading-relaxed max-w-xl mx-auto" style="color:rgba(255,255,255,.62);" data-rd-reveal>
                Let's talk about what's not working — and build something your organization is proud to send people to.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-9" data-rd-reveal>
                <a href="{{ route('consultation.create') }}" class="hero-btn-primary">
                    <span class="hero-btn-fill" aria-hidden="true"></span>
                    <span class="hero-btn-content">Book A Consultation</span>
                </a>
                <a href="{{ route('contact') }}" class="hero-btn-secondary" style="background:transparent;border-color:rgba(255,255,255,.30);color:rgba(255,255,255,.90);">
                    <span class="hero-btn-fill" aria-hidden="true" style="background:rgba(255,255,255,.10);"></span>
                    <span class="hero-btn-content">Request A Proposal</span>
                </a>
            </div>
        </div>
    </section>

</div>

{{-- Entrance reveal — same gsap.from() approach as welcome-back.blade.php:
     sets the invisible starting state only once GSAP has actually loaded,
     so this page's normal (visible) markup is the safe fallback if the CDN
     is ever blocked, rather than depending on the layout's own watchdog. --}}
<script>
(function () {
    function initRedesignReveal() {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') { setTimeout(initRedesignReveal, 80); return; }
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        gsap.registerPlugin(ScrollTrigger);
        var TOGGLE = 'play none none reverse';

        gsap.utils.toArray('[data-rd-reveal]').forEach(function (el) {
            gsap.from(el, {
                opacity: 0, y: 24, duration: 0.7, ease: 'power3.out',
                scrollTrigger: { trigger: el, start: 'top 88%', toggleActions: TOGGLE },
            });
        });

        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(function () { ScrollTrigger.refresh(); });
        }
    }
    if (document.readyState !== 'loading') { initRedesignReveal(); }
    else { window.addEventListener('DOMContentLoaded', initRedesignReveal); }
})();
</script>

{{-- FAQ accordion — plain max-height transition, same pattern as contact.blade.php --}}
<script>
(function () {
    document.querySelectorAll('.rd-faq-item').forEach(function (item) {
        var btn = item.querySelector('.rd-faq-question-btn');
        var wrap = item.querySelector('.rd-faq-answer-wrap');
        if (!btn || !wrap) return;

        btn.addEventListener('click', function () {
            var isOpen = item.classList.toggle('is-open');
            btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            wrap.style.maxHeight = isOpen ? wrap.scrollHeight + 'px' : '0px';
        });
    });

    window.addEventListener('resize', function () {
        document.querySelectorAll('.rd-faq-item.is-open .rd-faq-answer-wrap').forEach(function (wrap) {
            wrap.style.maxHeight = wrap.scrollHeight + 'px';
        });
    });
})();
</script>

@endsection
