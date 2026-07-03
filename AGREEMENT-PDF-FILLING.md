# Agreement PDF Filling — Technical Reference

How the Master Agreement PDF gets the client's Care Plan selection and
signature block stamped onto it at signing time, instead of staying blank.

## Why this exists

Before this, a client who signed a PDF-based Service Agreement only ever saw
the **blank** uploaded template plus a separate one-page signature
certificate — the "PROJECT INVESTMENT," "SELECTED WEBSITE CARE PLAN," and
"SIGNATURES" sections inside the actual PDF always stayed empty (see
FEATURES.md section 10 for why: no PDF-merge library was installed at the
time). This fills that gap for the fields we actually have data for.

## Source file this was built against

`storage/app/private/CLIENT WEBSITE DEVELOPMENT & WEBSITE CARE PLAN MASTER AGREEMENT-OFFICIAL 6.30.pdf`
— 134 pages, US Letter (612pt × 792pt).

**This entire approach is tied to that exact file's layout and page
numbers.** If the boss uploads a revised Master Agreement PDF with different
pagination or field wording, the coordinates below are wrong until
re-extracted (see "How to recalibrate" below).

## How coordinates were found

Not guessed from a screenshot. Extracted with:

```bash
pdftotext -f 130 -l 134 -bbox-layout "storage/app/private/CLIENT WEBSITE DEVELOPMENT & WEBSITE CARE PLAN MASTER AGREEMENT-OFFICIAL 6.30.pdf" output.xml
```

(`pdftotext` is part of `poppler-utils` — `brew install poppler` on macOS.)
This dumps every word's exact `xMin`/`yMin`/`xMax`/`yMax` in PDF points
(top-left origin, y increasing downward — same convention FPDF/FPDI use when
constructed with `new Fpdi('P', 'pt')`, so no unit conversion was needed).

