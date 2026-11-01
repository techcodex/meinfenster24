<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Review;

interface ReviewRepositoryInterface
{
    /** @return list<Review> active reviews, newest first, each with its images */
    public function findActive(): array;
}
