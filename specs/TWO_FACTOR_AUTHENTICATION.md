# Two-Factor Authentication (TOTP)

Optional TOTP-based 2FA (Google Authenticator / Authy / 1Password, etc.) for
any account — client or admin — since both handle sensitive data (signed
legal agreements, payment info) and this portal has no Fortify/Jetstream
already installed to lean on.

## Why TOTP, and why no new composer package

Considered `laravel/fortify` (brings 2FA out of the box) — rejected because
adopting it wholesale would mean restructuring the existing hand-rolled
`AuthenticatedSessionController`/`RegisteredUserController` flow this app
already has, for a single feature.

Considered `pragmarx/google2fa` + `bacon/bacon-qr-code` (the common
lightweight combo) — TOTP itself (RFC 6238) is short enough to implement
directly with PHP's built-in `hash_hmac`, and QR rendering was dropped from
v1 anyway (see below), so pulling in two packages for what's ultimately a
~60-line HMAC routine wasn't worth the dependency. `App\Services\TwoFactorAuthenticator`
implements RFC 4226 (HOTP) / RFC 6238 (TOTP) directly: base32 encode/decode,
counter-based HMAC-SHA1, 6-digit code, ±1 time-step window for clock drift.

**No QR code in v1.** Generating a scannable QR image either needs a new
package or hitting a third-party image API with the raw secret in the URL —
the latter is a real secrets-leak risk, not just a style choice, so it was
ruled out rather than deferred. v1 shows the base32 secret as text (grouped
in 4s for readability) for the "enter a setup key manually" flow every
authenticator app supports. Add `bacon/bacon-qr-code` later if manual entry
proves too much friction for clients.

## Data model

`users` gains three columns (migration
`2026_07_03_XXXXXX_add_two_factor_columns_to_users_table.php`):

- `two_factor_secret` (text, nullable, `encrypted` cast) — the base32 TOTP
  secret. Set as soon as setup starts, before it's confirmed.
- `two_factor_recovery_codes` (text, nullable, `encrypted` cast, JSON array)
  — generated once, at confirmation time. Stored encrypted-at-rest but not
  separately hashed (unlike a password) — a used code is removed from the
  array immediately, so the exposure window for a single leaked DB row is
  "the remaining unused codes," same class of risk as the TOTP secret
  itself, both protected by the same app-level encryption key.
- `two_factor_confirmed_at` (timestamp, nullable) — null means "setup
  started but never confirmed" (treated as *not* enabled) or "never
  attempted." Only a non-null value here gates login.

## Enrollment flow

`Portal\TwoFactorController` (same controller serves both client and admin
accounts — no portal-specific logic):

1. `GET /account/two-factor` — if no secret yet, generates one and saves it
   unconfirmed; shows the manual setup key + a 6-digit code input.
2. `POST /account/two-factor/confirm` — validates the code against the
   pending secret. On match: sets `two_factor_confirmed_at`, generates 8
   recovery codes (format `XXXX-XXXX`), and shows them **once** on the
   confirmation page with a "download as text file" link — same
   one-time-reveal pattern as the Agreement PDF's signature capture, nothing
   this sensitive is ever shown twice.
3. `POST /account/two-factor/disable` — requires re-entering the current
   password (not just being logged in), clears all three columns.
4. `POST /account/two-factor/recovery-codes` — requires current password,
   regenerates the 8 codes (invalidates the old set).

## Login challenge flow

`AuthenticatedSessionController::store()` changes: after `Auth::attempt()`
succeeds, if the authenticated user has `two_factor_confirmed_at` set, it
immediately `Auth::logout()`s again (login is not actually complete yet),
stashes `session(['2fa.user_id' => $user->id, '2fa.remember' => ...])`, and
redirects to the challenge page instead of the dashboard — the session
never holds a fully-authenticated user until the code is verified.

`Auth\TwoFactorChallengeController`:
- `GET /two-factor-challenge` — the code-entry page. 404s (redirects to
  login) if there's no pending `2fa.user_id` in session.
- `POST /two-factor-challenge` (`throttle:6,1`, same rate limit already used
  on the verification-email resend route) — accepts either a 6-digit TOTP
  code or an 8-character recovery code. On success: `Auth::login()` the
  pending user for real, regenerate the session, clear the `2fa.*` session
  keys, log a `LoginActivity` row (matching what a normal login already
  does), redirect to the intended destination. A recovery code is removed
  from the stored array the moment it's used — single use only.

## Where things live

| Piece | Code |
|---|---|
| Migration | `add_two_factor_columns_to_users_table` |
| TOTP implementation | `App\Services\TwoFactorAuthenticator` |
| Enrollment | `App\Http\Controllers\Portal\TwoFactorController`, `resources/views/portal/two-factor.blade.php` |
| Login gate | `App\Http\Controllers\Auth\AuthenticatedSessionController::store()` |
| Challenge page | `App\Http\Controllers\Auth\TwoFactorChallengeController`, `resources/views/auth/two-factor-challenge.blade.php` |
| Account Settings entry point | `resources/views/portal/account.blade.php` |

## Known limitations

- Admin accounts get the same enrollment page and columns but no dedicated
  admin-side settings UI entry point in v1 — only wired into the client
  Account Settings page. Trivial to add an equivalent link in
  `admin/team/index.blade.php` later; not done here to keep this pass
  scoped to what the client-facing ask covered.
- No "remember this device for 30 days" skip — every login prompts for a
  code if 2FA is enabled. A reasonable v2 addition (a signed, long-lived
  cookie checked before showing the challenge).
- No SMS fallback — consistent with the existing roadmap decision to not
  implement SMS at all (see FEATURES.md §7).
