<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

final class PdoFactory
{
    public static function make(): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            (string) ($_ENV['DB_HOST'] ?? '127.0.0.1'),
            (int) ($_ENV['DB_PORT'] ?? 3306),
            (string) ($_ENV['DB_NAME'] ?? 'inventory'),
        );

        $pdo = new PDO(
            $dsn,
            (string) ($_ENV['DB_USER'] ?? ''),
            (string) ($_ENV['DB_PASS'] ?? ''),
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ],
        );

        $pdo->exec("SET time_zone = '+00:00'");

        return $pdo;
    }
}
