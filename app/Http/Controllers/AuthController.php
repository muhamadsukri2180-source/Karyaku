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
use App\Models\IpBan;
use App\Support\BanReason;

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
    public function showSuspendedNotice(Request $request)
    {
        $ip = $request->ip();

        // Jika IP pengunjung sedang diblokir, wajib LANGSUNG tampilkan halaman IP Diblokir (errors.ip-blocked)
        if (IpBan::isBanned($ip) || Cache::has("banned_ip_{$ip}")) {
            $ban = IpBan::activeFor($ip);
            $reason = $ban?->reason ?: (Cache::get("banned_ip_{$ip}") ?: 'Akses Anda diblokir oleh Administrator sistem.');
            $category = $ban?->category ?: BanReason::categorize($reason);
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

        $info = session('suspended_info');


        if (!$info) {
            $userId = session('suspended_user_id') ?? old('user_id');
            $user = $userId ? User::find($userId) : (Auth::check() ? Auth::user() : null);

            if ($user && ($user->status === 'blocked' || Cache::has("banned_user_{$user->id_user}"))) {
                $countdown = $user->suspend_countdown;
                $appeal = AccountAppeal::where('user_id', $user->id_user)->latest()->first();

                $info = [
                    'user_id'          => $user->id_user,
                    'username'         => $user->name,
                    'email'            => $user->email,
                    'reason'           => $user->suspend_reason ?: (Cache::get("banned_user_{$user->id_user}") ?: 'Pelanggaran syarat dan ketentuan komunitas Karyaku'),
                    'duration_text'    => $countdown['formatted'] ?? 'Permanen (Tanpa batas waktu)',
                    'is_permanent'     => empty($user->suspended_until),
                    'is_expired'       => false,
                    'target_timestamp' => $user->suspended_until ? $user->suspended_until->timestamp * 1000 : null,
                    'appeal_status'    => $appeal ? $appeal->status : null,
                    'appeal_date'      => $appeal ? $appeal->created_at->translatedFormat('d M Y H:i') : null,
                    'appeal_admin_note'=> $appeal ? $appeal->admin_note : null,
                ];
            } else {
                $info = [
                    'user_id'          => $userId,
                    'username'         => 'Pengguna Karyaku',
                    'email'            => '',
                    'reason'           => 'Akun Anda sedang ditangguhkan karena indikasi pelanggaran aturan komunitas.',
                    'duration_text'    => 'Permanen (Tanpa batas waktu)',
                    'is_permanent'     => true,
                    'is_expired'       => false,
                    'target_timestamp' => null,
                    'appeal_status'    => null,
                    'appeal_date'      => null,
                    'appeal_admin_note'=> null,
                ];
            }
        }

        return view('disband.ban', compact('info'));
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

        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari user yang sedang login
        $checkUser = User::with('role')
            ->where('name', $credentials['username'])
            ->orWhere('email', $credentials['username'])
            ->first();

        // 1. Staff (Admin, Verifikator, CS) SELALU diizinkan login (kebal terhadap IP ban agar admin tidak terkunci)
        $isStaff = $checkUser && in_array(strtolower($checkUser->role?->role_name ?? ''), ['admin', 'verifikator', 'customer_service']);

        if ($isStaff) {
            if (Auth::attempt(['name' => $checkUser->name, 'password' => $credentials['password']])) {
                $request->session()->regenerate();
                LoginHistory::create([
                    'username'   => $checkUser->name,
                    'ip_address' => $ip,
                    'user_agent' => $request->userAgent(),
                    'type'       => 'login'
                ]);
                return $this->redirectByRole($checkUser);
            }
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        // 2. Cek apakah IP Pengunjung sedang diblokir
        $activeBan = IpBan::activeFor($ip);
        $isIpBanned = (bool)$activeBan
            || Cache::has("banned_ip_{$ip}") 
            || \App\Models\IpLog::where('ip_address', $ip)->where('status', 'abnormal')->exists();

        $isWhitelistedIp = in_array($ip, \App\Models\AllowedIp::pluck('ip_address')->toArray());

        if ($isIpBanned && !$isWhitelistedIp) {
            $banReason = $activeBan?->reason 
                ?: Cache::get("banned_ip_{$ip}") 
                ?: \App\Models\IpLog::where('ip_address', $ip)->where('status', 'abnormal')->latest('last_activity_at')->value('reason') 
                ?: 'Alamat IP Anda telah diblokir oleh Administrator.';

            $banCategory = $activeBan?->category ?: BanReason::categorize($banReason);

            return response()->view('errors.ip-blocked', [
                'ip'         => $ip,
                'username'   => $checkUser?->name,
                'email'      => $checkUser?->email,
                'category'   => $banCategory,
                'reason'     => $banReason,
                'blocked_at' => now()->translatedFormat('d F Y, H:i') . ' WIB'
            ], 403);
        }

        // 3. Cek apakah Akun Pengguna yang coba login sedang diblokir oleh Admin
        if ($checkUser && ($checkUser->status === 'blocked' || Cache::has("banned_user_{$checkUser->id_user}"))) {
            // Auto Unsuspend jika waktu pembekuan sudah selesai
            if ($checkUser->suspended_until && $checkUser->suspended_until->isPast()) {
                $checkUser->status = 'active';
                $checkUser->suspended_until = null;
                $checkUser->suspend_reason = null;
                $checkUser->save();
                Cache::forget("banned_user_{$checkUser->id_user}");
            } else {
                $reason = $checkUser->suspend_reason 
                    ?: Cache::get("banned_user_{$checkUser->id_user}") 
                    ?: 'Pelanggaran syarat dan ketentuan komunitas Karyaku';

                $countdown = $checkUser->suspend_countdown;
                $appeal = AccountAppeal::where('user_id', $checkUser->id_user)->latest()->first();

                $suspendedInfo = [
                    'user_id'          => $checkUser->id_user,
                    'username'         => $checkUser->name,
                    'email'            => $checkUser->email,
                    'reason'           => $reason,
                    'duration_text'    => $countdown['formatted'] ?? 'Permanen (Tanpa batas waktu)',
                    'is_permanent'     => empty($checkUser->suspended_until),
                    'is_expired'       => false,
                    'target_timestamp' => $checkUser->suspended_until ? $checkUser->suspended_until->timestamp * 1000 : null,
                    'appeal_status'    => $appeal ? $appeal->status : null,
                    'appeal_date'      => $appeal ? $appeal->created_at->translatedFormat('d M Y H:i') : null,
                    'appeal_admin_note'=> $appeal ? $appeal->admin_note : null,
                ];

                session(['suspended_user_id' => $checkUser->id_user]);
                return redirect()->route('suspended.notice')->with('suspended_info', $suspendedInfo);
            }
        }

        if (! Auth::attempt(['name' => $credentials['username'], 'password' => $credentials['password']])) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        $user = Auth::user();

        if ($user->status === 'blocked' || Cache::has("banned_user_{$user->id_user}")) {
            $reason = $user->suspend_reason 
                ?: Cache::get("banned_user_{$user->id_user}") 
                ?: 'Pelanggaran syarat dan ketentuan komunitas Karyaku';

            $countdown = $user->suspend_countdown;
            $appeal = AccountAppeal::where('user_id', $user->id_user)->latest()->first();

            $suspendedInfo = [
                'user_id'          => $user->id_user,
                'username'         => $user->name,
                'email'            => $user->email,
                'reason'           => $reason,
                'duration_text'    => $countdown['formatted'] ?? 'Permanen (Tanpa batas waktu)',
                'is_permanent'     => empty($user->suspended_until),
                'is_expired'       => false,
                'target_timestamp' => $user->suspended_until ? $user->suspended_until->timestamp * 1000 : null,
                'appeal_status'    => $appeal ? $appeal->status : null,
                'appeal_date'      => $appeal ? $appeal->created_at->translatedFormat('d M Y H:i') : null,
                'appeal_admin_note'=> $appeal ? $appeal->admin_note : null,
            ];

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            session(['suspended_user_id' => $user->id_user]);

            return redirect()->route('suspended.notice')->with('suspended_info', $suspendedInfo);
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
        // Jika user_id belum terisi di form tapi ada input email/username
        if (!$request->filled('user_id') && $request->filled('account_identifier')) {
            $identifier = trim($request->input('account_identifier'));
            $foundUser = User::where('email', $identifier)->orWhere('name', $identifier)->first();
            if ($foundUser) {
                $request->merge(['user_id' => $foundUser->id_user]);
            }
        }

        $request->validate([
            'user_id'     => 'required|exists:users,id_user',
            'reason'      => 'required|string|min:5|max:2000',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ], [
            'user_id.required'  => 'Akun pengguna tidak teridentifikasi. Masukkan email atau username Anda.',
            'user_id.exists'    => 'Akun pengguna tidak ditemukan dalam database.',
            'reason.required'   => 'Alasan pembelaan / penjelasan wajib diisi.',
            'reason.min'        => 'Alasan minimal 5 karakter.',
            'proof_image.image' => 'File bukti harus berupa gambar.',
            'proof_image.max'   => 'Ukuran gambar maksimal 5MB.',
        ]);

        $imagePath = null;
        if ($request->hasFile('proof_image')) {
            $imagePath = $request->file('proof_image')->store('appeals', 'public');
            try {
                $destDir = public_path('storage/appeals');
                if (!file_exists($destDir)) {
                    @mkdir($destDir, 0755, true);
                }
                @copy(storage_path('app/public/' . $imagePath), public_path('storage/' . $imagePath));
            } catch (\Throwable $e) {}
        }


        AccountAppeal::create([
            'user_id'     => $request->user_id,
            'reason'      => $request->reason,
            'proof_image' => $imagePath,
            'status'      => 'pending',
        ]);

        session(['suspended_user_id' => $request->user_id]);

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