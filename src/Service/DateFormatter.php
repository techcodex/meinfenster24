<?php

declare(strict_types=1);

namespace App\Service;

final class DateFormatter
{
    private const MONTHS = [
        1 => 'Januar', 2 => 'Februar', 3 => 'März', 4 => 'April', 5 => 'Mai', 6 => 'Juni',
        7 => 'Juli', 8 => 'August', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Dezember',
    ];

    public function monthYear(?string $date): ?string
    {
        if ($date === null || !preg_match('/^(\d{4})-(\d{2})(?:-\d{2})?$/', trim($date), $m)) {
            return null;
        }

        $month = (int) $m[2];

        return isset(self::MONTHS[$month]) ? self::MONTHS[$month] . ' ' . $m[1] : null;
    }
}
