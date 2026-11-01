<?php

declare(strict_types=1);

namespace App\Service;

final readonly class StarRating
{
    public const MAX = 5;

    public int $value;

    public function __construct(?int $value)
    {
        $this->value = max(0, min(self::MAX, $value ?? 0));
    }

    public function full(): int
    {
        return $this->value;
    }

    public function empty(): int
    {
        return self::MAX - $this->value;
    }

    public function label(): string
    {
        return sprintf('%d von %d Sternen', $this->value, self::MAX);
    }
}
