<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Model\Review;
use App\Model\ReviewImage;
use App\Service\ReviewService;
use App\View\Renderer;
use PHPUnit\Framework\TestCase;
use Tests\Support\FakeReviewRepository;

final class ReviewPageTest extends TestCase
{
    /** @param list<Review> $reviews */
    private function html(array $reviews): string
    {
        $renderer = new Renderer(__DIR__ . '/../../templates');

        return $renderer->render('reviews', ['page' => (new ReviewService(new FakeReviewRepository($reviews)))->getPage()]);
    }

    public function testCardContainsAllRequiredElements(): void
    {
        $html = $this->html([new Review(1, 4, 'Max Mustermann', 'Super Fenster', '2026-02', [new ReviewImage('https://x/a.jpg', 'Ein Fenster')])]);

        self::assertStringContainsString('src="https://x/a.jpg"', $html);
        self::assertStringContainsString('alt="Ein Fenster"', $html);
        self::assertStringContainsString('aria-label="4 von 5 Sternen"', $html);
        self::assertSame(4, substr_count($html, 'fill-amber-400') - 1); // minus the header star
        self::assertStringContainsString('Super Fenster', $html);
        self::assertStringContainsString('Max M.', $html);
        self::assertStringNotContainsString('Max Mustermann', $html);
    }

    public function testPlaceholderShownWithoutImage(): void
    {
        $html = $this->html([new Review(1, 5, 'Lukas Ränke', null, null)]);

        self::assertStringContainsString('aria-label="Kein Bild vorhanden"', $html);
        self::assertStringNotContainsString('data-review-image', $html);
        self::assertStringNotContainsString('data-review-opener', $html);
        self::assertStringContainsString('Lukas R.', $html);
    }

    public function testMultipleImagesShowBadgeAndGalleryData(): void
    {
        $html = $this->html([new Review(1, 5, 'Max Mustermann', 'Super', null, [
            new ReviewImage('https://x/a.jpg', 'A'),
            new ReviewImage('https://x/b.jpg', 'B'),
        ])]);

        self::assertStringContainsString('2 Fotos', $html);
        self::assertStringContainsString('data-review-opener', $html);
        self::assertStringContainsString('href="https://x/b.jpg"', $html);
        self::assertSame(2, substr_count($html, 'data-caption='));
        self::assertStringContainsString('review-media', $html);
    }

    public function testSingleImageHasNoBadge(): void
    {
        $html = $this->html([new Review(1, 5, 'Max Mustermann', 'Super', null, [new ReviewImage('https://x/a.jpg', 'A')])]);

        self::assertStringNotContainsString('Fotos</span>', $html);
        self::assertStringContainsString('data-review-opener', $html);
    }

    public function testUserContentIsEscaped(): void
    {
        $html = $this->html([new Review(1, 5, '<script>alert(1)</script> Evil', '<img src=x onerror=alert(1)>', null)]);

        self::assertStringNotContainsString('<script>alert(1)</script>', $html);
        self::assertStringNotContainsString('<img src=x', $html);
        self::assertStringContainsString('&lt;img src=x onerror=alert(1)&gt;', $html);
    }

    public function testResponsiveGridClasses(): void
    {
        $html = $this->html([new Review(1, 5, 'A B', 't', null)]);

        self::assertStringContainsString('grid-cols-1', $html);
        self::assertStringContainsString('md:grid-cols-2', $html);
        self::assertStringContainsString('lg:grid-cols-3', $html);
    }

    public function testEmptyState(): void
    {
        self::assertStringContainsString('Noch keine Bewertungen', $this->html([]));
    }
}
