<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\IpLog;
use App\Models\AllowedIp;
use Illuminate\Support\Facades\Cache;

class DetectAbnormalIp
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $path = $request->path();
        $userAgent = $request->header('User-Agent') ?? 'Unknown';

        // 1. Lewati asset statis biasa untuk efisiensi performa, kecuali jika path mencurigakan
        if (preg_match('/\.(css|js|png|jpg|jpeg|webp|gif|svg|ico|woff|woff2|ttf|map)$/i', $path)) {
            return $next($request);
        }

        // Cek apakah IP terdaftar di Whitelist (Admin) atau Localhost
        $isWhitelisted = in_array($ip, ['127.0.0.1', '::1']) || Cache::remember("allowed_ip_{$ip}", 60, function () use ($ip) {
            try {
                return AllowedIp::where('ip_address', $ip)->exists();
            } catch (\Throwable $e) {
                return false;
            }
        });

        // 2. Daftar endpoint jebakan (Honeypot) yang sering dicari bot/peretas
        $suspiciousPaths = [
            'wp-admin', 'wp-login.php', '.env', 'phpmyadmin', 
            'admin.php', 'config.json', 'backup.sql', 'xmlrpc.php',
            '.git', 'composer.json', 'eval-stdin.php', 'shell.php',
            'alfa.php', 'wso.php', 'c99.php', 'web.config'
        ];

        $isSuspicious = false;
        $reason = null;

        $lowerPath = strtolower($path);
        foreach ($suspiciousPaths as $badPath) {
            if (str_contains($lowerPath, $badPath)) {
                $isSuspicious = true;
                $reason = "Mencoba mengakses endpoint terlarang: /{$path}";
                break;
            }
        }

        // 3. Cek SQL Injection dan XSS dari Input Data dan URL
        if (!$isSuspicious && !$isWhitelisted) {
            $inputData = json_encode($request->all()) . ' ' . $request->fullUrl();

            $sqliPatterns = [
                '/\bunion\s+(all\s+)?select\b/i',
                '/\b(drop|truncate|alter)\s+table\b/i',
                '/\binsert\s+into\b/i',
                '/\bdelete\s+from\b/i',
                '/\bwaitfor\s+delay\b/i',
                '/\b(benchmark|sleep)\s*\(/i',
                '/\b(and|or)\b\s+[\'"]?\d+[\'"]?\s*=\s*[\'"]?\d+/i',
                '/\b(and|or)\b\s+true\b/i',
                '/\binformation_schema\b/i',
                '/\bload_file\s*\(/i',
                '/\binto\s+(outfile|dumpfile)\b/i',
                '/(\'|")\s*(or|and)\s*(\'|")?\w+(\'|")?\s*=/i'
            ];

            $xssPatterns = [
                '/<script\b[^>]*>(.*?)<\/script>/is',
                '/<script\b/i',
                '/javascript\s*:/i',
                '/\bon(error|load|click|mouseover|submit|focus|blur|keydown|keyup)\s*=/i',
                '/alert\s*\(/i',
                '/document\.cookie/i',
                '/document\.location/i',
                '/<iframe\b/i',
                '/<object\b/i',
                '/<embed\b/i',
                '/\beval\s*\(/i',
                '/base64_decode\s*\(/i'
            ];

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

        // 4. Deteksi DDoS & Rate Flooding (>120 request/menit untuk non-whitelist)
        if (!$isWhitelisted) {
            $floodKey = "ddos_flood_count_{$ip}";
            $reqInMinute = Cache::increment($floodKey);
            if ($reqInMinute === 1) {
                Cache::put($floodKey, 1, 60);
            }

            if ($reqInMinute > 120) {
                $isSuspicious = true;
                $reason = "Terdeteksi serangan DoS / Flooding ({$reqInMinute} req/menit)";
            }
        }

        // 5. Catat Log ke Database secara aman (try-catch agar tidak memutus aplikasi jika DB sibuk)
        try {
            $ipLog = IpLog::firstOrNew(['ip_address' => $ip]);

            if ($isSuspicious && !$isWhitelisted) {
                $ipLog->status = 'abnormal';
                $ipLog->reason = $reason;
            }

            $ipLog->user_agent = substr($userAgent, 0, 255);
            $ipLog->last_activity = substr($request->method() . ' ' . $request->fullUrl(), 0, 500);
            $ipLog->request_count = ($ipLog->request_count ?? 0) + 1;
            $ipLog->last_activity_at = now();
            $ipLog->save();

            // Blokir jika IP berstatus abnormal dan bukan Whitelist atau jika IP ada di cache pembekuan
            if (!$isWhitelisted && ($ipLog->status === 'abnormal' || \Illuminate\Support\Facades\Cache::has("frozen_ip_{$ip}"))) {
                if (str_contains($ipLog->reason ?? '', 'DoS') || str_contains($ipLog->reason ?? '', 'Flooding')) {
                    abort(429, 'Terlalu banyak permintaan (DDoS Mitigation System). Silakan tunggu beberapa saat.');
                }
                abort(403, 'Akses Anda diblokir sementara oleh Admin karena aktivitas mencurigakan atau pembekuan akun.');
            }

            if ($ipLog->request_count > 1000 && $ipLog->status !== 'abnormal') {
                $ipLog->update(['reason' => 'Terdeteksi aktivitas Bot / Spam (Lebih dari 1000 request)']);
            }
        } catch (\Throwable $e) {
            // Jika status mencurigakan tapi DB bermasalah, tetap cegah serangan
            if ($isSuspicious && !$isWhitelisted) {
                abort(403, 'Akses Anda diblokir karena aktivitas mencurigakan.');
            }
        }

        return $next($request);
    }
}