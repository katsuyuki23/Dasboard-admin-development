<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$orderId = 'DON-1771087993-jU5z0';
$updated = DB::table('donasi')->where('order_id', $orderId)->update(['status_pembayaran' => 'paid']);

if ($updated) {
    echo "Successfully updated $orderId to paid.\n";
}
else {
    echo "Failed to update $orderId or already paid.\n";
}
