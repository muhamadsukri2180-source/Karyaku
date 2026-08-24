<?php

namespace App\Http\Controllers;

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

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->status !== 'active') {
            Auth::logout();
            throw ValidationException::withMessages([
                'username' => 'Akun Anda tidak aktif. Hubungi admin.',
            ]);
        }

        if ($request->query('role') === 'penjual' && ($user->role->role_name ?? null) === 'pembeli') {
            return redirect()->route('pembeli.seller.registration.create');
        }

        if ($request->session()->has('url.intended')) {
            return redirect()->intended();
        }

        return $this->redirectByRole($user);
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
            'admin'       => redirect()->route('admin.dashboard'),
            'verifikator' => redirect()->route('verifikator.dashboard'),
            'penjual'     => redirect()->route('penjual.dashboard'),
            'pembeli'     => redirect()->route('pembeli.dashboard'),
            'customer_service' => redirect()->route('cs.dashboard'),
            default       => redirect()->route('landing'),
        };
    }
}