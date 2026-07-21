// iOS-only parallax fallback.
//
// The site's parallax dividers use background-attachment:fixed, which works
// natively everywhere else (desktop, Android Chrome, Chrome DevTools mobile
// emulation — same as the footer) but iOS Safari (and any iOS browser, since
// they're all WebKit under the hood) ignores it, so the photo just scrolls
// in lockstep with the text instead of staying optically anchored.
//
// A background-position shift alone can't fix that — the image is still
// glued to the same scrolling box as the text, so it moves with it no
// matter how much the crop is panned. The actual fix: pull the photo out
// into its own oversized layer, behind the text, and translate that layer
// at a different rate than the page scrolls — so it visibly decouples from
// the text/button above it, the way a real fixed background would.
(function () {
    var isIOS = /iP(hone|od|ad)/.test(navigator.platform || '')
        || /iP(hone|od|ad)/.test(navigator.userAgent || '')
        || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
    if (!isIOS) return;

    document.documentElement.classList.add('ios-fixed-broken');

    var dividers = Array.prototype.slice.call(document.querySelectorAll('.parallax-divider'));
    if (!dividers.length) return;

    var items = dividers.map(function (el) {
        var computed = getComputedStyle(el);
        var bgImage = computed.backgroundImage;
        var bgPosition = computed.backgroundPosition;

        var layer = document.createElement('div');
        layer.className = 'ios-parallax-layer';
        layer.style.backgroundImage = bgImage;
        layer.style.backgroundPosition = bgPosition;
        el.insertBefore(layer, el.firstChild);

        el.style.backgroundImage = 'none';

        return { el: el, layer: layer };
    });

    var ticking = false;

    function update() {
        var vh = window.innerHeight;
        items.forEach(function (item) {
            var rect = item.el.getBoundingClientRect();
            if (rect.bottom < -200 || rect.top > vh + 200) return;
            var progress = (vh - rect.top) / (vh + rect.height); // 0 entering → 1 leaving
            var translate = (progress - 0.5) * rect.height * 0.9; // up to ~45% of the divider's own height
            item.layer.style.transform = 'translateY(' + translate.toFixed(1) + 'px)';
        });
        ticking = false;
    }

    function onScroll() {
        if (!ticking) {
            window.requestAnimationFrame(update);
            ticking = true;
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    update();
})();

// Mobile-only Hero avatar pop trigger.
//
// The existing GSAP heroTl (home.blade.php) fades #hero-trust in as one flat
// block by setting its inline opacity from 0 to 1. mobile-design.css adds a
// staggered per-avatar pop-in animation gated behind an .avatars-pop class —
// this watches #hero-trust's style attribute and adds that class the moment
// heroTl reveals it, so the pop is timed with the rest of the intro.
(function () {
    if (!window.matchMedia('(max-width: 768px)').matches) return;

    var trust = document.getElementById('hero-trust');
    var avatars = document.getElementById('hero-avatars');
    if (!trust || !avatars) return;

    var triggered = false;
    var observer;

    function maybeTrigger() {
        if (triggered) return;
        if (parseFloat(getComputedStyle(trust).opacity) > 0.5) {
            triggered = true;
            avatars.classList.add('avatars-pop');
            observer.disconnect();
        }
    }

    observer = new MutationObserver(maybeTrigger);
    observer.observe(trust, { attributes: true, attributeFilter: ['style'] });
    maybeTrigger();
})();

// Mobile-only full-screen nav menu — owns the entire open/close sequence:
// the dim backdrop, the .hidden toggle, and a staggered entrance/exit.
//
// This used to be split across two places: app.blade.php's own click
// handler did an instant `.hidden` toggle, and this file just reacted after
// the fact to layer a backdrop + CSS-keyframe stagger on top. That worked
// for opening (nothing has to happen before the panel appears), but a real
// *animated close* is impossible under that split — by the time this file's
// listener ran, `.hidden` (display:none) had already been applied
// synchronously, so there was nothing left visible to animate out. This
// version is the single owner of #menu-btn's click, and controls exactly
// when `.hidden` gets added — only after the exit animation finishes.
//
// Stagger order matches the boss's own timeline: the panel itself (its
// background/blur "appearing"), then the header, then the 5 links in
// order, then the CTA — each 60ms after the previous. If GSAP hasn't
// loaded yet, both open and close fall back to an instant show/hide rather
// than leaving the menu stuck invisible or stuck open.
(function () {
    if (!window.matchMedia('(max-width: 768px)').matches) return;

    var menuBtn = document.getElementById('menu-btn');
    var menu = document.getElementById('mobile-menu');
    if (!menuBtn || !menu) return;

    var backdrop = document.createElement('div');
    backdrop.id = 'mobile-menu-backdrop';
    backdrop.setAttribute('aria-hidden', 'true');

    var isOpen = false;
    var animating = false;

    function staggerTargets() {
        var targets = [];
        var header = document.getElementById('mobile-menu-header');
        if (header) targets.push(header);
        menu.querySelectorAll('#mobile-menu-links > a').forEach(function (a) {
            targets.push(a);
        });
        return targets;
    }

    function openMenu() {
        if (isOpen || animating) return;
        isOpen = true;
        animating = true;

        menu.classList.remove('hidden');
        menuBtn.classList.add('is-open');
        document.body.classList.add('mobile-menu-is-open');
        document.body.appendChild(backdrop);
        requestAnimationFrame(function () { backdrop.classList.add('is-visible'); });
        backdrop.addEventListener('click', closeMenu);

        var targets = staggerTargets();
        var headerDivider = document.getElementById('mobile-menu-divider-header');
        var ctaDivider = document.getElementById('mobile-menu-divider-cta');

        if (typeof gsap === 'undefined') {
            animating = false;
            return;
        }

        gsap.set(menu, { opacity: 0 });
        gsap.set(targets, { opacity: 0, y: 16 });
        // Dividers draw in via scaleX (transform-origin:left is set in the
        // markup) rather than fading — a growing line reads as "drawing",
        // a fading line just reads as appearing.
        if (headerDivider) gsap.set(headerDivider, { scaleX: 0 });
        if (ctaDivider) gsap.set(ctaDivider, { scaleX: 0 });

        var tl = gsap.timeline({ onComplete: function () { animating = false; } })
            // Step 1: the panel itself fades in — its own background +
            // backdrop-filter is what reads as "blur" appearing.
            .to(menu, { opacity: 1, duration: 0.3, ease: 'power2.out' })
            // Steps 2–8: header, then each link, then the CTA — starting
            // 60ms after step 1 *starts* (not finishes), then 60ms apart
            // from each other, for one consistent cadence across all 8
            // steps rather than a bigger gap after just the first one.
            .to(targets, {
                opacity: 1,
                y: 0,
                duration: 0.35,
                ease: 'power2.out',
                stagger: 0.06,
            }, 0.06);

        // Header divider draws in just after the header itself has faded in
        // (header starts at 0.06); CTA divider draws in just before the CTA
        // does (CTA is the last of the 7 staggered targets, starting at
        // 0.06 + 6 × 0.06 = 0.42).
        if (headerDivider) tl.to(headerDivider, { scaleX: 1, duration: 0.3, ease: 'power2.out' }, 0.2);
        if (ctaDivider) tl.to(ctaDivider, { scaleX: 1, duration: 0.3, ease: 'power2.out' }, 0.38);
    }

    function closeMenu() {
        if (!isOpen || animating) return;
        isOpen = false;
        animating = true;

        menuBtn.classList.remove('is-open');
        document.body.classList.remove('mobile-menu-is-open');
        backdrop.classList.remove('is-visible');

        function finish() {
            menu.classList.add('hidden');
            if (backdrop.parentNode) backdrop.parentNode.removeChild(backdrop);
            animating = false;
        }

        if (typeof gsap === 'undefined') {
            finish();
            return;
        }

        var targets = staggerTargets().concat(
            [document.getElementById('mobile-menu-divider-header'), document.getElementById('mobile-menu-divider-cta')]
                .filter(function (el) { return el; })
        );

        // Reverse of the open sequence: everything fades upward together
        // first (no stagger on the way out — a staggered close reads as
        // sluggish, not premium), then the panel's own blur/background
        // fades last, and only then does `.hidden` actually get applied.
        // The dividers just fade with everything else here (no reverse
        // scaleX shrink) — keeping the close simple, one motion for
        // everything, matching "everything fades upward" as given.
        gsap.timeline({ onComplete: finish })
            .to(targets, { opacity: 0, y: -14, duration: 0.2, ease: 'power1.in' })
            .to(menu, { opacity: 0, duration: 0.22, ease: 'power1.in' }, '-=0.05');
    }

    menuBtn.addEventListener('click', function () {
        if (isOpen) closeMenu(); else openMenu();
    });

    menu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeMenu);
    });
})();

