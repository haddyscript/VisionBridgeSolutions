<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VisionBridge Solutions')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('image/logo/vbs-logo-v3.jpeg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;800;900&family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy:  { DEFAULT: '#1B2A4A', dark: '#111D33', light: '#243762' },
                        gold:  { DEFAULT: '#C9A84C', light: '#DFC06A', dark: '#A8872E' },
                        teal:  { DEFAULT: '#2A9D8F', light: '#3DBFB0', dark: '#1E7268' },
                    },
                    fontFamily: {
                        sans:    ['"Chakra Petch"', '"Chakra Petch Placeholder"', 'sans-serif'],
                        display: ['Orbitron', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    {{-- Custom "signal lock" cursor — same lag-stretch dot/ring technique
         used on the homepage and Contact page (see home.blade.php /
         contact.blade.php). Desktop/fine-pointer only; native cursor stays
         untouched until the script near the bottom of this file confirms
         it can actually run. --}}
    <style>
        #auth-cursor-dot, #auth-cursor-ring {
            position: fixed;
            top: 0; left: 0;
            pointer-events: none;
            z-index: 200;
            opacity: 0;
            transform: translate(-50%, -50%);
        }
        #auth-cursor-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #C9A84C;
            box-shadow: 0 0 10px rgba(201,168,76,.85);
        }
        /* A fixed large-px radius (not 50%) so this same rule reads as a
           circle at the default square size, and as a pill/card outline
           once the script below grows/morphs it — same technique used
           elsewhere on the site. */
        #auth-cursor-ring {
            width: 46px; height: 46px;
            border-radius: 999px;
            border: 1.5px solid rgba(201,168,76,.55);
            transition: border-color .3s ease, background-color .3s ease;
        }
        #auth-cursor-dot.is-visible, #auth-cursor-ring.is-visible { opacity: 1; }
        #auth-cursor-ring.is-hovering {
            background: rgba(201,168,76,.12);
            border-color: rgba(201,168,76,.85);
        }
        html.has-auth-cursor, html.has-auth-cursor a, html.has-auth-cursor button,
        html.has-auth-cursor input, html.has-auth-cursor label {
            cursor: none;
        }
        @media (hover: none), (pointer: coarse) {
            #auth-cursor-dot, #auth-cursor-ring { display: none; }
        }

        /* ─── Text "zoom" under the cursor — headings, field labels, and
             plain links (not the logo link, which wraps an image) get the
             same slow/enlarged treatment used across the rest of the site. ─── */
        h1.font-display, h2.font-display, label, a:not(:has(img)) {
            display: inline-block;
            transition: transform .65s cubic-bezier(.16,1,.3,1);
            transform-origin: left center;
        }
        h1.font-display:hover, h2.font-display:hover, label:hover, a:not(:has(img)):hover {
            transform: scale(1.15);
        }
        @media (prefers-reduced-motion: reduce) {
            #auth-cursor-dot, #auth-cursor-ring { display: none; }
            h1.font-display, h2.font-display, label, a:not(:has(img)) { transition: none; }
        }
    </style>
