<?php

namespace App\Services;

class Sanitizer
{

    public static function sanitize(array $args): array
    {
        return array_map(
            static function ($arg) {
                return filter_var($arg, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            },
            $args
        );
    }
}
