<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt — {{ $payment->description }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { DEFAULT: '#1B2A4A', dark: '#111D33' },
                        gold: { DEFAULT: '#C9A84C', dark: '#A8872E' },
                        teal: { DEFAULT: '#2A9D8F', dark: '#1E7268' },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['"Playfair Display"', 'serif'],
                    },
                },
            },
        };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <style>
        @media print {
            #print-button-wrap { display: none; }
            body { background: #ffffff !important; }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen py-12 px-4">

    <div id="print-button-wrap" class="max-w-2xl mx-auto mb-4 flex justify-end">
        <button onclick="window.print()" class="inline-flex items-center gap-2 bg-navy hover:bg-navy-dark text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print / Save as PDF
        </button>
    </div>

    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-8 py-7" style="background:linear-gradient(135deg,#111D33,#1B2A4A);">
            <div class="flex items-center gap-2.5 mb-6">
                <div class="w-8 h-8 bg-gold rounded-md flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-navy" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2L2 7v11h5v-6h6v6h5V7L10 2z"/></svg>
                </div>
                <span class="text-white font-bold text-base">VisionBridge <span class="text-gold">Solutions</span></span>
            </div>
            <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-1">Payment Receipt</p>
            <h1 class="font-display text-2xl font-bold text-white">{{ $payment->description }}</h1>
        </div>

        <div class="px-8 py-7 space-y-5">
            <div class="flex items-center justify-between pb-5 border-b border-gray-100">
                <span class="text-sm text-gray-500">Amount Paid</span>
                <span class="font-display text-2xl font-bold text-navy">{{ $payment->formattedAmount() }} {{ strtoupper($payment->currency) }}</span>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Billed To</span>
                <span class="text-sm font-semibold text-navy">{{ $project->user->name }}</span>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Project</span>
                <span class="text-sm font-semibold text-navy">{{ $project->name }}</span>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Date Paid</span>
                <span class="text-sm font-semibold text-navy">{{ $payment->paid_at?->format('M j, Y \a\t g:ia') }}</span>
            </div>

            @if ($payment->stripe_payment_intent_id)
                <div class="flex items-center justify-between gap-4">
                    <span class="text-sm text-gray-500 shrink-0">Transaction ID</span>
                    <span class="text-sm font-mono text-navy truncate">{{ $payment->stripe_payment_intent_id }}</span>
                </div>
            @endif

            <div class="pt-5 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400">Thank you for your business.</p>
            </div>
        </div>
    </div>

</body>
</html>
