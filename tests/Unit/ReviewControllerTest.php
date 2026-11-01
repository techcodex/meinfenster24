<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Controller\ReviewController;
use App\Model\Review;
use App\Repository\ReviewRepositoryInterface;
use App\Service\ReviewService;
use App\View\Renderer;
use PHPUnit\Framework\TestCase;
use Tests\Support\FakeReviewRepository;

final class ReviewControllerTest extends TestCase
{
    private function renderer(): Renderer
    {
        return new Renderer(__DIR__ . '/../../templates');
    }

    public function testIndexRendersReviewPage(): void
    {
        $controller = new ReviewController(
            new ReviewService(new FakeReviewRepository([new Review(1, 5, 'Max Mustermann', 'Super', null)])),
            $this->renderer(),
        );

        $response = $controller->index();

        self::assertSame(200, $response->status);
        self::assertStringContainsString('Max M.', $response->body);
    }

    public function testIndexReturns500WhenServiceFails(): void
    {
        $failing = new class implements ReviewRepositoryInterface {
            public function findActive(): array
            {
                throw new \RuntimeException('db down');
            }
        };
        $controller = new ReviewController(
            new ReviewService($failing),
            $this->renderer(),
        );

        $response = $controller->index();

        self::assertSame(500, $response->status);
        self::assertStringNotContainsString('db down', $response->body);
    }
}
