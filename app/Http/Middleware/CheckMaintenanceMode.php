<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. DAFTAR HALAMAN PUBLIK & ADMIN (Selalu diizinkan diakses)
        if ($request->is(
            '/',            // Landing page
            'auth*',        // Halaman Login & Register (karena berawalan auth/)
            'password*',    // Fitur Reset Password
            'forgot-password', 
            'reset-password',
            'admin*'        // Panel Admin (Aman mutlak)
        )) {
            return $next($request);
        }

        $statusFile = storage_path('framework/maintenance_mode.json');

        if (file_exists($statusFile)) {
            $data = json_decode(file_get_contents($statusFile), true);
            $targetRole = $data['target_role'] ?? 'none';

            if ($targetRole !== 'none') {
                $user = auth()->user();

                if ($user) {
                    $email = strtolower(trim($user->email ?? ''));
                    $roleName = strtolower(trim($user->role?->role_name ?? ''));

                    // Jika admin, selalu lolos
                    if ($roleName === 'admin' || str_contains($email, 'admin')) {
                        return $next($request);
                    }

                    // Jika user biasa memiliki role yang sedang di-down
                    if ($roleName === strtolower($targetRole)) {
                        abort(503, 'Akun anda sedang dibatasi karena maintenance.');
                    }
                }

                // Jika target adalah 'all' (Down Semua User kecuali Admin) untuk area user
                if ($targetRole === 'all') {
                    abort(503, 'Sistem sedang dalam pemeliharaan.');
                }
            }
        }

        return $next($request);
    }
}