// Mobile menu — drifting gold particles behind the frosted-glass content.
//
// Lazily created the first time the menu is opened, not on page load —
// the menu starts `hidden` and there's no reason to animate an invisible
// container. Reuses the .hero-particle CSS class already defined for the
// hero background (visual consistency, no new styling needed), at a much
// smaller count appropriate for a focused panel rather than a full-page
// hero. If GSAP hasn't loaded yet on first open, this quietly no-ops and
// retries on the next open — never blocks the menu's own open/close, which
// works entirely independently of this.
(function () {
    if (!window.matchMedia('(max-width: 768px)').matches) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var menuBtn = document.getElementById('menu-btn');
    var container = document.getElementById('mobile-menu-particles');
    if (!menuBtn || !container) return;

    var created = false;

    menuBtn.addEventListener('click', function () {
        if (created || typeof gsap === 'undefined') return;
        created = true;

        var count = 10;
        for (var i = 0; i < count; i++) {
            var el = document.createElement('div');
            el.className = 'hero-particle';
            var size = 3 + Math.random() * 4;
            el.style.width = size + 'px';
            el.style.height = size + 'px';
            el.style.left = Math.random() * 100 + '%';
            el.style.top = Math.random() * 100 + '%';
            el.style.opacity = 0;
            container.appendChild(el);

            gsap.set(el, { opacity: 0.3 + Math.random() * 0.35 });

            gsap.to(el, {
                x: (Math.random() - 0.5) * 60,
                y: -30 - Math.random() * 50,
                duration: 8 + Math.random() * 8,
                delay: Math.random() * 5,
                ease: 'sine.inOut',
                repeat: -1,
                yoyo: true,
            });

            gsap.to(el, {
                opacity: 0.85 + Math.random() * 0.15,
                duration: 1.2 + Math.random() * 1.5,
                delay: Math.random() * 3,
                ease: 'sine.inOut',
                repeat: -1,
                yoyo: true,
            });
        }
    });
})();

