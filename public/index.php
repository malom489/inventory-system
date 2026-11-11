<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if ($path === '/health') {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'time' => gmdate('c')]);
    exit;
}

http_response_code(404);
header('Content-Type: application/json');
echo json_encode(['error' => ['code' => 'NOT_FOUND', 'message' => 'Not found']]);
