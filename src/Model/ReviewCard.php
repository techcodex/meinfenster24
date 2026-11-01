<?php

declare(strict_types=1);

namespace App\Model;

use App\Service\StarRating;

/** Presentation-ready review, consumed by the templates. */
final readonly class ReviewCard
{
    /** @param list<ReviewImage> $images */
    public function __construct(
        public int $id,
        public string $displayName,
        public StarRating $rating,
        public ?string $text,
        public ?string $dateLabel,
        public array $images,
    ) {
    }

    public function primaryImage(): ?ReviewImage
    {
        return $this->images[0] ?? null;
    }
}
