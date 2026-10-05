<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AllowedIp;

class CheckSecurityAccess
{
    public function handle(Request $request, Closure $next)
    {
        $userIp = $request->ip();

        // Ambil daftar IP Whitelist
        $allowedIps = AllowedIp::pluck('ip_address')->toArray();

        // Jika Whitelist masih kosong, daftarkan IP Admin pertama secara otomatis (Auto Self-Whitelist)
        if (empty($allowedIps)) {
            AllowedIp::create([
                'ip_address' => $userIp,
                'label'      => 'Master Admin (Auto Registrasi Awal)',
                'added_by'   => 'System'
            ]);
            $allowedIps[] = $userIp;
        }

        // 1. CEK WHITELIST IP
        if (!in_array($userIp, $allowedIps)) {
            abort(403, 'AKSES DITOLAK: IP Anda (' . $userIp . ') tidak memiliki izin mengakses Pusat Keamanan.');
        }

        // 2. CEK SESI VERIFIKASI (Berlaku 15 Menit)
        $verifiedAt = session('security_verified_at');
        if (!$verifiedAt || now()->diffInMinutes($verifiedAt) > 15) {
            session()->forget('security_verified_at');
            return redirect()->route('admin.security.verify');
        }

        return $next($request);
    }
}