<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\IpLog;
use App\Models\AllowedIp;
use App\Models\IpBan;
use App\Support\BanReason;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class DetectAbnormalIp
{
    private const STAFF_ROLES = ['admin', 'verifikator', 'customer_service'];

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
            $isAdminOrStaff = in_array($userRole, self::STAFF_ROLES, true);
        }

        $exemptStaff = (bool) config('security_monitor.exempt_staff', false);

        // Jika staff dikecualikan penuh lewat config, lewati seluruh pemeriksaan
        if ($isAdminOrStaff && $exemptStaff) {
            return $next($request);
        }

        // Cek apakah IP terdaftar di Whitelist Eksplisit (hanya yang ditambahkan MANUAL oleh admin)
        $isWhitelisted = Cache::remember("allowed_ip_{$ip}", 60, function () use ($ip) {
            try {
                return AllowedIp::where('ip_address', $ip)->exists();
            } catch (\Throwable $e) {
                return false;
            }
        });

        // Whitelist IP hanya membebaskan TAMU dari deteksi. Akun non-admin yang login
        // tetap dipantau walau memakai IP yang sama dengan admin (WiFi / CGNAT seluler).
        $isGuestWhitelisted = $isWhitelisted && !Auth::check();

        // 2. CEK APAKAH ALAMAT IP DIBLOKIR (BAN CHECK GLOBAL - PRIORITAS TERTINGGI)
        // Berlaku untuk SEMUA halaman (termasuk landing page, refresh, suspended-notice, dll).
        // Jika IP diblokir, pengunjung LANGSUNG mendapatkan halaman IP Diblokir (errors.ip-blocked),
        // BUKAN halaman akun ditangguhkan! Staff & whitelist manual tidak pernah diblokir.
        if (!$isWhitelisted && !$isAdminOrStaff) {
            $ban = $this->findActiveBan($ip);

            if ($ban) {
                // Pengecualian SATU-SATUNYA: halaman login khusus staff (agar admin yang
                // logout tidak terkunci dari IP yang sama). User biasa yang mencoba login
                // dari IP ini tetap akan ditolak oleh AuthController.
                if ($this->isStaffLoginBypass($request)) {
                    return $next($request);
                }

                return $this->banResponse($request, [
                    'ip'           => $ip,
                    'reason'       => $ban['reason'],
                    'category'     => $ban['category'],
                    'banned_at'    => $ban['banned_at'],
                    'banned_until' => $ban['banned_until'],
                ]);
            }
        }

        // 3. CEK APAKAH SESI BROWSER DIBEKUKAN (FROZEN SESSION)
        if ($request->hasSession() && !$isAdminOrStaff) {
            $sessId = substr(md5($request->session()->getId()), 0, 16);
            $frozen = Cache::get("frozen_session_{$sessId}");
            if ($frozen) {
                return $this->banResponse($request, [
                    'ip'     => $ip,
                    'reason' => is_string($frozen)
                        ? $frozen
                        : 'Sesi browser Anda sedang dibekukan oleh Administrator karena indikasi pelanggaran.',
                ]);
            }
        }

        // 4. CEK APAKAH AKUN USER YANG SEDANG LOGIN DITANGGUHKAN (USER SUSPEND - KHUSUS JIKA IP TIDAK DIBAN)
        if (Auth::check() && !$isAdminOrStaff) {
            $currUser = Auth::user();

            // Biarkan user yang disuspend membuka halaman banding & submit banding
            if ($request->is('suspended-notice*') || $request->is('appeal*')) {
                return $next($request);
            }

            if ($currUser->status === 'blocked' || Cache::has("banned_user_{$currUser->id_user}")) {
                // Auto-unban akun jika durasi pembekuan sudah lewat
                if ($currUser->suspended_until && $currUser->suspended_until->isPast()) {
                    $currUser->status = 'active';
                    $currUser->suspended_until = null;
                    $currUser->suspend_reason = null;
                    $currUser->save();
                    Cache::forget("banned_user_{$currUser->id_user}");
                } else {
                    $uReason = $currUser->suspend_reason
                        ?: Cache::get("banned_user_{$currUser->id_user}")
                        ?: 'Akun Anda sedang ditangguhkan oleh Administrator sistem.';

                    $countdown = $currUser->suspend_countdown;
                    $appeal = \App\Models\AccountAppeal::where('user_id', $currUser->id_user)->latest()->first();

                    $suspendedInfo = [
                        'user_id'          => $currUser->id_user,
                        'username'         => $currUser->name,
                        'email'            => $currUser->email,
                        'reason'           => $uReason,
                        'duration_text'    => $countdown['formatted'] ?? 'Permanen (Tanpa batas waktu)',
                        'is_permanent'     => empty($currUser->suspended_until),
                        'is_expired'       => false,
                        'target_timestamp' => $currUser->suspended_until ? $currUser->suspended_until->timestamp * 1000 : null,
                        'appeal_status'    => $appeal ? $appeal->status : null,
                        'appeal_date'      => $appeal ? $appeal->created_at->translatedFormat('d M Y H:i') : null,
                        'appeal_admin_note'=> $appeal ? $appeal->admin_note : null,
                    ];

                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    session(['suspended_user_id' => $currUser->id_user]);

                    return redirect()->route('suspended.notice')->with('suspended_info', $suspendedInfo);
                }
            }
        }

        // 5. Pemeriksaan Ancaman Keamanan (Honeypot, SQLi, XSS, Bot, DDoS)
        // HANYA untuk pengunjung umum (NON-STAFF & NON-WHITELIST).
        // Role Admin, Verifikator, CS dan IP Whitelist DIKECUALIKAN 100% sehingga tidak pernah terdeteksi sebagai ancaman/bot.
        $isSuspicious = false;
        $reason = null;

        if (!$isAdminOrStaff && !$isGuestWhitelisted) {
            // A. Honeypot check
            $suspiciousPaths = [
                'wp-admin', 'wp-login.php', '.env', 'phpmyadmin',
                'admin.php', 'config.json', 'backup.sql', 'xmlrpc.php',
                '.git', 'composer.json', 'eval-stdin.php', 'shell.php',
                'alfa.php', 'wso.php', 'c99.php', 'web.config'
            ];

            $lowerPath = strtolower($path);
            foreach ($suspiciousPaths as $badPath) {
                if (str_contains($lowerPath, $badPath)) {
                    $isSuspicious = true;
                    $reason = "Mencoba mengakses endpoint terlarang: /{$path}";
                    break;
                }
            }

            // B. Cek SQL Injection dan XSS dari Input Data dan URL
            if (!$isSuspicious && !$request->is('admin/product*') && !$request->is('seller/product*')) {
                $inputData = urldecode($request->fullUrl()) . ' ' . json_encode($request->except(['password', 'password_confirmation', '_token']));

                // Pola SQLi yang dipertajam (Akurasi Tinggi, Bebas False-Positive)
                $sqliPatterns = [
                    '/\bunion\s+(all\s+)?select\b/i',
                    '/\b(drop|truncate|alter)\s+table\b/i',
                    '/\b(and|or)\b\s+[\'"]?\d+[\'"]?\s*=\s*[\'"]?\d+/i',
                    '/\binformation_schema\b/i',
                    '/\bload_file\s*\(/i',
                    '/\binto\s+(outfile|dumpfile)\b/i',
                    '/\bsleep\s*\(\s*\d+\s*\)/i',
                    '/\bbenchmark\s*\(/i',
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

            // C. Deteksi Bot / Scraper dari User-Agent (Akurat & Selektif)
            if (!$isSuspicious) {
                $lowerUa = strtolower($userAgent);

                // Kecualikan browser resmi pengguna biasa
                $isLegitBrowser = str_contains($lowerUa, 'mozilla/')
                    && (str_contains($lowerUa, 'chrome/') || str_contains($lowerUa, 'safari/') || str_contains($lowerUa, 'firefox/') || str_contains($lowerUa, 'edg/'));
                $isHeadless = str_contains($lowerUa, 'headless') || str_contains($lowerUa, 'phantomjs') || str_contains($lowerUa, 'selenium') || str_contains($lowerUa, 'puppeteer') || str_contains($lowerUa, 'playwright');

                if (!$isLegitBrowser || $isHeadless) {
                    if (strlen(trim($lowerUa)) < 5) {
                        $isSuspicious = true;
                        $reason = "Terdeteksi Bot Spam (User-Agent Kosong / Anomali)";
                    } else {
                        $badBotSignatures = [
                            'sqlmap'          => 'SQLMap Penetration Tool',
                            'nikto'           => 'Nikto Vulnerability Scanner',
                            'nmap'            => 'Nmap Port Scanner',
                            'zgrab'           => 'ZGrab Network Scanner',
                            'masscan'         => 'Masscan Fast Scanner',
                            'acunetix'        => 'Acunetix Security Scanner',
                            'dirbuster'       => 'DirBuster Brute Forcer',
                            'gobuster'        => 'Gobuster Path Scanner',
                            'wpscan'          => 'WPScan WordPress Scanner',
                            'hydra'           => 'THC Hydra Login Cracker',
                            'burpsuite'       => 'Burp Suite Security Proxy',
                            'python-requests' => 'Python Requests Bot',
                            'python-urllib'   => 'Python Urllib Crawler',
                            'aiohttp'         => 'Python AioHTTP Bot',
                            'go-http-client'  => 'Golang HTTP Client',
                            'java/'           => 'Java Automated Client',
                            'curl/'           => 'cURL Automated Command',
                            'wget/'           => 'Wget Downloader Bot',
                            'scrapy'          => 'Scrapy Web Scraper',
                            'httpclient'      => 'Apache HttpClient',
                            'libwww-perl'     => 'Perl LWP Bot',
                            'postmanruntime'  => 'Postman Automated Runner',
                            'headlesschrome'  => 'Headless Chrome Automation',
                            'phantomjs'       => 'PhantomJS Automated Script',
                            'selenium'        => 'Selenium Automated WebDriver',
                            'puppeteer'       => 'Puppeteer Automated Script',
                            'playwright'      => 'Playwright Automation Tool',
                        ];

                        foreach ($badBotSignatures as $sig => $name) {
                            if (str_contains($lowerUa, $sig)) {
                                $isSuspicious = true;
                                $reason = "Terdeteksi Bot / Scanner Berbahaya ($name)";
                                break;
                            }
                        }
                    }
                }
            }

            // D. Deteksi DDoS & Rate Flooding (>120 request/menit untuk non-whitelist)
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

        // 9. Catat Log ke Database secara aman
        try {
            if ($request->hasSession()) {
                $sessionId = substr(md5($request->session()->getId()), 0, 16);
            } else {
                $sessionIdCookie = $request->cookie(config('session.cookie'));
                $sessionId = $sessionIdCookie ? substr(md5($sessionIdCookie), 0, 16) : substr(md5($userAgent . $ip), 0, 16);
            }

            // Mengelompokkan log menjadi 1 data per IP dan HARI INI (tidak dipecah per session agar rapi)
            $today = now()->toDateString();
            $hasSessionIdCol = Schema::hasColumn('ip_logs', 'session_id');

            $ipLog = IpLog::where('ip_address', $ip)->whereDate('created_at', $today)->first();

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

            // Pembersihan otomatis: jika log sebelumnya pernah mencatat false positive resize/klik kanan/devtools-open, normalkan
            if ($ipLog->status === 'suspicious') {
                $r = $ipLog->reason ?? '';
                if (str_contains($r, 'Resize Window') || str_contains($r, 'right-click') || str_contains($r, 'Klik Kanan') || str_contains($r, 'panel DevTools') || str_contains($r, 'devtools-open')) {
                    $ipLog->status = 'normal';
                    $ipLog->reason = 'Aktivitas Normal Pengguna';
                }
            }

            // Staf/Admin & Whitelist SELALU berstatus normal (tidak pernah suspicious atau abnormal)
            if ($isAdminOrStaff) {
                $ipLog->status = 'normal';
                $ipLog->reason = 'Aktivitas Normal Administrator/Staff';
            } elseif ($isGuestWhitelisted) {
                if ($ipLog->status !== 'abnormal') {
                    $ipLog->status = 'normal';
                    $ipLog->reason = 'Aktivitas Normal (IP Whitelist)';
                }
            } elseif ($isSuspicious && $ipLog->status !== 'abnormal') {
                $ipLog->status = 'suspicious';
                $ipLog->reason = $reason;
            }

            $ipLog->user_agent = substr($userAgent, 0, 255);
            $ipLog->last_activity = substr($request->method() . ' ' . $request->fullUrl(), 0, 500);
            $ipLog->request_count = ($ipLog->request_count ?? 0) + 1;
            $ipLog->last_activity_at = now();

            // Hubungkan HANYA dengan akun yang benar-benar sedang login di sesi ini
            if (Auth::check() && Schema::hasColumn('ip_logs', 'user_id')) {
                $ipLog->user_id = Auth::id();
                if ($isAdminOrStaff) {
                    $ipLog->status = 'normal';
                    $ipLog->reason = 'Aktivitas Normal Administrator/Staff';
                }
            }

            try {
                $ipLog->save();
            } catch (\Throwable $saveEx) {
                if ($ipLog->status === 'suspicious') {
                    try {
                        DB::statement("ALTER TABLE ip_logs MODIFY COLUMN status ENUM('normal', 'abnormal', 'suspicious') NOT NULL DEFAULT 'normal'");
                        $ipLog->save();
                    } catch (\Throwable $alterEx) {
                        try {
                            $ipLog->status = 'normal';
                            $ipLog->save();
                        } catch (\Throwable $e3) {}
                    }
                }
            }
        } catch (\Throwable $e) {}

        return $next($request);
    }

    /**
     * Cari ban aktif untuk IP dari semua sumber (tabel ip_bans -> cache -> ip_logs 'abnormal').
     * Mengembalikan null jika IP tidak diblokir.
     */
    private function findActiveBan(string $ip): ?array
    {
        // Prioritas 1: Tabel ip_bans (sumber utama, punya kategori & durasi)
        try {
            $activeBan = IpBan::activeFor($ip);
            if ($activeBan) {
                return [
                    'reason'       => $activeBan->reason,
                    'category'     => $activeBan->category,
                    'banned_at'    => $activeBan->created_at,
                    'banned_until' => $activeBan->banned_until,
                ];
            }
        } catch (\Throwable $e) {}

        // Prioritas 2: Cache ban global
        $cached = Cache::get("banned_ip_{$ip}");
        if ($cached) {
            $cachedReason = is_string($cached) ? $cached : null;
            return [
                'reason'       => $cachedReason,
                'category'     => BanReason::categorize($cachedReason),
                'banned_at'    => null,
                'banned_until' => null,
            ];
        }

        // Prioritas 3: Log IP yang ditandai 'abnormal' oleh admin
        try {
            $bannedLog = IpLog::where('ip_address', $ip)
                ->where('status', 'abnormal')
                ->latest('last_activity_at')
                ->first();

            if ($bannedLog) {
                $logReason = $bannedLog->reason ?: 'Akses Anda diblokir oleh Administrator sistem.';
                Cache::put("banned_ip_{$ip}", $logReason, 86400);
                return [
                    'reason'       => $logReason,
                    'category'     => BanReason::categorize($logReason),
                    'banned_at'    => $bannedLog->updated_at,
                    'banned_until' => null,
                ];
            }
        } catch (\Throwable $e) {}

        return null;
    }

    /**
     * Halaman login boleh dibuka dari IP yang diblokir HANYA lewat tautan "Akses Staff"
     * (?staff_access=1). Flag disimpan di sesi agar redirect validasi login tetap bisa diakses.
     */
    private function isStaffLoginBypass(Request $request): bool
    {
        if (!$request->is('auth/login')) {
            return false;
        }

        if ($request->query('staff_access') === '1' && $request->hasSession()) {
            $request->session()->put('staff_login_bypass', true);
        }

        return $request->hasSession() && $request->session()->get('staff_login_bypass') === true;
    }

    /**
     * Logout user (jika ada) lalu tampilkan halaman ban kustom dengan alasan yang sesuai.
     */
    private function banResponse(Request $request, array $data)
    {
        if (Auth::check()) {
            Auth::logout();
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        }

        $reasonText = $data['reason'] ?? null;
        $bannedAt   = $data['banned_at'] ?? null;
        $until      = $data['banned_until'] ?? null;

        // Request AJAX/fetch (mis. ping detektor) cukup dapat JSON, bukan HTML
        if ($request->expectsJson()) {
            return response()->json([
                'banned' => true,
                'reason' => $reasonText,
            ], 403);
        }

        $blockedAtFormatted = now()->translatedFormat('d F Y, H:i') . ' WIB';
        if ($bannedAt instanceof \Carbon\Carbon || $bannedAt instanceof \DateTimeInterface) {
            $blockedAtFormatted = $bannedAt->copy()->timezone(config('app.timezone', 'Asia/Jakarta'))->translatedFormat('d F Y, H:i') . ' WIB';
        } elseif (is_string($bannedAt) && !empty($bannedAt)) {
            $blockedAtFormatted = $bannedAt;
        }

        $bannedUntilFormatted = null;
        if ($until instanceof \Carbon\Carbon || $until instanceof \DateTimeInterface) {
            $bannedUntilFormatted = $until->copy()->timezone(config('app.timezone', 'Asia/Jakarta'))->translatedFormat('d F Y, H:i') . ' WIB';
        } elseif (is_string($until)) {
            $bannedUntilFormatted = $until;
        }

        return response()->view('errors.ip-blocked', [
            'ip'           => $data['ip'] ?? $request->ip(),
            'username'     => $data['username'] ?? null,
            'email'        => $data['email'] ?? null,
            'reason'       => $reasonText,
            'category'     => $data['category'] ?? BanReason::categorize($reasonText),
            'blocked_at'   => $blockedAtFormatted,
            'banned_until' => $bannedUntilFormatted,
        ], 403)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}