<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

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
     */
    public static function ensureTable(): bool
    {
        if (Cache::get('ip_bans_table_ready')) {
            return true;
        }

        try {
            if (!Schema::hasTable('ip_bans')) {
                Schema::create('ip_bans', function ($table) {
                    $table->id();
                    $table->string('ip_address', 45)->unique();
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->string('category', 30)->default('manual');
                    $table->text('reason')->nullable();
                    $table->timestamp('banned_until')->nullable();
                    $table->string('banned_by', 100)->nullable();
                    $table->timestamps();
                });
            }
            Cache::forever('ip_bans_table_ready', true);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Ambil ban AKTIF untuk sebuah IP (di-cache 60 detik agar ringan).
     * Ban yang sudah kedaluwarsa otomatis dihapus.
     */
    public static function activeFor(string $ip): ?self
    {
        if (!self::ensureTable()) {
            return null;
        }

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
            self::where('ip_address', $ip)->delete();
        } catch (\Throwable $e) {}
        self::forgetCache($ip);
    }
}
