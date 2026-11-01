<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Model\Review;
use App\Repository\ReviewRepositoryInterface;

final class FakeReviewRepository implements ReviewRepositoryInterface
{
    /** @param list<Review> $reviews */
    public function __construct(private readonly array $reviews)
    {
    }

    public function findActive(): array
    {
        return $this->reviews;
    }
}
