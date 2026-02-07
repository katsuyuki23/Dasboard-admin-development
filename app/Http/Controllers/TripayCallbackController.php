<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Services\TripayService;
use Illuminate\Support\Facades\Log;

class TripayCallbackController extends Controller
{
    public function handle(Request $request, TripayService $tripayService)
    {
        // 1. Get Callback Data
        $data = $request->all();
        
        // 2. Validate Signature
        // Tripay sends JSON body, so we use $request->getContent() or just access parsed JSON from $request unless validasi signature raw needed.
        // However, standard Laravel Request handles JSON auto decoding.
        // Signature logic: HMAC_SHA256(merchant_code + merchant_ref + amount)
        
        $merchantRef = $request->input('merchant_ref');
        $amount = $request->input('total_amount'); // Tripay uses 'total_amount' in callback
        $status = $request->input('status');
        $signature = $request->input('signature');

        // Allow 'test' callback during development if needed, but Tripay usually sends real signature even in sandbox.
        
        $localSignature = $tripayService->createSignature($amount, $merchantRef);

        if ($signature !== $localSignature) {
            Log::warning('Tripay Callback Invalid Signature', [
                'incoming' => $signature,
                'calculated' => $localSignature,
                'data' => $data
            ]);
            return response()->json(['success' => false, 'message' => 'Invalid Signature'], 400);
        }

        // 3. Find Transaction (Donasi)
        // Format merchant_ref: DONASI-{id}-{timestamp}
        if (!str_contains($merchantRef, 'DONASI-')) {
            return response()->json(['success' => false, 'message' => 'Invalid Merchant Ref Format'], 400);
        }

        $parts = explode('-', $merchantRef);
        $donasiId = $parts[1] ?? null;
        
        $donasi = Donasi::find($donasiId);

        if (!$donasi) {
             return response()->json(['success' => false, 'message' => 'Invoice not found'], 404);
        }

        // 4. Update Status based on Callback Status
        // Callback status: UNPAID, PAID, EXPIRED, FAILED, REFUND
        
        switch ($status) {
            case 'PAID':
                $donasi->update(['status_pembayaran' => 'success']);
                break;
                
            case 'EXPIRED':
            case 'FAILED':
            case 'REFUND':
                $donasi->update(['status_pembayaran' => 'failed']);
                break;
            
            default:
                // UNPAID or others, ignore or set pending
                // $donasi->update(['status_pembayaran' => 'pending']);
                break;
        }

        return response()->json(['success' => true]);
    }
}
