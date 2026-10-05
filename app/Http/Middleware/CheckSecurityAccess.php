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

        $userIp = $request->ip();

        // Ambil daftar IP Whitelist
        $allowedIps = AllowedIp::pluck('ip_address')->toArray();

        // Selalu izinkan IP Localhost (Laragon/Development)
        $whitelist = array_merge($allowedIps, ['127.0.0.1', '::1']);

        // Jika IP pengguna belum di-whitelist tapi sudah lulus verifikasi PIN (session security_verified_at aktif),
        // otomatis tambahkan IP publik saat ini ke Whitelist agar Admin tidak terblokir saat IP berubah/hosting!
        if (!in_array($userIp, $whitelist)) {
            try {
                AllowedIp::firstOrCreate(
                    ['ip_address' => $userIp],
                    ['label' => 'Admin Verified (' . (auth()->user()->name ?? 'Admin') . ')', 'added_by' => auth()->user()->name ?? 'Admin']
                );
                Cache::forget("allowed_ip_{$userIp}");
                $whitelist[] = $userIp;
            } catch (\Throwable $e) {}
        }

        // 2. CEK WHITELIST IP
        if (!in_array($userIp, $whitelist)) {
            return redirect()->route('admin.security.verify')->with('warning', 'IP Anda (' . $userIp . ') belum terverifikasi. Silakan verifikasi PIN terlebih dahulu.');
        }

        return $next($request);
    }
}