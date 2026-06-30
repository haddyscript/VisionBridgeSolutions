// Mobile-only parallax fallback.
//
// The site's parallax dividers use background-attachment:fixed, which iOS
// Safari either ignores or renders glitchy — so on phones they show as a
// flat, static image instead of the depth effect seen on desktop. This
// swaps in a scroll-linked background-position shift (see mobile-design.css
// for the background-attachment:scroll override that pairs with it).
(function () {
    if (!window.matchMedia('(max-width: 768px)').matches) return;

    var dividers = Array.prototype.slice.call(document.querySelectorAll('.parallax-divider'));
    if (!dividers.length) return;

    var items = dividers.map(function (el) {
        var match = /center\s+(\d+(?:\.\d+)?)%/.exec(el.getAttribute('style') || '');
        return { el: el, basePercent: match ? parseFloat(match[1]) : 50 };
    });

    var ticking = false;

    function update() {
        var vh = window.innerHeight;
        items.forEach(function (item) {
            var rect = item.el.getBoundingClientRect();
            if (rect.bottom < -200 || rect.top > vh + 200) return;
            var progress = (vh - rect.top) / (vh + rect.height); // 0 entering → 1 leaving
            var shift = (progress - 0.5) * 24; // ±12% vertical drift
            item.el.style.backgroundPosition = 'center ' + (item.basePercent + shift) + '%';
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
            backdrop.addEventListener('click', closeMenu);
        }
    });

    menu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeMenu);
    });
})();
