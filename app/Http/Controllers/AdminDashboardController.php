<?php

namespace App\Http\Controllers;

use App\Models\Registrasi;      // buat hitung pending approval
use App\Models\Presensi;        // Untuk menghitung status presensi hari ini
use App\Models\KelolaJadwal;    // Untuk menghitung total sesi hari ini
use Illuminate\Http\Request;
use Carbon\Carbon;              // Untuk mengambil tanggal hari ini

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Tentukan tanggal hari ini
        $today = Carbon::today();

        // ----------------------------------------------------
        // 1. Jumlah registrasi siswa yang masih Pending
        // ----------------------------------------------------
        $pendingApproval = Registrasi::where('status', 'Pending')->count();

        // ----------------------------------------------------
        // 2. Hitung Status Presensi Hari Ini
        // ----------------------------------------------------
        
        // Ambil semua presensi yang dicatat HARI INI
        $presensiHariIni = Presensi::whereDate('tanggal', $today)
                                     ->get();

        // Agregasi status kehadiran
        $hadirHariIni = $presensiHariIni->where('status', 'Hadir')->count();
        $sakitHariIni = $presensiHariIni->where('status', 'Sakit')->count();
        $izinHariIni = $presensiHariIni->where('status', 'Izin')->count();
        
        // Alfa adalah total siswa yang seharusnya presensi tapi tidak hadir/izin/sakit.
        // Untuk dashboard sederhana, Alfa dihitung dari siswa yang tidak dicatat statusnya
        // atau sebagai fallback, kita hitung status 'Alfa' jika ada
        $alphaHariIni = $presensiHariIni->where('status', 'Alfa')->count();


        // ----------------------------------------------------
        // 3. Hitung Total Sesi Lab Hari Ini
        // ----------------------------------------------------
        
        // Tentukan hari ini dalam bahasa Indonesia (misal: Senin, Selasa, etc.)
        // Asumsi kolom 'hari' di KelolaJadwal menggunakan bahasa Indonesia
      $dayOfWeek = $today->isoFormat('dddd'); 

// 2. 🛑 FIX: Konversi ke huruf kecil untuk menghilangkan masalah Case Sensitivity 🛑
        $dayOfWeekLower = strtolower($dayOfWeek); 

        // FIX: Ambil dari database, pastikan kolom 'hari' di database juga dikonversi ke lowercase.
       $totalSesiHariIni = KelolaJadwal::count();
        // ----------------------------------------------------
        // 4. Kirim Data ke View
        // ----------------------------------------------------
        
        return view('admin.dashboard', [
            'pendingApproval'   => $pendingApproval,
            'hadirHariIni'      => $hadirHariIni,
            'sakitHariIni'      => $sakitHariIni,
            'izinHariIni'       => $izinHariIni,
            'alphaHariIni'      => $alphaHariIni,
            'totalSesiHariIni'  => $totalSesiHariIni,
        ]);
    }
}