<?php

namespace App\Support;

class PhoneNumber
{
    /**
     * Normalize Tanzania phone numbers to 255XXXXXXXXX format.
     */
    public static function normalizeTz(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $value);
        if (! $digits) {
            return null;
        }

        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            $digits = '255'.substr($digits, 1);
        } elseif (str_starts_with($digits, '255') && strlen($digits) === 12) {
            // Already normalized.
        } elseif (strlen($digits) === 9) {
            $digits = '255'.$digits;
        }

        return strlen($digits) === 12 ? $digits : null;
    }
}
