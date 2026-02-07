<?php

return [
    'api_key' => env('TRIPAY_API_KEY'),
    'private_key' => env('TRIPAY_PRIVATE_KEY'),
    'merchant_code' => env('TRIPAY_MERCHANT_CODE'),
    'is_production' => env('TRIPAY_IS_PRODUCTION', false),
    'api_url' => env('TRIPAY_IS_PRODUCTION', false) 
        ? 'https://tripay.co.id/api' 
        : 'https://tripay.co.id/api-sandbox',
];