The document has a consistent rhythm: each label (e.g. "Authorized
Representative") is followed by a blank line roughly **25-37pt below** the
label's `yMin`, and consecutive stacked single-line fields are spaced
**~49.92pt apart**. Confirmed against the one field that *does* render as
literal text (the "$ ____" blank for Monthly Investment uses underscore
characters, not a vector line, so its exact box was directly measurable:
label at y=667.42 → blank row at y=692.38–704.38, i.e. label yMin + 25 to
+37). Every other field's write position was placed at **label yMin + 34**
(near the bottom of that same band, just above the drawn rule) using this
confirmed offset.

## Field map (what's filled, pages 129, 132-134 of that PDF)

| Field | Page | x | y (baseline) | Source data |
|---|---|---|---|---|
| 5 pre-signature acknowledgment checkboxes | 129 | 74.5 | 109.99, 136.75, 163.39, 190.15, 216.79 (each checkbox's own yMin + 13) | Not per-field data — `ServiceAgreementController::store()` validates all 5 `ack_*` inputs as `'accepted'` before a signature can be created at all, so every filled PDF stamps an "X" on all 5 unconditionally |
| Selected Care Plan | 132 | 72.024 | 651.5 | `$project->carePlanAgreement->maintenancePlan->name` |
| Monthly Investment | 132 | 85 (right after the printed "$") | 702 | `maintenancePlan->price / 100`, formatted `number_format(..., 2)` |
| Client / Organization Name | 133 | 72.024 | 624.86 | `ServiceAgreementSignature->organization_name` |
| Authorized Representative | 133 | 72.024 | 674.78 | `ServiceAgreementSignature->signer_name` |
| Title | 133 | 72.024 | 724.696 | `ServiceAgreementSignature->title` |
| Signature (image) | 134 | 72.024 (top-left of image) | 105 (top), 110×33pt box | `ServiceAgreementSignature->signature_image_path` (PNG) |
| Date | 134 | 72.024 | 183.3 | `ServiceAgreementSignature->signed_at->format('F j, Y')` |

Font used for all text fields: Helvetica, 11pt, near-black (`SetTextColor(20,20,20)`).

The signature image box (110×33pt) matches the aspect ratio of the capture
canvas in `resources/views/portal/agreement.blade.php` (`600×180px` = 10:3),
so it doesn't stretch/distort what the client actually drew.

## Deliberately NOT filled (no data source exists yet)

| Field | Page | Why not |
|---|---|---|
| Project Name, Website Development Investment, Project Deposit, Remaining Balance Due, Estimated Project Start/Launch Date | 130 | "PROJECT INVESTMENT" block — project price/deposit/dates aren't captured anywhere in the current data model |
| Website Care Plan Effective Date, Recurring Billing Date | 133 | Not captured — would need a business decision on what date to use (signing date? first successful charge?) |
| VisionBridge's own Authorized Representative / Title / Signature / Date | 134 | Nothing stores who signs for VisionBridge, or a stored business signature image |
| Effective Date of Agreement | 134 | Not captured; could plausibly reuse `signed_at`, but that wasn't in the agreed scope (2026-07-02 scope decision — see FEATURES.md section 15) |

If any of these get added later, extend `AgreementPdfFiller` with the same
`pdftotext -bbox-layout` process rather than guessing.

## Implementation

- `app/Services/AgreementPdfFiller.php` — the whole thing. Uses
  `setasign/fpdi` (`setasign\Fpdi\Fpdi`, extends the global `FPDF` class
  provided by `setasign/fpdf`). Imports every page of the source PDF via
  `importPage()`/`useTemplate()` (so the rest of the 134-page document passes
  through untouched) and overlays text/image only on pages 132-134.
- Constants `PAGE_ACKNOWLEDGMENTS = 129`, `PAGE_CARE_PLAN = 132`,
  `PAGE_CLIENT_INFO = 133`, `PAGE_CLIENT_SIGNATURE = 134` at the top of the
  class — **update these first** if the page count changes.
- Triggered in `Portal\ServiceAgreementController::store()`, right after the
  signature certificate is generated, only when `$template->isPdfBased()`.
  Output saved to `service_agreement_signatures.filled_pdf_path`
  (migration `2026_07_05_000005_add_filled_pdf_path_to_service_agreement_signatures_table.php`).
- Consumed in three places:
  - Portal Documents page (`portal.agreement.filled` route) — replaces the
    blank-template link when a filled copy exists.
  - `ServiceAgreementSignedMail` — attached as `VisionBridge-Service-Agreement.pdf`
    instead of the blank template.
  - Same route is usable by admins (owner-or-admin check in
    `ServiceAgreementController::viewFilled()`).

## Server requirements

`setasign/fpdf` requires the `ext-gd` and `ext-zlib` PHP extensions.
Confirm both are enabled on Hostinger before relying on this in production —
if either is missing, signing throws a fatal error instead of failing
gracefully.

## How to recalibrate against a new PDF version

1. Get the new file into `storage/app/private/` (or wherever it's uploaded).
2. Re-run `pdftotext -bbox-layout` against the relevant pages (search the
   output for "SIGNATURES", "SELECTED WEBSITE CARE PLAN", "Client /
   Organization Name", etc. to find the new page numbers and y-coordinates).
3. Update the `PAGE_*` constants and the x/y values in each `fill*Page()`
   method in `AgreementPdfFiller.php`.
4. Sign a test agreement and download the filled PDF to visually confirm
   placement before trusting it for real clients.

## Status

**Untested end-to-end as of 2026-07-03.** The service was written and wired
in without a way to run PHP/Composer locally — it needs a real signing
walkthrough on the server to confirm the overlay actually lands correctly
and that the required PHP extensions are enabled.

**2026-07-03:** The 5 pre-signature acknowledgment checkboxes (page 129)
were missing from the original field map entirely — only pages 132-134 were
ever filled, so clients saw all 5 boxes still empty in their completed PDF
even though they'd checked them (and the signature was gated on it) in the
portal. Added `PAGE_ACKNOWLEDGMENTS` / `fillAcknowledgmentsPage()` to stamp
an "X" on all 5. Still needs the same real-signing verification as above.
