{{--
    The VisionBridge "bridge" illustration shared by every error page.
    $broken = true → the middle span is missing (404).
    $icon   = 'lock' | 'clock' | 'gear' | null → badge over an intact bridge.
--}}
@php($broken = $broken ?? false)
@php($icon = $icon ?? null)
<svg class="bridge" viewBox="0 0 560 120" fill="none" aria-hidden="true">
    <defs>
        <linearGradient id="deck" x1="0" x2="1">
            <stop offset="0" stop-color="#A8872E"/>
            <stop offset=".5" stop-color="#E8C96A"/>
            <stop offset="1" stop-color="#A8872E"/>
        </linearGradient>
    </defs>

    {{-- Water --}}
    <path d="M0 108 Q 70 102 140 108 T 280 108 T 420 108 T 560 108" stroke="rgba(42,157,143,0.35)" stroke-width="1.5"/>
    <path d="M20 116 Q 90 110 160 116 T 300 116 T 440 116 T 540 116" stroke="rgba(42,157,143,0.18)" stroke-width="1.5"/>

    {{-- Towers + outer cables --}}
    <rect x="70" y="18" width="10" height="90" rx="2" fill="#1f3157" stroke="rgba(201,168,76,0.5)"/>
    <rect x="480" y="18" width="10" height="90" rx="2" fill="#1f3157" stroke="rgba(201,168,76,0.5)"/>
    <path d="M75 20 Q 30 52 0 58" stroke="rgba(201,168,76,0.7)" stroke-width="1.5"/>
    <path d="M485 20 Q 530 52 560 58" stroke="rgba(201,168,76,0.7)" stroke-width="1.5"/>
    <g stroke="rgba(201,168,76,0.35)" stroke-width="1">
        <line x1="50" y1="36" x2="50" y2="62"/><line x1="25" y1="48" x2="25" y2="62"/>
        <line x1="510" y1="36" x2="510" y2="62"/><line x1="535" y1="48" x2="535" y2="62"/>
        <line x1="100" y1="34" x2="100" y2="62"/><line x1="125" y1="44" x2="125" y2="62"/>
        <line x1="150" y1="51" x2="150" y2="62"/><line x1="175" y1="55" x2="175" y2="62"/>
        <line x1="200" y1="58" x2="200" y2="62"/>
        <line x1="460" y1="34" x2="460" y2="62"/><line x1="435" y1="44" x2="435" y2="62"/>
        <line x1="410" y1="51" x2="410" y2="62"/><line x1="385" y1="55" x2="385" y2="62"/>
        <line x1="360" y1="58" x2="360" y2="62"/>
    </g>

    @if ($broken)
        {{-- Two halves with the middle span gone --}}
        <path d="M75 20 Q 140 58 228 60" stroke="rgba(201,168,76,0.7)" stroke-width="1.5"/>
        <path d="M485 20 Q 420 58 332 60" stroke="rgba(201,168,76,0.7)" stroke-width="1.5"/>
        <path d="M0 62 H232 L226 70 H0 Z" fill="url(#deck)"/>
        <path d="M328 62 H560 V70 H334 Z" fill="url(#deck)"/>
        <rect class="piece" x="252" y="74" width="22" height="7" rx="1.5" fill="#C9A84C" opacity=".9"/>
        <rect class="piece p2" x="284" y="84" width="16" height="6" rx="1.5" fill="#A8872E" opacity=".8"/>
        <rect class="piece p3" x="268" y="94" width="10" height="5" rx="1.5" fill="#E8C96A" opacity=".7"/>
        <circle class="spark" cx="232" cy="66" r="2.5" fill="#FFF2A8"/>
        <circle class="spark" cx="328" cy="66" r="2.5" fill="#FFF2A8" style="animation-delay:1.1s"/>
    @else
        {{-- Intact span --}}
        <path d="M75 20 Q 170 60 280 61" stroke="rgba(201,168,76,0.7)" stroke-width="1.5"/>
        <path d="M485 20 Q 390 60 280 61" stroke="rgba(201,168,76,0.7)" stroke-width="1.5"/>
        <g stroke="rgba(201,168,76,0.35)" stroke-width="1">
            <line x1="230" y1="60" x2="230" y2="62"/><line x1="330" y1="60" x2="330" y2="62"/>
        </g>
        <path d="M0 62 H560 V70 H0 Z" fill="url(#deck)"/>
    @endif

    @if ($icon)
        <g class="badge">
            <circle cx="280" cy="30" r="20" fill="#111D33" stroke="#C9A84C" stroke-width="1.5"/>
            <circle cx="280" cy="30" r="25" stroke="rgba(201,168,76,0.25)" stroke-width="1"/>
            <g transform="translate(268 18)" stroke="#DFC06A" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" fill="none">
                @if ($icon === 'lock')
                    <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v2"/>
                @elseif ($icon === 'clock')
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                @elseif ($icon === 'gear')
                    <g class="spin">
                        <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </g>
                @endif
            </g>
        </g>
    @endif
</svg>
