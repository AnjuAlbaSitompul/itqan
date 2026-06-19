<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\KpiPeriod; // Pastikan model ini sudah ada

#[Signature('kpiperiod:process')]
#[Description('Memproses status KpiPeriod untuk otomatis open dan closed berdasarkan waktu registrasi.')]
class ProcessKpiPeriod extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        // 1. Buka periode (draft -> open)
        // Syarat: Saat ini >= registration_start DAN <= registration_end
        $openedCount = KpiPeriod::where('status', 'draft')
            ->where('registration_start', '<=', $now)
            ->where('registration_end', '>=', $now)
            ->update(['status' => 'open']);

        // 2. Tutup periode (open -> closed)
        // Syarat: Saat ini sudah melewati registration_end
        $closedCount = KpiPeriod::where('status', 'open')
            ->where('registration_end', '<', $now)
            ->update(['status' => 'closed']);

        // Menampilkan output di console
        $this->info("Proses KpiPeriod selesai.");
        $this->line("- Berhasil membuka: {$openedCount} periode.");
        $this->line("- Berhasil menutup: {$closedCount} periode.");
    }
}