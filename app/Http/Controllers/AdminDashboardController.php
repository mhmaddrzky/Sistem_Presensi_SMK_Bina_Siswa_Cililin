<?php

namespace App\Http\Controllers;

use App\Models\Registrasi;   // buat hitung pending approval
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // --- Data yang dipakai di dashboard.blade.php ---

        // 1. Jumlah registrasi siswa yang masih Pending
        $pendingApproval = Registrasi::where('status', 'Pending')->count();

        // 2. Angka lain sementara 0 dulu (nanti bisa diisi query asli)
        $hadirHariIni     = 0;
        $sakitHariIni     = 0;
        $izinHariIni      = 0;
        $alphaHariIni     = 0;
        $totalSesiHariIni = 0;

        return view('admin.dashboard', [
            'pendingApproval'  => $pendingApproval,
            'hadirHariIni'     => $hadirHariIni,
            'sakitHariIni'     => $sakitHariIni,
            'izinHariIni'      => $izinHariIni,
            'alphaHariIni'     => $alphaHariIni,
            'totalSesiHariIni' => $totalSesiHariIni,
        ]);
    }
}
