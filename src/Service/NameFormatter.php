<?php

declare(strict_types=1);

namespace App\Service;

final class NameFormatter
{
    public const FALLBACK = 'Anonym';

    public function format(?string $fullName): string
    {
        $parts = preg_split('/\s+/u', trim((string) $fullName), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        if ($parts === []) {
            return self::FALLBACK;
        }

        $first = $parts[0];
        if (count($parts) === 1) {
            return $first;
        }

        $last = $parts[count($parts) - 1];

        return $first . ' ' . mb_strtoupper(mb_substr($last, 0, 1)) . '.';
    }
}
