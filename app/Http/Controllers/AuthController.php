<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }


public function login(Request $request)
{
    // 1. Validasi Input
    $credentials = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    // 2. Coba Otentikasi
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        // 3. 🛑 LOGIKA REDIRECT BERDASARKAN ROLE (Final Fix)
        $user = Auth::user();
        $role = $user->role;
        
        // Kepsek diarahkan langsung ke Laporan
        if ($role === 'Kepsek') { 
            return redirect()->intended('/admin/laporan'); 
        }
        
        // Admin, Guru, AsistenLab diarahkan ke Dashboard Admin
        if ($role === 'Admin' || $role === 'Guru' || $role === 'AsistenLab') {
            return redirect()->intended('/admin/dashboard'); 
        }
        
        // Siswa diarahkan ke Dashboard Siswa
        return redirect()->intended('/siswa/dashboard'); 
    }

    return back()->withErrors([
        'username' => 'Username atau Password tidak sesuai.',
    ])->onlyInput('username');
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