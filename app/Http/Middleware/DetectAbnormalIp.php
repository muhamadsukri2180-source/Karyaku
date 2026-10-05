<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\IpLog;
use App\Models\AllowedIp;
use App\Models\LoginHistory;
use App\Models\User;
use App\Models\IpBan;
use App\Support\BanReason;
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

        // Cek apakah Admin/Staff sedang login.
        // Staff (Admin, Verifikator, CS) TIDAK PERNAH diblokir, tetapi tetap DIDETEKSI
        // kecuali SECURITY_EXEMPT_STAFF=true di .env (config/security_monitor.php)
        $isAdminOrStaff = false;
        if (Auth::check()) {
            $userRole = strtolower(Auth::user()->role?->role_name ?? '');
            if (in_array($userRole, ['admin', 'verifikator', 'customer_service'])) {
                $isAdminOrStaff = true;
            }
        }

        $exemptStaff = (bool) config('security_monitor.exempt_staff', false);

        // Jika staff dikecualikan penuh lewat config, lewati seluruh pemeriksaan
        if ($isAdminOrStaff && $exemptStaff) {
            return $next($request);
        }

        // 2. CEK APAKAH AKUN USER YANG SEDANG LOGIN DIBLOKIR / DIBEKUKAN (staff tidak pernah diblokir)
        if (Auth::check() && !$isAdminOrStaff) {
            $currUser = Auth::user();
            if ($currUser->status === 'blocked' || Cache::has("banned_user_{$currUser->id_user}")) {
                $uName = $currUser->name;
                $uEmail = $currUser->email;
                $uReason = $currUser->suspend_reason 
                    ?: Cache::get("banned_user_{$currUser->id_user}") 
                    ?: 'Akun dan alamat IP Anda telah diblokir oleh Administrator sistem.';

                Auth::logout();
                if ($request->hasSession()) {
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                }

                return response()->view('errors.ip-blocked', [
                    'ip'         => $ip,
                    'username'   => $uName,
                    'email'      => $uEmail,
                    'reason'     => $uReason,
                    'blocked_at' => now()->translatedFormat('d F Y, H:i') . ' WIB'
                ], 403);
            }
        }

        // 3. CEK APAKAH SESI BROWSER DIBEKUKAN (FROZEN SESSION)
        if ($request->hasSession() && !$isAdminOrStaff) {
            $sessId = substr(md5($request->session()->getId()), 0, 16);
            if (Cache::has("frozen_session_{$sessId}")) {
                if (Auth::check()) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                }
                return response()->view('errors.ip-blocked', [
                    'ip'         => $ip,
                    'reason'     => 'Sesi browser Anda sedang dibekukan oleh Administrator karena indikasi pelanggaran.',
                    'blocked_at' => now()->translatedFormat('d F Y, H:i') . ' WIB'
                ], 403);
            }
        }

        // Cek apakah IP terdaftar di Whitelist Eksplisit
        $isWhitelisted = Cache::remember("allowed_ip_{$ip}", 60, function () use ($ip) {
            try {
                return AllowedIp::where('ip_address', $ip)->exists();
            } catch (\Throwable $e) {
                return false;
            }
        });

        // 4. CEK APAKAH ALAMAT IP DIBLOKIR (BAN CHECK GLOBAL)
        if (!$isWhitelisted && !$isAdminOrStaff) {
            $isBanned = false;
            $banReason = null;
            $banCategory = null;

            // Prioritas 1: Cek dari model IpBan
            try {
                $activeBan = IpBan::activeFor($ip);
                if ($activeBan) {
                    $isBanned = true;
                    $banReason = $activeBan->reason;
                    $banCategory = $activeBan->category;
                }
            } catch (\Throwable $e) {}

            // Prioritas 2: Cek dari Cache
            if (!$isBanned && Cache::has("banned_ip_{$ip}")) {
                $isBanned = true;
                $banReason = Cache::get("banned_ip_{$ip}");
                $banCategory = BanReason::categorize($banReason);
            }

            // Prioritas 3: Cek dari ip_logs status abnormal
            if (!$isBanned) {
                try {
                    $bannedLog = IpLog::where('ip_address', $ip)
                        ->where('status', 'abnormal')
                        ->latest('last_activity_at')
                        ->first();

                    if ($bannedLog) {
                        $isBanned = true;
                        $banReason = $bannedLog->reason ?: 'Akses Anda diblokir oleh Administrator sistem.';
                        $banCategory = BanReason::categorize($banReason);
                        Cache::put("banned_ip_{$ip}", $banReason, 86400);
                    }
                } catch (\Throwable $e) {}
            }

            if ($isBanned) {
                // Izinkan rute auth/login agar Admin/Staff yang sedang logout tetap bisa login dari IP ini
                $isLoginRoute = $request->is('auth/login*') || $request->is('login*');
                if (!$isLoginRoute) {
                    if (Auth::check()) {
                        Auth::logout();
                        if ($request->hasSession()) {
                            $request->session()->invalidate();
                            $request->session()->regenerateToken();
                        }
                    }

                    return response()->view('errors.ip-blocked', [
                        'ip'         => $ip,
                        'reason'     => $banReason ?: 'Alamat IP Anda diblokir oleh Administrator sistem.',
                        'category'   => $banCategory,
                        'blocked_at' => now()->translatedFormat('d F Y, H:i') . ' WIB'
                    ], 403);
                }
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
        if (!$isSuspicious && !$isWhitelisted && !$request->is('admin/product*') && !$request->is('seller/product*')) {
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
                $isCurrentlyBanned = Cache::has("banned_ip_{$ip}") 
                    || IpBan::where('ip_address', $ip)->exists();
                $attributes = [
                    'ip_address' => $ip,
                    'status'     => $isCurrentlyBanned ? 'abnormal' : 'normal',
                ];
                if ($isCurrentlyBanned) {
                    $attributes['reason'] = Cache::get("banned_ip_{$ip}") ?? 'Alamat IP diblokir oleh Administrator.';
                }
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

            // Catat sebagai 'suspicious' jika benar-benar terdeteksi ancaman dan bukan whitelist manual
            if ($isSuspicious && !$isWhitelisted && $ipLog->status !== 'abnormal') {
                $ipLog->status = 'suspicious';
                $ipLog->reason = $reason;
            }

            $ipLog->user_agent = substr($userAgent, 0, 255);
            $ipLog->last_activity = substr($request->method() . ' ' . $request->fullUrl(), 0, 500);
            $ipLog->request_count = ($ipLog->request_count ?? 0) + 1;
            $ipLog->last_activity_at = now();

            $hasUserIdCol = Schema::hasColumn('ip_logs', 'user_id');
            if ($hasUserIdCol) {
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
            }

            try {
                $ipLog->save();
            } catch (\Throwable $saveEx) {
                if ($ipLog->status === 'suspicious') {
                    // Hosting lama mungkin belum punya enum 'suspicious' -> perbaiki kolom lalu simpan ulang
                    try {
                        \Illuminate\Support\Facades\DB::statement("ALTER TABLE ip_logs MODIFY COLUMN status ENUM('normal', 'abnormal', 'suspicious') NOT NULL DEFAULT 'normal'");
                        $ipLog->save();
                    } catch (\Throwable $alterEx) {
                        $ipLog->status = 'normal';
                        $ipLog->save();
                    }
                } else {
                    throw $saveEx;
                }
            }

            // Blokir HANYA jika Admin sudah memblokir manual ('abnormal'). Staff tidak pernah diblokir.
            if (!$isWhitelisted && !$isAdminOrStaff && $ipLog->status === 'abnormal') {
                $isLoginRoute = $request->is('auth/login*') || $request->is('login*');
                if (!$isLoginRoute) {
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
                        'category'   => BanReason::categorize($ipLog->reason),
                        'blocked_at' => now()->translatedFormat('d F Y, H:i') . ' WIB'
                    ], 403);
                }
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