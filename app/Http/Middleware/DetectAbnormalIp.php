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
    private const STAFF_ROLES = [
        'admin',
        'verifikator',
        'customer_service'
    ];

    public function handle(Request $request, Closure $next)
    {
        // =====================================================
        // INFORMASI REQUEST
        // =====================================================

        $ip = $request->ip() ?: 'UNKNOWN';
        $path = $request->path();
        $userAgent = $request->userAgent() ?? 'Unknown';

        // =====================================================
        // 1. LEWATI ASSET STATIS
        // =====================================================

        if (
            preg_match(
                '/\.(css|js|png|jpg|jpeg|webp|gif|svg|ico|woff|woff2|ttf|map)$/i',
                $path
            )
        ) {
            return $next($request);
        }

        // =====================================================
        // CEK ADMIN / STAFF
        // =====================================================

        $isAdminOrStaff = false;

        if (Auth::check()) {
            $userRole = strtolower(
                Auth::user()->role?->role_name ?? ''
            );

            $isAdminOrStaff = in_array(
                $userRole,
                self::STAFF_ROLES,
                true
            );
        }

        $exemptStaff = (bool) config(
            'security_monitor.exempt_staff',
            false
        );

        // Staff dapat dikecualikan melalui config
        if ($isAdminOrStaff && $exemptStaff) {
            return $next($request);
        }

        // =====================================================
        // CEK WHITELIST IP
        // =====================================================

        $isWhitelisted = Cache::remember(
            "allowed_ip_{$ip}",
            60,
            function () use ($ip) {
                try {
                    return AllowedIp::where(
                        'ip_address',
                        $ip
                    )->exists();
                } catch (\Throwable $e) {
                    return false;
                }
            }
        );

        // Whitelist hanya membebaskan guest
        $isGuestWhitelisted =
            $isWhitelisted && !Auth::check();

        // =====================================================
        // 2. CEK IP YANG SUDAH DIBLOKIR
        // =====================================================

        if (!$isWhitelisted && !$isAdminOrStaff) {

            $ban = $this->findActiveBan($ip);

            if ($ban) {

                // Bypass khusus login staff
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

        // =====================================================
        // 3. CEK FROZEN SESSION
        // =====================================================

        if (
            $request->hasSession() &&
            !$isAdminOrStaff
        ) {

            $sessId = substr(
                md5($request->session()->getId()),
                0,
                16
            );

            $frozen = Cache::get(
                "frozen_session_{$sessId}"
            );

            if ($frozen) {

                return $this->banResponse($request, [
                    'ip' => $ip,

                    'reason' => is_string($frozen)
                        ? $frozen
                        : 'Sesi browser Anda sedang dibekukan oleh Administrator karena indikasi pelanggaran.',
                ]);
            }
        }

        // =====================================================
        // 4. CEK AKUN USER YANG DITANGGUHKAN
        // =====================================================

        if (
            Auth::check() &&
            !$isAdminOrStaff
        ) {

            $currUser = Auth::user();

            // Halaman suspend & appeal tetap bisa dibuka
            if (
                $request->is('suspended-notice*') ||
                $request->is('appeal*')
            ) {
                return $next($request);
            }

            if (
                $currUser->status === 'blocked' ||
                Cache::has(
                    "banned_user_{$currUser->id_user}"
                )
            ) {

                // Auto-unban jika masa suspend habis
                if (
                    $currUser->suspended_until &&
                    $currUser->suspended_until->isPast()
                ) {

                    $currUser->status = 'active';
                    $currUser->suspended_until = null;
                    $currUser->suspend_reason = null;
                    $currUser->save();

                    Cache::forget(
                        "banned_user_{$currUser->id_user}"
                    );

                } else {

                    $uReason =
                        $currUser->suspend_reason
                        ?: Cache::get(
                            "banned_user_{$currUser->id_user}"
                        )
                        ?: 'Akun Anda sedang ditangguhkan oleh Administrator sistem.';

                    $countdown =
                        $currUser->suspend_countdown;

                    $appeal =
                        \App\Models\AccountAppeal::where(
                            'user_id',
                            $currUser->id_user
                        )
                        ->latest()
                        ->first();

                    $suspendedInfo = [

                        'user_id' =>
                            $currUser->id_user,

                        'username' =>
                            $currUser->name,

                        'email' =>
                            $currUser->email,

                        'reason' =>
                            $uReason,

                        'duration_text' =>
                            $countdown['formatted']
                            ?? 'Permanen (Tanpa batas waktu)',

                        'is_permanent' =>
                            empty(
                                $currUser->suspended_until
                            ),

                        'is_expired' =>
                            false,

                        'target_timestamp' =>
                            $currUser->suspended_until
                            ? $currUser->suspended_until->timestamp * 1000
                            : null,

                        'appeal_status' =>
                            $appeal
                            ? $appeal->status
                            : null,

                        'appeal_date' =>
                            $appeal
                            ? $appeal->created_at
                                ->translatedFormat('d M Y H:i')
                            : null,

                        'appeal_admin_note' =>
                            $appeal
                            ? $appeal->admin_note
                            : null,
                    ];

                    Auth::logout();

                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    session([
                        'suspended_user_id' =>
                            $currUser->id_user
                    ]);

                    return redirect()
                        ->route('suspended.notice')
                        ->with(
                            'suspended_info',
                            $suspendedInfo
                        );
                }
            }
        }

        // =====================================================
        // 5. PEMERIKSAAN ANCAMAN KEAMANAN
        // =====================================================

        $isSuspicious = false;
        $reason = null;

        // Hanya pengunjung umum yang diperiksa
        if (
            !$isAdminOrStaff &&
            !$isGuestWhitelisted
        ) {

            // =================================================
            // A. HONEYPOT / PATH MENCURIGAKAN
            // =================================================

            $suspiciousPaths = [
                'wp-admin',
                'wp-login.php',
                '.env',
                'phpmyadmin',
                'admin.php',
                'config.json',
                'backup.sql',
                'xmlrpc.php',
                '.git',
                'composer.json',
                'eval-stdin.php',
                'shell.php',
                'alfa.php',
                'wso.php',
                'c99.php',
                'web.config'
            ];

            $lowerPath = strtolower($path);

            foreach ($suspiciousPaths as $badPath) {

                if (str_contains(
                    $lowerPath,
                    $badPath
                )) {

                    $isSuspicious = true;

                    $reason =
                        "Mencoba mengakses endpoint terlarang: /{$path}";

                    break;
                }
            }

            // =================================================
            // B. SQL INJECTION & XSS
            // =================================================

            if (
                !$isSuspicious &&
                !$request->is('admin/product*') &&
                !$request->is('seller/product*')
            ) {

                $inputData =
                    urldecode(
                        $request->fullUrl()
                    )
                    . ' '
                    . json_encode(
                        $request->except([
                            'password',
                            'password_confirmation',
                            '_token'
                        ])
                    );

                // -----------------------------
                // SQL INJECTION
                // -----------------------------

                $sqliPatterns = [

                    '/\bunion\s+(all\s+)?select\b/i',

                    '/\b(drop|truncate|alter)\s+table\b/i',

                    '/\b(and|or)\b\s+[\'"]?\d+[\'"]?\s*=\s*[\'"]?\d+/i',

                    '/\binformation_schema\b/i',

                    '/\bload_file\s*\(/i',

                    '/\binto\s+(outfile|dumpfile)\b/i',

                    '/\bsleep\s*\(\s*\d+\s*\)/i',

                    '/\bbenchmark\s*\(/i',

                    '/([\'"])\s*(or|and)\s*([\'"])?\w+([\'"])?\s*=/i'
                ];

                foreach ($sqliPatterns as $pattern) {

                    if (preg_match(
                        $pattern,
                        $inputData
                    )) {

                        $isSuspicious = true;

                        $reason =
                            'Terdeteksi percobaan SQL Injection (SQLi)';

                        break;
                    }
                }

                // -----------------------------
                // XSS
                // -----------------------------

                if (!$isSuspicious) {

                    $xssPatterns = [

                        '/<script\b[^>]*>(.*?)<\/script>/is',

                        '/<script\b/i',

                        '/javascript\s*:/i',

                        '/\bon(error|load|click|mouseover|submit|focus|blur|keydown|keyup)\s*=/i',

                        '/document\.cookie/i',

                        '/\beval\s*\(/i'
                    ];

                    foreach ($xssPatterns as $pattern) {

                        if (preg_match(
                            $pattern,
                            $inputData
                        )) {

                            $isSuspicious = true;

                            $reason =
                                'Terdeteksi percobaan Cross-Site Scripting (XSS)';

                            break;
                        }
                    }
                }
            }

            // =================================================
            // C. DETEKSI BOT / SCANNER
            // =================================================

            if (!$isSuspicious) {

                $lowerUa = strtolower($userAgent);

                $isLegitBrowser =
                    str_contains(
                        $lowerUa,
                        'mozilla/'
                    )
                    &&
                    (
                        str_contains(
                            $lowerUa,
                            'chrome/'
                        )
                        ||
                        str_contains(
                            $lowerUa,
                            'safari/'
                        )
                        ||
                        str_contains(
                            $lowerUa,
                            'firefox/'
                        )
                        ||
                        str_contains(
                            $lowerUa,
                            'edg/'
                        )
                    );

                $isHeadless =
                    str_contains(
                        $lowerUa,
                        'headless'
                    )
                    ||
                    str_contains(
                        $lowerUa,
                        'phantomjs'
                    )
                    ||
                    str_contains(
                        $lowerUa,
                        'selenium'
                    )
                    ||
                    str_contains(
                        $lowerUa,
                        'puppeteer'
                    )
                    ||
                    str_contains(
                        $lowerUa,
                        'playwright'
                    );

                if (
                    !$isLegitBrowser ||
                    $isHeadless
                ) {

                    if (
                        strlen(
                            trim($lowerUa)
                        ) < 5
                    ) {

                        $isSuspicious = true;

                        $reason =
                            'Terdeteksi Bot Spam (User-Agent Kosong / Anomali)';

                    } else {

                        $badBotSignatures = [

                            'sqlmap' =>
                                'SQLMap Penetration Tool',

                            'nikto' =>
                                'Nikto Vulnerability Scanner',

                            'nmap' =>
                                'Nmap Port Scanner',

                            'zgrab' =>
                                'ZGrab Network Scanner',

                            'masscan' =>
                                'Masscan Fast Scanner',

                            'acunetix' =>
                                'Acunetix Security Scanner',

                            'dirbuster' =>
                                'DirBuster Brute Forcer',

                            'gobuster' =>
                                'Gobuster Path Scanner',

                            'wpscan' =>
                                'WPScan WordPress Scanner',

                            'hydra' =>
                                'THC Hydra Login Cracker',

                            'burpsuite' =>
                                'Burp Suite Security Proxy',

                            'python-requests' =>
                                'Python Requests Bot',

                            'python-urllib' =>
                                'Python Urllib Crawler',

                            'aiohttp' =>
                                'Python AioHTTP Bot',

                            'go-http-client' =>
                                'Golang HTTP Client',

                            'java/' =>
                                'Java Automated Client',

                            'curl/' =>
                                'cURL Automated Command',

                            'wget/' =>
                                'Wget Downloader Bot',

                            'scrapy' =>
                                'Scrapy Web Scraper',

                            'httpclient' =>
                                'Apache HttpClient',

                            'libwww-perl' =>
                                'Perl LWP Bot',

                            'postmanruntime' =>
                                'Postman Automated Runner',

                            'headlesschrome' =>
                                'Headless Chrome Automation',

                            'phantomjs' =>
                                'PhantomJS Automated Script',

                            'selenium' =>
                                'Selenium Automated WebDriver',

                            'puppeteer' =>
                                'Puppeteer Automated Script',

                            'playwright' =>
                                'Playwright Automation Tool',
                        ];

                        foreach (
                            $badBotSignatures
                            as $sig => $name
                        ) {

                            if (
                                str_contains(
                                    $lowerUa,
                                    $sig
                                )
                            ) {

                                $isSuspicious = true;

                                $reason =
                                    "Terdeteksi Bot / Scanner Berbahaya ({$name})";

                                break;
                            }
                        }
                    }
                }
            }

            // =================================================
            // D. DETEKSI DDOS / FLOODING
            // =================================================

            $floodKey =
                "ddos_flood_count_{$ip}";

            $reqInMinute =
                Cache::increment($floodKey);

            if ($reqInMinute === 1) {

                Cache::put(
                    $floodKey,
                    1,
                    60
                );
            }

            if ($reqInMinute > 120) {

                $isSuspicious = true;

                $reason =
                    "Terdeteksi serangan DoS / Flooding ({$reqInMinute} req/menit)";
            }

            // =================================================
            // E. INDIKASI DEVTOOLS
            // =================================================
            //
            // Catatan:
            // Browser tidak otomatis mengirim informasi
            // bahwa F12 / Inspect sedang dibuka.
            //
            // Pemeriksaan ini hanya berguna jika terdapat
            // indikasi "devtools" pada URL/User-Agent.
            // =================================================

            $requestUri =
                strtolower(
                    $request->fullUrl()
                );

            $lowerUa =
                strtolower(
                    $request->userAgent() ?? ''
                );

            if (
                str_contains(
                    $requestUri,
                    'devtools'
                )
                ||
                str_contains(
                    $lowerUa,
                    'devtools'
                )
            ) {

                $isSuspicious = true;

                $reason =
                    'Terdeteksi indikasi membuka panel DevTools / Inspect Element';
            }
        }

        // =====================================================
        // 6. SIMPAN IP LOG KE DATABASE
        // =====================================================

        try {

            // Pastikan IP selalu tersedia
            $ip = $request->ip() ?: 'UNKNOWN';

            // Session ID
            $sessionId = null;

            if ($request->hasSession()) {

                $sessionId = substr(
                    md5(
                        $request->session()->getId()
                    ),
                    0,
                    16
                );
            }

            $today =
                now()->toDateString();

            // Cari log IP hari ini
            $ipLog = IpLog::where(
                'ip_address',
                $ip
            )
            ->whereDate(
                'created_at',
                $today
            )
            ->first();

            // Kalau belum ada, buat baru
            if (!$ipLog) {

                $ipLog = new IpLog();

                $ipLog->ip_address = $ip;

                $ipLog->status = 'normal';

                $ipLog->request_count = 0;

                if (
                    Schema::hasColumn(
                        'ip_logs',
                        'session_id'
                    )
                    &&
                    $sessionId
                ) {

                    $ipLog->session_id =
                        $sessionId;
                }
            }

            // =================================================
            // TENTUKAN STATUS
            // =================================================

            if ($isAdminOrStaff) {

                $ipLog->status = 'normal';

                $ipLog->reason =
                    'Aktivitas Normal Administrator/Staff';

            } elseif ($isGuestWhitelisted) {

                if (
                    $ipLog->status !== 'abnormal'
                ) {

                    $ipLog->status = 'normal';

                    $ipLog->reason =
                        'Aktivitas Normal (IP Whitelist)';
                }

            } elseif ($isSuspicious) {

                $ipLog->status =
                    'suspicious';

                $ipLog->reason =
                    $reason
                    ?: 'Aktivitas mencurigakan terdeteksi';
            }

            // =================================================
            // SIMPAN INFORMASI IP
            // =================================================

            $ipLog->ip_address = $ip;

            $ipLog->user_agent =
                substr(
                    $userAgent,
                    0,
                    255
                );

            $ipLog->last_activity =
                substr(
                    $request->method()
                    . ' '
                    . $request->fullUrl(),
                    0,
                    500
                );

            $ipLog->request_count =
                ($ipLog->request_count ?? 0) + 1;

            $ipLog->last_activity_at =
                now();

            // =================================================
            // HUBUNGKAN USER LOGIN
            // =================================================

            if (
                Auth::check() &&
                Schema::hasColumn(
                    'ip_logs',
                    'user_id'
                )
            ) {

                $ipLog->user_id =
                    Auth::id();

                // Staff selalu normal
                if ($isAdminOrStaff) {

                    $ipLog->status =
                        'normal';

                    $ipLog->reason =
                        'Aktivitas Normal Administrator/Staff';
                }
            }

            // =================================================
            // SIMPAN
            // =================================================

            $ipLog->save();

            // =================================================
            // LOG UNTUK DEBUG
            // =================================================

            Log::info(
                'SECURITY IP LOG',
                [
                    'ip' =>
                        $ipLog->ip_address,

                    'status' =>
                        $ipLog->status,

                    'reason' =>
                        $ipLog->reason,

                    'user_id' =>
                        Auth::id(),

                    'request' =>
                        $request->method()
                        . ' '
                        . $request->fullUrl(),
                ]
            );

        } catch (\Throwable $e) {

            // Jangan sembunyikan error saat debugging
            Log::error(
                'GAGAL MENYIMPAN SECURITY IP LOG',
                [
                    'ip' =>
                        $request->ip(),

                    'error' =>
                        $e->getMessage(),

                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine(),
                ]
            );
        }

        // =====================================================
        // LANJUTKAN REQUEST
        // =====================================================

        return $next($request);
    }

    /**
     * Cari ban aktif untuk IP.
     */
    private function findActiveBan(
        string $ip
    ): ?array {

        // =====================================================
        // PRIORITAS 1: TABEL IP BANS
        // =====================================================

        try {

            $activeBan =
                IpBan::activeFor($ip);

            if ($activeBan) {

                return [

                    'reason' =>
                        $activeBan->reason,

                    'category' =>
                        $activeBan->category,

                    'banned_at' =>
                        $activeBan->created_at,

                    'banned_until' =>
                        $activeBan->banned_until,
                ];
            }

        } catch (\Throwable $e) {
            // Lanjut ke cache
        }

        // =====================================================
        // PRIORITAS 2: CACHE
        // =====================================================

        $cached =
            Cache::get(
                "banned_ip_{$ip}"
            );

        if ($cached) {

            $cachedReason =
                is_string($cached)
                    ? $cached
                    : null;

            return [

                'reason' =>
                    $cachedReason,

                'category' =>
                    BanReason::categorize(
                        $cachedReason
                    ),

                'banned_at' =>
                    null,

                'banned_until' =>
                    null,
            ];
        }

        // =====================================================
        // PRIORITAS 3: IP LOG ABNORMAL
        // =====================================================

        try {

            $bannedLog =
                IpLog::where(
                    'ip_address',
                    $ip
                )
                ->where(
                    'status',
                    'abnormal'
                )
                ->latest(
                    'last_activity_at'
                )
                ->first();

            if ($bannedLog) {

                $logReason =
                    $bannedLog->reason
                    ?: 'Akses Anda diblokir oleh Administrator sistem.';

                Cache::put(
                    "banned_ip_{$ip}",
                    $logReason,
                    86400
                );

                return [

                    'reason' =>
                        $logReason,

                    'category' =>
                        BanReason::categorize(
                            $logReason
                        ),

                    'banned_at' =>
                        $bannedLog->updated_at,

                    'banned_until' =>
                        null,
                ];
            }

        } catch (\Throwable $e) {
            // Tidak ada ban
        }

        return null;
    }

    /**
     * Bypass login staff.
     */
    private function isStaffLoginBypass(
        Request $request
    ): bool {

        if (!$request->is('auth/login')) {
            return false;
        }

        if (
            $request->query('staff_access') === '1'
            &&
            $request->hasSession()
        ) {

            $request->session()->put(
                'staff_login_bypass',
                true
            );
        }

        return
            $request->hasSession()
            &&
            $request->session()->get(
                'staff_login_bypass'
            ) === true;
    }

    /**
     * Tampilkan halaman IP blocked.
     */
    private function banResponse(
        Request $request,
        array $data
    ) {

        // Logout user
        if (Auth::check()) {

            Auth::logout();

            if ($request->hasSession()) {

                $request->session()->invalidate();

                $request->session()->regenerateToken();
            }
        }

        $reasonText =
            $data['reason'] ?? null;

        $bannedAt =
            $data['banned_at'] ?? null;

        $until =
            $data['banned_until'] ?? null;

        // =====================================================
        // REQUEST AJAX / FETCH
        // =====================================================

        if ($request->expectsJson()) {

            return response()->json(
                [
                    'banned' => true,
                    'reason' => $reasonText,
                ],
                403
            );
        }

        // =====================================================
        // FORMAT TANGGAL BLOCK
        // =====================================================

        $blockedAtFormatted =
            now()
                ->translatedFormat(
                    'd F Y, H:i'
                )
            . ' WIB';

        if (
            $bannedAt instanceof \Carbon\Carbon ||
            $bannedAt instanceof \DateTimeInterface
        ) {

            $blockedAtFormatted =
                $bannedAt
                    ->copy()
                    ->timezone(
                        config(
                            'app.timezone',
                            'Asia/Jakarta'
                        )
                    )
                    ->translatedFormat(
                        'd F Y, H:i'
                    )
                . ' WIB';

        } elseif (
            is_string($bannedAt)
            &&
            !empty($bannedAt)
        ) {

            $blockedAtFormatted =
                $bannedAt;
        }

        // =====================================================
        // FORMAT TANGGAL BAN BERAKHIR
        // =====================================================

        $bannedUntilFormatted = null;

        if (
            $until instanceof \Carbon\Carbon ||
            $until instanceof \DateTimeInterface
        ) {

            $bannedUntilFormatted =
                $until
                    ->copy()
                    ->timezone(
                        config(
                            'app.timezone',
                            'Asia/Jakarta'
                        )
                    )
                    ->translatedFormat(
                        'd F Y, H:i'
                    )
                . ' WIB';

        } elseif (is_string($until)) {

            $bannedUntilFormatted =
                $until;
        }

        // =====================================================
        // TAMPILKAN HALAMAN BLOCK
        // =====================================================

        return response()
            ->view(
                'errors.ip-blocked',
                [

                    'ip' =>
                        $data['ip']
                        ?? $request->ip(),

                    'username' =>
                        $data['username']
                        ?? null,

                    'email' =>
                        $data['email']
                        ?? null,

                    'reason' =>
                        $reasonText,

                    'category' =>
                        $data['category']
                        ?? BanReason::categorize(
                            $reasonText
                        ),

                    'blocked_at' =>
                        $blockedAtFormatted,

                    'banned_until' =>
                        $bannedUntilFormatted,
                ],
                403
            )
            ->header(
                'Cache-Control',
                'no-store, no-cache, must-revalidate'
            );
    }
}