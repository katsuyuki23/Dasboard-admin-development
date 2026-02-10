<?php
// Simple verification script
$info = [
    'routes_api_exists' => file_exists(__DIR__ . '/../routes/api.php'),
    'routes_api_readable' => is_readable(__DIR__ . '/../routes/api.php'),
    'bootstrap_exists' => file_exists(__DIR__ . '/../bootstrap/app.php'),
    'base_path' => __DIR__ . '/..',
    'php_version' => phpversion(),
    'laravel_env' => $_ENV['APP_ENV'] ?? 'not set',
];

header('Content-Type: application/json');
echo json_encode($info, JSON_PRETTY_PRINT);
