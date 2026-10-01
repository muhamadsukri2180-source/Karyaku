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
        if (!$isSuspicious && !$isWhitelisted && !$request->is('admin/product*') && !$request->is('seller/product*')) {
            $inputData = urldecode($request->fullUrl()) . ' ' . json_encode($request->all());

            // Pola SQLi yang dipertajam (Akurasi Tinggi, Bebas False-Positive)
            $sqliPatterns = [
                '/\bunion\s+(all\s+)?select\b/i',
                '/\b(drop|truncate|alter)\s+table\b/i',
                '/\b(and|or)\b\s+[\'"]?\d+[\'"]?\s*=\s*[\'"]?\d+/i', // Contoh: or 1=1
                '/\binformation_schema\b/i',
                '/\bload_file\s*\(/i',
                '/\binto\s+(outfile|dumpfile)\b/i',
                '/(\'|")\s*(or|and)\s*(\'|")?\w+(\'|")?\s*=/i'
            ];

            // Pola XSS yang dipertajam
            $xssPatterns = [
                '/<script\b[^>]*>(.*?)<\/script>/is',
                '/<script\b/i',
                '/javascript\s*:/i',
                '/\bon(error|load|click|mouseover|submit|focus|blur|keydown|keyup)\s*=/i',
                '/document\.cookie/i',
                '/\beval\s*\(/i'
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

        // 4. Deteksi Bot / Scraper dari User-Agent
        if (!$isSuspicious && !$isWhitelisted) {
            $lowerUa = strtolower($userAgent);
            
            // Jika User-Agent benar-benar kosong atau terlalu pendek
            if (strlen(trim($lowerUa)) < 5) {
                $isSuspicious = true;
                $reason = "Terdeteksi Bot Spam (Tanpa User-Agent)";
            } else {
                // Daftar black-list bot peretas / scanner / scraper
                $badBots = [
                    'sqlmap', 'nikto', 'nmap', 'zgrab', 'masscan', 'acunetix', 
                    'dirbuster', 'wpcan', 'postmanruntime', 'python-requests', 
                    'go-http-client', 'java/', 'curl/', 'wget/', 'scrapy', 
                    'httpclient', 'libwww-perl', 'ahrefsbot', 'semrushbot'
                ];
                
                foreach ($badBots as $bot) {
                    if (str_contains($lowerUa, $bot)) {
                        $isSuspicious = true;
                        $reason = "Terdeteksi Bot / Scanner Berbahaya ($bot)";
                        break;
                    }
                }
            }
        }

        // 5. Deteksi DDoS & Rate Flooding (>120 request/menit untuk non-whitelist)
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

        // 6. Catat Log ke Database secara aman (try-catch agar tidak memutus aplikasi jika DB sibuk)
        try {
            $sessionIdCookie = $request->cookie(config('session.cookie'));
            $sessionId = $sessionIdCookie ? substr(md5($sessionIdCookie), 0, 16) : substr(md5($userAgent . $ip), 0, 16);

            $ipLog = IpLog::firstOrNew([
                'ip_address' => $ip,
                'session_id' => $sessionId
            ]);

            // Semi-otomatis: Hanya catat sebagai 'suspicious', JANGAN langsung 'abnormal' (terblokir)
            if ($isSuspicious && !$isWhitelisted && $ipLog->status !== 'abnormal') {
                $ipLog->status = 'suspicious';
                $ipLog->reason = $reason;
            }

            $ipLog->user_agent = substr($userAgent, 0, 255);
            $ipLog->last_activity = substr($request->method() . ' ' . $request->fullUrl(), 0, 500);
            $ipLog->request_count = ($ipLog->request_count ?? 0) + 1;
            $ipLog->last_activity_at = now();
            $ipLog->save();

            // Blokir HANYA jika Admin sudah memblokir manual ('abnormal') atau membekukan ('frozen_session_')
            if (!$isWhitelisted && ($ipLog->status === 'abnormal' || \Illuminate\Support\Facades\Cache::has("frozen_session_{$sessionId}"))) {
                if (str_contains($ipLog->reason ?? '', 'DoS') || str_contains($ipLog->reason ?? '', 'Flooding')) {
                    abort(429, 'Terlalu banyak permintaan (DDoS Mitigation System). Silakan tunggu beberapa saat.');
                }
                abort(403, 'Akses Anda diblokir sementara oleh Admin karena aktivitas mencurigakan atau pembekuan akun.');
            }

            // Dihapus: Pengecekan request_count > 1000 karena menyebabkan false-positive untuk pengguna aktif jangka panjang.
        } catch (\Throwable $e) {
            // Jika status mencurigakan tapi DB bermasalah, tetap cegah serangan
            if ($isSuspicious && !$isWhitelisted) {
                abort(403, 'Akses Anda diblokir karena aktivitas mencurigakan.');
            }
        }

        return $next($request);
    }
}