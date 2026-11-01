<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Database\Connection;
use PDO;

/** In-memory SQLite with the same schema as the delivered rezensionen.sqlite. */
final class TestDatabase
{
    public static function create(): PDO
    {
        $pdo = Connection::sqlite(':memory:');
        $pdo->exec('CREATE TABLE rezensionen (
            r_id INTEGER NOT NULL PRIMARY KEY, active INTEGER NOT NULL DEFAULT 1, anzahl_sterne INTEGER DEFAULT NULL,
            name TEXT, text TEXT, bestelldatum TEXT, u_id INTEGER NOT NULL DEFAULT 0)');
        $pdo->exec('CREATE TABLE rezensionen_grafiken (
            id INTEGER PRIMARY KEY AUTOINCREMENT, r_id INTEGER NOT NULL REFERENCES rezensionen(r_id) ON DELETE CASCADE,
            grafik TEXT, alt_attribut TEXT, position INTEGER NOT NULL)');
        $pdo->exec('CREATE UNIQUE INDEX pos ON rezensionen_grafiken (r_id, position)');

        return $pdo;
    }
}
