<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;  // WAJIB ditambah untuk cek username

class AuthController extends Controller
{
    // Halaman Login
    public function showLoginForm()
    {
        return view('auth.login');
    }



    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Cek apakah username ada
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Akun tidak terdaftar.',
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

        // 5. Redirect sesuai role (punya kamu tetap dipakai)
        $role = Auth::user()->role;

        if ($role === 'Kepsek') {
            return redirect()->intended('/admin/laporan');
        }

        if (in_array($role, ['Admin', 'Guru', 'AsistenLab'])) {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/siswa/dashboard');
    }




    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
