<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Review;
use App\Model\ReviewImage;
use PDO;

final class SqliteReviewRepository implements ReviewRepositoryInterface
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findActive(): array
    {
        $rows = $this->pdo->query(
            'SELECT r_id, anzahl_sterne, name, text, bestelldatum
               FROM rezensionen
              WHERE active = 1
              ORDER BY bestelldatum DESC, r_id DESC'
        )->fetchAll();

        if ($rows === []) {
            return [];
        }

        $imagesByReview = $this->loadImages(array_map(static fn (array $r): int => (int) $r['r_id'], $rows));

        return array_map(
            static fn (array $r): Review => new Review(
                id: (int) $r['r_id'],
                stars: $r['anzahl_sterne'] !== null ? (int) $r['anzahl_sterne'] : null,
                name: $r['name'],
                text: $r['text'],
                orderDate: $r['bestelldatum'],
                images: $imagesByReview[(int) $r['r_id']] ?? [],
            ),
            $rows
        );
    }

    /**
     * @param list<int> $reviewIds
     * @return array<int, list<ReviewImage>>
     */
    private function loadImages(array $reviewIds): array
    {
        $placeholders = implode(',', array_fill(0, count($reviewIds), '?'));
        $stmt = $this->pdo->prepare(
            "SELECT r_id, grafik, alt_attribut
               FROM rezensionen_grafiken
              WHERE r_id IN ($placeholders)
                AND grafik IS NOT NULL AND TRIM(grafik) <> ''
              ORDER BY r_id, position"
        );
        $stmt->execute($reviewIds);

        $grouped = [];
        foreach ($stmt->fetchAll() as $row) {
            $grouped[(int) $row['r_id']][] = new ReviewImage(trim($row['grafik']), (string) ($row['alt_attribut'] ?? ''));
        }

        return $grouped;
    }
}
