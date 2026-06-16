<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VisionBridge Solutions – Building Websites. Expanding Reach.')</title>
    <meta name="description" content="@yield('description', 'Custom websites designed to strengthen your brand, expand your reach, and protect your online presence.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CDN with custom config -->
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

    <style>
        html { scroll-behavior: smooth; }
        .nav-link { @apply text-white/80 hover:text-gold transition-colors duration-200 text-sm font-medium; }
        .btn-gold { @apply inline-block bg-gold hover:bg-gold-dark text-navy font-semibold px-7 py-3 rounded-lg transition-all duration-200 shadow hover:shadow-md; }
        .btn-outline { @apply inline-block border-2 border-white text-white hover:bg-white hover:text-navy font-semibold px-7 py-3 rounded-lg transition-all duration-200; }
        .section-title { @apply font-display text-3xl md:text-4xl font-bold text-navy leading-tight; }
        .section-subtitle { @apply text-gray-500 text-lg mt-3 max-w-2xl mx-auto; }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-white">

    <!-- Navigation -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-navy/95 backdrop-blur-sm shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="#hero" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gold rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-navy" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L2 7v11h5v-6h6v6h5V7L10 2z"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-lg leading-tight">VisionBridge<br><span class="text-gold text-xs font-medium tracking-widest uppercase">Solutions</span></span>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="#about" class="nav-link">About</a>
                    <a href="#services" class="nav-link">Services</a>
                    <a href="#plans" class="nav-link">Plans</a>
                    <a href="#portfolio" class="nav-link">Portfolio</a>
                    <a href="#contact" class="btn-gold text-sm py-2 px-5">Get Started</a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="menu-btn" class="md:hidden text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 flex flex-col gap-3">
                <a href="#about" class="nav-link py-1">About</a>
                <a href="#services" class="nav-link py-1">Services</a>
                <a href="#plans" class="nav-link py-1">Plans</a>
                <a href="#portfolio" class="nav-link py-1">Portfolio</a>
                <a href="#contact" class="btn-gold text-center mt-2">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-navy-dark text-white pt-16 pb-8" style="background-color:#111D33">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-12">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gold rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-navy" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2L2 7v11h5v-6h6v6h5V7L10 2z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-lg">VisionBridge <span class="text-gold">Solutions</span></span>
                    </div>
                    <p class="text-white/60 text-sm leading-relaxed">Building Websites. Expanding Reach.<br>Helping organizations establish a professional online presence.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-white/60">
                        <li><a href="#about" class="hover:text-gold transition-colors">About Us</a></li>
                        <li><a href="#services" class="hover:text-gold transition-colors">Services</a></li>
                        <li><a href="#plans" class="hover:text-gold transition-colors">Maintenance Plans</a></li>
                        <li><a href="#portfolio" class="hover:text-gold transition-colors">Portfolio</a></li>
                        <li><a href="#contact" class="hover:text-gold transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm text-white/60">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            info@visionbridgesolutions.com
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            (555) 000-0000
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-white/40">
                <p>&copy; {{ date('Y') }} VisionBridge Solutions. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-gold transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-gold transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('menu-btn').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Close mobile menu on link click
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.add('hidden');
            });
        });
    </script>

</body>
</html>
