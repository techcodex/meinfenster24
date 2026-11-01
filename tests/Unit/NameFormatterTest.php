<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Service\NameFormatter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NameFormatterTest extends TestCase
{
    /** @return array<string, array{?string, string}> */
    public static function names(): array
    {
        return [
            'simple' => ['Max Mustermann', 'Max M.'],
            'hyphenated first name' => ['Franz-Josef Oberhammer', 'Franz-Josef O.'],
            'middle name ignored' => ['Rene Bernd Holgerson', 'Rene H.'],
            'umlaut initial' => ['Lukas Ränke', 'Lukas R.'],
            'lowercase last name' => ['Max müller', 'Max M.'],
            'multibyte initial' => ['Anna Ötzi', 'Anna Ö.'],
            'extra whitespace' => ["  Max \t  Mustermann  ", 'Max M.'],
            'single name' => ['Madonna', 'Madonna'],
            'empty' => ['', 'Anonym'],
            'blank' => ['   ', 'Anonym'],
            'null' => [null, 'Anonym'],
        ];
    }

    #[DataProvider('names')]
    public function testFormat(?string $input, string $expected): void
    {
        self::assertSame($expected, (new NameFormatter())->format($input));
    }
}
