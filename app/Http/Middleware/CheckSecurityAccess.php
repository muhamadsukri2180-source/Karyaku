<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AllowedIp;
use Illuminate\Support\Facades\Cache;

class CheckSecurityAccess
{
    public function handle(Request $request, Closure $next)
    {
        // 1. CEK SESI VERIFIKASI PIN & SECURITY PASSWORD (Berlaku 15 Menit)
        $verifiedAt = session('security_verified_at');
        if (!$verifiedAt || now()->diffInMinutes($verifiedAt) > 15) {
            session()->forget('security_verified_at');
            return redirect()->route('admin.security.verify')->with('warning', 'Sesi keamanan Anda telah berakhir. Silakan verifikasi ulang.');
        }

        // CATATAN: IP admin TIDAK lagi otomatis dimasukkan ke Whitelist.
        // Dulu setiap kali admin membuka halaman ini, IP-nya di-whitelist otomatis,
        // sehingga SEMUA pengunjung yang berbagi IP tsb (WiFi/NAT yang sama) ikut kebal
        // deteksi & ban. Halaman ini sudah dilindungi Password + PIN + Captcha di atas.

        return $next($request);
    }
}