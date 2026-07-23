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

    // ── Ambient galaxy atmosphere ──
    // Populates the starfield + floating-dust layers behind the
    // intro/pinned gallery (see #cine-atmosphere in cinematic-gallery.css —
    // the nebula/rays there are pure CSS, these two need JS). Independent
    // of initCineOpening/initCinematicGallery below — safe to fail silently
    // if #cine-atmosphere isn't on the page.
    function initCineAtmosphere() {
        var starHost = document.getElementById('cine-atmo-starfield');
        var dustHost = document.getElementById('cine-atmo-dust');
        if (!starHost && !dustHost) return;
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        if (typeof gsap === 'undefined') { return setTimeout(initCineAtmosphere, 100); }

        var isMobile = window.matchMedia('(max-width: 767px)').matches;

        // Stars — genuinely random positions/sizes/brightness (Math.random,
        // not a CSS tiled background), which is what actually reads as a
        // starfield instead of a visible lattice. Size/opacity vary
        // per-star to fake depth (bigger+brighter = "nearer") without
        // needing separate near/far layers. Only a minority twinkle, each
        // on its own random timing, so it never reads as one grid pulsing
        // in sync.
        if (starHost) {
            var starCount = isMobile ? 55 : 120;
            for (var s = 0; s < starCount; s++) {
                var star = document.createElement('div');
                star.className = 'cine-atmo-star';
                var starSize = 0.8 + Math.random() * 1.7;
                star.style.width = starSize + 'px';
                star.style.height = starSize + 'px';
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                var baseOpacity = 0.22 + Math.random() * 0.35;
                star.style.opacity = baseOpacity;
                starHost.appendChild(star);

                if (Math.random() < 0.35) {
                    gsap.to(star, {
                        opacity: Math.min(1, baseOpacity + 0.35 + Math.random() * 0.3),
                        duration: 1.5 + Math.random() * 3, delay: Math.random() * 6,
                        ease: 'sine.inOut', repeat: -1, yoyo: true,
                    });
                }
            }
        }

        if (!dustHost) return;

        // Opacity range roughly tripled from the first pass (0.12–0.3 base /
        // 0.3–0.5 twinkle) — that version was correct in concept but too
        // faint to register as a starfield at all against #0B0F17, the same
        // lesson as the CSS layers above.
        var count = isMobile ? 18 : 34;
        for (var i = 0; i < count; i++) {
            var el = document.createElement('div');
            el.className = 'hero-particle';
            var size = 2 + Math.random() * 3;
            el.style.width = size + 'px';
            el.style.height = size + 'px';
            el.style.left = Math.random() * 100 + '%';
            el.style.top = Math.random() * 100 + '%';
            dustHost.appendChild(el);

            gsap.set(el, { opacity: 0.35 + Math.random() * 0.25 });
            // Drift duration cut roughly in half and travel distance
            // increased — same "too slow/small to register as motion"
            // fix applied to the CSS nebula/stars/rays above.
            gsap.to(el, {
                x: (Math.random() - 0.5) * 110, y: -60 - Math.random() * 90,
                duration: 9 + Math.random() * 10, delay: Math.random() * 6,
                ease: 'sine.inOut', repeat: -1, yoyo: true,
            });
            gsap.to(el, {
                opacity: 0.6 + Math.random() * 0.3,
                duration: 2 + Math.random() * 3, delay: Math.random() * 4,
                ease: 'sine.inOut', repeat: -1, yoyo: true,
            });
        }
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
        var progressFill = root.querySelector('.cine-progress-fill');
        var trails = Array.prototype.slice.call(root.querySelectorAll('.cine-trail'));
        var trailSparks = Array.prototype.slice.call(root.querySelectorAll('.cine-trail-spark'));

        // Five named entrance choreographies (data-preset in the markup,
        // index % 5) — each one animates a DIFFERENT element/property
        // combination on purpose. That's not just variety for its own sake:
        // a preset's ongoing ambient motion (startAmbient below) never
        // touches the same element+property its own entrance tween just
        // animated, so the two can never fight over who owns a value once
        // scrubbing settles and the ambient loop takes over.
        var PRESETS = ['A', 'B', 'C', 'D', 'E'];

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
                info: scene.querySelector('.cine-info'),
                glow: scene.querySelector('.cine-frame-glow'),
                sweep: scene.querySelector('.cine-frame-sweep'),
                borderRect: scene.querySelector('.cine-frame-border rect'),
                ghost1: scene.querySelector('.cine-frame-ghost-1'),
                ghost2: scene.querySelector('.cine-frame-ghost-2'),
                revealEls: Array.prototype.slice.call(scene.querySelectorAll('.cine-info [data-reveal]')),
                preset: scene.getAttribute('data-preset') || PRESETS[i % PRESETS.length],
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

            // Sets a scene's inner content to whatever hidden/pre-entrance
            // state its preset's own reveal tween below animates FROM — so
            // there's no visible jump the moment GSAP takes over. Every
            // preset always includes the baseline text stagger; the switch
            // only adds the preset-specific extra(s) on top of it.
            function setPresetInitial(s) {
                gsap.set(s.revealEls, { opacity: 0, y: 16, filter: 'blur(6px)' });
                switch (s.preset) {
                    case 'A':
                        if (s.img) gsap.set(s.img, { filter: 'blur(4px)' });
                        break;
                    case 'B':
                        if (s.frame) gsap.set(s.frame, { scale: 0.72 });
                        if (s.img) gsap.set(s.img, { filter: 'blur(16px)' });
                        break;
                    case 'C':
                        if (s.info) gsap.set(s.info, { x: 60 });
                        if (s.frame) gsap.set(s.frame, { rotationY: -16 });
                        if (s.img) gsap.set(s.img, { filter: 'blur(6px)' });
                        break;
                    case 'D':
                        if (s.wrap) gsap.set(s.wrap, { y: 14 });
                        if (s.img) gsap.set(s.img, { filter: 'blur(6px)' });
                        break;
                    case 'E':
                        if (s.frame) gsap.set(s.frame, { scale: 0.92 });
                        if (s.img) gsap.set(s.img, { filter: 'blur(4px)' });
                        break;
                }
            }

            // Non-first scenes' inner content starts hidden to match their
            // parent scene's own hidden state — revealed together with it in
            // the crossfade loop below, never independently, so there's only
            // ever one clock driving both (no double-fade mismatch).
            sceneData.slice(1).forEach(setPresetInitial);

            // Adds a scene's preset-specific reveal tweens to `timeline` at
            // position `pos` — used both by the scrubbed crossfade loop
            // (below) and scene 0's own one-shot timeline (further below),
            // so there's exactly one definition of what each preset does.
            var dur = 1, ease = 'power1.inOut';
            function addPresetReveal(timeline, s, pos) {
                timeline.fromTo(s.revealEls,
                    { opacity: 0, y: 16, filter: 'blur(6px)' },
                    { opacity: 1, y: 0, filter: 'blur(0px)', duration: dur, ease: ease, stagger: 0.06 },
                    pos);

                switch (s.preset) {
                    case 'A': // Gentle float + subtle rotation — the float
                        // itself is the ONGOING ambient loop (startAmbient);
                        // entrance here stays to blur-resolve only, so it
                        // never competes with that loop over .wrap's transform.
                        if (s.img) timeline.fromTo(s.img, { filter: 'blur(4px)' }, { filter: 'blur(0px)', duration: dur, ease: ease }, pos);
                        break;

                    case 'B': // Camera zoom + blur-to-focus reveal
                        if (s.frame) timeline.fromTo(s.frame, { scale: 0.72 }, { scale: 1, duration: dur, ease: ease }, pos);
                        if (s.img) timeline.fromTo(s.img, { filter: 'blur(16px)' }, { filter: 'blur(0px)', duration: dur, ease: ease }, pos);
                        break;

                    case 'C': // Glass panel slides in while the image tilts
                        if (s.info) timeline.fromTo(s.info, { x: 60 }, { x: 0, duration: dur, ease: ease }, pos);
                        if (s.frame) timeline.fromTo(s.frame, { rotationY: -16 }, { rotationY: 0, duration: dur, ease: ease }, pos);
                        if (s.img) timeline.fromTo(s.img, { filter: 'blur(6px)' }, { filter: 'blur(0px)', duration: dur, ease: ease }, pos);
                        break;

                    case 'D': // Light sweep with layered parallax — the wrap's
                        // one-time arrival offset here is a DIFFERENT element/
                        // property than the ongoing ambient drift (which moves
                        // .glow, not .wrap), so the two never collide either.
                        if (s.wrap) timeline.fromTo(s.wrap, { y: 14 }, { y: 0, duration: dur, ease: ease }, pos);
                        if (s.img) timeline.fromTo(s.img, { filter: 'blur(6px)' }, { filter: 'blur(0px)', duration: dur, ease: ease }, pos);
                        if (s.borderRect) timeline.fromTo(s.borderRect, { strokeDashoffset: 100 }, { strokeDashoffset: 0, duration: dur, ease: ease }, pos);
                        break;

                    case 'E': // Stacked cards separating into depth
                        if (s.ghost1) timeline.fromTo(s.ghost1,
                            { x: 0, y: 0, rotation: 0, opacity: 0 },
                            { x: -18, y: 14, rotation: -4, opacity: 1, duration: dur, ease: ease }, pos);
                        if (s.ghost2) timeline.fromTo(s.ghost2,
                            { x: 0, y: 0, rotation: 0, opacity: 0 },
                            { x: 22, y: 20, rotation: 5, opacity: 1, duration: dur, ease: ease }, pos);
                        if (s.frame) timeline.fromTo(s.frame, { scale: 0.92 }, { scale: 1, duration: dur, ease: ease }, pos);
                        if (s.img) timeline.fromTo(s.img, { filter: 'blur(4px)' }, { filter: 'blur(0px)', duration: dur, ease: ease }, pos);
                        break;
                }
            }

            var currentActive = 0;
            var activatedOnce = { 0: true };

            // GSAP can't target a ::before pseudo-element directly, so the
            // one-shot sweep pass is driven by toggling a class that
            // triggers a plain CSS @keyframes animation instead (see
            // .cine-frame-sweep.is-sweeping in cinematic-gallery.css).
            // Preset D only ("light sweep with layered parallax").
            function playSweep(s) {
                if (s.preset !== 'D' || s.sweepPlayed || !s.sweep) return;
                s.sweepPlayed = true;
                gsap.delayedCall(s.variant.sweepDelay, function () {
                    s.sweep.classList.add('is-sweeping');
                    s.sweep.addEventListener('animationend', function () {
                        s.sweep.classList.remove('is-sweeping');
                    }, { once: true });
                });
            }

            // Ongoing (while-active) motion, also preset-specific — see the
            // note on PRESETS above for why each case only ever touches
            // elements/properties its own entrance tween (addPresetReveal)
            // left alone.
            function startAmbient(s) {
                if (s.ambientTweens.length) return;
                switch (s.preset) {
                    case 'A':
                        if (s.wrap) s.ambientTweens.push(gsap.to(s.wrap, {
                            y: '+=' + s.variant.floatAmp, rotation: '+=2.5',
                            duration: s.variant.floatDur, ease: 'sine.inOut',
                            repeat: -1, yoyo: true,
                        }));
                        pulseGlow(s);
                        break;
                    case 'C':
                        pulseGlow(s);
                        break;
                    case 'D':
                        if (s.glow) s.ambientTweens.push(gsap.to(s.glow, {
                            y: '+=10',
                            duration: s.variant.floatDur * 1.4, ease: 'sine.inOut',
                            repeat: -1, yoyo: true,
                        }));
                        pulseGlow(s);
                        break;
                    // B relies on the existing, separate Ken Burns CSS
                    // creep already applied sitewide to every .cine-frame
                    // img — an ongoing subtle zoom is literally its theme,
                    // for free, with nothing extra to start/stop here.
                    // E stays deliberately still — a settled composition,
                    // not a moving one, is the point of "stacked cards".
                    default:
                        break;
                }
                if (s.glow) gsap.to(s.glow, { opacity: 1, duration: 0.6, ease: 'power2.out' });
            }

            function pulseGlow(s) {
                if (s.glow) s.glow.classList.add('cine-glow-pulse');
            }

            function stopAmbient(s) {
                s.ambientTweens.forEach(function (tw) { tw.kill(); });
                s.ambientTweens = [];
                if (s.wrap) gsap.set(s.wrap, { y: 0, rotation: 0 });
                if (s.glow) {
                    gsap.set(s.glow, { y: 0 });
                    s.glow.classList.remove('cine-glow-pulse');
                    gsap.to(s.glow, { opacity: 0, duration: 0.4, ease: 'power2.out' });
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

            // Shooting-star trail for transition i (connecting scene i to
            // scene i+1) — the line draws in then fades out, and the spark
            // riding along it "travels" the path twice (repeat:1), all as
            // plain tweens INSIDE the single scrubbed `tl` below, same as
            // everything else in this timeline. Earlier version drove the
            // spark with an independent gsap.to(repeat:-1), started/stopped
            // via tl.call() — reliable scrolling down, but scrubbing back up
            // (especially fast) could skip the "stop" callback, leaving that
            // spark instance frozen mid-path forever instead of resetting to
            // hidden. Keeping it entirely inside `tl` means there's no
            // second tween lifecycle to leak — the single scrub timeline
            // owns and correctly interpolates it in both directions, exactly
            // like every other effect here. A plain named function (not an
            // inline closure inside the `for` loop below) so `i` is a real
            // per-call parameter, not a shared loop variable.
            function addTrail(i, t) {
                var trail = trails[i];
                if (!trail) return;
                // Real geometric length of this specific line (set inline
                // in Blade — see the comment there on why pathLength="100"
                // wasn't reliable enough on <line> to use as a shortcut).
                var len = parseFloat(trail.getAttribute('data-length')) || 100;
                tl.fromTo(trail,
                    { strokeDashoffset: len, opacity: 0 },
                    { strokeDashoffset: 0, opacity: 0.7, duration: 0.6, ease: 'power1.out' }, t + 0.4)
                    .to(trail, { opacity: 0, duration: 0.5, ease: 'power1.in' }, t + 1.0);

                var spark = trailSparks[i];
                if (!spark) return;
                var sparkLen = parseFloat(spark.getAttribute('data-length')) || len;
                tl.fromTo(spark,
                    { strokeDashoffset: sparkLen, opacity: 0 },
                    { opacity: 1, duration: 0.15, ease: 'power1.out' }, t + 0.4)
                    .to(spark, { strokeDashoffset: 0, duration: 0.45, ease: 'none', repeat: 1 }, t + 0.4)
                    .to(spark, { opacity: 0, duration: 0.2, ease: 'power1.in' }, t + 1.3);
            }

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
                        // Constellation line grows to the active star as the
                        // camera moves through the gallery.
                        if (progressFill) progressFill.style.height = (self.progress * 100) + '%';
                        onSceneActivate(active);
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
                // Content settle — driven by the SAME timeline position as
                // the scene crossfade above, so it's scrubbed in lockstep
                // with scroll rather than autoplaying on its own clock.
                addPresetReveal(tl, sceneData[i + 1], t + 0.4);

                addTrail(i, t);
            }

            // Scene 0 has no crossfade to piggyback on (nothing scrolls it
            // into view — it's just there from the start), so its reveal
            // plays once on its own short timeline instead. It's already
            // hidden behind the opening overlay for the first few seconds,
            // so there's no visible pop.
            addPresetReveal(gsap.timeline(), sceneData[0], 0);
            playSweep(sceneData[0]);
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
        initCineAtmosphere();
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
