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

        return $response;
    }
}
