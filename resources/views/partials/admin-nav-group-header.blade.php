{{-- Admin sidebar section header — toggles its .nav-group open/closed (see the script in layouts/admin). --}}
<button type="button" aria-expanded="true"
        class="nav-group-toggle w-full flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-bold uppercase tracking-wider text-white/70 hover:text-white hover:bg-white/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-gold/60 transition-colors">
    <span class="flex-1 text-left">{{ $label }}</span>
    <span class="nav-group-badge hidden min-w-[1.25rem] text-center text-[10px] font-bold leading-none px-1.5 py-1 rounded-full bg-red-500 text-white normal-case tracking-normal"></span>
    <svg class="nav-group-chevron w-4 h-4 shrink-0 opacity-80 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
    </svg>
</button>
