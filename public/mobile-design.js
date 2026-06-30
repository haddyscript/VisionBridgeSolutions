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
            var translate = (progress - 0.5) * rect.height * 0.7; // up to ~35% of the divider's own height
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

// Mobile-only nav dropdown wow-factor: dim backdrop + staggered link entrance.
//
// app.blade.php's own click handler on #menu-btn toggles the `hidden` class
// on #mobile-menu and runs first (it's registered earlier in the document,
// and listeners on the same element fire in registration order). This just
// reacts after that toggle to add/remove a backdrop behind the panel and a
// `menu-opening` class that drives the staggered slide-in in mobile-design.css.
(function () {
    if (!window.matchMedia('(max-width: 768px)').matches) return;

    var menuBtn = document.getElementById('menu-btn');
    var menu = document.getElementById('mobile-menu');
    if (!menuBtn || !menu) return;

    var backdrop = document.createElement('div');
    backdrop.id = 'mobile-menu-backdrop';
    backdrop.setAttribute('aria-hidden', 'true');

    function closeMenu() {
        menu.classList.add('hidden');
        menu.classList.remove('menu-opening');
        menuBtn.classList.remove('is-open');
        document.body.classList.remove('mobile-menu-is-open');
        backdrop.classList.remove('is-visible');
        if (backdrop.parentNode) backdrop.parentNode.removeChild(backdrop);
    }

    menuBtn.addEventListener('click', function () {
        if (menu.classList.contains('hidden')) {
            closeMenu();
        } else {
            document.body.appendChild(backdrop);
            requestAnimationFrame(function () { backdrop.classList.add('is-visible'); });
            menu.classList.add('menu-opening');
            menuBtn.classList.add('is-open');
            document.body.classList.add('mobile-menu-is-open');
            backdrop.addEventListener('click', closeMenu);
        }
    });

    menu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeMenu);
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
    var founder = document.getElementById('founder');
    var contact = document.getElementById('contact');
    if (!sourceCta || !hero) return;

    var pill = document.createElement('a');
    pill.id = 'mobile-sticky-cta';
    pill.href = sourceCta.getAttribute('href');
    pill.textContent = 'Get Started';
    document.body.appendChild(pill);

    var hiddenByHero = true;
    var hiddenByFounder = false;
    var hiddenByContact = false;

    function updateVisibility() {
        pill.classList.toggle('is-visible', !hiddenByHero && !hiddenByFounder && !hiddenByContact);
    }

    new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) { hiddenByHero = entry.isIntersecting; });
        updateVisibility();
    }, { threshold: 0.15 }).observe(hero);

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
