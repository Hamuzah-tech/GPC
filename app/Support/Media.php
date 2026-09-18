<?php

namespace App\Support;

class Media
{
    public static function url(?string $path, string $fallback = '/images/image12.jpg'): string
    {
        if (! $path) {
            return asset(ltrim($fallback, '/'));
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
            return $path;
        }

        if (str_starts_with($path, 'leadership/')) {
            $path = 'images/'.$path;
        }

        return asset(ltrim($path, '/'));
    }
}
