<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Database\Connection;
use App\Repository\SqliteReviewRepository;
use App\Service\ReviewService;
use App\View\Renderer;

$dbPath = getenv('DB_PATH') ?: __DIR__ . '/../data/rezensionen.sqlite';

try {
    $reviewService = new ReviewService(new SqliteReviewRepository(Connection::sqlite($dbPath)));
    $page = $reviewService->getPage();
    $renderer = new Renderer(__DIR__ . '/../templates');
    echo $renderer->render('reviews', ['page' => $page, 'renderer' => $renderer]);
} catch (Throwable $e) {
    error_log((string) $e);
    http_response_code(500);
    echo 'Die Bewertungen konnten momentan nicht geladen werden.';
}
