<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Deteksi IP pengunjung asli pada server hosting (Cloudflare, LiteSpeed, Nginx, Apache Reverse Proxy, cPanel, dll)
        $realIp = null;

        if ($cfIp = $request->header('CF-Connecting-IP')) {
            $realIp = trim($cfIp);
        } elseif ($xRealIp = $request->header('X-Real-IP')) {
            $realIp = trim($xRealIp);
        } elseif ($forwardedFor = $request->header('X-Forwarded-For')) {
            $ips = array_map('trim', explode(',', $forwardedFor));
            foreach ($ips as $possibleIp) {
                if (filter_var($possibleIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    $realIp = $possibleIp;
                    break;
                }
            }
            if (!$realIp && !empty($ips[0]) && filter_var($ips[0], FILTER_VALIDATE_IP)) {
                $realIp = $ips[0];
            }
        }

        if ($realIp && filter_var($realIp, FILTER_VALIDATE_IP)) {
            $request->server->set('REMOTE_ADDR', $realIp);
        }

        return parent::handle($request, $next);
    }
}

