<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Restricted – VisionBridge Solutions</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('image/logo/vbs-logo-v3.jpeg') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: linear-gradient(135deg, #111D33 0%, #1B2A4A 100%);
            color: #fff;
            text-align: center;
        }
        .card {
            max-width: 30rem;
            width: 100%;
        }
        .icon {
            width: 4.5rem;
            height: 4.5rem;
            margin: 0 auto 1.75rem;
            border-radius: 9999px;
            background: rgba(201, 168, 76, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon svg { width: 2.25rem; height: 2.25rem; color: #DFC06A; }
        .eyebrow {
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-size: 0.7rem;
            font-weight: 700;
            color: #DFC06A;
            margin-bottom: 0.75rem;
        }
        h1 {
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        .actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        a.btn, button.btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            font: inherit;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.7rem 1.4rem;
            border-radius: 0.6rem;
            border: none;
            cursor: pointer;
            transition: opacity 0.15s ease;
        }
        a.btn:hover, button.btn:hover { opacity: 0.85; }
        .btn-gold { background: #C9A84C; color: #111D33; }
        .btn-ghost { border: 1px solid rgba(255,255,255,0.25); color: #fff; background: transparent; }
        .support {
            margin-top: 2rem;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.45);
        }
        .support a { color: #DFC06A; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v2"/>
            </svg>
        </div>
        <p class="eyebrow">403 · VisionBridge Solutions</p>
        <h1>Access Restricted</h1>
        <p>{{ $exception->getMessage() ?: "You don't have access to this section. Ask a super admin to grant it." }}</p>
        <div class="actions">
            <button type="button" class="btn btn-gold" onclick="if (window.history.length > 1) { history.back(); } else { window.location = '{{ auth()->check() ? \App\Support\AdminPermissions::adminLandingRoute(auth()->user()) : url('/') }}'; }">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Go Back
            </button>
            <a class="btn btn-ghost" href="{{ auth()->check() ? \App\Support\AdminPermissions::adminLandingRoute(auth()->user()) : url('/') }}">Go to Dashboard</a>
        </div>
        <p class="support">
            Need this section unlocked? Email
            <a href="mailto:{{ config('mail.admin_address', 'support@visionbridgesolutions.com') }}">{{ config('mail.admin_address', 'support@visionbridgesolutions.com') }}</a>
        </p>
    </div>
</body>
</html>
