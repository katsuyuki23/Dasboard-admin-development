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

// Wrap Laravel bootstrap to catch errors
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    // Log the error to stderr (visible in Vercel logs)
    error_log('Laravel Bootstrap Error: ' . $e->getMessage());
    error_log('File: ' . $e->getFile() . ':' . $e->getLine());
    error_log('Stack trace: ' . $e->getTraceAsString());
    
    // Return JSON error for easier debugging
    header('Content-Type: application/json', true, 500);
    echo json_encode([
        'error' => 'Laravel Bootstrap Failed',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'type' => get_class($e)
    ]);
    exit(1);
}

