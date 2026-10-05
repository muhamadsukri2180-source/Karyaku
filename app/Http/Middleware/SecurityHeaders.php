<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    /**
     * Handle an incoming request and attach cyber security headers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Perlindungan terhadap Clickjacking & Phishing (Mencegah situs disematkan di iframe berbahaya)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Perlindungan terhadap Cross-Site Scripting (XSS)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Perlindungan terhadap MIME Confusion & Malware sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Mencegah kebocoran URL sensitif via referrer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Batasi fitur browser yang tidak diperlukan (Hardware API)
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // HSTS dan CSP: Hanya diaktifkan di production agar tidak memblokir Vite/Livewire di local
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://cdn.tailwindcss.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data: https: blob:; connect-src 'self'; frame-ancestors 'self'; base-uri 'self'; form-action 'self';");
        }

        return $response;
    }
}
