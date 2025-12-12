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

    /** Menampilkan Jadwal (Tanpa Button Presensi - Read Only) */
    public function showPresensiForm()
    {
        $siswa = Auth::user()->siswa;
        
        if (!$siswa) {
            return redirect('/')->with('error', 'Data Siswa tidak ditemukan.');
        }

        $hariIni = Carbon::now()->locale('id')->dayName; 
        
        $jadwalIdsSiswa = SesiSiswa::where('siswa_id', $siswa->id)->pluck('jadwal_id');

        $jadwals = KelolaJadwal::whereIn('id', $jadwalIdsSiswa)->get()
                                ->sortByDesc('created_at')
                                ->values();
        
        // Ambil SEMUA presensi siswa (tidak hanya hari ini) untuk cek status dari guru
        $presensiSiswa = Presensi::where('siswa_id', $siswa->id)
                                  ->get()
                                  ->keyBy('jadwal_id');
        
        $jadwals = $jadwals->map(function ($jadwal) use ($presensiSiswa, $hariIni) {
            $now = Carbon::now();
            $isHariIni = $jadwal->hari === $hariIni;
            
            // Cek apakah sudah ada presensi untuk jadwal ini (dari siswa atau guru)
            $presensi = $presensiSiswa->get($jadwal->id);
            
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
            
            // Set status dari presensi (bisa dari siswa atau guru)
            $jadwal->is_hadir = $presensi ? true : false;
            $jadwal->status_presensi = $presensi ? $presensi->status : null;
            
            return $jadwal;
        });

        return view('siswa.presensi.form', ['jadwals' => $jadwals]);
    }

    /** 🆕 HALAMAN PRESENSI DENGAN BUTTON (yang ada di navbar PRESENSI) */
    public function index()
    {
        $siswa = Auth::user()->siswa;
        
        if (!$siswa) {
            return redirect('/')->with('error', 'Data Siswa tidak ditemukan.');
        }

        $hariIni = Carbon::now()->locale('id')->dayName; 
        
        $jadwalIdsSiswa = SesiSiswa::where('siswa_id', $siswa->id)->pluck('jadwal_id');

        $jadwals = KelolaJadwal::whereIn('id', $jadwalIdsSiswa)->get()
                                ->sortByDesc('created_at')
                                ->values();
        
        // Ambil SEMUA presensi siswa untuk cek status dari guru
        $presensiSiswa = Presensi::where('siswa_id', $siswa->id)
                                  ->get()
                                  ->keyBy('jadwal_id');
        
        $jadwalsAktif = $jadwals->map(function ($jadwal) use ($presensiSiswa, $hariIni) {
            $now = Carbon::now();
            $isHariIni = $jadwal->hari === $hariIni;
            
            // Cek apakah sudah ada presensi untuk jadwal ini
            $presensi = $presensiSiswa->get($jadwal->id);
            
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
            $jadwal->is_hadir = $presensi ? true : false;
            $jadwal->status_presensi = $presensi ? $presensi->status : null;
            
            return $jadwal;
        });

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

    /** 🆕 Menampilkan HANYA Riwayat (tanpa card presensi) */
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