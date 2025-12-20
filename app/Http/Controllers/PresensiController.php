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
        
        // Ambil jadwal siswa
        $jadwalIdsSiswa = SesiSiswa::where('siswa_id', $siswa->id)->pluck('jadwal_id');
        
        // Ambil 1 jadwal terdekat hari ini yang belum lewat jamnya
        $jadwalTerdekat = KelolaJadwal::whereIn('id', $jadwalIdsSiswa)
                                      ->where('hari', $hariIni)
                                      ->get()
                                      ->sortBy('waktu_mulai')
                                      ->filter(function ($jadwal) use ($now) {
                                          $waktuSelesai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_selesai);
                                          return $now->lessThan($waktuSelesai);
                                      })
                                      ->first();
        
        // Ambil 1 presensi terbaru untuk histori mini
        $presensiTerbaru = Presensi::where('siswa_id', $siswa->id)
                                   ->orderBy('created_at', 'desc')
                                   ->with('jadwal')
                                   ->first();
        
        return view('siswa.dashboard', compact('jadwalTerdekat', 'presensiTerbaru'));
    }

    /** Menampilkan Halaman Jadwal (Read Only) */
    public function showPresensiForm()
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) return redirect('/')->with('error', 'Data Siswa tidak ditemukan.');

        $hariIni = Carbon::now()->locale('id')->dayName;
        $jadwalIdsSiswa = SesiSiswa::where('siswa_id', $siswa->id)->pluck('jadwal_id');

        // 1. AMBIL DATA
        $rawJadwals = KelolaJadwal::whereIn('id', $jadwalIdsSiswa)->get();

        // 2. SORTING (Senin - Minggu)
        $urutanHari = [
            'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4,
            'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7
        ];

        $jadwals = $rawJadwals->sortBy(function ($item) use ($urutanHari) {
            $nilaiHari = $urutanHari[$item->hari] ?? 99;
            return [$nilaiHari, $item->waktu_mulai];
        })->values();

        // 3. LOGIKA CEK STATUS
        $presensiSiswa = Presensi::where('siswa_id', $siswa->id)
                          ->whereDate('created_at', Carbon::today())
                          ->get()
                          ->keyBy('jadwal_id');

        $jadwals = $jadwals->map(function ($jadwal) use ($presensiSiswa, $hariIni) {
            $now = Carbon::now();
            $isHariIni = $jadwal->hari === $hariIni;
            $presensi = $presensiSiswa->get($jadwal->id);
            
            $sudahSelesai = false;

            if ($isHariIni) {
                $waktuMulai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_mulai);
                $waktuSelesai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_selesai);
                
                if ($now->greaterThanOrEqualTo($waktuMulai) && $now->lessThan($waktuSelesai)) {
                    $statusWaktu = 'Sedang Berlangsung';
                } elseif ($now->lessThan($waktuMulai)) {
                    $statusWaktu = 'Belum Dimulai';
                } else {
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
            
            // Logika Tampilan Status
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

        // 1. SETTING HARI & WAKTU
        $now = Carbon::now();
        $hariIni = $now->locale('id')->dayName; // Senin, Selasa, dst.
        
        // 2. AMBIL ID JADWAL SISWA
        $jadwalIdsSiswa = SesiSiswa::where('siswa_id', $siswa->id)->pluck('jadwal_id');

        // 3. AMBIL DATA JADWAL (HANYA HARI INI)
       
        $rawJadwals = KelolaJadwal::whereIn('id', $jadwalIdsSiswa)
                                  ->where('hari', $hariIni) 
                                  ->orderBy('waktu_mulai', 'asc')
                                  ->get();

        
        $totalJadwalHariIni = $rawJadwals->count();

        // 4. CEK DATA PRESENSI HARI INI (Untuk validasi button)
        $presensiSiswa = Presensi::where('siswa_id', $siswa->id)
                                 ->whereDate('created_at', Carbon::today())
                                 ->get()
                                 ->keyBy('jadwal_id');
        
        // 5. MAPPING & FILTERING (Logic Card Hilang)
        $jadwalsAktif = $rawJadwals->map(function ($jadwal) use ($presensiSiswa, $now) {
            
            $presensi = $presensiSiswa->get($jadwal->id);
            
            // Cek Status Waktu
            $waktuMulai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_mulai);
            $waktuSelesai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_selesai);
            
            if ($now->greaterThanOrEqualTo($waktuMulai) && $now->lessThan($waktuSelesai)) {
                $statusWaktu = 'Sedang Berlangsung';
            } elseif ($now->lessThan($waktuMulai)) {
                $statusWaktu = 'Belum Dimulai';
            } else {
                $statusWaktu = 'Selesai';
            }

            // Cek Kapasitas
            $jumlahHadir = Presensi::where('jadwal_id', $jadwal->id)
                                   ->whereDate('created_at', Carbon::today())
                                   ->count();

            $jadwal->is_penuh = $jumlahHadir >= $jadwal->kapasitas;
            $jadwal->waktu_status = $statusWaktu;
            $jadwal->is_hadir = $presensi ? true : false;
            
            return $jadwal;
        })
        // === FILTER PEMBERSIH (CARD HILANG) ===
        ->filter(function ($jadwal) {
            // 1. Jika SUDAH Presensi -> HILANGKAN DARI LIST
            if ($jadwal->is_hadir) {
                return false; 
            }

            // 2. (Opsional) Jika WAKTU SUDAH HABIS tapi lupa absen -> HILANGKAN JUGA?
            // Jika ingin tetap muncul walau telat, hapus if di bawah ini.
            if ($jadwal->waktu_status === 'Selesai') {
                 return false; 
            }

            return true; 
        })->values();

      
        
        return view('siswa.presensi.index', compact('jadwalsAktif', 'totalJadwalHariIni'));
    }

    /** Memproses Presensi */
    public function storePresensi(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:kelola_jadwals,id',
        ]);

        $jadwal = KelolaJadwal::find($request->jadwal_id);
        $siswa = Auth::user()->siswa;
        
        // GUNAKAN WAKTU SEKARANG 
        $now = Carbon::now(); 


        if (Presensi::where('siswa_id', $siswa->id)
                    ->where('jadwal_id', $jadwal->id)
                    ->whereDate('created_at', Carbon::today())
                    ->exists()) {
            return back()->with('error', 'Anda sudah melakukan presensi untuk jadwal ini.');
        }

        // Cek 2: Kontrol Waktu 
        $waktuMulai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_mulai);
        $waktuSelesai = Carbon::today()->setTimeFromTimeString($jadwal->waktu_selesai);
        
        if ($now->lessThan($waktuMulai)) {
            return back()->with('error', 'Sesi belum dimulai. Presensi dibuka pukul ' . $jadwal->waktu_mulai . '.');
        }
        
        if ($now->greaterThanOrEqualTo($waktuSelesai)) {
            return back()->with('error', 'Sesi sudah berakhir. Anda tidak dapat melakukan presensi.');
        }

        // Cek 3: Kontrol Kapasitas
        $jumlahHadir = Presensi::where('jadwal_id', $jadwal->id)
                               ->whereDate('created_at', Carbon::today())
                               ->count();
                               
        if ($jumlahHadir >= $jadwal->kapasitas) {
            return back()->with('error', 'Maaf, kuota presensi untuk sesi ini sudah penuh.');
        }
        
        // SIMPAN PRESENSI
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

    /** Menampilkan Halaman Riwayat */
    public function showRiwayat()
    {
        $siswa = Auth::user()->siswa;
        
        if (!$siswa) {
            return redirect('/')->with('error', 'Data Siswa tidak ditemukan.');
        }
        
        $riwayats = Presensi::where('siswa_id', $siswa->id)

                            ->orderBy('tanggal', 'desc') 
                            

                            ->orderBy('waktu', 'desc') 

                            
                            ->with('jadwal')
                            ->get();

   
        return view('siswa.riwayat.index', compact('riwayats'));
    }
}