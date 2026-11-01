<?php

declare(strict_types=1);

namespace App\Model;

final readonly class ReviewImage
{
    public function __construct(
        public string $url,
        public string $alt,
    ) {
    }
}
