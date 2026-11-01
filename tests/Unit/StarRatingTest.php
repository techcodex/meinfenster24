<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Service\StarRating;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class StarRatingTest extends TestCase
{
    /** @return array<string, array{?int, int, int}> */
    public static function ratings(): array
    {
        return ['five' => [5, 5, 0], 'two' => [2, 2, 3], 'zero' => [0, 0, 5], 'null' => [null, 0, 5], 'too high' => [9, 5, 0], 'negative' => [-3, 0, 5]];
    }

    #[DataProvider('ratings')]
    public function testFullAndEmptyStars(?int $input, int $full, int $empty): void
    {
        $rating = new StarRating($input);
        self::assertSame($full, $rating->full());
        self::assertSame($empty, $rating->empty());
        self::assertSame(StarRating::MAX, $rating->full() + $rating->empty());
    }

    public function testLabel(): void
    {
        self::assertSame('4 von 5 Sternen', (new StarRating(4))->label());
    }

    public function testIsRated(): void
    {
        self::assertFalse((new StarRating(null))->isRated());
        self::assertTrue((new StarRating(1))->isRated());
    }
}
