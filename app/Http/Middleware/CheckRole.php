<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $userRole = strtolower(trim($user->role->role_name ?? ''));

        // Admin selalu diizinkan mengakses dan melihat pratinjau semua halaman
        if ($userRole === 'admin') {
            return $next($request);
        }

        $allowedRoles = array_map(fn($r) => strtolower(trim($r)), $roles);

        if (! in_array($userRole, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}