// Mobile-only top scroll-progress bar — desktop has the #section-rail dot
// nav for scroll feedback; mobile has no equivalent, so this fills a glowing
// gradient bar across the very top edge of the viewport as the page scrolls.
(function () {
    if (!window.matchMedia('(max-width: 768px)').matches) return;

    var bar = document.createElement('div');
    bar.id = 'mobile-scroll-progress';
    var fill = document.createElement('div');
    fill.id = 'mobile-scroll-progress-fill';
    bar.appendChild(fill);
    document.body.appendChild(bar);

    var ticking = false;

    function update() {
        var doc = document.documentElement;
        var scrollTop = window.scrollY || doc.scrollTop;
        var max = doc.scrollHeight - doc.clientHeight;
        var pct = max > 0 ? Math.min(100, (scrollTop / max) * 100) : 0;
        fill.style.width = pct + '%';
        ticking = false;
    }

    function onScroll() {
        if (!ticking) {
            window.requestAnimationFrame(update);
            ticking = true;
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    update();
})();

// Mobile-only sticky floating CTA.
//
// Reuses #mobile-menu-cta's already-resolved href (set by Blade — correct
// route/anchor logic lives there, not duplicated here) so the pill always
// points wherever "Get Started" points. Visible while scrolling through
// content, hidden over the hero (own CTA already on screen), over #founder
// (its story text/Read More toggle sit where the pill would land), and over
// #contact (the real form is the better target there).
(function () {
    if (!window.matchMedia('(max-width: 768px)').matches) return;
    if (!('IntersectionObserver' in window)) return;

    var sourceCta = document.getElementById('mobile-menu-cta');
    var hero = document.getElementById('hero');
    var about = document.getElementById('about');
    var founder = document.getElementById('founder');
    var contact = document.getElementById('contact');
    if (!sourceCta || !hero) return;

    var pill = document.createElement('a');
    pill.id = 'mobile-sticky-cta';
    pill.href = sourceCta.getAttribute('href');
    pill.textContent = 'Get Started';
    document.body.appendChild(pill);

    var hiddenByHero = true;
    var hiddenByAbout = false;
    var hiddenByFounder = false;
    var hiddenByContact = false;

    function updateVisibility() {
        pill.classList.toggle('is-visible', !hiddenByHero && !hiddenByAbout && !hiddenByFounder && !hiddenByContact);
    }

    new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) { hiddenByHero = entry.isIntersecting; });
        updateVisibility();
    }, { threshold: 0.15 }).observe(hero);

    // The About section now has its own inline CTA bar right under the
    // Mission/Vision cards (home.blade.php) — hiding the floating pill here
    // stops it from overlapping that bar or the card copy above it.
    if (about) {
        new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) { hiddenByAbout = entry.isIntersecting; });
            updateVisibility();
        }, { threshold: 0.1 }).observe(about);
    }

    if (founder) {
        new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) { hiddenByFounder = entry.isIntersecting; });
            updateVisibility();
        }, { threshold: 0.1 }).observe(founder);
    }

    if (contact) {
        new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) { hiddenByContact = entry.isIntersecting; });
            updateVisibility();
        }, { threshold: 0.1 }).observe(contact);
    }
})();

