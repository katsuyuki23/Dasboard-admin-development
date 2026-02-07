<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DonorInsightService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendMonthlyReports extends Command
{
    protected $signature = 'reports:monthly {--test : Send to admin only for testing}';
    protected $description = 'Send monthly financial reports to all active donors via WhatsApp';

    protected $insightService;
    protected $waService;

    public function __construct()
    {
        parent::__construct();
        $this->insightService = new DonorInsightService();
        $this->waService = new WhatsAppNotificationService();
    }

    public function handle()
    {
        $this->info('🚀 Starting monthly report delivery...');

        // Generate monthly report
        $report = $this->insightService->generateMonthlyReport();
        
        if (!$report) {
            $this->error('Failed to generate monthly report');
            return 1;
        }

        $this->info('📊 Report generated successfully');

        // Get recipients
        if ($this->option('test')) {
            // Test mode: send to admin only
            $this->info('🧪 TEST MODE: Sending to admin only');
            $result = $this->waService->notifyAdmin($report);
            
            if ($result) {
                $this->info('✅ Test report sent to admin');
                return 0;
            } else {
                $this->error('❌ Failed to send test report');
                return 1;
            }
        }

        // Production mode: send to all registered donors
        $donors = DB::table('donatur_whatsapp')
            ->join('donatur', 'donatur_whatsapp.id_donatur', '=', 'donatur.id_donatur')
            ->where('donatur_whatsapp.is_verified', true)
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('donasi')
                    ->whereColumn('donasi.id_donatur', 'donatur.id_donatur')
                    ->where('donasi.tanggal_catat', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 3 MONTH)'));
            })
            ->select('donatur.nama', 'donatur_whatsapp.whatsapp_number')
            ->get();

        if ($donors->isEmpty()) {
            $this->warn('⚠️  No active donors found with registered WhatsApp');
            return 0;
        }

        $this->info("📤 Sending to {$donors->count()} donors...");
        
        $successCount = 0;
        $failCount = 0;

        $bar = $this->output->createProgressBar($donors->count());
        $bar->start();

        foreach ($donors as $donor) {
            try {
                // Personalized greeting
                $message = "Halo *{$donor->nama}*! 🙏\n\n" . $report;
                
                $result = $this->waService->send($donor->whatsapp_number, $message);
                
                if ($result) {
                    $successCount++;
                } else {
                    $failCount++;
                    Log::warning("Failed to send report to {$donor->nama} ({$donor->whatsapp_number})");
                }

                // Rate limiting: 1 message per second
                sleep(1);
                
            } catch (\Exception $e) {
                $failCount++;
                Log::error("Error sending to {$donor->nama}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Successfully sent: {$successCount}");
        
        if ($failCount > 0) {
            $this->warn("⚠️  Failed: {$failCount}");
        }

        $this->info('🎉 Monthly report delivery completed!');

        return 0;
    }
}
