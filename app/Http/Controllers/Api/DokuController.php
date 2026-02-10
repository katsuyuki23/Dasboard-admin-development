<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\Donatur;
use App\Models\TransaksiKas;
use App\Services\DokuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nominal' => 'required|numeric|min:10000',
            'pesan' => 'nullable|string',
            'metode_pembayaran' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            // 1. Find or Create Donatur
            $donatur = Donatur::firstOrCreate(
                ['email' => $request->email],
                [
                    'nama' => $request->nama,
                    'no_hp' => $request->no_hp ?? '-',
                    'alamat' => '-',
                    'deskripsi' => $request->pesan
                ]
            );

            // 2. Generate unique order ID
            $orderId = 'ORD-' . time() . '-' . strtoupper(substr(md5(uniqid()), 0, 8));

            // 3. Create Donasi record with pending status
            $donasi = new Donasi();
            $donasi->id_donatur = $donatur->id_donatur;
            $donasi->type_donasi = 'DONATUR_TETAP'; // From landing page (online donors)
            $donasi->jumlah = $request->nominal;
            $donasi->tanggal_catat = now();
            $donasi->status_pembayaran = 'pending';
            $donasi->order_id = $orderId;
            $donasi->payment_method = $request->metode_pembayaran;
            $donasi->save();

            // 4. Create payment via DOKU
            $paymentResult = $this->dokuService->createPayment(
                $orderId,
                $request->nominal,
                $request->nama,
                $request->email,
                $request->metode_pembayaran
            );

            if (!$paymentResult['success']) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat pembayaran: ' . ($paymentResult['message'] ?? 'Unknown error')
                ], 500);
            }

            // 5. Update donasi with payment details
            $donasi->payment_url = $paymentResult['payment_url'];
            $donasi->payment_channel = $paymentResult['payment_channel'];
            $donasi->va_number = $paymentResult['va_number'];
            $donasi->qr_string = $paymentResult['qr_string'];
            $donasi->expired_at = $paymentResult['expired_at'];
            $donasi->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dibuat',
                'data' => [
                    'order_id' => $orderId,
                    'payment_url' => $paymentResult['payment_url'],
                    'payment_channel' => $paymentResult['payment_channel'],
                    'va_number' => $paymentResult['va_number'],
                    'qr_string' => $paymentResult['qr_string'],
                    'amount' => $request->nominal,
                    'expired_at' => $paymentResult['expired_at'],
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('DOKU Create Payment Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle DOKU payment callback
     * POST /api/landing/payment/callback
     */
    public function handleCallback(Request $request)
    {
        try {
            Log::info('DOKU Callback received', $request->all());

            // Get signature from header
            $receivedSignature = $request->header('Signature');
            
            // Verify signature
            if (!$this->dokuService->verifySignature($receivedSignature, $request->all())) {
                Log::warning('DOKU Callback: Invalid signature');
                return response()->json(['message' => 'Invalid signature'], 401);
            }

            $orderId = $request->input('order.invoice_number');
            $status = $request->input('transaction.status');
            $amount = $request->input('order.amount');

            // Find donation by order_id
            $donasi = Donasi::where('order_id', $orderId)->first();

            if (!$donasi) {
                Log::warning('DOKU Callback: Donation not found', ['order_id' => $orderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Update donation status based on DOKU status
            if ($status === 'SUCCESS' || $status === 'COMPLETED') {
                DB::beginTransaction();

                // Update donasi status
                $donasi->status_pembayaran = 'paid';
                $donasi->save();

                // Create TransaksiKas record for successful payment
                $transaksiKas = new TransaksiKas();
                $transaksiKas->tanggal = now();
                $transaksiKas->jenis_transaksi = 'masuk';
                $transaksiKas->id_kategori = 1; // Assuming 1 is for donations, adjust if needed
                $transaksiKas->nominal = $amount;
                $transaksiKas->keterangan = 'Donasi dari ' . $donasi->donatur->nama . ' via DOKU (' . $donasi->payment_channel . ')';
                $transaksiKas->save();

                // Send notification to admins
                \App\Models\User::all()->each(function($admin) use ($donasi) {
                    $admin->notify(new \App\Notifications\NewDonationNotification($donasi));
                });

                DB::commit();

                Log::info('DOKU Callback: Payment success', ['order_id' => $orderId]);
            } elseif ($status === 'FAILED' || $status === 'EXPIRED') {
                $donasi->status_pembayaran = 'failed';
                $donasi->save();
                
                Log::info('DOKU Callback: Payment failed/expired', ['order_id' => $orderId, 'status' => $status]);
            }

            return response()->json([
                'message' => 'Callback processed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('DOKU Callback Error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Callback processing failed'
            ], 500);
        }
    }

    /**
     * Check payment status
     * GET /api/landing/payment/status/{orderId}
     */
    public function checkStatus($orderId)
    {
        try {
            $donasi = Donasi::with('donatur')
                ->where('order_id', $orderId)
                ->first();

            if (!$donasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $donasi->order_id,
                    'status' => $donasi->status_pembayaran,
                    'amount' => $donasi->jumlah,
                    'payment_method' => $donasi->payment_method,
                    'payment_channel' => $donasi->payment_channel,
                    'va_number' => $donasi->va_number,
                    'qr_string' => $donasi->qr_string,
                    'customer_name' => $donasi->donatur->nama ?? 'Guest',
                    'customer_email' => $donasi->donatur->email ?? '-',
                    'created_at' => $donasi->created_at,
                    'expired_at' => $donasi->expired_at,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('DOKU Check Status Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengecek status pembayaran'
            ], 500);
        }
    }
}
