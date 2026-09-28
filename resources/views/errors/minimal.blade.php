{{--
    Shared branded shell for every error page. Named "minimal" on purpose:
    Laravel's own built-in error views (401, 402, 429, 503…) extend
    `errors::minimal` with title/code/message sections, so overriding it here
    gives those the same look automatically.

    Sections: title, code, message (Laravel's) + optional eyebrow, heading,
    description, illustration, extra, actions, links, help.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>@yield('title') — VisionBridge Solutions</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('image/logo/vbs-logo-v3.jpeg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #111D33;
            --navy-deep: #07101c;
            --gold: #C9A84C;
            --gold-light: #DFC06A;
            --teal: #2A9D8F;
            --text: rgba(255,255,255,0.92);
            --muted: rgba(255,255,255,0.62);
            --line: rgba(255,255,255,0.10);
        }
        * { box-sizing: border-box; }
        html, body { margin: 0; }
        body {
            min-height: 100vh;
            background: radial-gradient(ellipse 90% 70% at 50% 35%, #152443 0%, #0b1525 50%, var(--navy-deep) 100%);
            font-family: 'Inter', sans-serif;
            color: var(--text);
            overflow-x: hidden;
            position: relative;
        }

        /* Background: dot grid, drifting glows, pointer glow */
        .bg-grid {
            position: fixed; inset: 0; pointer-events: none;
            background-image: radial-gradient(rgba(255,255,255,0.07) 1px, transparent 1px);
            background-size: 28px 28px;
            mask-image: radial-gradient(ellipse 70% 60% at 50% 40%, #000 30%, transparent 80%);
            -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 40%, #000 30%, transparent 80%);
        }
        .glow { position: fixed; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: .55; }
        .glow-gold { width: 420px; height: 420px; background: rgba(201,168,76,0.22); top: -120px; right: -80px; animation: drift 14s ease-in-out infinite; }
        .glow-teal { width: 380px; height: 380px; background: rgba(42,157,143,0.18); bottom: -140px; left: -100px; animation: drift 17s ease-in-out infinite reverse; }
        .cursor-glow {
            position: fixed; width: 360px; height: 360px; border-radius: 50%; pointer-events: none;
            background: radial-gradient(circle, rgba(201,168,76,0.10) 0%, transparent 65%);
            transform: translate(-50%, -50%); left: 50%; top: 40%;
            transition: left .25s ease-out, top .25s ease-out;
        }
        @keyframes drift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%      { transform: translate(-40px, 30px) scale(1.08); }
        }

        /* Layout + staggered entrance */
        .wrap {
            position: relative; z-index: 1;
            max-width: 760px; margin: 0 auto;
            min-height: 100vh;
            padding: 48px 20px 40px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center;
        }
        .reveal { opacity: 0; transform: translateY(14px); animation: rise .7s cubic-bezier(.2,.7,.2,1) forwards; }
        .d1 { animation-delay: .05s; } .d2 { animation-delay: .15s; } .d3 { animation-delay: .25s; }
        .d4 { animation-delay: .35s; } .d5 { animation-delay: .45s; } .d6 { animation-delay: .55s; }
        @keyframes rise { to { opacity: 1; transform: none; } }

        .logo {
            display: inline-flex; border-radius: 14px; overflow: hidden;
            box-shadow: 0 0 0 1px rgba(201,168,76,0.35), 0 12px 32px -12px rgba(0,0,0,0.6);
            margin-bottom: 26px; text-decoration: none;
        }
        .logo img { height: 56px; width: auto; display: block; }

        .eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: .72rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase;
            color: var(--gold);
            padding: 7px 14px; border-radius: 999px;
            background: rgba(201,168,76,0.08); border: 1px solid rgba(201,168,76,0.25);
            margin-bottom: 18px;
        }
        .eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--gold); animation: ping 2.2s ease-out infinite; }
        @keyframes ping { 0% { box-shadow: 0 0 0 0 rgba(201,168,76,.6); } 100% { box-shadow: 0 0 0 8px rgba(201,168,76,0); } }

        /* Code + bridge illustration */
        .scene { position: relative; width: 100%; max-width: 560px; margin: 0 auto 8px; }
        .code {
            font-family: 'Playfair Display', serif;
            font-size: clamp(5rem, 18vw, 9rem);
            font-weight: 800; line-height: .9; letter-spacing: -.02em;
            margin: 0;
            background: linear-gradient(100deg, #A8872E 0%, #C9A84C 20%, #FFF2A8 42%, #E8C96A 55%, #C9A84C 80%, #A8872E 100%);
            background-size: 220% 100%;
            -webkit-background-clip: text; background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 6s ease-in-out infinite;
            filter: drop-shadow(0 10px 30px rgba(201,168,76,0.18));
        }
        @keyframes shimmer { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
        .bridge { display: block; width: 100%; height: auto; margin-top: -6px; }
        .bridge .piece { animation: fall 4.5s ease-in-out infinite; transform-box: fill-box; transform-origin: center; }
        .bridge .piece.p2 { animation-delay: .8s; }
        .bridge .piece.p3 { animation-delay: 1.6s; }
        @keyframes fall {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 1; }
            50%      { transform: translateY(8px) rotate(8deg); opacity: .75; }
        }
        .bridge .spark { animation: spark 3.2s ease-in-out infinite; }
        @keyframes spark { 0%, 100% { opacity: .25; } 50% { opacity: 1; } }
        .bridge .badge { animation: bob 4s ease-in-out infinite; }
        @keyframes bob { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }
        .bridge .spin { animation: spin 8s linear infinite; transform-box: fill-box; transform-origin: center; }
        @keyframes spin { to { transform: rotate(360deg); } }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.7rem, 4.5vw, 2.4rem);
            font-weight: 800; line-height: 1.2;
            color: #fff; margin: 18px 0 12px;
        }
        .sub { color: var(--muted); font-size: 1.05rem; line-height: 1.7; margin: 0 auto 18px; max-width: 520px; }

        .path {
            display: inline-flex; align-items: center; gap: 8px; max-width: 100%;
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: .82rem;
            color: rgba(255,255,255,0.75);
            padding: 8px 14px; border-radius: 10px;
            background: rgba(255,255,255,0.04); border: 1px solid var(--line);
            margin-bottom: 28px;
        }
        .path span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .path svg { flex-shrink: 0; color: #f87171; }

        /* Buttons */
        .actions { display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; margin: 10px 0 40px; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            font: 700 1rem 'Inter', sans-serif;
            padding: 14px 26px; border-radius: 12px; text-decoration: none; cursor: pointer;
            transition: transform .2s ease-out, box-shadow .2s ease-out, background .2s, border-color .2s;
        }
        .btn:focus-visible { outline: none; box-shadow: 0 0 0 3px rgba(201,168,76,0.55); }
        .btn:active { transform: scale(.97); }
        .btn-gold { background: var(--gold); color: var(--navy); border: 0; box-shadow: 0 10px 28px -10px rgba(201,168,76,0.65); }
        .btn-gold:hover { background: var(--gold-light); transform: translateY(-2px); box-shadow: 0 14px 34px -10px rgba(201,168,76,0.8); }
        .btn-ghost { background: rgba(255,255,255,0.03); color: var(--text); border: 1.5px solid rgba(255,255,255,0.22); }
        .btn-ghost:hover { border-color: rgba(201,168,76,0.6); background: rgba(201,168,76,0.06); transform: translateY(-2px); }
        .btn svg { width: 18px; height: 18px; }

        /* Quick-link cards */
        .links-title { font-size: .72rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: rgba(255,255,255,0.45); margin: 0 0 14px; }
        .links { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; width: 100%; max-width: 620px; }
        .link-card {
            display: flex; align-items: flex-start; gap: 12px; text-align: left;
            padding: 16px; border-radius: 14px; text-decoration: none; color: var(--text);
            background: linear-gradient(180deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02));
            border: 1px solid var(--line);
            backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
            transition: transform .2s ease-out, border-color .2s, background .2s;
        }
        .link-card:hover { transform: translateY(-3px); border-color: rgba(201,168,76,0.45); background: linear-gradient(180deg, rgba(201,168,76,0.08), rgba(255,255,255,0.02)); }
        .link-card:focus-visible { outline: none; box-shadow: 0 0 0 3px rgba(201,168,76,0.55); }
        .link-icon {
            flex-shrink: 0; width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(201,168,76,0.12); color: var(--gold);
        }
        .link-icon svg { width: 20px; height: 20px; }
        .link-card strong { display: block; font-size: .95rem; font-weight: 700; color: #fff; margin-bottom: 2px; }
        .link-card small { display: block; font-size: .82rem; line-height: 1.45; color: var(--muted); }
        .link-card .arrow { margin-left: auto; align-self: center; color: rgba(255,255,255,0.3); transition: transform .2s, color .2s; }
        .link-card:hover .arrow { transform: translateX(3px); color: var(--gold); }

        .help { margin-top: 32px; font-size: .9rem; color: var(--muted); }
        .help a { color: var(--gold); text-decoration: none; font-weight: 600; }
        .help a:hover { text-decoration: underline; }
        .copyright { margin-top: 14px; font-size: .75rem; color: rgba(255,255,255,0.3); }

        @media (max-width: 560px) {
            .wrap { padding-top: 32px; }
            .links { grid-template-columns: 1fr; }
            .btn { flex: 1 1 100%; justify-content: center; }
            .glow { display: none; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
            .reveal { opacity: 1; transform: none; }
        }
    </style>
</head>
<body>
    <div class="bg-grid" aria-hidden="true"></div>
    <div class="glow glow-gold" aria-hidden="true"></div>
    <div class="glow glow-teal" aria-hidden="true"></div>
    <div class="cursor-glow" id="cursor-glow" aria-hidden="true"></div>

    <main class="wrap">
        <a href="{{ route('home') }}" class="logo reveal d1" aria-label="VisionBridge Solutions home">
            <img src="{{ asset('image/logo/vbs-logo-v3.jpeg') }}" alt="VisionBridge Solutions">
        </a>

        <span class="eyebrow reveal d1">
            <span class="dot"></span>
            @hasSection('code')
                Error @yield('code') ·
            @endif
            @yield('eyebrow', $__env->yieldContent('title'))
        </span>

        <div class="scene reveal d2">
            @hasSection('code')
                <p class="code" aria-hidden="true">@yield('code')</p>
            @endif
            @hasSection('illustration')
                @yield('illustration')
            @else
                @include('errors.partials.bridge', ['broken' => false, 'icon' => null])
            @endif
        </div>

        <h1 class="reveal d3">@yield('heading', $__env->yieldContent('message'))</h1>

        @hasSection('description')
            <p class="sub reveal d3">@yield('description')</p>
        @endif

        @yield('extra')

        <div class="actions reveal d4">
            @hasSection('actions')
                @yield('actions')
            @else
                <a href="{{ route('home') }}" class="btn btn-gold">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8M5 10v10h5v-6h4v6h5V10"/></svg>
                    Back to Home
                </a>
                <button type="button" class="btn btn-ghost" data-go-back hidden>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7 7-7M3 12h18"/></svg>
                    Go Back
                </button>
            @endif
        </div>

        @yield('links')

        <p class="help reveal d6">
            @hasSection('help')
                @yield('help')
            @else
                Need a hand? Email
            @endif
            <a href="mailto:{{ config('mail.admin_address', 'support@visionbridgesolutions.com') }}">{{ config('mail.admin_address', 'support@visionbridgesolutions.com') }}</a>
        </p>
        <p class="copyright reveal d6">© {{ date('Y') }} VisionBridge Solutions</p>
    </main>

    <script>
        // [data-go-back] buttons only appear when there's a page on this site
        // to go back to (optionally falling back to data-fallback instead).
        (function () {
            var ref = document.referrer;
            var sameSite = false;
            try { sameSite = window.history.length > 1 && !!ref && new URL(ref).host === location.host; } catch (e) {}

            document.querySelectorAll('[data-go-back]').forEach(function (btn) {
                var fallback = btn.getAttribute('data-fallback');
                if (!sameSite && !fallback) return;
                btn.hidden = false;
                btn.addEventListener('click', function () {
                    if (sameSite) { history.back(); } else { location.href = fallback; }
                });
            });
        })();

        // Soft gold glow that follows the pointer (mouse devices only).
        (function () {
            var glow = document.getElementById('cursor-glow');
            if (!window.matchMedia('(pointer: fine)').matches || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                glow.remove();
                return;
            }
            window.addEventListener('pointermove', function (e) {
                glow.style.left = e.clientX + 'px';
                glow.style.top = e.clientY + 'px';
            });
        })();
    </script>
</body>
</html>
