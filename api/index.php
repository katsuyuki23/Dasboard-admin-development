<?php

// Ensure /tmp directories exist for Vercel serverless environment
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/storage/app',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Forward Vercel requests to public/index.php
// Fix for Vercel: Force script name to root index.php to avoid path stripping
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';

// [NEW] Debug Block
if (isset($_GET['vercel_debug'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'ok',
        'message' => 'Vercel Entry Point Reachable',
        'uri' => $_SERVER['REQUEST_URI'],
        'script' => $_SERVER['SCRIPT_NAME'],
        'method' => $_SERVER['REQUEST_METHOD']
    ]);
    exit;
}

require __DIR__ . '/../public/index.php';

