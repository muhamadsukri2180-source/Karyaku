<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\IpLog;

class DetectAbnormalIp
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $path = $request->path();
        $userAgent = $request->header('User-Agent');

        // Daftar endpoint jebakan (Honeypot) yang sering dicari bot/peretas
        $suspiciousPaths = [
            'wp-admin', 'wp-login.php', '.env', 'phpmyadmin', 
            'admin.php', 'config.json', 'backup.sql', 'xmlrpc.php'
        ];

        $isSuspicious = false;
        $reason = null;

        foreach ($suspiciousPaths as $badPath) {
            if (str_contains(strtolower($path), $badPath)) {
                $isSuspicious = true;
                $reason = "Mencoba mengakses endpoint terlarang: /{$path}";
                break;
            }
        }

        $ipLog = IpLog::firstOrNew(['ip_address' => $ip]);

        if ($isSuspicious) {
            $ipLog->status = 'abnormal';
            $ipLog->reason = $reason;
        }

        $ipLog->user_agent = substr($userAgent ?? 'Unknown', 0, 255);
        $ipLog->last_activity = $request->method() . ' ' . $request->fullUrl();
        $ipLog->request_count = ($ipLog->request_count ?? 0) + 1;
        $ipLog->last_activity_at = now();
        $ipLog->save();

        return $next($request);
    }
}