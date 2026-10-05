<?php

namespace App\Http\Controllers;

use App\Models\AccountAppeal;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\LoginHistory;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }
    public function showRegister()
    {
        return view('auth.register');
    }
    public function showSuspendedNotice()
    {
        $info = session('suspended_info') ?? [];
        return response()->view('errors.ip-blocked', [
            'ip'         => request()->ip(),
            'username'   => $info['username'] ?? null,
            'email'      => $info['email'] ?? null,
            'reason'     => $info['reason'] ?? 'Akun dan alamat IP Anda telah diblokir oleh Administrator sistem.',
            'blocked_at' => now()->translatedFormat('d F Y, H:i') . ' WIB'
        ], 403);
    }
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,name',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'phone'    => ['required', 'string', 'max:20', 'regex:/^(\+62|08)[0-9]{8,13}$/'],
            'password' => 'required|string|min:8|confirmed',
            'terms'    => 'required',
        ], [
            'phone.required' => 'No. telepon wajib diisi.',
            'phone.regex'    => 'No. telepon harus diawali 08 atau +62 dan minimal 10 digit.',
        ]);

        $role = Role::where('role_name', 'pembeli')->firstOrFail();

        User::create([
            'id_role'  => $role->id_role,
            'name'     => $validated['username'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'status'   => 'active',
        ]);

        return redirect()
            ->route('auth.login')
            ->with('success', 'Registrasi berhasil! Silakan masuk dengan akun kamu.')
            ->with('registered_username', $validated['username']);
    }
    public function login(Request $request)
    {
        $ip = $request->ip();

        // 1. Cek apakah IP Pengunjung sedang diblokir
        $isIpBanned = \Illuminate\Support\Facades\Cache::has("banned_ip_{$ip}") 
            || \App\Models\IpLog::where('ip_address', $ip)->where('status', 'abnormal')->exists();

        $isWhitelistedIp = in_array($ip, \App\Models\AllowedIp::pluck('ip_address')->toArray());

        if ($isIpBanned && !$isWhitelistedIp) {
            $reason = \Illuminate\Support\Facades\Cache::get("banned_ip_{$ip}") 
                ?? \App\Models\IpLog::where('ip_address', $ip)->where('status', 'abnormal')->value('reason') 
                ?? 'Alamat IP Anda telah diblokir oleh Administrator.';

            return response()->view('errors.ip-blocked', [
                'ip'         => $ip,
                'reason'     => $reason,
                'blocked_at' => now()->translatedFormat('d F Y, H:i') . ' WIB'
            ], 403);
        }

        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Cek apakah Akun Pengguna yang coba login sedang diblokir oleh Admin
        $checkUser = User::where('name', $credentials['username'])
            ->orWhere('email', $credentials['username'])
            ->first();

        if ($checkUser && ($checkUser->status === 'blocked' || \Illuminate\Support\Facades\Cache::has("banned_user_{$checkUser->id_user}"))) {
            // Auto Unsuspend jika waktu pembekuan sudah selesai
            if ($checkUser->suspended_until && $checkUser->suspended_until->isPast()) {
                $checkUser->status = 'active';
                $checkUser->suspended_until = null;
                $checkUser->suspend_reason = null;
                $checkUser->save();
                \Illuminate\Support\Facades\Cache::forget("banned_user_{$checkUser->id_user}");
            } else {
                $reason = $checkUser->suspend_reason 
                    ?: \Illuminate\Support\Facades\Cache::get("banned_user_{$checkUser->id_user}") 
                    ?: 'Akun dan alamat IP Anda telah diblokir oleh Administrator sistem.';

                return response()->view('errors.ip-blocked', [
                    'ip'         => $ip,
                    'username'   => $checkUser->name,
                    'email'      => $checkUser->email,
                    'reason'     => $reason,
                    'blocked_at' => now()->translatedFormat('d F Y, H:i') . ' WIB'
                ], 403);
            }
        }

        if (! Auth::attempt(['name' => $credentials['username'], 'password' => $credentials['password']])) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        $user = Auth::user();

        if ($user->status === 'blocked' || \Illuminate\Support\Facades\Cache::has("banned_user_{$user->id_user}")) {
            $reason = $user->suspend_reason 
                ?: \Illuminate\Support\Facades\Cache::get("banned_user_{$user->id_user}") 
                ?: 'Akun dan alamat IP Anda telah diblokir oleh Administrator.';

            $userName = $user->name;
            $userEmail = $user->email;

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->view('errors.ip-blocked', [
                'ip'         => $ip,
                'username'   => $userName,
                'email'      => $userEmail,
                'reason'     => $reason,
                'blocked_at' => now()->translatedFormat('d F Y, H:i') . ' WIB'
            ], 403);
        }

        if ($user->status !== 'active') {
            Auth::logout();
            throw ValidationException::withMessages([
                'username' => 'Akun Anda tidak aktif. Hubungi admin.',
            ]);
        }

        $request->session()->regenerate();

        LoginHistory::create([
            'username' => $user->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'type' => 'login'
        ]);

        if ($request->query('role') === 'penjual' && ($user->role->role_name ?? null) === 'pembeli') {
            return redirect()->route('pembeli.seller.registration.create');
        }

        if ($request->session()->has('url.intended')) {
            return redirect()->intended();
        }

        return $this->redirectByRole($user);
    }
    public function submitAppeal(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id_user',
            'reason'      => 'required|string|min:5|max:2000',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ], [
            'reason.required'   => 'Alasan pembelaan / penjelasan wajib diisi.',
            'reason.min'        => 'Alasan minimal 5 karakter.',
            'proof_image.image' => 'File bukti harus berupa gambar.',
            'proof_image.max'   => 'Ukuran gambar maksimal 5MB.',
        ]);

        $imagePath = null;
        if ($request->hasFile('proof_image')) {
            $imagePath = $request->file('proof_image')->store('appeals', 'public');
        }

        AccountAppeal::create([
            'user_id'     => $request->user_id,
            'reason'      => $request->reason,
            'proof_image' => $imagePath,
            'status'      => 'pending',
        ]);

        $user = User::find($request->user_id);
        $countdown = $user ? $user->suspend_countdown : ['formatted' => '-'];
        $appeal = AccountAppeal::where('user_id', $request->user_id)->latest()->first();

        $suspendedInfo = [
            'user_id'          => $user->id_user ?? $request->user_id,
            'username'         => $user->name ?? '',
            'email'            => $user->email ?? '',
            'reason'           => $user->suspend_reason ?? 'Pelanggaran syarat dan ketentuan komunitas Karyaku',
            'duration_text'    => $countdown['formatted'],
            'appeal_status'    => $appeal ? $appeal->status : 'pending',
            'appeal_date'      => $appeal ? $appeal->created_at->translatedFormat('d M Y H:i') : now()->translatedFormat('d M Y H:i'),
            'appeal_admin_note'=> $appeal ? $appeal->admin_note : null,
        ];

        return redirect()->route('suspended.notice')
            ->with('suspended_info', $suspendedInfo)
            ->with('success_appeal', 'Pengajuan banding Anda berhasil dikirim! Tim Admin akan segera meninjau laporan dan bukti Anda.');
    }

    // Logout
    public function logout(Request $request)
    {
        if (Auth::check()) {
            LoginHistory::create([
                'username' => Auth::user()->name,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'type' => 'logout'
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot_password');
    }
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tersebut tidak terdaftar di sistem kami.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Tautan reset password berhasil dikirim ke email Anda.')
            : back()->withErrors(['email' => 'Terjadi kesalahan, silakan coba lagi.']);
    }
    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset_password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
            }
        );
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('auth.login')->with('success', 'Password berhasil diubah! Silakan masuk dengan password baru.')
            : back()->withErrors(['email' => [__($status)]]);
    }
    protected function redirectByRole(User $user)
    {
        $roleName = $user->role->role_name ?? null;

        return match ($roleName) {
            'admin'            => redirect()->route('admin.dashboard'),
            'verifikator'      => redirect()->route('verifikator.dashboard'),
            'penjual'          => redirect()->route('penjual.dashboard'),
            'pembeli'          => redirect()->route('pembeli.dashboard'),
            'customer_service' => redirect()->route('cs.dashboard'),
            default            => redirect()->route('landing'),
        };
    }
}