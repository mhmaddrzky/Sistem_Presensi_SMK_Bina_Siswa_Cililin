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
        $siswa = Auth::user()->siswa;
        
        if (!$siswa) {
            return redirect('/')->with('error', 'Data Siswa tidak ditemukan.');
        }

        $hariIni = Carbon::now()->locale('id')->dayName;
        $now = Carbon::now();
        
        // Ambil jadwal siswa untuk hari ini
        $jadwalIdsSiswa = SesiSiswa::where('siswa_id', $siswa->id)->pluck('jadwal_id');
        
        // Ambil 1 jadwal terdekat (yang akan datang atau sedang berlangsung)
        $jadwalTerdekat = KelolaJadwal::whereIn('id', $jadwalIdsSiswa)
                                      ->where('hari', $hariIni)
                                      ->get()
                                      ->sortBy('waktu_mulai')
                                      ->filter(function ($jadwal) use ($now) {
                                          $waktuSelesai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_selesai);
                                          return $now->lessThan($waktuSelesai);
                                      })
                                      ->first();
        
        // Ambil 1 presensi terbaru
        $presensiTerbaru = Presensi::where('siswa_id', $siswa->id)
                                    ->orderBy('created_at', 'desc')
                                    ->with('jadwal')
                                    ->first();
        
        return view('siswa.dashboard', compact('jadwalTerdekat', 'presensiTerbaru'));
    }

/** Menampilkan Jadwal**/

public function showPresensiForm()
{
    $siswa = Auth::user()->siswa;
    if (!$siswa) return redirect('/')->with('error', 'Data Siswa tidak ditemukan.');

    $hariIni = Carbon::now()->locale('id')->dayName;
    $jadwalIdsSiswa = SesiSiswa::where('siswa_id', $siswa->id)->pluck('jadwal_id');

    // 1. AMBIL DATA & SORTING (Sama seperti sebelumnya)
    $rawJadwals = KelolaJadwal::whereIn('id', $jadwalIdsSiswa)->get();

    $urutanHari = [
        'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4,
        'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7
    ];

    $jadwals = $rawJadwals->sortBy(function ($item) use ($urutanHari) {
        $nilaiHari = $urutanHari[$item->hari] ?? 99;
        return [$nilaiHari, $item->waktu_mulai];
    })->values();

    // 2. LOGIKA PRESENSI
    $presensiSiswa = Presensi::where('siswa_id', $siswa->id)
                      ->whereDate('created_at', Carbon::today())
                      ->get()
                      ->keyBy('jadwal_id');

    $jadwals = $jadwals->map(function ($jadwal) use ($presensiSiswa, $hariIni) {
        $now = Carbon::now();
        $isHariIni = $jadwal->hari === $hariIni;
        $presensi = $presensiSiswa->get($jadwal->id);
        
        // Variabel penanda apakah waktu sudah habis
        $sudahSelesai = false;

        if ($isHariIni) {
            $waktuMulai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_mulai);
            $waktuSelesai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_selesai);
            
            if ($now->greaterThanOrEqualTo($waktuMulai) && $now->lessThan($waktuSelesai)) {
                $statusWaktu = 'Sedang Berlangsung';
            } elseif ($now->lessThan($waktuMulai)) {
                $statusWaktu = 'Belum Dimulai';
            } else {
                // JIKA WAKTU HABIS
                $statusWaktu = 'Selesai'; 
                $sudahSelesai = true; 
            }
        } else {
            $statusWaktu = 'Menunggu Hari: ' . $jadwal->hari;
        }

        $jumlahHadir = Presensi::where('jadwal_id', $jadwal->id)
                                ->whereDate('created_at', Carbon::today())
                                ->count();
        
        $jadwal->is_penuh = $jumlahHadir >= $jadwal->kapasitas;
        $jadwal->waktu_status = $statusWaktu;
        
        // --- LOGIKA UTAMA PERUBAHAN DISINI ---
        if ($sudahSelesai) {

            $jadwal->is_hadir = false; 
            $jadwal->status_presensi = null; 
        } else {
          
            $jadwal->is_hadir = $presensi ? true : false;
            $jadwal->status_presensi = $presensi ? $presensi->status : null;
        }
        
        return $jadwal;
    });

    return view('siswa.presensi.form', ['jadwals' => $jadwals]);
}


