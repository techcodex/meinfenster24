<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Review;
use App\Model\ReviewCard;
use App\Model\ReviewPage;
use App\Repository\ReviewRepositoryInterface;

final class ReviewService
{
    public function __construct(
        private readonly ReviewRepositoryInterface $reviews,
        private readonly NameFormatter $names = new NameFormatter(),
        private readonly DateFormatter $dates = new DateFormatter(),
    ) {
    }

    public function getPage(): ReviewPage
    {
        $cards = array_map($this->toCard(...), $this->reviews->findActive());

        $rated = array_filter($cards, fn (ReviewCard $c): bool => $c->rating->value > 0);
        $average = $rated === []
            ? 0.0
            : array_sum(array_map( fn (ReviewCard $c): int => $c->rating->value, $rated)) / count($rated);
        
        return new ReviewPage($cards, round($average, 1), count($cards));
    }

    private function toCard(Review $review): ReviewCard
    {
        $text = $review->text !== null ? trim($review->text) : null;

        return new ReviewCard(
            id: $review->id,
            displayName: $this->names->format($review->name),
            rating: new StarRating($review->stars),
            text: $text === '' ? null : $text,
            dateLabel: $this->dates->monthYear($review->orderDate),
            images: $review->images,
        );
    }
}