</head>
<body class="font-sans antialiased min-h-screen bg-white">

    <div id="auth-cursor-dot" aria-hidden="true"></div>
    <div id="auth-cursor-ring" aria-hidden="true"></div>

    <div class="min-h-screen flex flex-col lg:flex-row lg:relative">

        {{-- Illustration panel (slanted on desktop) --}}
        <div class="hidden lg:flex lg:absolute lg:inset-y-0 lg:left-0 lg:w-[58%] relative overflow-hidden items-center p-12 pr-24"
             style="background-image:url('{{ asset('image/Landing_Page_Development.jpeg') }}'); background-size:cover; background-position:center; clip-path:polygon(0 0, 100% 0, 78% 100%, 0 100%);">
            <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(17,29,51,0.88),rgba(27,42,74,0.78));"></div>
            <div class="absolute bottom-0 left-1/4 w-3/4 h-28" style="background-image:radial-gradient(circle,rgba(255,255,255,0.5) 1.5px,transparent 1.5px);background-size:14px 14px;"></div>

            <div class="relative max-w-sm pl-4">
                <div class="flex items-center mb-10">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('image/logo/vbs-logo-v3.jpeg') }}" alt="VisionBridge Solutions" class="h-28 w-auto object-contain rounded-md shadow-lg">
                    </a>
                </div>

                <h2 class="font-display text-3xl font-extrabold text-white mb-3" style="text-shadow:0 1px 3px rgba(0,0,0,0.35);">Your project, all in one place</h2>
                <p class="text-white text-base font-medium leading-relaxed mb-6" style="text-shadow:0 1px 3px rgba(0,0,0,0.35);">
                    Upload files, track progress, and manage billing for your website project &mdash; every step of the way, from onboarding to launch.
                </p>

                <ul class="space-y-2.5">
                    @foreach (['Track milestones in real time', 'Secure file uploads & approvals', 'Pay invoices straight from your portal'] as $point)
                        <li class="flex items-center gap-2.5 text-base font-medium text-white/90">
                            <span class="w-4 h-4 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                                <svg class="w-2.5 h-2.5 text-teal-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Form panel --}}
        <div class="flex-1 flex items-center justify-center px-4 py-12 lg:ml-[58%]">
            <div class="w-full max-w-md">
                <div class="flex items-center justify-center mb-8 lg:hidden">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('image/logo/vbs-logo-v3.jpeg') }}" alt="VisionBridge Solutions" class="h-9 w-auto object-contain">
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
                    @yield('content')
                </div>

                <p class="text-center text-gray-600 text-sm font-medium mt-6">&copy; {{ date('Y') }} VisionBridge Solutions. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(button) {
            const input = button.parentElement.querySelector('input');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            button.querySelector('.eye-icon').classList.toggle('hidden', isHidden);
            button.querySelector('.eye-off-icon').classList.toggle('hidden', !isHidden);
        }

        function updatePasswordStrength(input, groupId) {
            const group = document.getElementById(groupId);
            const meter = group.querySelector('.password-strength-meter');
            const bars  = group.querySelectorAll('.strength-bar');
            const label = group.querySelector('.strength-label');
            const password = input.value;

            if (!password) {
                meter.classList.add('hidden');
                return;
            }
            meter.classList.remove('hidden');

            let score = 0;
            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;

            let tier, color, filledBars;
            if (score <= 2) {
                tier = 'Weak'; color = '#DC2626'; filledBars = 1;
            } else if (score <= 4) {
                tier = 'Strong'; color = '#C9A84C'; filledBars = 2;
            } else {
                tier = 'Very Strong'; color = '#2A9D8F'; filledBars = 3;
            }

            bars.forEach((bar, i) => {
                bar.style.backgroundColor = i < filledBars ? color : '#E5E7EB';
            });

            label.textContent = tier;
            label.style.color = color;
        }
    </script>

    {{-- Custom "signal lock" cursor — dot snaps to the pointer, the ring
         eases behind it and stretches with the lag distance, and morphs
         into a pill over plain links (same technique as the desktop
         full-screen menu's own nav-link morph, layouts/app.blade.php) or a
         rounded-card hug over inputs/submit buttons (same as
         contact.blade.php's form fields). Desktop/fine-pointer only. --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script>
    (function () {
        function initAuthCursor() {
            if (typeof gsap === 'undefined') { setTimeout(initAuthCursor, 80); return; }

            var dot = document.getElementById('auth-cursor-dot');
            var ring = document.getElementById('auth-cursor-ring');
            if (!dot || !ring) return;
            if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            var moveDotX = gsap.quickTo(dot, 'x', { duration: 0.05, ease: 'power3.out' });
            var moveDotY = gsap.quickTo(dot, 'y', { duration: 0.05, ease: 'power3.out' });

            var mouseX = 0, mouseY = 0, ringX = 0, ringY = 0;
            var ringReady = false, pressed = false, hovering = false, visible = false;
            // The element currently being "morphed" onto, if any — while
            // set, the ticker hands the ring's position over to the morph
            // tween entirely instead of fighting it with the lag-follow loop.
            var morphedEl = null;

            document.addEventListener('mousemove', function (e) {
                mouseX = e.clientX; mouseY = e.clientY;
                moveDotX(mouseX); moveDotY(mouseY);
                if (!ringReady) { ringX = mouseX; ringY = mouseY; ringReady = true; }
                if (!visible) {
                    visible = true;
                    document.documentElement.classList.add('has-auth-cursor');
                    dot.classList.add('is-visible');
                    ring.classList.add('is-visible');
                }
            });

            document.addEventListener('mouseleave', function () {
                visible = false;
                document.documentElement.classList.remove('has-auth-cursor');
                dot.classList.remove('is-visible');
                ring.classList.remove('is-visible');
            });

            gsap.ticker.add(function () {
                if (!visible || morphedEl) return;
                // Lower factor = more lag = smoother/slower catch-up; the
                // resulting lag distance is what drives the stretch below.
                ringX += (mouseX - ringX) * 0.1;
                ringY += (mouseY - ringY) * 0.1;
                var dist = Math.hypot(mouseX - ringX, mouseY - ringY);
                var stretch = pressed ? 0.8 : gsap.utils.clamp(1, 1.7, 1 + dist / 130);
                gsap.set(ring, { x: ringX, y: ringY, scale: hovering ? 1 : stretch });
            });

            function growRing(w, h) {
                gsap.to(ring, { width: w, height: h, duration: 0.35, ease: 'power3.out', overwrite: 'auto' });
            }

            // Morphs the ring to hug `el`'s own footprint (plus optional
            // padding/radius) and locks onto its center, instead of just
            // growing into a bigger circle around the raw mouse position.
            function morphTo(el, opts) {
                hovering = true;
                morphedEl = el;
                ring.classList.add('is-hovering');
                var r = el.getBoundingClientRect();
                var padX = opts.padX || 0, padY = opts.padY || 0;
                var tween = {
                    x: r.left + r.width / 2,
                    y: r.top + r.height / 2,
                    width: r.width + padX * 2,
                    height: r.height + padY * 2,
                    scale: 1,
                    duration: 0.45,
                    ease: 'power3.out',
                    overwrite: 'auto',
                };
                if (opts.borderRadius) tween.borderRadius = opts.borderRadius;
                gsap.to(ring, tween);
            }

            function unmorph() {
                hovering = false;
                morphedEl = null;
                ring.classList.remove('is-hovering');
                // Resume the lag-follow from wherever the mouse actually is
                // now, not from the target's center — avoids a visible jump.
                ringX = mouseX; ringY = mouseY;
                gsap.to(ring, {
                    width: 46, height: 46, borderRadius: 999,
                    duration: 0.3, ease: 'power2.out', overwrite: 'auto',
                    clearProps: 'borderRadius',
                });
            }

            // Plain text links (Forgot password? / Create one / Sign in /
            // Back to sign in) get the same oblong full-pill hug as the
            // desktop full-screen menu's own nav links — the ring's default
            // border-radius:999px already reads as one. The logo link (wraps
            // an <img>, no text to hug) is excluded.
            var pillMorphEls = Array.prototype.slice.call(document.querySelectorAll('a')).filter(function (a) {
                return !a.querySelector('img');
            });
            // Inputs + submit buttons get a gentler radius matching their
            // own rounded-lg shape, same as contact.blade.php's form fields.
            var fieldMorphEls = document.querySelectorAll('input[type="email"], input[type="password"], input[type="text"], button[type="submit"]');

            var morphedSet = new Set();
            pillMorphEls.forEach(function (el) {
                morphedSet.add(el);
                el.addEventListener('mouseenter', function () { morphTo(el, { padX: 10, padY: 6 }); });
                el.addEventListener('mouseleave', unmorph);
            });
            fieldMorphEls.forEach(function (el) {
                morphedSet.add(el);
                el.addEventListener('mouseenter', function () { morphTo(el, { padX: 4, padY: 4, borderRadius: 12 }); });
                el.addEventListener('mouseleave', unmorph);
            });

            // Everything else clickable (the "Remember me" checkbox, the
            // password-visibility eye-toggle buttons) keeps the original
            // simple circle-grow acquire.
            var interactiveEls = document.querySelectorAll('a, button, input');
            interactiveEls.forEach(function (el) {
                if (morphedSet.has(el)) return;
                el.addEventListener('mouseenter', function () { hovering = true; ring.classList.add('is-hovering'); growRing(68, 68); });
                el.addEventListener('mouseleave', function () { hovering = false; ring.classList.remove('is-hovering'); growRing(46, 46); });
            });

            document.addEventListener('mousedown', function () { pressed = true; });
            document.addEventListener('mouseup', function () { pressed = false; });
        }
        if (document.readyState !== 'loading') { initAuthCursor(); }
        else { window.addEventListener('DOMContentLoaded', initAuthCursor); }
    })();
    </script>
</body>
</html>
