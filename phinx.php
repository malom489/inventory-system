<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

Dotenv::createImmutable(__DIR__)->safeLoad();

return [
    'paths' => [
        'migrations' => 'config/database/migrations',
        'seeds'      => 'config/database/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment'     => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host'    => $_ENV['DB_HOST'],
            'port'    => (int) $_ENV['DB_PORT'],
            'name'    => $_ENV['DB_NAME'],
            'user'    => $_ENV['DB_MIGRATION_USER'],
            'pass'    => $_ENV['DB_MIGRATION_PASS'],
            'charset' => 'utf8mb4',
        ],
    ],
];
