<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- START VERIFICATION ---\n";

// Test PredictionService
echo "\nTesting PredictionService::predictNextMonthDonations...\n";
try {
    $service = new \App\Services\PredictionService();
    $result = $service->predictNextMonthDonations();
    if ($result) {
        echo "Success! Prediction: " . $result['predicted_amount'] . " (Confidence: " . $result['confidence'] . "%)\n";
    } else {
        echo "Result is null (possibly insufficient data)\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

// Test DonorAnalyticsService (Churn Risk)
echo "\nTesting DonorAnalyticsService::detectChurnRisk...\n";
try {
    $service = new \App\Services\DonorAnalyticsService();
    $risks = $service->detectChurnRisk();
    echo "Success! Found " . count($risks) . " donors at risk.\n";
    if (count($risks) > 0) {
        print_r($risks[0]); // Show sample
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

// Test DonorAnalyticsService (Segment Donors)
echo "\nTesting DonorAnalyticsService::segmentDonors...\n";
try {
    $service = new \App\Services\DonorAnalyticsService();
    $segments = $service->segmentDonors();
    echo "Success! Segments: \n";
    print_r($segments['segments']);
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n--- END VERIFICATION ---\n";
