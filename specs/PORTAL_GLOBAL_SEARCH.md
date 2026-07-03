# Global Portal Search

A single search box in the portal header that searches across a client's own
Project Files, Website Content & Revisions, Documents, and Payments —
instead of them having to remember which sidebar section a file or thread
lives under.

## Why this exists

The portal has grown to ~10 sidebar sections. A client hunting for "that PDF
I uploaded three weeks ago" currently has to guess which of Project Files'
5 tabs it's in, or whether it's actually in Documents instead. There's no
way to search across sections at once.

## Scope (v1)

Searches four real, DB-backed sources, all scoped to the logged-in client's
own project (never another client's data):

| Source | Matched against | Links to |
|---|---|---|
| Project Files | `uploads.original_name` (file categories: image, video, logo, document, marketing) | `portal.category` for that item's category |
| Website Content & Revisions | `uploads.body` (categories: content, revision) | `portal.category` for that item's category |
| Documents | Signed agreement template title (`service_agreement_signatures` → `service_agreement_templates.title`) | `portal.documents.index` |
| Payments | `payments.description` | `portal.payments.index` |

**Deliberately excluded in v1: FAQ.** FAQ content is a hardcoded PHP array
inside `resources/views/portal/faq.blade.php` (not a database table), so it
isn't queryable from a backend search endpoint without first extracting it
into a real `faqs` table — a bigger refactor than this feature needs.
`portal.faq` already has its own client-side search box for the same
content. Revisit if FAQ ever moves into the database.

## How it works

- `Portal\SearchController::index()` — `GET /portal/search?q=...`, returns
  JSON grouped by source (`files`, `content`, `documents`, `payments`),
  each entry `{ title, subtitle, url }`. Empty/short (`< 2` char) queries
  return all-empty groups rather than erroring.
- A search input sits in the portal header, before the notification bell.
  Typing debounces (250ms) and fetches the endpoint, rendering a dropdown
  grouped by source with a small icon per group — same visual language as
  the notification bell dropdown. Empty state: "No matches for '…'".
- No new table — this is a live query across existing models, not an
  indexed/precomputed search. Fine at this data volume (a handful of
  uploads/payments per client); revisit with a real search index
  (Laravel Scout, a `LIKE`-unfriendly database, etc.) only if query time
  becomes a problem.

## Where things live

| Piece | Code |
|---|---|
| Search endpoint | `App\Http\Controllers\Portal\SearchController::index` |
| Route | `GET /portal/search` → `portal.search` |
| Header search box + dropdown JS | `resources/views/layouts/portal.blade.php` |

## Known limitations

- Plain `LIKE '%term%'` matching — no fuzzy matching, typo tolerance, or
  relevance ranking beyond "most recent first" per group.
- FAQ not included (see above).
- No result highlighting of the matched term within the title/subtitle.
