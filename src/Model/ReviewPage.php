<?php

declare(strict_types=1);

namespace App\Model;

final readonly class ReviewPage
{
    /** @param list<ReviewCard> $cards */
    public function __construct(
        public array $cards,
        public float $averageRating,
        public int $count,
    ) {
    }
}
