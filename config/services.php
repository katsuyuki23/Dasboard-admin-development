<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'duckdb' => [
        'bin_path' => env('DUCKDB_BIN_PATH', base_path('bin/duckdb.exe')),
        'db_path' => env('DUCKDB_DATABASE', storage_path('app/analytics/panti_dw.db')),
        'cli_mode' => true, // Set to false if using PHP extension
    ],

    'twilio' => [
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'whatsapp_number' => env('TWILIO_WHATSAPP_NUMBER', 'whatsapp:+14155238886'),
    ],

    'doku' => [
        'client_id' => env('DOKU_CLIENT_ID'),
        'secret_key' => env('DOKU_SECRET_KEY'),
        'api_key' => env('DOKU_API_KEY'),
        'base_url' => env('DOKU_BASE_URL', 'https://api-sandbox.doku.com'),
        'is_production' => env('DOKU_IS_PRODUCTION', false),
        'public_key' => env('DOKU_PUBLIC_KEY'),
        'frontend_url' => env('APP_FRONTEND_URL', 'http://localhost:5173'),
        'callback_url' => env('DOKU_CALLBACK_URL', env('APP_URL') . '/api/landing/payment/callback'),
    ],

];