/** Halaman Presensi (Tombol Absen) */
public function index()
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) return redirect('/')->with('error', 'Data Siswa tidak ditemukan.');

        $hariIni = Carbon::now()->locale('id')->dayName;
        $jadwalIdsSiswa = SesiSiswa::where('siswa_id', $siswa->id)->pluck('jadwal_id');

        // 1. AMBIL DATA JADWAL
        $rawJadwals = KelolaJadwal::whereIn('id', $jadwalIdsSiswa)->get();

        // 2. SORTING (Senin -> Minggu, Pagi -> Sore)
        $urutanHari = [
            'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4,
            'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7
        ];

        $jadwals = $rawJadwals->sortBy(function ($item) use ($urutanHari) {
            $nilaiHari = $urutanHari[$item->hari] ?? 99;
            return [$nilaiHari, $item->waktu_mulai];
        })->values();

        // 3. LOGIKA CEK PRESENSI HARI INI
        // Hanya cek data presensi yang tanggal created_at-nya HARI INI
        $presensiSiswa = Presensi::where('siswa_id', $siswa->id)
                          ->whereDate('created_at', Carbon::today())
                          ->get()
                          ->keyBy('jadwal_id');
        
        // 4. MAPPING STATUS & FILTER
        $jadwalsAktif = $jadwals->map(function ($jadwal) use ($presensiSiswa, $hariIni) {
            $now = Carbon::now();
            $isHariIni = $jadwal->hari === $hariIni;
            
            $presensi = $presensiSiswa->get($jadwal->id);
            
            // Tentukan Status Waktu
            if ($isHariIni) {
                $waktuMulai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_mulai);
                $waktuSelesai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_selesai);
                
                if ($now->greaterThanOrEqualTo($waktuMulai) && $now->lessThan($waktuSelesai)) {
                    $statusWaktu = 'Sedang Berlangsung';
                } elseif ($now->lessThan($waktuMulai)) {
                    $statusWaktu = 'Belum Dimulai';
                } else {
                    $statusWaktu = 'Selesai'; // Waktu Habis
                }
            } else {
                $statusWaktu = 'Menunggu Hari: ' . $jadwal->hari;
            }

            // Cek Kapasitas (Opsional jika ingin ditampilkan)
            $jumlahHadir = Presensi::where('jadwal_id', $jadwal->id)
                                    ->whereDate('created_at', Carbon::today())
                                    ->count();

            $jadwal->is_penuh = $jumlahHadir >= $jadwal->kapasitas;
            $jadwal->waktu_status = $statusWaktu;
            
            // Cek apakah sudah absen (Hadir/Sakit/Izin/Alfa)
            $jadwal->is_hadir = $presensi ? true : false;
            $jadwal->status_presensi = $presensi ? $presensi->status : null;
            
            return $jadwal;
        })
        // === FILTER LOGIC (YANG MEMBERSIHKAN CARD) ===
        ->filter(function ($jadwal) use ($hariIni) {
            $isHariIni = $jadwal->hari === $hariIni;

            // 1. Jika SUDAH Presensi (Entah itu Hadir/Sakit/Izin/Alfa) -> HILANGKAN
            if ($jadwal->is_hadir) {
                return false; 
            }

            // 2. Jika HARI INI tapi WAKTU SUDAH HABIS (Lupa absen) -> HILANGKAN
            if ($isHariIni && $jadwal->waktu_status === 'Selesai') {
                return false;
            }

            // 3. Sisanya (Jadwal Nanti, Jadwal Besok, Jadwal Lusa) -> TAMPILKAN
            return true;
            
        })->values();

        return view('siswa.presensi.index', compact('jadwalsAktif'));
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

            return back()->with('success', 'Presensi berhasil dicatat untuk ' . $jadwal->mata_pelajaran . '!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencatat presensi: ' . $e->getMessage());
        }
    }

    /** Menampilkan HANYA Riwayat */
    public function showRiwayat()
    {
        $siswa = Auth::user()->siswa;
        
        if (!$siswa) {
            return redirect('/')->with('error', 'Data Siswa tidak ditemukan.');
        }
        
        $riwayats = Presensi::where('siswa_id', $siswa->id)
                            ->orderBy('created_at', 'desc')
                            ->with('jadwal')
                            ->get();

        return view('siswa.riwayat.index', compact('riwayats'));
    }
}