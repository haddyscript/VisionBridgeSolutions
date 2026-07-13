{{-- Shared typography for Markdown-rendered announcement bodies (used by the
     nag modal in partials.announcement-banner and the portal history page).
     Both layouts load Tailwind via the CDN runtime script rather than the
     Vite-built app.css, so this can't rely on the (uninstalled)
     @tailwindcss/typography plugin or a `prose` class — it's hand-rolled and
     scoped to .announcement-prose instead. --}}
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
