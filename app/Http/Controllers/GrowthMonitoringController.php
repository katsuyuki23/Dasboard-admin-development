<?php

namespace App\Http\Controllers;

use App\Models\Anak;
use App\Models\GrowthMonitoring;
use Illuminate\Http\Request;
use App\Services\WhatsAppNotificationService;

class GrowthMonitoringController extends Controller
{
    public function create(Anak $anak)
    {
        return view('anak.growth.create', compact('anak'));
    }

    public function store(Request $request, Anak $anak)
    {
        $request->validate([
            'tanggal_ukur' => 'required|date',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'lingkar_kepala' => 'required|numeric',
        ]);

        // Calculate Age in Months
        $usiaBulan = \Carbon\Carbon::parse($anak->tanggal_lahir)->diffInMonths($request->tanggal_ukur);
        
        // Simplified Z-Score Calculation (Mock Logic for MVP)
        // In real world, this compares against WHO Standards table based on Gender & Age
        $zScoreTinggi = $this->calculateZScoreTinggi($anak->jenis_kelamin, $usiaBulan, $request->tinggi_badan);
        $zScoreBerat = $this->calculateZScoreBerat($anak->jenis_kelamin, $usiaBulan, $request->berat_badan);
        
        $statusGizi = 'NORMAL';
        if ($zScoreTinggi < -3) $statusGizi = 'SEVERELY_STUNTED';
        elseif ($zScoreTinggi < -2) $statusGizi = 'STUNTED';
        elseif ($zScoreBerat < -2) $statusGizi = 'WASTED';
        elseif ($zScoreBerat > 2) $statusGizi = 'OVERWEIGHT';

        $monitoring = GrowthMonitoring::create([
            'id_anak' => $anak->id_anak,
            'tanggal_ukur' => $request->tanggal_ukur,
            'usia_bulan' => $usiaBulan,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'lingkar_kepala' => $request->lingkar_kepala,
            'z_score_tinggi' => $zScoreTinggi,
            'z_score_berat' => $zScoreBerat,
            'status_gizi' => $statusGizi,
            'catatan' => $request->catatan
        ]);

        // Trigger WhatsApp Alert if Stunted
        if (in_array($statusGizi, ['STUNTED', 'SEVERELY_STUNTED', 'WASTED'])) {
            $this->sendStuntingAlert($anak, $monitoring);
        }

        return redirect()->route('anak.show', $anak->id_anak)->with('success', 'Data tumbuh kembang berhasil dicatat.');
    }

    private function calculateZScoreTinggi($gender, $ageMonth, $height)
    {
        // Mock Formula: Standard height approx 50 + (age * 0.5). Deviation 5cm.
        // Needs proper WHO tables
        $standard = 50 + ($ageMonth * 2.5); // Rough avg growth
        return ($height - $standard) / 5; // Deviation
    }

    private function calculateZScoreBerat($gender, $ageMonth, $weight)
    {
         // Mock Formula
         $standard = 3.3 + ($ageMonth * 0.5);
         return ($weight - $standard) / 2;
    }

    private function sendStuntingAlert($anak, $monitoring)
    {
        try {
            $notifService = app(WhatsAppNotificationService::class);
            
            $message = "🚨 *PERINGATAN KESEHATAN* 🚨\n\n" .
                       "Nama Anak: *{$anak->nama}*\n" .
                       "Status Gizi: *{$monitoring->status_gizi}*\n" .
                       "Umur: {$monitoring->usia_bulan} bulan\n" .
                       "BB/TB: {$monitoring->berat_badan}kg / {$monitoring->tinggi_badan}cm\n\n" .
                       "Mohon segera lakukan pemeriksaan atau intervensi gizi.";
            
            $notifService->sendToGroup($message);
        } catch (\Exception $e) {
            \Log::error("Stunting Alert Error: " . $e->getMessage());
        }
    }
}
