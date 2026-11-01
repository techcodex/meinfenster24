<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Repository\SqliteReviewRepository;
use PDO;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestDatabase;

final class SqliteReviewRepositoryTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = TestDatabase::create();
        $this->pdo->exec("INSERT INTO rezensionen (r_id, active, anzahl_sterne, name, text, bestelldatum) VALUES
            (1, 1, 5, 'Alt Kunde', 'alt', '2026-01'),
            (2, 1, 4, 'Neu Kunde', 'neu', '2026-05-20'),
            (3, 0, 2, 'Inaktiv Kunde', 'versteckt', '2026-07-08'),
            (4, 1, 5, 'Ohne Bild', NULL, '2026-03')");
        $this->pdo->exec("INSERT INTO rezensionen_grafiken (r_id, grafik, alt_attribut, position) VALUES
            (2, 'https://x/2b.jpg', 'zweites', 2),
            (2, 'https://x/2a.jpg', 'erstes', 1),
            (4, NULL, 'leer', 1),
            (3, 'https://x/3.jpg', 'inaktiv', 1)");
    }

    public function testExcludesInactiveReviews(): void
    {
        $ids = array_map(static fn ($r) => $r->id, (new SqliteReviewRepository($this->pdo))->findActive());

        self::assertNotContains(3, $ids);
        self::assertCount(3, $ids);
    }

    public function testNewestFirst(): void
    {
        $ids = array_map(static fn ($r) => $r->id, (new SqliteReviewRepository($this->pdo))->findActive());

        self::assertSame([2, 4, 1], $ids);
    }

    public function testImagesAreOrderedByPosition(): void
    {
        $review = (new SqliteReviewRepository($this->pdo))->findActive()[0];

        self::assertSame(['https://x/2a.jpg', 'https://x/2b.jpg'], array_map(static fn ($i) => $i->url, $review->images));
        self::assertSame('erstes', $review->images[0]->alt);
    }

    public function testNullImageUrlIsSkippedAndReviewStillReturned(): void
    {
        $review = (new SqliteReviewRepository($this->pdo))->findActive()[1];

        self::assertSame(4, $review->id);
        self::assertSame([], $review->images);
        self::assertNull($review->text);
    }

    public function testReviewWithoutImageRowsHasEmptyImageList(): void
    {
        $review = (new SqliteReviewRepository($this->pdo))->findActive()[2];

        self::assertSame([], $review->images);
    }

    public function testReturnsEmptyListWhenNoReviews(): void
    {
        $this->pdo->exec('DELETE FROM rezensionen');

        self::assertSame([], (new SqliteReviewRepository($this->pdo))->findActive());
    }

    public function testWorksAgainstDeliveredDatabase(): void
    {
        $real = \App\Database\Connection::sqlite(__DIR__ . '/../../data/rezensionen.sqlite');
        $reviews = (new SqliteReviewRepository($real))->findActive();

        self::assertCount(6, $reviews); // 7 rows, 1 inactive
        self::assertNotContains(9, array_map(static fn ($r) => $r->id, $reviews));
    }
}
