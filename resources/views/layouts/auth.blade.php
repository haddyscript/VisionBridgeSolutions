<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VisionBridge Solutions')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">

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
                        sans:    ['Inter', 'sans-serif'],
                        display: ['"Playfair Display"', 'serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-navy-dark min-h-screen flex items-center justify-center px-4" style="background:#111D33;">

    <div class="w-full max-w-md">
        <div class="flex items-center justify-center gap-2.5 mb-8">
            <div class="w-9 h-9 bg-gold rounded-md flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-navy" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2L2 7v11h5v-6h6v6h5V7L10 2z"/>
                </svg>
            </div>
            <span class="text-white font-bold text-xl leading-tight">VisionBridge <span class="text-gold">Solutions</span></span>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            @yield('content')
        </div>

        <p class="text-center text-white/40 text-xs mt-6">&copy; {{ date('Y') }} VisionBridge Solutions. All rights reserved.</p>
    </div>

</body>
</html>
