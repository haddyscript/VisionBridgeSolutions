<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Rejects phone numbers that are the wrong shape entirely (letters, or too
 * few/many digits once punctuation is stripped) rather than just checking
 * the field isn't empty — a plain `string|max:N` rule happily accepts
 * garbage like "dfgfdgdfgdfgdf". Not a strict E.164/libphonenumber check
 * (no external dependency for that exists in this project yet), just a
 * sanity floor: only digits and common phone punctuation, and a digit count
 * that could plausibly be a real number (7–15 digits, matching the range
 * E.164 numbers actually use worldwide).
 */
class ValidPhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || preg_match('/[^0-9+\-\s().]/', $value)) {
            $fail('The :attribute must be a valid phone number.');

            return;
        }

        $digitCount = strlen(preg_replace('/\D/', '', $value));

        if ($digitCount < 7 || $digitCount > 15) {
            $fail('The :attribute must be a valid phone number.');
        }
    }
}
