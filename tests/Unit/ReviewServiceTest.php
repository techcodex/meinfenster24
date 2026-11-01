<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Model\Review;
use App\Model\ReviewImage;
use App\Service\ReviewService;
use PHPUnit\Framework\TestCase;
use Tests\Support\FakeReviewRepository;

final class ReviewServiceTest extends TestCase
{
    public function testMapsReviewToCard(): void
    {
        $service = new ReviewService(new FakeReviewRepository([
            new Review(1, 5, 'Sigrid Peters', ' Tolle Arbeit ', '2026-02', [new ReviewImage('https://x/y.jpg', 'Fenster')]),
        ]));

        $card = $service->getPage()->cards[0];

        self::assertSame('Sigrid P.', $card->displayName);
        self::assertSame(5, $card->rating->value);
        self::assertSame('Tolle Arbeit', $card->text);
        self::assertSame('Februar 2026', $card->dateLabel);
        self::assertSame('https://x/y.jpg', $card->primaryImage()?->url);
    }

    public function testReviewWithoutImageOrTextIsHandled(): void
    {
        $service = new ReviewService(new FakeReviewRepository([new Review(2, 5, 'Lukas Ränke', null, '2026-08-20')]));

        $card = $service->getPage()->cards[0];

        self::assertNull($card->primaryImage());
        self::assertNull($card->text);
    }

    public function testBlankTextBecomesNull(): void
    {
        $service = new ReviewService(new FakeReviewRepository([new Review(3, 4, 'A B', "  \n ", null)]));

        self::assertNull($service->getPage()->cards[0]->text);
        self::assertNull($service->getPage()->cards[0]->dateLabel);
    }

    public function testAverageAndCount(): void
    {
        $service = new ReviewService(new FakeReviewRepository([
            new Review(1, 5, 'A B', 't', null),
            new Review(2, 4, 'C D', 't', null),
            new Review(3, null, 'E F', 't', null),
        ]));

        $page = $service->getPage();

        self::assertSame(3, $page->count);
        self::assertSame(4.5, $page->averageRating); // unrated review ignored in average
        self::assertSame(2, $page->ratedCount);
    }

    public function testEmptyRepository(): void
    {
        $page = (new ReviewService(new FakeReviewRepository([])))->getPage();

        self::assertSame([], $page->cards);
        self::assertSame(0.0, $page->averageRating);
    }
}
