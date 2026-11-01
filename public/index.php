<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Controller\ReviewController;
use App\Database\Connection;
use App\Repository\SqliteReviewRepository;
use App\Service\ReviewService;
use App\View\Renderer;

$dbPath = getenv('DB_PATH') ?: __DIR__ . '/../data/rezensionen.sqlite';

try {
    $reviewController = new ReviewController(
        new ReviewService(new SqliteReviewRepository(Connection::sqlite($dbPath))),
        new Renderer(__DIR__ . '/../templates'),
    );
    $response = $reviewController->index();
} catch (Throwable $e) {
    // Bootstrapping failed (e.g. database file cannot be opened).
    error_log((string) $e);
    $response = new App\Http\Response('Die Bewertungen konnten momentan nicht geladen werden.', 500);
}

http_response_code($response->status);
echo $response->body;