// Mobile-only navbar glow — the pill grows more glassy (heavier blur) and
// gains a soft gold ambient glow the deeper the page is scrolled, instead of
// the flat single blur value it had before. Reuses the same scroll-percent
// math as the top progress bar.
(function () {
    if (!window.matchMedia('(max-width: 768px)').matches) return;

    var nav = document.getElementById('nav-inner');
    if (!nav) return;

    var ticking = false;

    function update() {
        var doc = document.documentElement;
        var max = doc.scrollHeight - doc.clientHeight;
        var pct = max > 0 ? Math.min(1, (window.scrollY || doc.scrollTop) / max) : 0;

        var blur = 16 + pct * 16;          // 16px at top -> 32px at bottom
        var glowAlpha = 0.12 + pct * 0.38; // 0.12 -> 0.50
        var glowSpread = 16 + pct * 26;    // 16px -> 42px

        nav.style.setProperty('--nav-blur', blur.toFixed(1) + 'px');
        nav.style.setProperty('--nav-glow',
            '0 0 ' + glowSpread.toFixed(0) + 'px ' + (glowSpread * 0.35).toFixed(0) +
            'px rgba(201,168,76,' + glowAlpha.toFixed(2) + ')');

        ticking = false;
    }

    function onScroll() {
        if (!ticking) {
            window.requestAnimationFrame(update);
            ticking = true;
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    update();
})();

// Mobile-only: iOS Safari only triggers :active on non-link elements once a
// touchstart listener exists somewhere in the document. This unlocks the
// card tap-feedback (:active rings) defined in mobile-design.css.
(function () {
    if (!window.matchMedia('(max-width: 768px)').matches) return;
    document.addEventListener('touchstart', function () {}, { passive: true });
})();

// Mobile-only Services card blur-up: removes the blur/opacity placeholder
// once each lazy-loaded photo actually finishes fetching.
(function () {
    if (!window.matchMedia('(max-width: 768px)').matches) return;

    document.querySelectorAll('.services-card img').forEach(function (img) {
        if (img.complete && img.naturalWidth > 0) {
            img.classList.add('is-loaded');
        } else {
            img.addEventListener('load', function () { img.classList.add('is-loaded'); }, { once: true });
        }
    });
})();

// Mobile-only section-entrance bounce — adds a one-time overshoot-settle
// class to each major section the first time it scrolls into view.
(function () {
    if (!window.matchMedia('(max-width: 768px)').matches) return;
    if (!('IntersectionObserver' in window)) return;

    var ids = ['about', 'services', 'why', 'plans', 'portfolio', 'partnership', 'contact'];
    var io = new IntersectionObserver(function (entries, obs) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('mobile-section-bounce-in');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0, rootMargin: '0px 0px -10% 0px' });

    ids.forEach(function (id) {
        var el = document.getElementById(id);
        if (el) io.observe(el);
    });
})();

// Mobile-only inner-card staggered reveal — cards inside Core Values,
// Services, Portfolio, and Plans fade/slide in individually as each one
// scrolls into view, staggered per parent so siblings cascade in rather
// than the whole row appearing at once like the section-level bounce above.
(function () {
    if (!window.matchMedia('(max-width: 768px)').matches) return;
    if (!('IntersectionObserver' in window)) return;

    var cards = Array.prototype.slice.call(document.querySelectorAll(
        '#about .value-card, #services .services-card, #portfolio .portfolio-card, #plans .plan-card-panel'
    ));
    if (!cards.length) return;

    var byParent = [];
    cards.forEach(function (el) {
        var group = byParent.filter(function (g) { return g.parent === el.parentElement; })[0];
        if (!group) {
            group = { parent: el.parentElement, items: [] };
            byParent.push(group);
        }
        group.items.push(el);
    });
    byParent.forEach(function (group) {
        group.items.forEach(function (el, i) {
            el.classList.add('mobile-reveal-pending');
            el.style.animationDelay = (i * 90) + 'ms';
        });
    });

    var io = new IntersectionObserver(function (entries, obs) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.remove('mobile-reveal-pending');
                entry.target.classList.add('mobile-reveal-in');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -8% 0px' });

    cards.forEach(function (el) { io.observe(el); });
})();
