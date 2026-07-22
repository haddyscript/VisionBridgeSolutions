@extends('layouts.admin')

@section('title', 'Two-Factor Authentication – Admin')
@section('page-title', 'Two-Factor Authentication')

@section('content')

<div class="max-w-lg space-y-6">

    <a href="{{ route('admin.team.index') }}"
       class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Team
    </a>

    @if (session('status'))
        <div class="text-sm text-teal-dark dark:text-teal-light bg-teal/10 border border-teal/30 rounded-lg px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-lg px-4 py-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if ($recoveryCodes)
        <div class="bg-white dark:bg-navy rounded-xl border-2 border-gold/40 p-6">
            <h3 class="font-display text-base font-bold text-navy dark:text-white mb-1.5">Save your recovery codes</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Each code can be used once to sign in if you lose access to your authenticator app. Store them somewhere safe — they won't be shown again.
            </p>
            <div class="grid grid-cols-2 gap-2 font-mono text-sm bg-gray-50 dark:bg-navy-dark rounded-lg p-4 mb-4">
                @foreach ($recoveryCodes as $code)
                    <span class="text-navy dark:text-white">{{ $code }}</span>
                @endforeach
            </div>
            <a download="visionbridge-admin-recovery-codes.txt"
               href="data:text/plain;charset=utf-8,{{ urlencode(implode("\n", $recoveryCodes)) }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold text-gold-dark hover:underline">
                Download as text file
            </a>
        </div>
    @endif

    @if ($enabled)
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="w-2 h-2 rounded-full bg-teal"></span>
                <h3 class="font-display text-base font-bold text-navy dark:text-white">Two-factor authentication is enabled</h3>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
                You'll be asked for a code from your authenticator app every time you sign in.
            </p>

            <form method="POST" action="{{ route('admin.two-factor.recovery-codes') }}" class="mb-4 space-y-3">
                @csrf
                <label class="block text-sm font-semibold text-navy dark:text-white">Regenerate recovery codes</label>
                <input type="password" name="password" placeholder="Current password" required
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                <button type="submit" class="text-sm font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded-lg transition-colors">
                    Generate New Codes
                </button>
            </form>

            <form method="POST" action="{{ route('admin.two-factor.disable') }}" class="pt-4 border-t border-gray-100 dark:border-gray-700 space-y-3">
                @csrf
                <label class="block text-sm font-semibold text-navy dark:text-white">Disable two-factor authentication</label>
                <input type="password" name="password" placeholder="Current password" required
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                <button type="submit" class="text-sm font-semibold text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 px-4 py-2 rounded-lg transition-colors">
                    Disable 2FA
                </button>
            </form>
        </div>
    @else
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="font-display text-base font-bold text-navy dark:text-white mb-1.5">Set up two-factor authentication</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Add an extra layer of security to your account using an authenticator app like Google Authenticator, Authy, or 1Password.
            </p>

            <ol class="text-sm text-gray-600 dark:text-gray-300 space-y-3 mb-5 list-decimal list-inside">
                <li>Open your authenticator app.</li>
                <li>Scan the QR code below, or choose "Enter a setup key" and enter <strong>{{ auth()->user()->email }}</strong> as the account name with the key underneath it.</li>
            </ol>

            <div class="bg-white rounded-lg border border-gray-200 dark:border-gray-700 p-4 mb-4 flex justify-center">
                {!! $qrCodeSvg !!}
            </div>

            <div class="bg-gray-50 dark:bg-navy-dark rounded-lg px-4 py-3 mb-5 text-center">
                <code class="text-base font-mono tracking-widest text-navy dark:text-white">{{ $secretForDisplay }}</code>
            </div>

            <form method="POST" action="{{ route('admin.two-factor.confirm') }}" class="space-y-3">
                @csrf
                <label class="block text-sm font-semibold text-navy dark:text-white">Enter the 6-digit code your app shows</label>
                <input type="text" name="code" required autocomplete="one-time-code" inputmode="numeric" placeholder="123456"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm text-center tracking-widest focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                <button type="submit" class="w-full bg-gold hover:bg-gold-dark text-navy font-bold text-sm py-2.5 rounded-lg transition-colors">
                    Enable Two-Factor Authentication
                </button>
            </form>
        </div>
    @endif

</div>

@endsection
