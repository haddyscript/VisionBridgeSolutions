{{-- Branded pagination for admin tables: Prev · 1 2 3 … 9 · Next, current
     page in gold. Use with $paginator->links('admin._pagination'). --}}
@if ($paginator->hasPages())
    @php
        $base = 'inline-flex items-center justify-center h-9 rounded-lg border text-sm transition-colors';
        $idle = 'border-gray-200 dark:border-gray-600 bg-white dark:bg-navy-dark text-gray-600 dark:text-gray-300 hover:border-gold hover:bg-gold/10 hover:text-gold-dark';
        $disabled = 'border-gray-200 dark:border-gray-700 text-gray-300 dark:text-gray-600 cursor-not-allowed';
    @endphp
    <nav role="navigation" aria-label="Pagination" class="flex items-center gap-1.5">
        @if ($paginator->onFirstPage())
            <span class="{{ $base }} {{ $disabled }} gap-1 px-3" aria-disabled="true">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span class="hidden sm:inline">Prev</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $base }} {{ $idle }} gap-1 px-3" aria-label="Previous page">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span class="hidden sm:inline">Prev</span>
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-1 text-sm text-gray-400 dark:text-gray-500">…</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="{{ $base }} w-9 border-gold bg-gold text-navy-dark font-bold shadow-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="{{ $base }} {{ $idle }} w-9 font-medium" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $base }} {{ $idle }} gap-1 px-3" aria-label="Next page">
                <span class="hidden sm:inline">Next</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <span class="{{ $base }} {{ $disabled }} gap-1 px-3" aria-disabled="true">
                <span class="hidden sm:inline">Next</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </span>
        @endif
    </nav>
@endif
