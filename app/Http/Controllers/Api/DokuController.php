<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\Donatur;
use App\Models\TransaksiKas;
use App\Services\Doku\DokuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DokuController extends Controller
{
    protected $dokuService;

    public function __construct(DokuService $dokuService)
    {
        $this->dokuService = $dokuService;
    }

    /**
     * Create DOKU payment
     * POST /api/landing/payment/create
     */
    public function createPayment(Request $request)
    {
        Log::info('Create Donation Payment Hit', $request->all());

        // Support both field name styles
        $donor_name = $request->input('donor_name') ?? $request->input('nama');
        $donor_email = $request->input('donor_email') ?? $request->input('email');
        $donor_phone = $request->input('no_wa') ?? $request->input('no_hp');
        $amount = $request->input('amount') ?? $request->input('nominal');
        $payment_method = $request->input('payment_method') ?? $request->input('method');

        // Manual validation if needed, but sticking to Laravel's request injection for now
        // If the fields are missing from $request->all(), let's check $request->input()

        $request->validate([
            'donor_name' => 'required_without:nama|string|max:255',
            'donor_email' => 'required_without:email|email|max:255',
            'amount' => 'required_without:nominal|numeric|min:10000',
        ]);

        try {
            DB::beginTransaction();

            // 1. Find or Create/Update Donatur
            // We use explicit find/update to ensure it actually hits the DB and we can log it
            $donatur = Donatur::where('email', $donor_email)->first();

            if ($donatur) {
                Log::info('Found existing donatur, updating name', [
                    'id' => $donatur->id_donatur,
                    'old_name' => $donatur->nama,
                    'new_name' => $donor_name
                ]);
                $donatur->nama = $donor_name;
                $donatur->no_hp = $donor_phone ?? $donatur->no_hp;
                $donatur->save();
            }
            else {
                Log::info('Creating new donatur', ['name' => $donor_name, 'email' => $donor_email]);
                $donatur = Donatur::create([
                    'nama' => $donor_name,
                    'email' => $donor_email,
                    'no_hp' => $donor_phone ?? '-',
                    'alamat' => '-',
                ]);
            }

            // 2. Generate unique order ID
            $orderId = 'DON-' . time() . '-' . Str::random(5);

            // 3. Create Donasi record with pending status
            $donasi = new Donasi();
            $donasi->id_donatur = $donatur->id_donatur;
            $donasi->type_donasi = 'DONATUR_TETAP';
            $donasi->jumlah = $amount;
            $donasi->tanggal_catat = now();
            $donasi->status_pembayaran = 'pending';
            $donasi->order_id = $orderId;
            $donasi->payment_method = $payment_method;
            $donasi->save();

            // 4. Create payment via DOKU
            $paymentResult = $this->dokuService->createPayment($donasi, $payment_method);

            if (!$paymentResult || !$paymentResult['success']) {
                DB::rollback();
                Log::error('DOKU Payment Creation Failed', ['result' => $paymentResult]);
                return response()->json([
                    'status' => 'error',
                    'success' => false,
                    'message' => 'Gagal membuat pembayaran di Gateway'
                ], 500);
            }

            // 5. Update donasi with payment details
            $donasi->payment_url = $paymentResult['payment_url'];
            $donasi->payment_channel = $paymentResult['payment_channel'] ?? $payment_method;
            $donasi->va_number = $paymentResult['va_number'] ?? null;
            $donasi->expired_at = $paymentResult['expired_at'];
            $donasi->payment_info = $paymentResult['raw'];
            $donasi->save();

            DB::commit();

            $responsePayload = [
                'status' => 'success',
                'success' => true,
                'message' => 'Donation created successfully',
                'invoice_number' => $orderId,
                'payment_url' => $donasi->payment_url,
                'payment_type' => $donasi->va_number ? 'VA' : 'REDIRECT',
                'data' => [
                    'order_id' => $orderId,
                    'payment_url' => $donasi->payment_url,
                    'va_number' => $donasi->va_number,
                ]
            ];

            return response()->json($responsePayload);

        }
        catch (\Exception $e) {
            DB::rollback();
            Log::error('Create Payment Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check payment status
     * GET /api/landing/payment/status/{orderId}
     */
    public function checkStatus($orderId)
    {
        Log::info('DOKU Check Status Request', ['order_id' => $orderId]);

        $donasi = Donasi::where('order_id', $orderId)->with('donatur')->first();

        // Fallback: Check if orderId matches any part of payment_info or if it's slightly different
        if (!$donasi) {
            Log::warning('Donation not found by direct order_id', ['order_id' => $orderId]);
            $donasi = Donasi::where('order_id', 'LIKE', '%' . $orderId . '%')->with('donatur')->first();
        }

        if (!$donasi) {
            return response()->json(['success' => false, 'message' => 'Donation not found'], 404);
        }

        // If status is still pending, attempt a real-time sync with DOKU
        if ($donasi->status_pembayaran === 'pending') {
            Log::info('Status is pending, attempting sync with DOKU API', ['order_id' => $donasi->order_id]);
            $this->syncStatus($donasi->order_id);
            // Refresh model to get updated status
            $donasi->refresh();
        }

        Log::info('Donation found for status check', ['id' => $donasi->id_donasi, 'status' => $donasi->status_pembayaran]);

        return response()->json([
            'success' => true,
            'data' => [
                'order_id' => $donasi->order_id,
                'amount' => $donasi->jumlah,
                'customer_name' => $donasi->donatur->nama ?? 'Donatur',
                'payment_channel' => $donasi->payment_channel,
                'status' => $donasi->status_pembayaran === 'paid' ? 'success' : $donasi->status_pembayaran,
                'response_code' => $donasi->payment_info['transaction']['response_code'] ?? null,
                'created_at' => $donasi->created_at->toIso8601String()
            ]
        ]);
    }

    /**
     * Synchronize donation status with DOKU API
     */
    public function syncStatus($orderId)
    {
        $donasi = Donasi::where('order_id', $orderId)->first();
        if (!$donasi)
            return false;

        $response = $this->dokuService->getStatus($orderId);

        if ($response['success']) {
            $payload = $response['data'];
            $dokuStatus = $payload['transaction']['status'] ?? null;
            $responseCode = (string)($payload['transaction']['response_code'] ?? $payload['transaction']['responseCode'] ?? '');

            $internalStatus = strtolower($dokuStatus ?? 'pending');

            if ($responseCode === '0000' || strtolower($dokuStatus) === 'success') {
                $internalStatus = 'paid';
            }
            elseif (in_array($responseCode, ['5586', '5587'])) {
                $internalStatus = 'pending';
            }
            elseif (in_array($responseCode, ['U102', 'IT03', '5585'])) {
                $internalStatus = 'expired';
            }
            elseif ($responseCode && !in_array($responseCode, ['0000', '5586', '5587'])) {
                $internalStatus = 'failed';
            }

            if ($internalStatus === 'paid' && $donasi->status_pembayaran !== 'paid') {
                DB::beginTransaction();
                try {
                    $donasi->status_pembayaran = 'paid';
                    $donasi->payment_info = array_merge(is_array($donasi->payment_info) ? $donasi->payment_info : [], is_array($payload) ? $payload : []);
                    $donasi->save();

                    // Create TransaksiKas if needed
                    if (!$donasi->transaksiKas()->exists()) {
                        $transaksiKas = new TransaksiKas();
                        $transaksiKas->tanggal = now();
                        $transaksiKas->jenis_transaksi = 'masuk';
                        $transaksiKas->id_kategori = 1;
                        $transaksiKas->nominal = $donasi->jumlah;
                        $transaksiKas->keterangan = 'Donasi via DOKU Sync: ' . $orderId;
                        $transaksiKas->id_donasi = $donasi->id_donasi;
                        $kas = \App\Models\Kas::first();
                        $transaksiKas->id_kas = $kas ? $kas->id_kas : 1;
                        $transaksiKas->save();
                    }

                    DB::commit();
                    \App\Jobs\PaymentNotificationJob::dispatch($donasi);
                    Log::info('DOKU Sync: Transaction marked as PAID', ['order_id' => $orderId]);
                }
                catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('DOKU Sync: Error updating database', ['error' => $e->getMessage()]);
                }
            }
            else {
                $donasi->status_pembayaran = $internalStatus;
                $donasi->payment_info = array_merge(is_array($donasi->payment_info) ? $donasi->payment_info : [], is_array($payload) ? $payload : []);
                $donasi->save();
            }
            return true;
        }
        return false;
    }

    /**
     * Handle DOKU callback
     * POST/GET /api/doku/callback
     */
    public function handleCallback(Request $request)
    {
        $uri = $request->getRequestUri();
        Log::info('DOKU Callback Hit [' . $uri . ']', $request->all());

        if ($request->isMethod('get')) {
            $orderId = $request->query('order_id') ?? $request->query('invoice_number') ?? $request->query('invoiceNumber') ?? $request->query('TRANSID');
            Log::info('Callback is GET, redirecting to React success page', [
                'full_url' => $request->fullUrl(),
                'order_id' => $orderId
            ]);

            $url = (env('DOKU_RETURN_URL') ?: url('/success'));
            if ($orderId) {
                $separator = str_contains($url, '?') ? '&' : '?';
                $url .= $separator . 'order_id=' . $orderId;
            }

            return redirect($url);
        }

        try {
            $headers = $request->headers->all();
            $body = $request->getContent();

            Log::info('DOKU SNAP Callback received', [
                'headers' => $headers,
                'body' => $body
            ]);

            // Validate Signature
            $incomingSignature = $request->header('Signature');
            $calculatedSignature = $this->dokuService->generateIncomingSignature($headers, $body);

            if ($incomingSignature !== $calculatedSignature) {
                Log::warning('DOKU SNAP Callback: Invalid signature', [
                    'received' => $incomingSignature,
                    'calculated' => $calculatedSignature
                ]);
                return response()->json(['message' => 'Invalid signature'], 401);
            }

            $payload = json_decode($body, true);
            Log::info('DOKU SNAP Callback Payload Parsed', ['payload' => $payload]);

            $orderId = $payload['order']['invoice_number'] ?? null;
            $amount = $payload['order']['amount'] ?? 0;
            $dokuStatus = $payload['transaction']['status'] ?? null;
            $responseCode = (string)($payload['transaction']['response_code'] ?? $payload['transaction']['responseCode'] ?? '');

            if (!$orderId) {
                Log::error('DOKU SNAP Callback: Invoice number missing from payload');
                return response()->json(['message' => 'Invoice number not found'], 400);
            }

            $donasi = Donasi::where('order_id', $orderId)->first();

            if (!$donasi) {
                Log::warning('DOKU SNAP Callback: Donation not found', ['order_id' => $orderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Map DOKU Response Codes to internal status
            // Reference: https://developers.doku.com/flexibill/doku-biller/response-code
            $internalStatus = strtolower($dokuStatus ?? 'pending');

            // 0000 is SUCCESS, 5586 is PENDING/PROCESS
            if ($responseCode === '0000' || strtolower($dokuStatus) === 'success') {
                $internalStatus = 'paid';
            }
            elseif (in_array($responseCode, ['5586', '5587'])) {
                $internalStatus = 'pending';
            }
            elseif (in_array($responseCode, ['U102', 'IT03', '5585'])) {
                $internalStatus = 'expired';
            }
            elseif ($responseCode && !in_array($responseCode, ['0000', '5586', '5587'])) {
                $internalStatus = 'failed';
            }

            Log::info('DOKU SNAP Callback processing', [
                'order_id' => $orderId,
                'doku_status' => $dokuStatus,
                'response_code' => $responseCode,
                'mapped_status' => $internalStatus
            ]);

            if ($internalStatus === 'paid') {
                DB::beginTransaction();

                if ($donasi->status_pembayaran !== 'paid') {
                    // Extract actual payment channel from DOKU callback
                    $actualPaymentChannel = $payload['payment']['channel']
                        ?? $payload['virtual_account_info']['channel']
                        ?? $payload['transaction']['channel']
                        ?? $donasi->payment_channel
                        ?? 'DOKU';

                    $donasi->status_pembayaran = 'paid';
                    $donasi->payment_info = array_merge($donasi->payment_info ?? [], $payload);

                    // Update payment channel with actual method used
                    if ($actualPaymentChannel && $actualPaymentChannel !== 'DOKU_CHECKOUT') {
                        $donasi->payment_channel = $actualPaymentChannel;
                    }

                    $donasi->save();

                    // Create TransaksiKas record
                    $transaksiKas = new TransaksiKas();
                    $transaksiKas->tanggal = now();
                    $transaksiKas->jenis_transaksi = 'masuk';
                    $transaksiKas->id_kategori = 1; // Donation category
                    $transaksiKas->nominal = $amount;
                    $transaksiKas->keterangan = 'Donasi via DOKU SNAP: ' . $orderId . ' (' . ($donasi->donatur->nama ?? 'Hamba Allah') . ')';
                    $transaksiKas->id_donasi = $donasi->id_donasi;
                    // Add default kas if exists
                    $kas = \App\Models\Kas::first();
                    if ($kas) {
                        $transaksiKas->id_kas = $kas->id_kas;
                    }
                    $transaksiKas->save();

                    DB::commit();

                    // Dispatch notifications to background job for faster response
                    try {
                        \App\Jobs\PaymentNotificationJob::dispatch($donasi);
                        Log::info('PaymentNotificationJob dispatched', ['order_id' => $orderId]);
                    }
                    catch (\Exception $ne) {
                        Log::error('Failed to dispatch PaymentNotificationJob: ' . $ne->getMessage());
                    }
                }
                else {
                    DB::commit();
                    Log::info('DOKU SNAP Callback: Transaction already paid', ['order_id' => $orderId]);
                }

                Log::info('DOKU SNAP Callback: Payment success processed', ['order_id' => $orderId, 'code' => $responseCode]);
            }
            else {
                $donasi->status_pembayaran = $internalStatus;
                $donasi->payment_info = array_merge($donasi->payment_info ?? [], $payload);
                $donasi->save();
                Log::info('DOKU SNAP Callback: Payment status updated', ['order_id' => $orderId, 'status' => $internalStatus, 'code' => $responseCode]);
            }

            return response()->json(['message' => 'Callback processed successfully']);

        }
        catch (\Exception $e) {
            Log::error('DOKU SNAP Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Callback processing failed'], 500);
        }
    }
}
