<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found — VisionBridge Solutions</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'><rect width='20' height='20' rx='3' fill='%23C9A84C'/><path d='M10 2L2 7v11h5v-6h6v6h5V7L10 2z' fill='%23111D33'/></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <style>
        :root { --navy:#111D33; --gold:#C9A84C; --teal:#2A9D8F; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(ellipse 90% 70% at 50% 45%, #132038 0%, #0b1525 48%, #07101c 100%);
            font-family: 'Inter', sans-serif;
            color: #fff;
            padding: 24px;
        }
        .card { max-width: 480px; text-align: center; }
        .logo {
            display: inline-flex; align-items: center; justify-content: center;
            width: 52px; height: 52px; border-radius: 12px;
            background: var(--gold); margin-bottom: 28px;
        }
        .logo svg { width: 28px; height: 28px; color: var(--navy); }
        .code {
            font-family: 'Playfair Display', serif;
            font-size: clamp(3.5rem, 10vw, 5.5rem);
            font-weight: 800;
            line-height: 1;
            margin: 0 0 12px;
            background: linear-gradient(100deg, #C9A84C 0%, #FFF2A8 38%, #E8C96A 52%, #C9A84C 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        h1 { font-size: 1.4rem; font-weight: 700; margin: 0 0 14px; }
        p.sub { color: rgba(255,255,255,0.55); font-size: 0.95rem; line-height: 1.7; margin: 0 0 32px; }
        .actions { display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; }
        .btn-gold {
            background: var(--gold); color: var(--navy); font-weight: 700; font-size: 0.92rem;
            padding: 12px 26px; border-radius: 10px; text-decoration: none;
            transition: background 0.2s, transform 0.2s;
        }
        .btn-gold:hover { background: #DFC06A; transform: translateY(-2px); }
        .btn-outline {
            border: 1.5px solid rgba(255,255,255,0.25); color: rgba(255,255,255,0.85); font-weight: 600; font-size: 0.92rem;
            padding: 12px 26px; border-radius: 10px; text-decoration: none;
            transition: border-color 0.2s, background 0.2s;
        }
        .btn-outline:hover { border-color: rgba(255,255,255,0.5); background: rgba(255,255,255,0.06); }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <svg fill="currentColor" viewBox="0 0 20 20"><path d="M10 2L2 7v11h5v-6h6v6h5V7L10 2z"/></svg>
        </div>
        <p class="code">404</p>
        <h1>This page doesn't exist.</h1>
        <p class="sub">
            The page you're looking for may have been moved or removed.<br>
            If you think this is a mistake, please contact
            <a href="mailto:{{ config('mail.admin_address') }}" style="color:#C9A84C;">{{ config('mail.admin_address') }}</a>.
        </p>
        <div class="actions">
            <a href="{{ route('home') }}" class="btn-gold">Back to Home</a>
            <a href="mailto:{{ config('mail.admin_address') }}" class="btn-outline">Contact Support</a>
        </div>
    </div>
</body>
</html>
