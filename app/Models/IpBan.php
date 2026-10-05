<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IpBan extends Model
{
    protected $table = 'ip_bans';

    protected $fillable = [
        'ip_address',
        'user_id',
        'category',
        'reason',
        'banned_until',
        'banned_by',
    ];

    protected $casts = [
        'banned_until' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function isPermanent(): bool
    {
        return $this->banned_until === null;
    }

    public function isExpired(): bool
    {
        return $this->banned_until !== null && $this->banned_until->isPast();
    }

    /**
     * Pastikan tabel ip_bans tersedia (self-healing untuk hosting yang belum migrate).
     * Menggunakan CREATE TABLE IF NOT EXISTS murni sehingga instan dan tidak error.
     */
    public static function ensureTable(): bool
    {
        try {
            DB::statement("
                CREATE TABLE IF NOT EXISTS `ip_bans` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `ip_address` varchar(45) NOT NULL,
                    `user_id` bigint(20) unsigned DEFAULT NULL,
                    `category` varchar(30) NOT NULL DEFAULT 'manual',
                    `reason` text DEFAULT NULL,
                    `banned_until` timestamp NULL DEFAULT NULL,
                    `banned_by` varchar(100) DEFAULT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `ip_bans_ip_address_unique` (`ip_address`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
            return true;
        } catch (\Throwable $e) {
            Log::warning('IpBan::ensureTable warning: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cek apakah IP sedang dalam daftar ban aktif (Aman dari QueryException jika tabel belum ada).
     */
    public static function isBanned(string $ip): bool
    {
        try {
            self::ensureTable();
            return self::where('ip_address', $ip)->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Simpan atau perbarui status ban untuk sebuah IP.
     */
    public static function recordBan(string $ip, array $attributes): bool
    {
        try {
            self::ensureTable();
            self::updateOrCreate(['ip_address' => $ip], $attributes);
            self::forgetCache($ip);
            return true;
        } catch (\Throwable $e) {
            Log::warning('IpBan::recordBan warning: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ambil ban AKTIF untuk sebuah IP (di-cache 60 detik agar ringan).
     * Ban yang sudah kedaluwarsa otomatis dihapus.
     */
    public static function activeFor(string $ip): ?self
    {
        try {
            self::ensureTable();
        } catch (\Throwable $e) {}

        try {
            $cached = Cache::remember("ip_ban_lookup_{$ip}", 60, function () use ($ip) {
                try {
                    return self::where('ip_address', $ip)->first()?->getAttributes() ?? false;
                } catch (\Throwable $e) {
                    return false;
                }
            });

            if (!$cached) {
                return null;
            }

            $ban = (new self())->newFromBuilder($cached);

            if ($ban->isExpired()) {
                self::lift($ip);
                return null;
            }

            return $ban;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function forgetCache(string $ip): void
    {
        Cache::forget("ip_ban_lookup_{$ip}");
        Cache::forget("banned_ip_{$ip}");
    }

    /**
     * Hapus ban sebuah IP beserta cache terkait.
     */
    public static function lift(string $ip): void
    {
        try {
            self::ensureTable();
            self::where('ip_address', $ip)->delete();
        } catch (\Throwable $e) {}
        self::forgetCache($ip);
    }
}
