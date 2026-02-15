<?php

namespace App\Services\Doku;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Doku\Snap\Snap;
use Doku\Snap\Models\TotalAmount\TotalAmount;
use Doku\Snap\Models\Payment\PaymentRequestDto;
use Doku\Snap\Models\Payment\PaymentAdditionalInfoRequestDto;
use Doku\Snap\Models\PaymentJumpApp\PaymentJumpAppRequestDto;
use Doku\Snap\Models\PaymentJumpApp\PaymentJumpAppAdditionalInfoRequestDto;
use Doku\Snap\Models\PaymentJumpApp\UrlParamDto;

class DokuService
{
    protected $clientId;
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->clientId = env('DOKU_CLIENT_ID');
        $this->secretKey = env('DOKU_SECRET_KEY');
        $this->baseUrl = env('DOKU_BASE_URL', 'https://api-sandbox.doku.com');
    }

    /**
     * Generate complete signature for DOKU API
     */
    public function generateSignature($requestId, $timestamp, $requestTarget, $body = null)
    {
        $digest = base64_encode(hash('sha256', $body, true));

        $signatureString = "Client-Id:" . $this->clientId . "\n" .
            "Request-Id:" . $requestId . "\n" .
            "Request-Timestamp:" . $timestamp . "\n" .
            "Request-Target:" . $requestTarget . "\n" .
            "Digest:" . $digest;

        return 'HMACSHA256=' . base64_encode(hash_hmac('sha256', $signatureString, $this->secretKey, true));
    }

    public function generateIncomingSignature($headers, $body)
    {
        $requestId = $headers['Request-Id'][0] ?? $headers['Request-Id'] ?? '';
        $timestamp = $headers['Request-Timestamp'][0] ?? $headers['Request-Timestamp'] ?? '';
        $requestTarget = $headers['Request-Target'][0] ?? $headers['Request-Target'] ?? '';

        return $this->generateSignature($requestId, $timestamp, $requestTarget, $body);
    }

    public function createPayment($donasi, $channel = null)
    {
        if ($channel) {
            if (Str::startsWith($channel, 'VIRTUAL_ACCOUNT_')) {
                $bank = str_replace('VIRTUAL_ACCOUNT_', '', $channel);
                return $this->createVaPayment($donasi, $bank);
            }
            elseif (Str::startsWith($channel, 'EMONEY_')) {
                return $this->createDokuWalletPayment($donasi, $channel . '_SNAP');
            }
        }

        return $this->createPaymentRedirect($donasi, $channel);
    }

    public function createPaymentRedirect($donasi, $originalMethod = null)
    {
        $requestId = Str::uuid()->toString();
        $timestamp = gmdate("Y-m-d\TH:i:s\Z");
        $target = '/checkout/v1/payment';

        // Ensure donatur is loaded to avoid null pointer
        if (!$donasi->donatur) {
            $donasi->load('donatur');
        }

        $body = [
            'order' => [
                'amount' => (int)$donasi->jumlah,
                'invoice_number' => $donasi->order_id,
                'currency' => 'IDR',
                'callback_url' => (env('DOKU_CALLBACK_URL') ?: url('/api/doku/callback')) . '?order_id=' . $donasi->order_id,
                'auto_redirect' => true,
                'return_url' => (env('DOKU_RETURN_URL') ?: url('/success')) . '?order_id=' . $donasi->order_id,
                'line_items' => [
                    [
                        'name' => 'Donasi Panti Asuhan Assholihin',
                        'price' => (int)$donasi->jumlah,
                        'quantity' => 1
                    ]
                ]
            ],
            'payment' => [
                'payment_due_date' => 60 // 60 minutes
            ],
            'customer' => [
                'name' => $donasi->donatur->nama ?? 'Donatur',
                'email' => $donasi->donatur->email ?? '-'
            ]
        ];

        $jsonBody = json_encode($body);
        $signature = $this->generateSignature($requestId, $timestamp, $target, $jsonBody);

        try {
            // SOLUTION 1: Skip SSL verification for sandbox/development
            // This fixes "cURL error 35: Connection was reset" issues with DOKU sandbox
            $httpOptions = [];

            // Only skip SSL verification in development OR when using sandbox URL
            $isSandbox = str_contains($this->baseUrl, 'sandbox');
            $isLocal = app()->environment('local', 'development');

            if ($isSandbox || $isLocal) {
                $httpOptions['verify'] = false;
                Log::info('DOKU API: SSL verification disabled for sandbox/development');
            }

            $response = Http::withOptions($httpOptions)
                ->timeout(60) // Increase timeout to 60 seconds
                ->withHeaders([
                'Client-Id' => $this->clientId,
                'Request-Id' => $requestId,
                'Request-Timestamp' => $timestamp,
                'Signature' => $signature,
                'Content-Type' => 'application/json',
            ])->withBody($jsonBody, 'application/json')
                ->post($this->baseUrl . $target);

            if ($response->successful()) {
                $data = $response->json();

                // Extract payment channel from response or use original method
                $paymentChannel = $originalMethod ?? 'DOKU_CHECKOUT';

                // Try to get more specific channel from DOKU response if available
                if (isset($data['response']['payment']['payment_method'])) {
                    $paymentChannel = $data['response']['payment']['payment_method'];
                }

                return [
                    'success' => true,
                    'payment_url' => $data['response']['payment']['url'] ?? null,
                    'payment_channel' => $paymentChannel,
                    'expired_at' => now()->addMinutes(60)->toDateTimeString(),
                    'raw' => $data
                ];
            }
            else {
                Log::error('DOKU Redirect Payment Failed', ['response' => $response->body()]);
                return ['success' => false, 'message' => 'DOKU API Error'];
            }
        }
        catch (\Exception $e) {
            Log::error('DOKU Connection Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function createDokuWalletPayment($donasi, $channel = 'EMONEY_DANA_SNAP')
    {
        $privateKeyPath = env('DOKU_MERCHANT_PRIVATE_KEY_PATH');
        $publicKeyPath = env('DOKU_MERCHANT_PUBLIC_KEY_PATH');
        $dokuPublicKeyPath = env('DOKU_GATEWAY_PUBLIC_KEY_PATH');

        $privateKey = file_exists(base_path($privateKeyPath)) ? file_get_contents(base_path($privateKeyPath)) : null;
        $publicKey = file_exists(base_path($publicKeyPath)) ? file_get_contents(base_path($publicKeyPath)) : null;
        $dokuPublicKey = file_exists(base_path($dokuPublicKeyPath)) ? file_get_contents(base_path($dokuPublicKeyPath)) : null;

        $isProduction = env('DOKU_IS_PRODUCTION', false);

        if (!$privateKey) {
            Log::error('DOKU Private Key not found at ' . $privateKeyPath);
            return null;
        }

        try {
            $snap = new Snap(
                $privateKey,
                $publicKey,
                $dokuPublicKey,
                $this->clientId,
                'DOKU',
                $isProduction,
                $this->secretKey,
                null
                );

            $invoiceNumber = $donasi->order_id;
            $amount = new TotalAmount(number_format($donasi->jumlah, 2, '.', ''), 'IDR');

            if (in_array($channel, ['EMONEY_DANA_SNAP', 'EMONEY_SHOPEE_PAY_SNAP'])) {
                $additionalInfo = new PaymentJumpAppAdditionalInfoRequestDto(
                    $channel,
                    'Donasi Panti Asuhan',
                    null,
                    null,
                    null
                    );

                $urlParams = [
                    new UrlParamDto(config('app.url') . '/success', 'PAY_RETURN', 'N')
                ];

                $requestBody = new PaymentJumpAppRequestDto(
                    $invoiceNumber,
                    date('c', strtotime('+60 minutes')),
                    'mweb',
                    $urlParams,
                    $amount,
                    $additionalInfo
                    );

                $ip = request()->ip();
                $deviceId = request()->header('User-Agent') ?? 'Unknown';

                $response = $snap->doPaymentJumpApp($requestBody, $deviceId, $ip);
                return $this->formatResponse($response, 'REDIRECT');
            }
            else {
                $additionalInfo = new PaymentAdditionalInfoRequestDto(
                    $channel,
                    'Donasi Panti Asuhan',
                    config('app.url') . '/success',
                    config('app.url') . '/failed',
                [
                    [
                        'name' => 'Donasi',
                        'price' => number_format($donasi->jumlah, 2, '.', ''),
                        'quantity' => 1
                    ]
                ],
                    'SALE'
                    );

                $request = new PaymentRequestDto(
                    $invoiceNumber,
                    $amount,
                    null,
                    $additionalInfo,
                    null,
                    null
                    );

                $response = $snap->doPayment($request, null, request()->ip());
                return $this->formatResponse($response, 'VA');
            }

        }
        catch (\Exception $e) {
            Log::error("Doku Library Error: " . $e->getMessage());
            return null;
        }
    }

    public function createVaPayment($donasi, $bank = 'BCA')
    {
        $privateKeyPath = env('DOKU_MERCHANT_PRIVATE_KEY_PATH');
        $publicKeyPath = env('DOKU_MERCHANT_PUBLIC_KEY_PATH');
        $dokuPublicKeyPath = env('DOKU_GATEWAY_PUBLIC_KEY_PATH');

        $privateKey = file_exists(base_path($privateKeyPath)) ? file_get_contents(base_path($privateKeyPath)) : null;
        $publicKey = file_exists(base_path($publicKeyPath)) ? file_get_contents(base_path($publicKeyPath)) : null;
        $dokuPublicKey = file_exists(base_path($dokuPublicKeyPath)) ? file_get_contents(base_path($dokuPublicKeyPath)) : null;

        $isProduction = env('DOKU_IS_PRODUCTION', false);

        if (!$privateKey) {
            Log::error('DOKU Private Key not found at ' . $privateKeyPath);
            return null;
        }

        try {
            $snap = new Snap(
                $privateKey,
                $publicKey,
                $dokuPublicKey,
                $this->clientId,
                'DOKU',
                $isProduction,
                $this->secretKey
                );

            $invoiceNumber = $donasi->order_id;
            $amount = new TotalAmount(number_format($donasi->jumlah, 2, '.', ''), 'IDR');

            $channel = 'VIRTUAL_ACCOUNT_' . strtoupper($bank);

            $additionalInfo = new PaymentAdditionalInfoRequestDto(
                $channel,
                'Donasi Panti Asuhan',
                null,
                null,
            [
                [
                    'name' => 'Donasi Panti Asuhan',
                    'price' => number_format($donasi->jumlah, 2, '.', ''),
                    'quantity' => 1
                ]
            ],
                'SALE'
                );

            $request = new PaymentRequestDto(
                $invoiceNumber,
                $amount,
                null,
                $additionalInfo,
                null,
                null
                );

            $response = $snap->doPayment($request, null, request()->ip());
            return $this->formatResponse($response, 'VA');

        }
        catch (\Exception $e) {
            Log::error("Doku Library Error (VA): " . $e->getMessage());
            return null;
        }
    }

    protected function formatResponse($response, $type)
    {
        if (!$response)
            return null;

        $result = [
            'success' => true,
            'raw' => $response
        ];

        if ($type === 'VA') {
            $result['va_number'] = $response['virtualAccountData']['virtualAccountNumber'] ?? null;
            $result['payment_url'] = $response['virtualAccountData']['howToPayPage'] ?? null;
            $result['expired_at'] = $response['virtualAccountData']['expiredDate'] ?? null;
            $result['payment_channel'] = $response['virtualAccountData']['paymentChannel'] ?? null;
        }
        else {
            $result['payment_url'] = $response['paymentPageUrl'] ?? null;
            $result['expired_at'] = now()->addHour()->toDateTimeString();
        }

        return $result;
    }

    /**
     * Get transaction status from DOKU
     */
    public function getStatus($orderId)
    {
        $requestId = Str::uuid()->toString();
        $timestamp = gmdate("Y-m-d\TH:i:s\Z");
        $target = '/merchants/v1/payment-inquiry/' . $orderId;

        $signature = $this->generateSignature($requestId, $timestamp, $target, "");

        try {
            Log::info('DOKU Get Status Request', ['order_id' => $orderId, 'url' => $this->baseUrl . $target]);

            $response = Http::withHeaders([
                'Client-Id' => $this->clientId,
                'Request-Id' => $requestId,
                'Request-Timestamp' => $timestamp,
                'Signature' => $signature,
            ])->get($this->baseUrl . $target);

            if ($response->successful()) {
                Log::info('DOKU Get Status Success', ['order_id' => $orderId, 'status' => $response->status()]);
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }
            else {
                Log::error('DOKU Get Status Failed', [
                    'order_id' => $orderId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [
                    'success' => false,
                    'message' => 'DOKU API Error: ' . ($response->json()['message'] ?? 'Unknown error'),
                    'raw' => $response->json()
                ];
            }
        }
        catch (\Exception $e) {
            Log::error('DOKU Connection Error (Status): ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
