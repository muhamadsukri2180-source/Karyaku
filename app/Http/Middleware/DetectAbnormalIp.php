<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\IpLog;
use App\Models\AllowedIp;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class DetectAbnormalIp
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $path = $request->path();
        $userAgent = $request->header('User-Agent') ?? 'Unknown';

        // 1. Lewati asset statis biasa untuk efisiensi performa
        if (preg_match('/\.(css|js|png|jpg|jpeg|webp|gif|svg|ico|woff|woff2|ttf|map)$/i', $path)) {
            return $next($request);
        }

        // Cek apakah Admin/Staff sedang login (Admin, Verifikator, CS selalu dikecualikan dari pemblokiran & false-positive)
        $isAdminOrStaff = false;
        if (Auth::check()) {
            $userRole = strtolower(Auth::user()->role?->role_name ?? '');
            if (in_array($userRole, ['admin', 'verifikator', 'customer_service'])) {
                $isAdminOrStaff = true;
            }
        }

        // Cek apakah IP terdaftar di Whitelist (Manual dari Admin, Localhost, atau IP Admin Aktif)
        $isWhitelisted = $isAdminOrStaff || in_array($ip, ['127.0.0.1', '::1']) || Cache::remember("allowed_ip_{$ip}", 60, function () use ($ip) {
            try {
                return AllowedIp::where('ip_address', $ip)->exists();
            } catch (\Throwable $e) {
                return false;
            }
        });

        // 2. CEK APAKAH IP DIBLOKIR / DIBEKUKAN OLEH ADMIN (BAN CHECK KETAT)
        // Jika IP diblokir, blokir SELURUH rute (termasuk landing page '/', dashboard, dan akun).
        if (!$isWhitelisted) {
            $isBanned = Cache::has("banned_ip_{$ip}");
            $banReason = null;

            if ($isBanned) {
                $banReason = Cache::get("banned_ip_{$ip}");
            } else {
                // Periksa di database jika cache belum ada
                try {
                    $bannedLog = IpLog::where('ip_address', $ip)
                        ->where('status', 'abnormal')
                        ->latest('last_activity_at')
                        ->first();

                    if ($bannedLog) {
                        $isBanned = true;
                        $banReason = $bannedLog->reason ?: 'Akses Anda diblokir oleh Administrator sistem.';
                        Cache::put("banned_ip_{$ip}", $banReason, 86400);
                    }
                } catch (\Throwable $e) {}
            }

            // Periksa session freeze jika ada
            if (!$isBanned && $request->hasSession()) {
                $sessId = substr(md5($request->session()->getId()), 0, 16);
                if (Cache::has("frozen_session_{$sessId}")) {
                    $isBanned = true;
                    $banReason = 'Sesi Anda sedang dibekukan oleh Administrator.';
                }
            }

            // JIKA TERKONFIRMASI DIBLOKIR:
            if ($isBanned) {
                // 1. Putuskan sesi login pengguna jika ada agar akun tidak bisa dilihat lagi
                if (Auth::check()) {
                    Auth::logout();
                    if ($request->hasSession()) {
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                    }
                }

                // 2. Tampilkan halaman khusus IP DIBLOKIR (Errors 403 / ip-blocked)
                return response()->view('errors.ip-blocked', [
                    'ip'         => $ip,
                    'reason'     => $banReason ?: 'Akses Anda diblokir sementara oleh Admin karena aktivitas mencurigakan atau pembekuan akun.',
                    'blocked_at' => now()->translatedFormat('d F Y, H:i') . ' WIB'
                ], 403);
            }
        }

        // 3. Daftar endpoint jebakan (Honeypot) yang sering dicari bot/peretas
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

        // 4. Cek SQL Injection dan XSS dari Input Data dan URL (Hanya untuk non-whitelist)
        if (!$isSuspicious && !$isWhitelisted && !$isAdminOrStaff && !$request->is('admin/product*') && !$request->is('seller/product*')) {
            $inputData = urldecode($request->fullUrl()) . ' ' . json_encode($request->all());

            // Pola SQLi yang dipertajam (Akurasi Tinggi, Bebas False-Positive)
            $sqliPatterns = [
                '/\bunion\s+(all\s+)?select\b/i',
                '/\b(drop|truncate|alter)\s+table\b/i',
                '/\b(and|or)\b\s+[\'"]?\d+[\'"]?\s*=\s*[\'"]?\d+/i',
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

        // 5. Deteksi Bot / Scraper dari User-Agent (Hanya untuk non-whitelist)
        if (!$isSuspicious && !$isWhitelisted && !$isAdminOrStaff) {
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

        // 6. Deteksi DDoS & Rate Flooding (>120 request/menit untuk non-whitelist)
        if (!$isWhitelisted && !$isAdminOrStaff) {
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

        // 7. Catat Log ke Database secara aman
        try {
            $sessionId = null;
            if ($request->hasSession()) {
                $sessionId = substr(md5($request->session()->getId()), 0, 16);
            } else {
                $sessionIdCookie = $request->cookie(config('session.cookie'));
                $sessionId = $sessionIdCookie ? substr(md5($sessionIdCookie), 0, 16) : substr(md5($userAgent . $ip), 0, 16);
            }

            // Mengelompokkan log berdasarkan IP, Session ID, dan HARI INI
            $today = now()->toDateString();
            $hasSessionIdCol = Schema::hasColumn('ip_logs', 'session_id');

            $query = IpLog::where('ip_address', $ip)->whereDate('created_at', $today);
            if ($hasSessionIdCol && $sessionId) {
                $query->where('session_id', $sessionId);
            }

            $ipLog = $query->first();

            if (!$ipLog) {
                $attributes = [
                    'ip_address' => $ip,
                    'status'     => 'normal',
                ];
                if ($hasSessionIdCol && $sessionId) {
                    $attributes['session_id'] = $sessionId;
                }
                $ipLog = new IpLog($attributes);
            }

            // Pembersihan otomatis: jika log sebelumnya pernah mencatat false positive devtools/resize window, normalkan
            if ($ipLog->status === 'suspicious') {
                $r = $ipLog->reason ?? '';
                if (str_contains($r, 'Resize Window') || str_contains($r, 'right-click') || str_contains($r, 'Klik Kanan') || str_contains($r, 'DevTools Terdeteksi via Resize Window')) {
                    $ipLog->status = 'normal';
                    $ipLog->reason = 'Aktivitas Normal Pengguna';
                }
            }

            // Semi-otomatis: Hanya catat sebagai 'suspicious' jika benar-benar terdeteksi ancaman dan bukan whitelist/staff
            if ($isSuspicious && !$isWhitelisted && !$isAdminOrStaff && $ipLog->status !== 'abnormal') {
                $ipLog->status = 'suspicious';
                $ipLog->reason = $reason;
            }

            $ipLog->user_agent = substr($userAgent, 0, 255);
            $ipLog->last_activity = substr($request->method() . ' ' . $request->fullUrl(), 0, 500);
            $ipLog->request_count = ($ipLog->request_count ?? 0) + 1;
            $ipLog->last_activity_at = now();

            if (auth()->check()) {
                $ipLog->user_id = auth()->id();
            } elseif (!$ipLog->user_id) {
                // Fallback: cari user dari riwayat login berdasarkan IP yang sama
                $loginHist = LoginHistory::where('ip_address', $ip)
                    ->whereNotNull('username')
                    ->latest()
                    ->first();
                if ($loginHist) {
                    $foundUser = User::where('name', $loginHist->username)
                        ->orWhere('email', $loginHist->username)
                        ->first();
                    if ($foundUser) {
                        $ipLog->user_id = $foundUser->id_user;
                    }
                }
            }

            try {
                $ipLog->save();
            } catch (\Throwable $saveEx) {
                if ($ipLog->status === 'suspicious') {
                    $ipLog->status = 'normal';
                    $ipLog->save();
                } else {
                    throw $saveEx;
                }
            }

            // Blokir HANYA jika Admin sudah memblokir manual ('abnormal')
            if (!$isWhitelisted && $ipLog->status === 'abnormal') {
                if (Auth::check()) {
                    Auth::logout();
                    if ($request->hasSession()) {
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                    }
                }
                return response()->view('errors.ip-blocked', [
                    'ip'         => $ip,
                    'reason'     => $ipLog->reason ?: 'Alamat IP Anda diblokir oleh Administrator.',
                    'blocked_at' => now()->translatedFormat('d F Y, H:i') . ' WIB'
                ], 403);
            }

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('DetectAbnormalIp Logging Error: ' . $e->getMessage());
            if ($isSuspicious && !$isWhitelisted && !$isAdminOrStaff) {
                abort(403, 'Akses Anda diblokir karena aktivitas mencurigakan.');
            }
        }

        return $next($request);
    }
}