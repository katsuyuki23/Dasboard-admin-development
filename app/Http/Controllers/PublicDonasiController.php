<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donasi;
use Illuminate\Support\Facades\DB;
use App\Models\Pesan;
use App\Services\TripayService;

class PublicDonasiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Fetch Recent Donasi (Success & Pending)
        $recentDonasi = Donasi::with('donatur')
            ->whereIn('status_pembayaran', ['success', 'pending'])
            ->latest('tanggal_catat')
            ->paginate(10);

        // 2. Chart Data (Monthly for current year)
        $year = $request->input('year', date('Y'));
        
        $chartDataRaw = Donasi::selectRaw('MONTH(tanggal_catat) as month, SUM(jumlah) as total')
            ->whereIn('status_pembayaran', ['success', 'pending'])
            ->whereYear('tanggal_catat', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $chartData = array_fill(1, 12, 0); // Initialize 1-12 with 0
        foreach ($chartDataRaw as $data) {
            $chartData[$data->month] = $data->total;
        }

        // 3. Testimonials & Forum Messages
        $donaturWithDesc = \App\Models\Donatur::whereNotNull('deskripsi')
            ->where('deskripsi', '!=', '')
            ->select('nama', 'deskripsi', 'created_at', DB::raw("'Donatur' as type"))
            ->latest()
            ->limit(5)
            ->get();

        $pesanForum = Pesan::select('nama', 'pesan as deskripsi', 'created_at', DB::raw("'Tamu' as type"))
            ->latest()
            ->limit(5)
            ->get();

        $testimonials = $donaturWithDesc->concat($pesanForum)->sortByDesc('created_at')->take(5);

        // 4. Activity Gallery
        $activities = \App\Models\FotoKegiatan::latest('tanggal_kegiatan')->take(10)->get();

        return view('landing', compact('recentDonasi', 'chartData', 'year', 'testimonials', 'activities'));
    }

    public function form(TripayService $tripayService)
    {
        $channels = $tripayService->getPaymentChannels();
        return view('public.donasi.form', compact('channels'));
    }

    public function store(Request $request, TripayService $tripayService)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'jumlah' => 'required|numeric|min:1000|max:100000000',
            'keterangan' => 'nullable|string',
            'method' => 'required|string' // Payment Method Code (e.g., BRIVA, QRIS)
        ]);

        DB::beginTransaction();
        try {
            // Create Donasi Record
            $donasi = Donasi::create([
                'type_donasi' => 'NON_DONATUR',
                'sumber_non_donatur' => 'NON_DONATUR',
                'jumlah' => $request->jumlah,
                'tanggal_catat' => now(),
                'bulan' => now()->month,
                'tahun' => now()->year,
                'status_pembayaran' => 'pending'
            ]);
            
            $merchantRef = 'DONASI-' . $donasi->id_donasi . '-' . time();
            
            // Prepare Customer Details for Tripay
            $customerDetails = [
                'nama' => $request->nama,
                'email' => $request->email ?? 'no-email@example.com',
                'telepon' => '08123456789' // Dummy/Default if not collected
            ];

            // Prepare Order Items (Single Item: Donasi)
            $orderItems = [
                [
                    'sku' => 'DONASI-' . $donasi->id_donasi,
                    'name' => 'Donasi Sukarela',
                    'price' => (int) $request->jumlah,
                    'quantity' => 1
                ]
            ];

            // Request Transaction to Tripay
            $tripayResponse = $tripayService->requestTransaction(
                $request->method,
                (int) $request->jumlah,
                $customerDetails,
                $orderItems,
                $merchantRef
            );

            if (!$tripayResponse['success']) {
                throw new \Exception($tripayResponse['message'] ?? 'Gagal menghubungi Payment Gateway.');
            }

            // Save reference or checkout url if needed?
            // Midtrans used snap_token. Tripay provides 'checkout_url' and 'reference'.
            // Ideally we store 'reference' in DB.
            // For now, let's update snap_token column to hold the checkout_url merely for temporary storage if fields are limited,
            // OR better, create/update a 'keterangan' or new column. 
            // Since migration is expensive, I'll store the 'reference' in 'snap_token' column for now as a workaround 
            // since that column is unused now.
            $donasi->update(['snap_token' => $tripayResponse['data']['reference']]);

            // Save Message to Forum if exists
            if($request->filled('keterangan')) {
                Pesan::create([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'pesan' => $request->keterangan
                ]);
            }

            DB::commit();

            // Redirect to Tripay Checkout Page
            return redirect($tripayResponse['data']['checkout_url']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function success($id)
    {
        // Donasi ID is actually embedded in merchant_ref: DONASI-{id}-{time}
        // But the route param 'id' typically expects the main ID.
        // If Tripay redirects back, it might not pass clean ID.
        // But our return_url in Service was: route('public.donasi.success', ['id' => $merchantRef])
        // So $id here is actually the merchantRef string.
        
        // Let's parse it if needed, or find donasi by reference (stored in snap_token).
        
        // Case 1: $id is numeric (Direct link) -> find by ID
        // Case 2: $id is string (DONASI-123-9999) -> Extract ID
        
        $donasi = null;
        if (str_contains($id, 'DONASI-')) {
             $parts = explode('-', $id);
             $realId = $parts[1] ?? null;
             if($realId) $donasi = Donasi::find($realId);
        } else {
             $donasi = Donasi::find($id);
        }

        if(!$donasi) abort(404);

        return view('public.donasi.success', compact('donasi'));
    }

    public function forum()
    {
        $donaturWithDesc = \App\Models\Donatur::whereNotNull('deskripsi')
            ->where('deskripsi', '!=', '')
            ->select('nama', 'deskripsi', 'created_at', DB::raw("'Donatur' as type"))
            ->get();

        $pesanForum = Pesan::select('nama', 'pesan as deskripsi', 'created_at', DB::raw("'Tamu' as type"))
            ->get();

        $merged = $donaturWithDesc->concat($pesanForum)->sortByDesc('created_at');

        $perPage = 12;
        $page = request()->get('page', 1);
        $testimonials = new \Illuminate\Pagination\LengthAwarePaginator(
            $merged->forPage($page, $perPage), 
            $merged->count(), 
            $perPage, 
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('public.donasi.forum', compact('testimonials'));
    }

    public function storePesan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'pesan' => 'required|string|max:1000',
        ]);

        Pesan::create($request->only('nama', 'email', 'pesan'));

        return redirect()->back()->with('success', 'Terima kasih! Pesan Anda telah berhasil dikirim.');
    }
}
