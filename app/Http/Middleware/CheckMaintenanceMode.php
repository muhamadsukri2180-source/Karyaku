<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Route Publik & Admin yang selalu diizinkan diakses
        if ($request->is(
            '/',            // Landing page
            'auth*',        // Halaman Login & Register
            'password*',    // Reset Password
            'forgot-password',
            'reset-password',
            'admin*'        // Panel Admin (Selalu bisa diakses admin)
        )) {
            return $next($request);
        }

        $statusFile = storage_path('framework/maintenance_mode.json');

        if (file_exists($statusFile)) {
            $data       = json_decode(file_get_contents($statusFile), true);
            $targetRole = $data['target_role'] ?? 'none';
            $endAt      = $data['end_at'] ?? null;

            // =========================================================
            // AUTO-EXPIRE PAKAI TIMESTAMP (100% Presisi WIB)
            // Jika waktu sekarang sudah melebihi target, matikan maintenance
            // =========================================================
            if ($targetRole !== 'none' && $endAt) {
                try {
                    $targetTimestamp = isset($data['timestamp']) 
                        ? $data['timestamp'] 
                        : Carbon::parse($endAt, 'Asia/Jakarta')->timestamp;

                    if (now('Asia/Jakarta')->timestamp >= $targetTimestamp) {
                        @unlink($statusFile);
                        return $next($request);
                    }
                } catch (\Exception $e) {
                    @unlink($statusFile);
                    return $next($request);
                }
            }

            if ($targetRole !== 'none') {
                $user = auth()->user();

                if ($user) {
                    $email    = strtolower(trim($user->email ?? ''));
                    $roleName = strtolower(trim($user->role?->role_name ?? ''));

                    // Admin selalu lolos
                    if ($roleName === 'admin' || str_contains($email, 'admin')) {
                        return $next($request);
                    }

                    // Jika role user saat ini sedang di-maintenance
                    if ($roleName === strtolower($targetRole)) {
                        return $this->maintenanceResponse($endAt);
                    }
                }

                // Jika mode 'all' (Down Semua User kecuali Admin)
                if ($targetRole === 'all') {
                    return $this->maintenanceResponse($endAt);
                }
            }
        }

        return $next($request);
    }

    private function maintenanceResponse(?string $endAt): Response
    {
        return response()
            ->view('errors.503', [
                'maintenance_end_at' => $endAt,
            ], 503)
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
}