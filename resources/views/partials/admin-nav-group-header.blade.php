{{-- Admin sidebar section header — toggles its .nav-group open/closed (see the script in layouts/admin). --}}
<button type="button" aria-expanded="true"
        class="nav-group-toggle w-full flex items-center gap-2 px-3 pb-1.5 text-[11px] font-bold uppercase tracking-widest text-white/35 hover:text-white/70 transition-colors">
    <span class="flex-1 text-left">{{ $label }}</span>
    <span class="nav-group-dot hidden w-1.5 h-1.5 rounded-full bg-red-500"></span>
    <svg class="nav-group-chevron w-3.5 h-3.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
    </svg>
</button>
