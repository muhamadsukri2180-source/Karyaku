<?php

namespace App\Http\Controllers;

use App\Models\AccountAppeal;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Tampilkan form register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Tampilkan Halaman Khusus Penangguhan (Ban)
    public function showSuspendedNotice()
    {
        if (! session()->has('suspended_info') && ! old('user_id')) {
            return redirect()->route('auth.login');
        }

        return view('disband.ban');
    }

    // Proses register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,name',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'phone'    => ['required', 'string', 'max:20', 'regex:/^(\+62|08)[0-9]{8,13}$/'],
            'password' => 'required|string|min:6|confirmed',
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

    // Proses login (pakai username, bukan email)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt(['name' => $credentials['username'], 'password' => $credentials['password']])) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        $user = Auth::user();

        // Cek apakah user diblokir / disuspend
        if ($user->status === 'blocked') {
            // Jika masa suspend sudah lewat waktu, aktifkan kembali otomatis
            if ($user->suspended_until && $user->suspended_until->isPast()) {
                $user->status = 'active';
                $user->suspended_until = null;
                $user->suspend_reason = null;
                $user->save();
            } else {
                // Masih dalam masa penangguhan (Suspend)
                $countdown = $user->suspend_countdown;
                $appeal = AccountAppeal::where('user_id', $user->id_user)->latest()->first();

                $suspendedInfo = [
                    'user_id'          => $user->id_user,
                    'username'         => $user->name,
                    'email'            => $user->email,
                    'reason'           => $user->suspend_reason ?: 'Pelanggaran syarat dan ketentuan komunitas Karyaku',
                    'duration_text'    => $countdown['formatted'],
                    'is_permanent'     => $countdown['is_permanent'],
                    'is_expired'       => $countdown['is_expired'],
                    'target_timestamp' => $countdown['target_timestamp'] ?? null,
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

        if ($user->status !== 'active') {
            Auth::logout();
            throw ValidationException::withMessages([
                'username' => 'Akun Anda tidak aktif. Hubungi admin.',
            ]);
        }

        $request->session()->regenerate();

        if ($request->query('role') === 'penjual' && ($user->role->role_name ?? null) === 'pembeli') {
            return redirect()->route('pembeli.seller.registration.create');
        }

        if ($request->session()->has('url.intended')) {
            return redirect()->intended();
        }

        return $this->redirectByRole($user);
    }

    // Proses pengajuan banding oleh pengguna terblokir
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
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }

    // ==========================================
    // FITUR LUPA & RESET PASSWORD
    // ==========================================

    // Tampilkan form lupa password (kirim email)
    public function showForgotPassword()
    {
        return view('auth.forgot_password');
    }

    // Kirim tautan reset password ke email
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

    // Tampilkan form buat password baru (dari link email)
    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset_password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // Proses simpan password baru
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

    // Helper redirect sesuai role
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