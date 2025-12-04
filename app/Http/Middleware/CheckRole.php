<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        // 🛑 FIX UTAMA: Trim role string dari database untuk menghapus spasi tersembunyi
        $userRole = trim($user->role); 
        
        // Cek apakah role pengguna ada di dalam daftar role yang diizinkan
        if (!in_array($userRole, $roles)) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}