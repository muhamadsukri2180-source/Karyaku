<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'password' => 'required|string|min:6|confirmed',
            'terms'    => 'required',
        ]);

        // Semua user yang daftar lewat form ini otomatis jadi "pembeli"
        $role = Role::where('role_name', 'pembeli')->firstOrFail();

        User::create([
            'id_role'  => $role->id_role,
            'name'     => $validated['username'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status'   => 'active',
        ]);

        // Tidak auto-login. Arahkan ke halaman login,
        // sambil bawa email agar bisa langsung diisi otomatis di form login.
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

    // Helper redirect sesuai role
    protected function redirectByRole(User $user)
    {
        $roleName = $user->role->role_name ?? null;

        return match ($roleName) {
            'admin'       => redirect()->route('admin.dashboard'),
            'verifikator' => redirect()->route('verifikator.dashboard'),
            'penjual'     => redirect()->route('penjual.dashboard'),
            'pembeli'     => redirect()->route('pembeli.dashboard'),
            default       => redirect()->route('landing'),
        };
    }
}