<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We'll be right back – VisionBridge Solutions</title>
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
        a.btn {
            display: inline-block;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.7rem 1.4rem;
            border-radius: 0.6rem;
            transition: opacity 0.15s ease;
        }
        a.btn:hover { opacity: 0.85; }
        .btn-gold { background: #C9A84C; color: #111D33; }
        .btn-ghost { border: 1px solid rgba(255,255,255,0.25); color: #fff; }
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <p class="eyebrow">VisionBridge Solutions</p>
        <h1>We'll be right back</h1>
        <p>We're doing a bit of maintenance to keep things running smoothly. Please check back in a few minutes — your data is safe and nothing is lost.</p>
        <div class="actions">
            <a class="btn btn-gold" href="{{ url()->current() }}">Try Again</a>
            <a class="btn btn-ghost" href="{{ url('/') }}">Go to Homepage</a>
        </div>
        <p class="support">
            Need help right away? Email
            <a href="mailto:{{ config('mail.admin_address', 'support@visionbridgesolutions.com') }}">{{ config('mail.admin_address', 'support@visionbridgesolutions.com') }}</a>
        </p>
    </div>
</body>
</html>
