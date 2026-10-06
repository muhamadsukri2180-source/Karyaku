<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountAppeal;

class CheckSuspended
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();

        // Jika IP diblokir, langsung tampilkan halaman IP diblokir (errors.ip-blocked)
        if (\App\Models\IpBan::isBanned($ip) || \Illuminate\Support\Facades\Cache::has("banned_ip_{$ip}")) {
            $ban = \App\Models\IpBan::activeFor($ip);
            $reason = $ban?->reason ?: (\Illuminate\Support\Facades\Cache::get("banned_ip_{$ip}") ?: 'Akses Anda diblokir oleh Administrator.');
            $category = $ban?->category ?: \App\Support\BanReason::categorize($reason);
            $blockedAt = ($ban?->created_at ?? now())->translatedFormat('d F Y, H:i') . ' WIB';
            $bannedUntil = $ban?->banned_until ? $ban->banned_until->translatedFormat('d F Y, H:i') . ' WIB' : null;

            return response()->view('errors.ip-blocked', [
                'ip'           => $ip,
                'reason'       => $reason,
                'category'     => $category,
                'blocked_at'   => $blockedAt,
                'banned_until' => $bannedUntil,
            ], 403);
        }

        if (Auth::check()) {

            $user = Auth::user();

            if ($user->status === 'blocked') {
                // Auto Unsuspend jika masa penangguhan sudah selesai
                if ($user->suspended_until && $user->suspended_until->isPast()) {
                    $user->status = 'active';
                    $user->suspended_until = null;
                    $user->suspend_reason = null;
                    $user->save();

                    return $next($request);
                }

                // Ambil data suspend & banding terakhir
                $countdown = $user->suspend_countdown;
                $appeal = AccountAppeal::where('user_id', $user->id_user)->latest()->first();

                $suspendedInfo = [
                    'user_id'          => $user->id_user,
                    'username'         => $user->name,
                    'email'            => $user->email,
                    'reason'           => $user->suspend_reason ?: 'Pelanggaran syarat dan ketentuan komunitas Karyaku',
                    'duration_text'    => $countdown['formatted'],
                    'appeal_status'    => $appeal ? $appeal->status : null,
                    'appeal_date'      => $appeal ? $appeal->created_at->translatedFormat('d M Y H:i') : null,
                    'appeal_admin_note'=> $appeal ? $appeal->admin_note : null,
                ];

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('suspended.notice')->with('suspended_info', $suspendedInfo);
            }
        }

        return $next($request);
    }
}