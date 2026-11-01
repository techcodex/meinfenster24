<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Service\DateFormatter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DateFormatterTest extends TestCase
{
    /** @return array<string, array{?string, ?string}> */
    public static function dates(): array
    {
        return [
            'month only' => ['2026-02', 'Februar 2026'],
            'full date' => ['2026-04-20', 'April 2026'],
            'umlaut month' => ['2026-03-16', 'März 2026'],
            'invalid month' => ['2026-13', null],
            'garbage' => ['gestern', null],
            'empty' => ['', null],
            'null' => [null, null],
        ];
    }

    #[DataProvider('dates')]
    public function testMonthYear(?string $input, ?string $expected): void
    {
        self::assertSame($expected, (new DateFormatter())->monthYear($input));
    }
}
