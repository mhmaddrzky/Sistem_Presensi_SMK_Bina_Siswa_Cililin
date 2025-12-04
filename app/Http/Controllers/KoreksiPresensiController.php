<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\KelolaJadwal;
use App\Models\SesiSiswa;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Wajib di-import

class KoreksiPresensiController extends Controller
{
    /** Menampilkan form filter dan tabel koreksi absensi */
    public function index(Request $request)
    {
        $jadwals = KelolaJadwal::orderBy('hari')->orderBy('waktu_mulai')->get();
        $jadwalId = $request->input('jadwal_id');
        $rekapKoreksi = collect();
        $jadwalTerpilih = null;
        
        // 🛑 FIX TANGGAL 1: Selalu ambil tanggal hari ini untuk koreksi presensi yang baru terjadi
        $tanggalKoreksi = Carbon::today()->toDateString(); 

        if ($jadwalId) {
            $jadwalTerpilih = KelolaJadwal::find($jadwalId);
            
            // 1. Ambil ID Siswa yang terdaftar untuk Jadwal ini
            $siswaIdsSesi = SesiSiswa::where('jadwal_id', $jadwalId)->pluck('siswa_id');

            // 2. Ambil semua data Siswa tersebut
            $pesertaSesi = Siswa::whereIn('id', $siswaIdsSesi)->orderBy('kelas')->get();

            // 3. Ambil data presensi yang sudah tercatat (Hadir Otomatis) untuk sesi ini
            $presensiOtomatis = Presensi::where('jadwal_id', $jadwalId)
                                        // 🛑 FIX TANGGAL 2: Filter data presensi HANYA untuk hari ini
                                        ->where('tanggal', $tanggalKoreksi) 
                                        ->get()
                                        ->keyBy('siswa_id'); 

            // 4. Proses Rekap Koreksi
            foreach ($pesertaSesi as $siswa) {
                $presensi = $presensiOtomatis->get($siswa->id);
                
                $rekapKoreksi[] = [
                    'siswa_id' => $siswa->id,
                    'nama' => $siswa->nama,
                    'kelas' => $siswa->kelas,
                    'status_otomatis' => $presensi ? $presensi->status : 'Alfa', 
                    'presensi_id' => $presensi ? $presensi->id_presensi : null, 
                ];
            }
        }

        return view('admin.koreksi.index', compact('jadwals', 'rekapKoreksi', 'jadwalTerpilih'));
    }

    /** Menyimpan koreksi status presensi manual (Sakit, Izin, Alfa) */
    public function store(Request $request)
    {
        // Validasi input status dan ID Siswa/Jadwal
        $request->validate([
            'jadwal_id' => 'required|exists:kelola_jadwals,id',
            'koreksi.*.siswa_id' => 'required|exists:siswas,id',
            'koreksi.*.status' => 'required|in:Hadir,Sakit,Izin,Alfa', // Status final
        ]);

        $jadwalId = $request->jadwal_id;
        // 🛑 FIX TANGGAL 3: Tanggal presensi adalah TANGGAL HARI INI
        $tanggalKoreksi = Carbon::today()->toDateString(); 

        try {
            DB::beginTransaction();
            
            foreach ($request->koreksi as $data) {
                $siswaId = $data['siswa_id'];
                $statusKoreksi = $data['status'];

                // Hapus presensi otomatis (Hadir) jika status diubah menjadi Sakit/Izin/Alfa
                if ($statusKoreksi !== 'Hadir') {
                    // Hapus record yang ada di hari ini
                    Presensi::where('siswa_id', $siswaId)
                            ->where('jadwal_id', $jadwalId)
                            ->where('tanggal', $tanggalKoreksi)
                            ->delete();
                }

                // Cek apakah presensi koreksi (Sakit/Izin/Alfa) sudah ada
                // Gunakan firstOrNew untuk membuat atau menemukan record berdasarkan TANGGAL KOREKSI
                $presensiKoreksi = Presensi::firstOrNew([
                    'siswa_id' => $siswaId,
                    'jadwal_id' => $jadwalId,
                    'tanggal' => $tanggalKoreksi,
                ]);

                // Update status 
                $presensiKoreksi->fill([
                    'waktu' => now()->toTimeString(), // Waktu koreksi
                    'status' => $statusKoreksi,
                ])->save();
            }

            DB::commit();
            return back()->with('success', 'Koreksi kehadiran berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan koreksi: ' . $e->getMessage());
        }
    }
}