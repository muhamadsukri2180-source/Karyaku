<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\IpLog;

class DetectAbnormalIp
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $path = $request->path();
        $userAgent = $request->header('User-Agent');

        // Daftar endpoint jebakan (Honeypot) yang sering dicari bot/peretas
        $suspiciousPaths = [
            'wp-admin', 'wp-login.php', '.env', 'phpmyadmin', 
            'admin.php', 'config.json', 'backup.sql', 'xmlrpc.php'
        ];

        $isSuspicious = false;
        $reason = null;

        foreach ($suspiciousPaths as $badPath) {
            if (str_contains(strtolower($path), $badPath)) {
                $isSuspicious = true;
                $reason = "Mencoba mengakses endpoint terlarang: /{$path}";
                break;
            }
        }

        // Cek SQL Injection dan XSS dari Input Data
        if (!$isSuspicious) {
            $inputData = json_encode($request->all()) . ' ' . $request->fullUrl();
            $sqliPatterns = ['/union\s+select/i', '/drop\s+table/i', '/insert\s+into/i', '/waitfor\s+delay/i', '/sleep\(/i', '/\b(and|or)\b\s+\d+=\d+/i'];
            $xssPatterns = ['/<script\b[^>]*>(.*?)<\/script>/i', '/javascript:/i', '/onerror=/i', '/onload=/i', '/alert\(/i', '/document\.cookie/i'];
            
            foreach ($sqliPatterns as $pattern) {
                if (preg_match($pattern, $inputData)) {
                    $isSuspicious = true;
                    $reason = "Terdeteksi percobaan SQL Injection (SQLi)";
                    break;
                }
            }

            if (!$isSuspicious) {
                foreach ($xssPatterns as $pattern) {
                    if (preg_match($pattern, $inputData)) {
                        $isSuspicious = true;
                        $reason = "Terdeteksi percobaan Cross-Site Scripting (XSS)";
                        break;
                    }
                }
            }
        }

        $ipLog = IpLog::firstOrNew(['ip_address' => $ip]);

        if ($isSuspicious) {
            $ipLog->status = 'abnormal';
            $ipLog->reason = $reason;
        }

        $ipLog->user_agent = substr($userAgent ?? 'Unknown', 0, 255);
        $ipLog->last_activity = substr($request->method() . ' ' . $request->fullUrl(), 0, 500);
        $ipLog->request_count = ($ipLog->request_count ?? 0) + 1;
        $ipLog->last_activity_at = now();
        $ipLog->save();

        // Blokir jika statusnya abnormal atau melebihi limit DDoS (1000 requests/hari)
        if ($ipLog->status === 'abnormal') {
            abort(403, 'Akses Anda diblokir karena terdeteksi aktivitas mencurigakan. (Security System)');
        }

        if ($ipLog->request_count > 1000 && $ipLog->status !== 'abnormal') {
            $ipLog->update(['reason' => 'Terdeteksi aktivitas Bot / Spam (Lebih dari 1000 request)']);
            // Tidak di-abort otomatis. Admin harus memblokir manual di dashboard.
        }

        return $next($request);
    }
}