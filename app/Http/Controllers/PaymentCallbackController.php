<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Models\TransaksiKas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $privateKey = config('tripay.private_key');

        // 1. Validation Signature
        $callbackSignature = $request->header('X-Callback-Signature');
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, $privateKey);

        if ($signature !== $callbackSignature) {
            return response()->json(['success' => false, 'message' => 'Invalid Signature'], 400);
        }

        // 2. Event Handler (We only care about 'payment_status')
        if ($request->header('X-Callback-Event') !== 'payment_status') {
            return response()->json(['success' => true, 'message' => 'Event ignored']);
        }

        $data = json_decode($json);
        $merchantRef = $data->merchant_ref; // DONASI-{id}-{time}
        $status = $data->status; // PAID, EXPIRED, FAILED, REFUND

        // 3. Find Donasi
        // Extract ID from merchant_ref
        $parts = explode('-', $merchantRef);
        $idDonasi = $parts[1] ?? null;

        if (!$idDonasi) {
            return response()->json(['success' => false, 'message' => 'Invalid Merchant Ref'], 400);
        }

        $donasi = Donasi::find($idDonasi);

        if (!$donasi) {
            return response()->json(['success' => false, 'message' => 'Donasi Not Found'], 404);
        }

        // 4. Update Status
        try {
            switch ($status) {
                case 'PAID':
                    $this->markSuccess($donasi);
                    break;
                case 'EXPIRED':
                    $donasi->update(['status_pembayaran' => 'expired']);
                    break;
                case 'FAILED':
                    $donasi->update(['status_pembayaran' => 'failed']);
                    break;
                default:
                    // UNPAID or other
                    $donasi->update(['status_pembayaran' => 'pending']);
                    break;
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Tripay Callback Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function markSuccess(Donasi $donasi)
    {
        // Prevent duplicate processing
        if ($donasi->status_pembayaran === 'success') {
            return;
        }

        DB::transaction(function () use ($donasi) {
            $donasi->update(['status_pembayaran' => 'success']);

            // Create TransaksiKas entry
            TransaksiKas::create([
                'id_donasi' => $donasi->id_donasi,
                'jenis' => 'MASUK',
                'nominal' => $donasi->jumlah,
                'keterangan' => 'Donasi Online (Tripay): ' . ($donasi->donatur->nama ?? $donasi->sumber_non_donatur),
                'tanggal' => now(), 
            ]);
        });
    }
}
