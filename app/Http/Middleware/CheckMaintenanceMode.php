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
        $user = auth()->user();
        $email = strtolower(trim($user->email ?? ''));
        $roleName = strtolower(trim($user->role?->role_name ?? ''));
        $isAdmin = $user && ($roleName === 'admin' || str_contains($email, 'admin'));

        // 1. Admin SELALU diizinkan mengakses & melihat pratinjau seluruh halaman sistem saat maintenance
        if ($isAdmin) {
            return $next($request);
        }

        // 2. Route Infrastruktur & Admin yang selalu diizinkan diakses (Auth, Reset Password, Admin Panel)
        if ($request->is(
            'auth*',        // Halaman Login & Register
            'password*',    // Reset Password
            'forgot-password',
            'reset-password',
            'admin*',       // Panel Admin
            'logout',
            'suspended-notice',
            'appeal*'
        )) {
            return $next($request);
        }

        // 3. Cek Maintenance bawaan Artisan (php artisan down)
        if (app()->isDownForMaintenance()) {
            return $this->maintenanceResponse(null);
        }

        // 4. Cek Maintenance Mode Kustom (per Peran / Semua User)
        $statusFile = storage_path('framework/maintenance_mode.json');

        if (file_exists($statusFile)) {
            $data       = json_decode(file_get_contents($statusFile), true);
            $targetRole = strtolower(trim($data['target_role'] ?? 'none'));
            $endAt      = $data['end_at'] ?? null;

            // AUTO-EXPIRE PAKAI TIMESTAMP (100% Presisi WIB)
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
                // Jika mode 'all' (Down Semua User kecuali Admin)
                if ($targetRole === 'all') {
                    return $this->maintenanceResponse($endAt);
                }

                // Jika role user saat ini (atau guest jika role down spesifik) sedang di-maintenance
                if ($user && $roleName === $targetRole) {
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