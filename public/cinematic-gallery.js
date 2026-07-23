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
    // of the real page rendering normally underneath. Scrolling is fully
    // locked for the duration — no early skip — via the body position:fixed
    // technique (works on iOS Safari, unlike plain overflow:hidden).
    //
    // The lock is applied by THIS function only, never by default CSS —
    // so if this script never runs at all (blocked/slow CDN), the page was
    // never locked in the first place ("fail open"). See the CSS failsafe
    // keyframe in cinematic-gallery.css for the matching visual guarantee
    // (the overlay itself fades away unconditionally even without JS).
    // Once the lock *does* engage, a hard setTimeout safety net guarantees
    // it's released even if the GSAP timeline never completes for some
    // unforeseen reason — this class of "stuck forever" bug is exactly
    // what's documented in FEATURES.md §29/§30 for the old video intro and
    // the film-grain overlay, so it gets the same defensive treatment here.
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
        var scrollLockY = 0;

        function lockScroll() {
            scrollLockY = window.scrollY || window.pageYOffset || 0;
            document.body.style.position = 'fixed';
            document.body.style.top = (-scrollLockY) + 'px';
            document.body.style.left = '0';
            document.body.style.right = '0';
            document.body.style.width = '100%';
        }

        function unlockScroll() {
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.left = '';
            document.body.style.right = '';
            document.body.style.width = '';
            window.scrollTo(0, scrollLockY);
        }

        function finish() {
            if (dismissed) return;
            dismissed = true;
            clearTimeout(safety);
            floatTweens.forEach(function (t) { t.kill(); });
            unlockScroll();
            if (overlay.parentNode) overlay.remove();
        }

        lockScroll();

        // Independent of the GSAP timeline below — guarantees scroll is
        // released even if that timeline somehow never reaches its own
        // onComplete (~4.1s of animation; 6s gives a comfortable margin).
        var safety = setTimeout(finish, 6000);

        // Frames start matching their CSS defaults (opacity 0, scale .6,
        // blur 14px) so there's no visible jump when GSAP takes over the
        // transform. Centering uses xPercent/yPercent (not a CSS
        // translate, which GSAP would silently overwrite) — same
        // convention already used for the homepage overture's scenes.
        gsap.set(frames, {
            xPercent: -50, yPercent: -50, scale: 0.6, opacity: 0,
            filter: 'blur(14px)', transformOrigin: 'center center', force3D: true,
        });

        var tl = gsap.timeline({ onComplete: finish });

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
            // tl's onComplete (= finish, set above) fires right after this,
            // releasing the scroll lock and removing the overlay.
            .to(overlay, { opacity: 0, duration: 0.5, ease: 'power2.inOut' }, 3.6);
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

        // Deterministic per-scene variation (index-based, not random) so
        // repeat visits/screenshots are consistent — glow color comes from
        // the .cine-variant-* CSS classes already in the markup; only the
        // float rhythm and sweep timing need JS values.
        function sceneVariant(i) {
            return {
                floatAmp: 8 + (i % 4) * 2,
                floatDur: 5 + (i % 3),
                sweepDelay: 0.15 + (i % 3) * 0.08,
            };
        }

        // Per-scene resources gathered once — the reveal/ambient logic below
        // reads scene count and structure from the DOM, same as the rest of
        // this file, so adding a project only means editing config/gallery.php.
        var sceneData = scenes.map(function (scene, i) {
            return {
                el: scene,
                wrap: scene.querySelector('.cine-frame-wrap'),
                frame: scene.querySelector('.cine-frame'),
                img: scene.querySelector('.cine-frame img'),
                sweep: scene.querySelector('.cine-frame-sweep'),
                borderRect: scene.querySelector('.cine-frame-border rect'),
                revealEls: Array.prototype.slice.call(scene.querySelectorAll('.cine-info [data-reveal]')),
                variant: sceneVariant(i),
                ambientTweens: [],
                sweepPlayed: false,
            };
        });

        if (reduce || !track || !stage || scenes.length < 2) {
            root.classList.add('cine-reduced');
            if (sceneData[0]) sceneData[0].el.classList.add('is-active');
        } else {
            gsap.set(scenes, { transformPerspective: 1600, transformOrigin: 'center center', force3D: true });
            gsap.set(scenes[0], { autoAlpha: 1, scale: 1, z: 0, filter: 'blur(0px)' });
            gsap.set(scenes.slice(1), { autoAlpha: 0, scale: 0.84, z: -360, filter: 'blur(12px)' });
            sceneData[0].el.classList.add('is-active');

            // Non-first scenes' inner content starts hidden/blurred to match
            // their parent scene's own hidden state — revealed together with
            // it in the crossfade loop below, never independently, so there's
            // only ever one clock driving both (no double-fade mismatch).
            sceneData.slice(1).forEach(function (s) {
                gsap.set(s.revealEls, { opacity: 0, y: 16, filter: 'blur(6px)' });
                if (s.img) gsap.set(s.img, { filter: 'blur(8px)' });
            });

            var currentActive = 0;
            var activatedOnce = { 0: true };

            // GSAP can't target a ::before pseudo-element directly, so the
            // one-shot sweep pass is driven by toggling a class that
            // triggers a plain CSS @keyframes animation instead (see
            // .cine-frame-sweep.is-sweeping in cinematic-gallery.css).
            function playSweep(s) {
                if (s.sweepPlayed || !s.sweep) return;
                s.sweepPlayed = true;
                gsap.delayedCall(s.variant.sweepDelay, function () {
                    s.sweep.classList.add('is-sweeping');
                    s.sweep.addEventListener('animationend', function () {
                        s.sweep.classList.remove('is-sweeping');
                    }, { once: true });
                });
            }

            function startAmbient(s) {
                if (s.ambientTweens.length || !s.wrap) return;
                s.ambientTweens.push(gsap.to(s.wrap, {
                    y: '+=' + s.variant.floatAmp,
                    duration: s.variant.floatDur, ease: 'sine.inOut',
                    repeat: -1, yoyo: true,
                }));
                var glow = s.el.querySelector('.cine-frame-glow');
                if (glow) {
                    glow.classList.add('cine-glow-pulse');
                    gsap.to(glow, { opacity: 1, duration: 0.6, ease: 'power2.out' });
                }
            }

            function stopAmbient(s) {
                s.ambientTweens.forEach(function (tw) { tw.kill(); });
                s.ambientTweens = [];
                if (s.wrap) gsap.set(s.wrap, { y: 0 });
                var glow = s.el.querySelector('.cine-frame-glow');
                if (glow) {
                    glow.classList.remove('cine-glow-pulse');
                    gsap.to(glow, { opacity: 0, duration: 0.4, ease: 'power2.out' });
                }
            }

            function onSceneActivate(newIndex) {
                if (newIndex === currentActive && sceneData[newIndex].el.classList.contains('is-active')) return;
                var prev = sceneData[currentActive];
                var next = sceneData[newIndex];
                if (prev) {
                    prev.el.classList.remove('is-active');
                    stopAmbient(prev);
                }
                next.el.classList.add('is-active');
                startAmbient(next);
                if (!activatedOnce[newIndex]) {
                    activatedOnce[newIndex] = true;
                    playSweep(next);
                }
                currentActive = newIndex;
            }

            // Kick off scene 0's own ambient loop immediately — it's the
            // only scene visible before any scrolling happens.
            startAmbient(sceneData[0]);

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
                        onSceneActivate(active);
                    },
                },
            });

            var unit = 2;
            for (var i = 0; i < scenes.length - 1; i++) {
                var t = i * unit;
                var next = sceneData[i + 1];
                tl.to(scenes[i], {
                    scale: 1.3, z: 260, filter: 'blur(13px)', autoAlpha: 0,
                    duration: 1, ease: 'power1.inOut',
                }, t)
                    .fromTo(scenes[i + 1],
                        { autoAlpha: 0, scale: 0.84, z: -360, filter: 'blur(12px)' },
                        {
                            autoAlpha: 1, scale: 1, z: 0, filter: 'blur(0px)',
                            duration: 1, ease: 'power1.inOut',
                        }, t + 0.4)
                    // Content settle — driven by the SAME timeline position as
                    // the scene crossfade above, so it's scrubbed in lockstep
                    // with scroll rather than autoplaying on its own clock.
                    .fromTo(next.revealEls,
                        { opacity: 0, y: 16, filter: 'blur(6px)' },
                        { opacity: 1, y: 0, filter: 'blur(0px)', duration: 1, ease: 'power1.inOut', stagger: 0.06 },
                        t + 0.4);
                if (next.img) {
                    tl.fromTo(next.img, { filter: 'blur(8px)' }, { filter: 'blur(0px)', duration: 1, ease: 'power1.inOut' }, t + 0.4);
                }
                if (next.borderRect) {
                    tl.fromTo(next.borderRect, { strokeDashoffset: 100 }, { strokeDashoffset: 0, duration: 1, ease: 'power1.inOut' }, t + 0.4);
                }
            }

            // Scene 0 has no crossfade to piggyback on (nothing scrolls it
            // into view — it's just there from the start), so its reveal
            // plays once on its own short timeline instead. It's already
            // hidden behind the opening overlay for the first few seconds,
            // so there's no visible pop.
            var first = sceneData[0];
            gsap.timeline()
                .fromTo(first.revealEls,
                    { opacity: 0, y: 16, filter: 'blur(6px)' },
                    { opacity: 1, y: 0, filter: 'blur(0px)', duration: 1, ease: 'power1.inOut', stagger: 0.06 }, 0)
                .fromTo(first.borderRect,
                    { strokeDashoffset: 100 }, { strokeDashoffset: 0, duration: 1, ease: 'power1.inOut' }, 0);
            playSweep(first);
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

        // ── Cursor tilt + glow + dynamic shadow on frames, and a subtler
        // matching tilt on the glass info panel — desktop/pointer:fine only.
        // Listeners are attached to every frame/panel up front, but only the
        // currently-active scene can actually receive the events at all
        // (see .cine-scene.is-active in the CSS) — inactive ones sit inert.
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
                    // Shadow leans away from the cursor, like a light source
                    // following it — reads as the card physically responding.
                    frame.style.setProperty('--shadow-x', ((px - 0.5) * -30).toFixed(1) + 'px');
                    frame.style.setProperty('--shadow-y', (40 + (py - 0.5) * -20).toFixed(1) + 'px');
                });
                frame.addEventListener('mouseleave', function () {
                    rotX(0);
                    rotY(0);
                    frame.style.setProperty('--shadow-x', '0px');
                    frame.style.setProperty('--shadow-y', '40px');
                });
            });

            root.querySelectorAll('.cine-info').forEach(function (panel) {
                var rotX = gsap.quickTo(panel, 'rotationX', { duration: 0.6, ease: 'power3.out' });
                var rotY = gsap.quickTo(panel, 'rotationY', { duration: 0.6, ease: 'power3.out' });

                panel.addEventListener('mousemove', function (e) {
                    var r = panel.getBoundingClientRect();
                    var px = (e.clientX - r.left) / r.width;
                    var py = (e.clientY - r.top) / r.height;
                    // Noticeably subtler than the image's own tilt (±4° vs
                    // ±10°) — the panel is glass chrome, not the focal point.
                    rotY((px - 0.5) * 4);
                    rotX(-(py - 0.5) * 4);
                    panel.style.setProperty('--mx', (px * 100) + '%');
                    panel.style.setProperty('--my', (py * 100) + '%');
                });
                panel.addEventListener('mouseleave', function () {
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
