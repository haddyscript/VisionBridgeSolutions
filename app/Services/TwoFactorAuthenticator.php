<?php

namespace App\Services;

/**
 * RFC 4226 (HOTP) / RFC 6238 (TOTP) implemented directly rather than pulling
 * in pragmarx/google2fa — see specs/TWO_FACTOR_AUTHENTICATION.md for why.
 * Compatible with Google Authenticator, Authy, 1Password, etc. (6-digit
 * codes, 30-second step, SHA1 — the universal defaults every app assumes).
 */
class TwoFactorAuthenticator
{
    private const BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    private const PERIOD = 30;

    private const DIGITS = 6;

    public function generateSecretKey(int $length = 32): string
    {
        $secret = '';
        $alphabetLength = strlen(self::BASE32_ALPHABET);

        for ($i = 0; $i < $length; $i++) {
            $secret .= self::BASE32_ALPHABET[random_int(0, $alphabetLength - 1)];
        }

        return $secret;
    }

    /**
     * "Enter a setup key manually" format most authenticator apps accept —
     * account label, issuer, and the secret grouped in 4s for readability.
     */
    public function formatSecretForDisplay(string $secret): string
    {
        return trim(chunk_split($secret, 4, ' '));
    }

    public function verify(string $secret, string $code): bool
    {
        $code = preg_replace('/\s+/', '', $code);

        if (! preg_match('/^\d{6}$/', $code)) {
            return false;
        }

        $timestamp = time();

        // ±1 step (30s) tolerance for clock drift between the client's phone
        // and this server.
        foreach ([-1, 0, 1] as $stepOffset) {
            $counter = intdiv($timestamp, self::PERIOD) + $stepOffset;

            if (hash_equals($this->generateCode($secret, $counter), $code)) {
                return true;
            }
        }

        return false;
    }

    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(4))).'-'.strtoupper(bin2hex(random_bytes(4)));
        }

        return $codes;
    }

    private function generateCode(string $secret, int $counter): string
    {
        $binaryCounter = pack('N*', 0, $counter);
        $key = $this->base32Decode($secret);
        $hash = hash_hmac('sha1', $binaryCounter, $key, true);

        $offset = ord($hash[19]) & 0x0F;

        $truncated =
            ((ord($hash[$offset]) & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8) |
            (ord($hash[$offset + 3]) & 0xFF);

        $code = $truncated % (10 ** self::DIGITS);

        return str_pad((string) $code, self::DIGITS, '0', STR_PAD_LEFT);
    }

    private function base32Decode(string $secret): string
    {
        $secret = strtoupper(preg_replace('/[^A-Z2-7]/i', '', $secret));
        $bits = '';

        foreach (str_split($secret) as $char) {
            $bits .= str_pad(decbin((int) strpos(self::BASE32_ALPHABET, $char)), 5, '0', STR_PAD_LEFT);
        }

        $bytes = '';
        foreach (str_split($bits, 8) as $byte) {
            if (strlen($byte) === 8) {
                $bytes .= chr(bindec($byte));
            }
        }

        return $bytes;
    }
}
