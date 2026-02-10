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
require __DIR__ . '/../public/index.php';

