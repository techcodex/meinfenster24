<?php

declare(strict_types=1);

namespace App\Model;

/** Raw review as stored in the database (domain model). */
final readonly class Review
{
    /** @param list<ReviewImage> $images ordered by position */
    public function __construct(
        public int $id,
        public ?int $stars,
        public ?string $name,
        public ?string $text,
        public ?string $orderDate,
        public array $images = [],
    ) {
    }
}
