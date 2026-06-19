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
<body class="font-sans antialiased min-h-screen bg-gray-50">

    <div class="min-h-screen flex flex-col lg:flex-row lg:relative">

        {{-- Illustration panel (slanted on desktop) --}}
        <div class="hidden lg:flex lg:absolute lg:inset-y-0 lg:left-0 lg:w-[58%] relative overflow-hidden items-center p-12 pr-24"
             style="background:linear-gradient(135deg,#243762,#1B2A4A); clip-path:polygon(0 0, 100% 0, 78% 100%, 0 100%);">
            <div class="absolute inset-0 opacity-20" style="background-image:radial-gradient(circle,rgba(255,255,255,0.3) 1px,transparent 1px);background-size:22px 22px;"></div>
            <div class="absolute -top-20 -left-10 w-64 h-64 rounded-full" style="background:radial-gradient(circle,rgba(201,168,76,0.16) 0%,transparent 70%);"></div>
            <div class="absolute bottom-10 left-20 w-48 h-48 rounded-full" style="background:radial-gradient(circle,rgba(42,157,143,0.14) 0%,transparent 70%);"></div>
            <div class="absolute bottom-0 left-0 w-3/4 h-28" style="background-image:radial-gradient(circle,rgba(255,255,255,0.5) 1.5px,transparent 1.5px);background-size:14px 14px;"></div>

            <div class="relative max-w-sm pl-4">
                <div class="flex items-center gap-2.5 mb-10">
                    <div class="w-9 h-9 bg-gold rounded-md flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-navy" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L2 7v11h5v-6h6v6h5V7L10 2z"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-lg leading-tight">VisionBridge <span class="text-gold">Solutions</span></span>
                </div>

                <div class="relative w-64 h-44 mb-10">
                    {{-- Floating accent badges --}}
                    <div class="absolute -top-3 -left-2 w-10 h-10 rounded-xl bg-teal/20 border border-teal/30 flex items-center justify-center shadow-lg -rotate-6">
                        <svg class="w-5 h-5 text-teal-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-3.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/></svg>
                    </div>
                    <div class="absolute -top-4 right-2 w-10 h-10 rounded-xl bg-gold/20 border border-gold/30 flex items-center justify-center shadow-lg rotate-6">
                        <svg class="w-5 h-5 text-gold-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>

                    {{-- Platform illustration --}}
                    <svg viewBox="0 0 320 220" class="w-full h-full drop-shadow-2xl">
                        <ellipse cx="160" cy="195" rx="120" ry="14" fill="#000000" opacity="0.18"/>
                        <rect x="50" y="148" width="220" height="36" rx="7" fill="#C9A84C"/>
                        <rect x="50" y="148" width="220" height="10" rx="5" fill="#DFC06A"/>
                        <circle cx="120" cy="118" r="23" fill="#3DBFB0"/>
                        <rect x="104" y="141" width="32" height="48" rx="9" fill="#3DBFB0"/>
                        <circle cx="183" cy="104" r="26" fill="#DFC06A"/>
                        <rect x="165" y="130" width="36" height="54" rx="10" fill="#DFC06A"/>
                        <circle cx="240" cy="120" r="21" fill="#ffffff" opacity="0.9"/>
                        <rect x="225" y="141" width="30" height="46" rx="9" fill="#ffffff" opacity="0.9"/>
                    </svg>
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

        {{-- Dot accent crossing the diagonal seam --}}
        <div class="hidden lg:block lg:absolute bottom-0 left-[40%] w-32 h-28 z-10" style="background-image:radial-gradient(circle,rgba(27,42,74,0.35) 1.5px,transparent 1.5px);background-size:14px 14px;"></div>

        {{-- Form panel --}}
        <div class="flex-1 flex items-center justify-center px-4 py-12 lg:ml-[58%]">
            <div class="w-full max-w-md">
                <div class="flex items-center justify-center gap-2.5 mb-8 lg:hidden">
                    <div class="w-9 h-9 bg-gold rounded-md flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-navy" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L2 7v11h5v-6h6v6h5V7L10 2z"/>
                        </svg>
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
