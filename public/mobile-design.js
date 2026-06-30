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
