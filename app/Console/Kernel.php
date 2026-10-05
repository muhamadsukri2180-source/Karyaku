<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Otomatis hapus notifikasi yang sudah lebih dari 1 bulan setiap hari
        $schedule->call(function () {
            \App\Models\Notification::where('created_at', '<', now()->subMonth())->delete();
        })->daily();

        // Otomatis backup riwayat login setiap bulan (ke Google Drive)
        $schedule->call(function () {
            $ts = now()->format('Y-m-d_His');
            $csvFile = "backup-login-history-{$ts}.csv";
            $backupDir = storage_path('app/backups');
            \Illuminate\Support\Facades\Storage::disk('local')->makeDirectory('backups');
            
            $histories = \App\Models\LoginHistory::all();
            
            $handle = fopen("$backupDir/$csvFile", 'w');
            fputcsv($handle, ['Username/Akun', 'Status Aktivitas', 'Alamat IP', 'Perangkat/Browser', 'Waktu Kejadian']);
            foreach ($histories as $h) {
                $status = ($h->type == 'login') ? 'Sedang Login' : 'Telah Logout';
                fputcsv($handle, [
                    $h->username ?? 'Unknown',
                    $status,
                    $h->ip_address,
                    $h->user_agent,
                    $h->created_at ? $h->created_at->format('d M Y, H:i:s') : ''
                ]);
            }
            fclose($handle);
            
            try {
                $stream = fopen("$backupDir/$csvFile", 'r');
                \Illuminate\Support\Facades\Storage::disk('google')->put($csvFile, $stream);
                if (is_resource($stream)) fclose($stream);
                @unlink("$backupDir/$csvFile");
            } catch (\Throwable $e) {}
            
        })->monthly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
