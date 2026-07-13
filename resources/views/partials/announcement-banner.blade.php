{{-- Polished announcement banner. Expects:
     $announcement — the Announcement model instance
     $dismissUrl   — POST route to dismiss it for the current user
     $domId        — unique id prefix (differs per layout so admin + portal
                      banners never collide if both were ever on one page) --}}

@php
    $bodyHtml = \Illuminate\Support\Str::markdown($announcement->body, [
        'html_input' => 'strip',
        'allow_unsafe_links' => false,
    ]);
@endphp

<div id="{{ $domId }}-banner" class="relative rounded-xl border border-gold/25 dark:border-gold/15 bg-gold/5 dark:bg-white/[0.03] p-6 sm:p-8 mb-6">

    <button type="button" id="{{ $domId }}-dismiss" data-url="{{ $dismissUrl }}" aria-label="Dismiss announcement"
            class="absolute top-4 right-4 sm:top-5 sm:right-5 rounded-full p-1.5 text-gray-400 hover:text-navy dark:hover:text-white hover:ring-2 hover:ring-gold/40 focus:outline-none focus:ring-2 focus:ring-gold/50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>

    {{-- Metadata strip: title, subtitle, and a date/time row with SVG icons
         in place of raw emoji. --}}
    <div class="flex items-start gap-3 pr-8">
        <svg class="w-5 h-5 text-gold-dark shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
        <div class="min-w-0 flex-1">
            <p class="text-base font-bold text-navy dark:text-white leading-snug">{{ $announcement->title }}</p>

            @if ($announcement->subtitle)
                <p class="text-sm text-navy/60 dark:text-white/60 whitespace-pre-line mt-0.5">{{ $announcement->subtitle }}</p>
            @endif

            @if ($announcement->event_date || $announcement->event_time)
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-xs font-medium text-gray-500 dark:text-gray-400">
                    @if ($announcement->event_date)
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            {{ $announcement->event_date->format('l, F j, Y') }}
                        </span>
                    @endif
                    @if ($announcement->event_time)
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $announcement->event_time }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="border-t border-gold/20 dark:border-gold/10 my-4"></div>

    <div class="announcement-prose">
        {!! $bodyHtml !!}
    </div>
</div>

{{-- Both layouts load Tailwind via the CDN runtime script rather than the
     Vite-built app.css, so this typography can't rely on the (uninstalled)
     @tailwindcss/typography plugin or a `prose` class — it's hand-rolled and
     scoped to .announcement-prose here instead. --}}
<style>
    .announcement-prose { color: #4B5563; font-size: 0.9375rem; line-height: 1.65; }
    .dark .announcement-prose { color: #D1D5DB; }
    .announcement-prose > *:first-child { margin-top: 0; }
    .announcement-prose > *:last-child { margin-bottom: 0; }

    .announcement-prose h1,
    .announcement-prose h2,
    .announcement-prose h3 { color: #1B2A4A; font-weight: 700; margin-top: 1.25em; margin-bottom: 0.5em; }
    .dark .announcement-prose h1,
    .dark .announcement-prose h2,
    .dark .announcement-prose h3 { color: #FFFFFF; }
    .announcement-prose h1 { font-size: 1.15rem; }
    .announcement-prose h2 { font-size: 1.05rem; }
    .announcement-prose h3 { font-size: 0.9375rem; }

    .announcement-prose p { margin-top: 0.85em; margin-bottom: 0.85em; }

    .announcement-prose strong { color: #1B2A4A; font-weight: 600; }
    .dark .announcement-prose strong { color: #FFFFFF; }
    .announcement-prose a { color: #A8872E; text-decoration: underline; }

    .announcement-prose ol { list-style: decimal; padding-left: 1.25rem; margin-top: 0.85em; margin-bottom: 0.85em; }
    .announcement-prose ol > li { font-weight: 600; color: #1B2A4A; margin-top: 0.6em; }
    .dark .announcement-prose ol > li { color: #FFFFFF; }

    .announcement-prose ul { list-style: disc; padding-left: 1.25rem; margin-top: 0.35em; margin-bottom: 0.35em; }
    .announcement-prose ul > li { font-weight: 400; color: inherit; margin-top: 0.25em; }

    /* Bullets properly nested inside a numbered item (indented in the source). */
    .announcement-prose ol li ul { margin-top: 0.35em; padding-left: 1.25rem; }

    /* Bullets typed flat right after a numbered list still read as its
       sub-items, so indent that case too even though Markdown treats it as
       a sibling list rather than nesting it. */
    .announcement-prose ol + ul { margin-top: -0.35em; padding-left: 2.5rem; }
</style>

<script>
    document.getElementById('{{ $domId }}-dismiss')?.addEventListener('click', function () {
        document.getElementById('{{ $domId }}-banner')?.remove();
        fetch(this.dataset.url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        });
    });
</script>
