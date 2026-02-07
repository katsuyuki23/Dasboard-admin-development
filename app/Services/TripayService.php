<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TripayService
{
    protected $apiKey;
    protected $privateKey;
    protected $merchantCode;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('tripay.api_key');
        $this->privateKey = config('tripay.private_key');
        $this->merchantCode = config('tripay.merchant_code');
        $this->baseUrl = config('tripay.api_url');
    }

    /**
     * Get active payment channels
     */
    public function getPaymentChannels()
    {
        try {
            $url = $this->baseUrl . '/merchant/payment-channel';
            Log::info('Tripay Requesting Channels: ' . $url . ' | Key: ' . substr($this->apiKey, 0, 5) . '...');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($url);

            Log::info('Tripay Response Status: ' . $response->status());
            
            if ($response->successful()) {
                return $response->json()['data'];
            }

            Log::error('Tripay Error (Get Channels): ' . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error('Tripay Exception: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Request a new transaction
     */
    public function requestTransaction($method, $amount, $customerDetails, $orderItems, $merchantRef)
    {
        $payload = [
            'method'         => $method, // e.g., 'BRIVA', 'QRIS'
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $customerDetails['nama'],
            'customer_email' => $customerDetails['email'],
            'customer_phone' => $customerDetails['telepon'] ?? '08123456789',
            'order_items'    => $orderItems,
            'return_url'     => route('public.donasi.success', ['id' => $merchantRef]), // Redirect after payment
            'expired_time'   => (time() + (24 * 60 * 60)), // 24 hours expiry
            'signature'      => $this->createSignature($amount, $merchantRef)
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post($this->baseUrl . '/transaction/create', $payload);

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Tripay Transaction Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Generate Signature for Transaction Request & Callback Validation
     * HMAC-SHA256 (merchant_code + merchant_ref + amount) using private_key
     */
    public function createSignature($amount, $merchantRef)
    {
        return hash_hmac('sha256', $this->merchantCode . $merchantRef . $amount, $this->privateKey);
    }
    
    /**
     * Get Detailed Transaction
     */
    public function getTransactionDetail($reference)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/transaction/detail?reference=' . $reference);

            return $response->json();
        } catch (\Exception $e) {
             Log::error('Tripay Detail Error: ' . $e->getMessage());
             return null;
        }
    }
}
