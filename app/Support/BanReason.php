<?php

namespace App\Support;

/**
 * Menerjemahkan alasan (reason) ban mentah dari sistem deteksi / admin
 * menjadi kategori, judul, pesan ramah, ikon, dan warna untuk halaman ban.
 */
class BanReason
{
    public const CATEGORIES = [
        'devtools' => [
            'label'   => 'Inspect Element / DevTools',
            'title'   => 'Membuka Inspect Element / DevTools',
            'action'  => 'mencoba membuka Inspect Element / DevTools browser (F12, Ctrl+Shift+I, Ctrl+U) untuk mengutak-atik kode website',
            'icon'    => 'fa-solid fa-code',
            'color'   => 'violet',
        ],
        'sqli' => [
            'label'   => 'SQL Injection',
            'title'   => 'Percobaan SQL Injection',
            'action'  => 'menyisipkan perintah SQL berbahaya (SQL Injection) untuk membobol atau mencuri data database',
            'icon'    => 'fa-solid fa-database',
            'color'   => 'red',
        ],
        'xss' => [
            'label'   => 'Cross-Site Scripting (XSS)',
            'title'   => 'Percobaan Cross-Site Scripting',
            'action'  => 'menyisipkan skrip berbahaya (XSS) untuk mencuri sesi atau data pengguna lain',
            'icon'    => 'fa-solid fa-file-code',
            'color'   => 'orange',
        ],
        'honeypot' => [
            'label'   => 'Akses File Terlarang',
            'title'   => 'Mengakses File / Endpoint Terlarang',
            'action'  => 'mencoba mengakses file atau halaman rahasia server (seperti .env, wp-admin, phpmyadmin)',
            'icon'    => 'fa-solid fa-folder-closed',
            'color'   => 'amber',
        ],
        'bot' => [
            'label'   => 'Bot / Scanner Otomatis',
            'title'   => 'Terdeteksi Bot / Scanner',
            'action'  => 'menggunakan bot atau tools scanner otomatis (sqlmap, curl, dsb.) untuk memindai celah keamanan',
            'icon'    => 'fa-solid fa-robot',
            'color'   => 'sky',
        ],
        'ddos' => [
            'label'   => 'Serangan DoS / Flooding',
            'title'   => 'Serangan DoS / Flooding',
            'action'  => 'membanjiri server dengan permintaan berlebihan (DoS / Flooding) hingga mengganggu layanan',
            'icon'    => 'fa-solid fa-bolt',
            'color'   => 'yellow',
        ],
        'bruteforce' => [
            'label'   => 'Brute Force Login',
            'title'   => 'Percobaan Brute Force',
            'action'  => 'mencoba menebak username / kata sandi secara berulang (Brute Force)',
            'icon'    => 'fa-solid fa-key',
            'color'   => 'rose',
        ],
        'manual' => [
            'label'   => 'Pelanggaran Kebijakan',
            'title'   => 'Diblokir oleh Administrator',
            'action'  => 'melanggar syarat dan ketentuan penggunaan platform Karyaku',
            'icon'    => 'fa-solid fa-gavel',
            'color'   => 'red',
        ],
    ];

    /**
     * Deteksi kategori dari teks reason.
     */
    public static function categorize(?string $reason): string
    {
        $r = strtolower((string) $reason);

        if ($r === '') {
            return 'manual';
        }

        $map = [
            'sqli'       => ['sql injection', 'sqli', 'union select', 'information_schema'],
            'xss'        => ['xss', 'cross-site', 'cross site', '<script'],
            'devtools'   => ['devtools', 'inspect', 'f12', 'ctrl+shift', 'ctrl+u', 'view source', 'console'],
            'ddos'       => ['serangan dos', 'dos /', 'flood', 'ddos', 'denial of service'],
            'bot'        => ['bot', 'scanner', 'spam', 'user-agent'],
            'honeypot'   => ['endpoint terlarang', 'honeypot', '.env', 'wp-admin', 'phpmyadmin', 'file terlarang'],
            'bruteforce' => ['brute', 'menebak', 'password salah', 'percobaan login'],
        ];

        foreach ($map as $category => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($r, $kw)) {
                    return $category;
                }
            }
        }

        return 'manual';
    }

    /**
     * Data lengkap siap tampil untuk halaman ban.
     */
    public static function present(?string $reason, ?string $category = null): array
    {
        $category = ($category && isset(self::CATEGORIES[$category]) && $category !== 'manual')
            ? $category
            : self::categorize($reason);

        $meta = self::CATEGORIES[$category];

        $cleanReason = trim((string) $reason);
        $cleanReason = preg_replace('/^terdeteksi\s*:\s*/i', '', $cleanReason);

        $genericReasons = [
            '',
            'aktivitas normal pengguna',
            'akun dan alamat ip anda telah diblokir oleh administrator sistem.',
            'akun dan alamat ip anda telah diblokir oleh administrator.',
            'dibekukan & diblokir total oleh administrator',
            'dibekukan manual oleh admin',
        ];

        if ($category === 'manual') {
            $headline = in_array(strtolower($cleanReason), $genericReasons, true)
                ? 'Anda terkena ban karena telah ' . $meta['action'] . '.'
                : 'Anda terkena ban karena: ' . rtrim($cleanReason, '.') . '.';
        } else {
            $headline = 'Anda terkena ban karena telah ' . $meta['action'] . '.';
        }

        return [
            'category' => $category,
            'label'    => $meta['label'],
            'title'    => $meta['title'],
            'headline' => $headline,
            'detail'   => in_array(strtolower($cleanReason), $genericReasons, true) ? $meta['label'] : $cleanReason,
            'icon'     => $meta['icon'],
            'color'    => $meta['color'],
        ];
    }
}
