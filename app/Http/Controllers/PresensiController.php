<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelolaJadwal;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\SesiSiswa; // Wajib di-import untuk filter sesi
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon; // Wajib di-import untuk kontrol waktu

class PresensiController extends Controller
{
    /** Menampilkan Dashboard Siswa */
    public function showSiswaDashboard()
    {
        return view('siswa.dashboard');
    }

    /** 🛑 METHOD YANG HILANG: Menampilkan Jadwal yang tersedia untuk Presensi hari ini */
 public function showPresensiForm()
    {
        $siswa = Auth::user()->siswa;
        
        if (!$siswa) {
            return redirect('/')->with('error', 'Data Siswa tidak ditemukan.');
        }

        $hariIni = Carbon::now()->locale('id')->dayName; 
        
        // 1. Ambil ID Jadwal di mana Siswa ini terdaftar (Filter Sesi)
        $jadwalIdsSiswa = SesiSiswa::where('siswa_id', $siswa->id)->pluck('jadwal_id');

        // 2. 🛑 PERUBAHAN UTAMA: Ambil SEMUA Jadwal yang terdaftar (tanpa filter Hari)
        $jadwals = KelolaJadwal::whereIn('id', $jadwalIdsSiswa) 
                                ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')") // Urutkan berdasarkan urutan Hari
                                ->orderBy('waktu_mulai')
                                ->get();
        
        // 3. Ambil presensi siswa hari ini
        $presensiSiswaHariIni = Presensi::where('siswa_id', $siswa->id)
                                        ->whereDate('created_at', Carbon::today())
                                        ->pluck('jadwal_id');
        
        // 4. Proses status waktu, kuota, dan presensi untuk View
        $jadwalsAktif = $jadwals->map(function ($jadwal) use ($presensiSiswaHariIni, $hariIni) {
            $now = Carbon::now();
            $isHariIni = $jadwal->hari === $hariIni;
            
            // Logika Status Waktu
            if ($isHariIni) {
                // Jika hari jadwal cocok dengan hari ini, cek waktu secara spesifik
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
                // Jika bukan hari jadwal, status pasti 'Menunggu Hari'
                $statusWaktu = 'Menunggu Hari: ' . $jadwal->hari;
            }

            // Logika Kapasitas
            // Cek kuota hanya di hari ini, karena presensi hanya berlaku hari ini.
            $jumlahHadir = Presensi::where('jadwal_id', $jadwal->id)
                                    ->whereDate('created_at', Carbon::today())
                                    ->count();
                                    
            $jadwal->is_penuh = $jumlahHadir >= $jadwal->kapasitas;
            
            $jadwal->waktu_status = $statusWaktu;
            $jadwal->is_hadir = $presensiSiswaHariIni->contains($jadwal->id);
            
            return $jadwal;
        });

        // 🛑 TIDAK ADA FILTER .reject() lagi. Semua jadwal akan tampil.

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
        // Fix Timezone: Pastikan Waktu Mulai/Selesai menggunakan Tanggal Hari Ini
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

            return redirect()->route('siswa.dashboard')->with('success', 'Presensi berhasil dicatat!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencatat presensi: ' . $e->getMessage());
        }
    }

    public function showRiwayat()
    {
        $siswa = Auth::user()->siswa;
        
        // Ambil semua riwayat presensi siswa ini, diurutkan dari yang terbaru
        $riwayats = Presensi::where('siswa_id', $siswa->id)
                            ->orderBy('tanggal', 'desc')
                            ->orderBy('waktu', 'desc')
                            ->with('jadwal') // Ambil data jadwal terkait
                            ->get();

        return view('siswa.riwayat.index', compact('riwayats'));
    }
}