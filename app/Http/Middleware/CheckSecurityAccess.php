<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AllowedIp;

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

        // Jika Whitelist masih kosong, daftarkan IP Admin pertama secara otomatis
        if (empty($allowedIps)) {
            try {
                AllowedIp::create([
                    'ip_address' => $userIp,
                    'label'      => 'Master Admin (Auto Registrasi Awal)',
                    'added_by'   => auth()->user()->name ?? 'System'
                ]);
            } catch (\Throwable $e) {}
            $whitelist[] = $userIp;
        }

        // 2. CEK WHITELIST IP
        if (!in_array($userIp, $whitelist)) {
            abort(403, 'AKSES DITOLAK: Anda tidak memiliki izin mengakses Pusat Keamanan Sistem.');
        }

        return $next($request);
    }
}