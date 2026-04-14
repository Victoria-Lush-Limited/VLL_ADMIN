<?php

namespace App\Support;

use Illuminate\Support\Facades\Hash;

class LegacyPassword
{
    public static function verify(string $plain, string $stored): bool
    {
        $stored = trim($stored);
        if ($stored !== '' && str_starts_with($stored, '$2y$')) {
            return Hash::check($plain, $stored);
        }

        return md5($plain) === $stored;
    }

    public static function hash(string $plain): string
    {
        return Hash::make($plain);
    }

    /** MD5 for entities that still use legacy client/reseller/agent portals. */
    public static function hashMd5(string $plain): string
    {
        return md5($plain);
    }
}
