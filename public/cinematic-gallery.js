/* ════════════════════════════════════════════════════════════
   CINEMATIC SCROLL GALLERY — animation logic only.
   Markup: resources/views/gallery.blade.php
   Data:   config/gallery.php
   Adding/removing a project only touches config/gallery.php —
   this file reads scene count from the DOM at runtime.
   ════════════════════════════════════════════════════════════ */
(function () {
    function ready(fn) {
        if (document.readyState !== 'loading') fn();
        else document.addEventListener('DOMContentLoaded', fn);
    }

    // ── Cinematic opening sequence ──
    // A fixed overlay (pointer-events:none) that plays once on load, on top
    // of the real page rendering normally underneath. Because it never
    // intercepts input, there's no scroll-lock to release — the first
    // wheel/touch/key/scroll just fades it away immediately. See the CSS
    // failsafe keyframe in cinematic-gallery.css for what happens if this
    // function never runs at all (blocked script, etc.).
    function initCineOpening() {
        var overlay = document.getElementById('cine-opening');
        if (!overlay) return;

        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            overlay.remove();
            return;
        }

        if (typeof gsap === 'undefined') {
            return setTimeout(initCineOpening, 100);
        }

        var bg = overlay.querySelector('.cine-opening-bg');
        var stars = overlay.querySelector('.cine-opening-stars');
        var field = overlay.querySelector('.cine-opening-field');
        var frames = Array.prototype.slice.call(overlay.querySelectorAll('.cine-opening-frame'));
        var featured = overlay.querySelector('.cine-opening-frame.is-featured');
        var background = frames.filter(function (f) { return f !== featured; });

        if (!featured || !frames.length) {
            overlay.remove();
            return;
        }

        var dismissed = false;
        var floatTweens = [];

        function cleanup() {
            floatTweens.forEach(function (t) { t.kill(); });
            if (overlay.parentNode) overlay.remove();
        }

        function dismiss() {
            if (dismissed) return;
            dismissed = true;
            tl.kill();
            floatTweens.forEach(function (t) { t.kill(); });
            gsap.to(overlay, {
                opacity: 0, duration: 0.35, ease: 'power2.out',
                onComplete: cleanup,
            });
        }

        // Any real input at all — scrolling in immediately, not waiting on
        // the sequence to finish — dismisses it. {once:true} so this never
        // fires twice; passive since nothing here needs to preventDefault.
        ['wheel', 'touchstart', 'keydown', 'scroll'].forEach(function (evt) {
            window.addEventListener(evt, dismiss, { passive: true, once: true });
        });

        // Frames start matching their CSS defaults (opacity 0, scale .6,
        // blur 14px) so there's no visible jump when GSAP takes over the
        // transform. Centering uses xPercent/yPercent (not a CSS
        // translate, which GSAP would silently overwrite) — same
        // convention already used for the homepage overture's scenes.
        gsap.set(frames, {
            xPercent: -50, yPercent: -50, scale: 0.6, opacity: 0,
            filter: 'blur(14px)', transformOrigin: 'center center', force3D: true,
        });

        var tl = gsap.timeline();

        tl.to(bg, { opacity: 1, duration: 0.5, ease: 'power2.out' }, 0)
            .to(stars, { opacity: 0.5, duration: 0.6, ease: 'power2.out' }, 0.05)
            // Depth is faked with scale/blur only (each frame's own CSS
            // width already sets its apparent size) — no z-translate/
            // perspective math needed for this effect.
            .to(frames, {
                opacity: 1, scale: 1, filter: 'blur(0px)',
                duration: 0.9, ease: 'power2.out', stagger: 0.12,
            }, 0.3)
            // Idle float once each background frame has arrived — same
            // slow bob/drift feel as .story-float elsewhere on the site.
            .call(function () {
                background.forEach(function (f, i) {
                    floatTweens.push(gsap.to(f, {
                        y: '+=14', rotation: (i % 2 === 0 ? 1 : -1) * 1.4,
                        duration: 3 + Math.random() * 2, ease: 'sine.inOut',
                        repeat: -1, yoyo: true, delay: Math.random() * 0.6,
                    }));
                });
            }, null, 1.6)
            // Camera dolly: the whole field pushes forward; background
            // frames blur/fade as they're passed, the featured one
            // sharpens and grows ahead of the rest.
            .to(field, { scale: 1.15, duration: 1.4, ease: 'power1.inOut' }, 1.4)
            .to(background, { filter: 'blur(10px)', opacity: 0, scale: 0.9, duration: 1.2, ease: 'power1.inOut' }, 1.6)
            .to(featured, { scale: 1.9, duration: 1.6, ease: 'power2.inOut' }, 1.4)
            // Convergence: featured image settles into final prominence.
            .to(featured, { scale: 2.1, duration: 1.0, ease: 'power2.out' }, 2.8)
            // Handoff: the overlay itself lifts away. The real page
            // underneath (title already faded in on load, gallery scene 0
            // already resting at full opacity showing this same photo) is
            // what's revealed — same image, similar scale/position, no cut.
            .to(overlay, { opacity: 0, duration: 0.5, ease: 'power2.inOut' }, 3.6)
            .call(function () { dismissed = true; cleanup(); }, null, 4.1);
    }

    function initCinematicGallery() {
        var root = document.getElementById('cine-gallery');
        if (!root) return;

        // GSAP/ScrollTrigger load with `defer` near the bottom of the layout
        // and this script (via @yield('scripts')) can run before they're
        // ready — poll like the rest of the site's inline scripts do.
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
            return setTimeout(initCinematicGallery, 100);
        }
        gsap.registerPlugin(ScrollTrigger);

        var reduce = window.matchMedia &&
            window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // ── Intro / finale fade-ins ──
        [{ sel: '.cine-intro', stagger: 0.12 }, { sel: '.cine-finale', stagger: 0.12 }]
            .forEach(function (group) {
                var container = root.querySelector(group.sel);
                if (!container) return;
                var els = container.querySelectorAll('[data-reveal]');
                if (!els.length) return;

                if (reduce) {
                    els.forEach(function (el) { el.style.opacity = 1; });
                    return;
                }
                gsap.set(els, { opacity: 0, y: 26 });
                gsap.to(els, {
                    opacity: 1, y: 0, duration: 0.9, ease: 'power3.out', stagger: group.stagger,
                    scrollTrigger: { trigger: container, start: 'top 78%' },
                });
            });

        // ── Pinned camera through the project scenes ──
        var track = root.querySelector('.cine-pin-track');
        var stage = root.querySelector('.cine-stage');
        var scenes = Array.prototype.slice.call(root.querySelectorAll('.cine-scene'));
        var dots = Array.prototype.slice.call(root.querySelectorAll('.cine-dot'));
        var counter = root.querySelector('.cine-progress-count');

        if (reduce || !track || !stage || scenes.length < 2) {
            root.classList.add('cine-reduced');
        } else {
            gsap.set(scenes, { transformPerspective: 1600, transformOrigin: 'center center', force3D: true });
            gsap.set(scenes[0], { autoAlpha: 1, scale: 1, z: 0, filter: 'blur(0px)' });
            gsap.set(scenes.slice(1), { autoAlpha: 0, scale: 0.84, z: -360, filter: 'blur(12px)' });

            var tl = gsap.timeline({
                scrollTrigger: {
                    trigger: track,
                    start: 'top top',
                    end: 'bottom bottom',
                    scrub: 1,
                    pin: stage,
                    pinType: 'fixed',
                    anticipatePin: 1,
                    invalidateOnRefresh: true,
                    onUpdate: function (self) {
                        var active = Math.min(scenes.length - 1, Math.floor(self.progress * scenes.length));
                        dots.forEach(function (d, i) { d.classList.toggle('is-active', i === active); });
                        if (counter) {
                            counter.textContent =
                                String(active + 1).padStart(2, '0') + ' / ' + String(scenes.length).padStart(2, '0');
                        }
                    },
                },
            });

            var unit = 2;
            for (var i = 0; i < scenes.length - 1; i++) {
                var t = i * unit;
                tl.to(scenes[i], {
                    scale: 1.3, z: 260, filter: 'blur(13px)', autoAlpha: 0,
                    duration: 1, ease: 'power1.inOut',
                }, t)
                    .fromTo(scenes[i + 1],
                        { autoAlpha: 0, scale: 0.84, z: -360, filter: 'blur(12px)' },
                        {
                            autoAlpha: 1, scale: 1, z: 0, filter: 'blur(0px)',
                            duration: 1, ease: 'power1.inOut',
                        }, t + 0.4);
            }
        }

        ScrollTrigger.refresh();

        // ── Ken Burns drift on the frame currently in view, paused off-screen ──
        var frameImgs = root.querySelectorAll('.cine-frame img');
        if (!reduce && 'IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    entry.target.classList.toggle('cine-kenburns-run', entry.isIntersecting);
                });
            }, { threshold: 0.35 });
            frameImgs.forEach(function (img) { io.observe(img); });
        } else if (!reduce) {
            frameImgs.forEach(function (img) { img.classList.add('cine-kenburns-run'); });
        }

        // ── Cursor tilt + glow on frames — desktop/pointer:fine only ──
        if (!reduce && window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
            root.querySelectorAll('.cine-frame').forEach(function (frame) {
                var rotX = gsap.quickTo(frame, 'rotationX', { duration: 0.6, ease: 'power3.out' });
                var rotY = gsap.quickTo(frame, 'rotationY', { duration: 0.6, ease: 'power3.out' });

                frame.addEventListener('mousemove', function (e) {
                    var r = frame.getBoundingClientRect();
                    var px = (e.clientX - r.left) / r.width;
                    var py = (e.clientY - r.top) / r.height;
                    rotY((px - 0.5) * 10);
                    rotX(-(py - 0.5) * 10);
                    frame.style.setProperty('--mx', (px * 100) + '%');
                    frame.style.setProperty('--my', (py * 100) + '%');
                });
                frame.addEventListener('mouseleave', function () {
                    rotX(0);
                    rotY(0);
                });
            });
        }
    }

    ready(function () {
        initCineOpening();
        initCinematicGallery();

        // Lenis smooth scroll — self-contained to this page, loaded lazily so
        // it never blocks first paint, skipped entirely for reduced-motion.
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        var s = document.createElement('script');
        s.src = 'https://cdnjs.cloudflare.com/ajax/libs/lenis/1.1.18/lenis.min.js';
        s.onload = function () {
            if (typeof Lenis === 'undefined' || typeof gsap === 'undefined') return;
            var lenis = new Lenis({ duration: 1.1, smoothWheel: true });
            if (window.ScrollTrigger) lenis.on('scroll', ScrollTrigger.update);
            gsap.ticker.add(function (time) { lenis.raf(time * 1000); });
            gsap.ticker.lagSmoothing(0);
        };
        document.head.appendChild(s);
    });
})();
