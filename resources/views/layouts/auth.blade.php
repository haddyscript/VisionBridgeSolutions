<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VisionBridge Solutions')</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo-v2.png') }}">

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
<body class="font-sans antialiased min-h-screen bg-gray-50">

    <div class="min-h-screen flex flex-col lg:flex-row lg:relative">

        {{-- Illustration panel (slanted on desktop) --}}
        <div class="hidden lg:flex lg:absolute lg:inset-y-0 lg:left-0 lg:w-[58%] relative overflow-hidden items-center p-12 pr-24"
             style="background-image:url('{{ asset('image/Landing_Page_Development.jpeg') }}'); background-size:cover; background-position:center; clip-path:polygon(0 0, 100% 0, 78% 100%, 0 100%);">
            <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(17,29,51,0.78),rgba(27,42,74,0.55));"></div>
            <div class="absolute bottom-0 left-1/4 w-3/4 h-28" style="background-image:radial-gradient(circle,rgba(255,255,255,0.5) 1.5px,transparent 1.5px);background-size:14px 14px;"></div>

            <div class="relative max-w-sm pl-4">
                <div class="flex items-center gap-2.5 mb-10">
                    <div class="w-9 h-9 rounded-md overflow-hidden shrink-0">
                        <img src="{{ asset('image/logo-v2.png') }}" alt="VisionBridge Solutions" class="w-full h-full object-cover">
                    </div>
                    <span class="text-white font-bold text-lg leading-tight">VisionBridge <span class="text-gold">Solutions</span></span>
                </div>

                <h2 class="font-display text-2xl font-bold text-white mb-3">Your project, all in one place</h2>
                <p class="text-white/55 text-sm leading-relaxed mb-6">
                    Upload files, track progress, and manage billing for your website project &mdash; every step of the way, from onboarding to launch.
                </p>

                <ul class="space-y-2.5">
                    @foreach (['Track milestones in real time', 'Secure file uploads & approvals', 'Pay invoices straight from your portal'] as $point)
                        <li class="flex items-center gap-2.5 text-sm text-white/70">
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
                <div class="flex items-center justify-center gap-2.5 mb-8 lg:hidden">
                    <div class="w-9 h-9 rounded-md overflow-hidden shrink-0">
                        <img src="{{ asset('image/logo-v2.png') }}" alt="VisionBridge Solutions" class="w-full h-full object-cover">
                    </div>
                    <span class="text-navy font-bold text-xl leading-tight">VisionBridge <span class="text-gold-dark">Solutions</span></span>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
                    @yield('content')
                </div>

                <p class="text-center text-gray-400 text-xs mt-6">&copy; {{ date('Y') }} VisionBridge Solutions. All rights reserved.</p>
            </div>
        </div>
    </div>

</body>
</html>
