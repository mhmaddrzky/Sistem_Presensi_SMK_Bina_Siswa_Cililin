<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Menampilkan Halaman Login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses Login
     */
    public function login(Request $request)
    {
        // 1. Validasi Input dengan pesan bahasa Indonesia
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Cek apakah username terdaftar
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Username tidak terdaftar.',
            ])->onlyInput('username');
        }

        // 3. Coba Autentikasi (cek password)
        if (!Auth::attempt($request->only('username', 'password'))) {
            return back()->withErrors([
                'password' => 'Password salah.',
            ])->onlyInput('username');
        }

        // 4. Login sukses → regenerate session
        $request->session()->regenerate();

        // 5. Redirect sesuai role
        $role = Auth::user()->role;

        if ($role === 'Kepsek') {
            return redirect()->intended('/admin/laporan');
        }

        if (in_array($role, ['Admin', 'Guru', 'AsistenLab'])) {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/siswa/dashboard');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}