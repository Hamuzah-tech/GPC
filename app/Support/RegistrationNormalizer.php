<?php

namespace App\Support;

class RegistrationNormalizer
{
    public static function registrationNumber(string $value): string
    {
        $value = preg_replace('/\s+/', '', trim($value)) ?? '';

        return strtoupper($value);
    }

    public static function email(string $value): string
    {
        return strtolower(trim($value));
    }

    public static function phone(string $value): string
    {
        $digits = preg_replace('/\D+/', '', $value) ?? '';

        if (str_starts_with($digits, '265') && strlen($digits) > 9) {
            $digits = '0'.substr($digits, 3);
        }

        return $digits;
    }
}
