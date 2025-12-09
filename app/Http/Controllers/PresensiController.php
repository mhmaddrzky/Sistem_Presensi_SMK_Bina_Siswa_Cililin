<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelolaJadwal;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\SesiSiswa;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;

class PresensiController extends Controller
{
    /** Menampilkan Dashboard Siswa */
    public function showSiswaDashboard()
    {
        return view('siswa.dashboard');
    }

    /** Menampilkan Jadwal yang tersedia untuk Presensi */
    public function showPresensiForm()
    {
        $siswa = Auth::user()->siswa;
        
        if (!$siswa) {
            return redirect('/')->with('error', 'Data Siswa tidak ditemukan.');
        }

        $hariIni = Carbon::now()->locale('id')->dayName; 
        
        // Ambil ID Jadwal di mana Siswa ini terdaftar (Filter Sesi)
        $jadwalIdsSiswa = SesiSiswa::where('siswa_id', $siswa->id)->pluck('jadwal_id');

        // Ambil SEMUA Jadwal yang terdaftar, urutkan dari yang TERBARU dibuat (DESC)
        $jadwals = KelolaJadwal::whereIn('id', $jadwalIdsSiswa)->get()
                                ->sortByDesc('created_at')
                                ->values();
        
        // Ambil presensi siswa hari ini
        $presensiSiswaHariIni = Presensi::where('siswa_id', $siswa->id)
                                        ->whereDate('created_at', Carbon::today())
                                        ->pluck('jadwal_id');
        
        // Proses status waktu, kuota, dan presensi untuk View
        $jadwalsAktif = $jadwals->map(function ($jadwal) use ($presensiSiswaHariIni, $hariIni) {
            $now = Carbon::now();
            $isHariIni = $jadwal->hari === $hariIni;
            
            // Logika Status Waktu
            if ($isHariIni) {
                $waktuMulai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_mulai);
                $waktuSelesai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_selesai);
                
                if ($now->greaterThanOrEqualTo($waktuMulai) && $now->lessThan($waktuSelesai)) {
                    $statusWaktu = 'Sedang Berlangsung';
                } elseif ($now->lessThan($waktuMulai)) {
                    $statusWaktu = 'Belum Dimulai';
                } else {
                    $statusWaktu = 'Selesai (Waktu Terlewat)';
                }
            } else {
                $statusWaktu = 'Menunggu Hari: ' . $jadwal->hari;
            }

            // Logika Kapasitas
            $jumlahHadir = Presensi::where('jadwal_id', $jadwal->id)
                                    ->whereDate('created_at', Carbon::today())
                                    ->count();
                                    
            $jadwal->is_penuh = $jumlahHadir >= $jadwal->kapasitas;
            $jadwal->waktu_status = $statusWaktu;
            $jadwal->is_hadir = $presensiSiswaHariIni->contains($jadwal->id);
            
            return $jadwal;
        });

        return view('siswa.presensi.form', ['jadwals' => $jadwalsAktif]);
    }

    /** Memproses Presensi (Hadir Otomatis) */
    public function storePresensi(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:kelola_jadwals,id',
        ]);

        $jadwal = KelolaJadwal::find($request->jadwal_id);
        $siswa = Auth::user()->siswa;
        $now = Carbon::now();

        // Cek 1: Duplikasi Presensi
        if (Presensi::where('siswa_id', $siswa->id)->where('jadwal_id', $jadwal->id)->whereDate('created_at', Carbon::today())->exists()) {
            return back()->with('error', 'Anda sudah melakukan presensi untuk jadwal ini.');
        }

        // Cek 2: Kontrol Waktu (Harus Sedang Berlangsung)
        $waktuMulai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_mulai);
        $waktuSelesai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_selesai);
        
        if ($now->lessThan($waktuMulai)) {
            return back()->with('error', 'Sesi belum dimulai. Presensi dibuka dari pukul ' . $jadwal->waktu_mulai . '.');
        }
        
        if ($now->greaterThanOrEqualTo($waktuSelesai)) {
            return back()->with('error', 'Sesi sudah berakhir. Anda tidak dapat melakukan presensi.');
        }

        // Cek 3: Kontrol Kapasitas (Kuota)
        $jumlahHadir = Presensi::where('jadwal_id', $jadwal->id)->count();
        if ($jumlahHadir >= $jadwal->kapasitas) {
            return back()->with('error', 'Maaf, kuota presensi untuk sesi ini sudah penuh (' . $jadwal->kapasitas . ' siswa).');
        }
        
        // Simpan Presensi
        try {
            Presensi::create([
                'siswa_id' => $siswa->id,
                'jadwal_id' => $jadwal->id,
                'tanggal' => $now->toDateString(), 
                'waktu' => $now->toTimeString(),
                'status' => 'Hadir', 
            ]);

            // Redirect kembali ke halaman presensi agar card bisa hilang otomatis
            return back()->with('success', '✅ Presensi berhasil dicatat untuk ' . $jadwal->mata_pelajaran . '!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencatat presensi: ' . $e->getMessage());
        }
    }

    /**
     * ✅ METHOD YANG DIPERBAIKI: Menampilkan Halaman Presensi + Riwayat
     * Sekarang passing KEDUA variabel: $jadwals dan $riwayats
     */
    public function showRiwayat()
    {
        $siswa = Auth::user()->siswa;
        
        if (!$siswa) {
            return redirect('/')->with('error', 'Data Siswa tidak ditemukan.');
        }

        $hariIni = Carbon::now()->locale('id')->dayName;
        
        // ========== AMBIL DATA JADWAL, URUTKAN TERBARU DULUAN ==========
        $jadwalIdsSiswa = SesiSiswa::where('siswa_id', $siswa->id)->pluck('jadwal_id');

        $jadwals = KelolaJadwal::whereIn('id', $jadwalIdsSiswa)->get()
                                ->sortByDesc('created_at')
                                ->values();
        
        $presensiSiswaHariIni = Presensi::where('siswa_id', $siswa->id)
                                        ->whereDate('created_at', Carbon::today())
                                        ->pluck('jadwal_id');
        
        // Proses status waktu, kuota, dan presensi
        $jadwalsAktif = $jadwals->map(function ($jadwal) use ($presensiSiswaHariIni, $hariIni) {
            $now = Carbon::now();
            $isHariIni = $jadwal->hari === $hariIni;
            
            if ($isHariIni) {
                $waktuMulai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_mulai);
                $waktuSelesai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_selesai);
                
                if ($now->greaterThanOrEqualTo($waktuMulai) && $now->lessThan($waktuSelesai)) {
                    $statusWaktu = 'Sedang Berlangsung';
                } elseif ($now->lessThan($waktuMulai)) {
                    $statusWaktu = 'Belum Dimulai';
                } else {
                    $statusWaktu = 'Selesai (Waktu Terlewat)';
                }
            } else {
                $statusWaktu = 'Menunggu Hari: ' . $jadwal->hari;
            }

            $jumlahHadir = Presensi::where('jadwal_id', $jadwal->id)
                                    ->whereDate('created_at', Carbon::today())
                                    ->count();
                                    
            $jadwal->is_penuh = $jumlahHadir >= $jadwal->kapasitas;
            $jadwal->waktu_status = $statusWaktu;
            $jadwal->is_hadir = $presensiSiswaHariIni->contains($jadwal->id);
            
            return $jadwal;
        });
        
        // ========== AMBIL DATA RIWAYAT, URUTKAN DARI TERBARU (ATAS) ==========
        $riwayats = Presensi::where('siswa_id', $siswa->id)
                            ->orderBy('created_at', 'desc')
                            ->with('jadwal')
                            ->get();

        // ========== PASSING KEDUA VARIABEL ==========
        return view('siswa.riwayat.index', compact('jadwalsAktif', 'riwayats'));
    }
}