<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Pastikan hanya user dengan role "Admin" yang boleh mengakses halaman ini.
     * Cocok dipasang di route group: Route::middleware(['auth','admin'])->group(...)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Pastikan relasi role sudah di-load agar tidak query berulang
        $roleName = $user->role?->role_name;

        if (strtolower((string) $roleName) !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman Admin.');
        }

        if ($user->status === 'blocked') {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Akun Anda diblokir.']);
        }

        return $next($request);
    }
}