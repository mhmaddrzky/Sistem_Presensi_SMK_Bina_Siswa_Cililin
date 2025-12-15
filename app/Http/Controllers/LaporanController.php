<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\KelolaJadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Exports\LaporanPresensiExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    /**
     * Menampilkan daftar rekapitulasi presensi siswa di View Admin.
     */
    public function index(Request $request)
    {
        // Pastikan hanya Pengelola yang bisa mengakses laporan
        $allowedRoles = ['Admin', 'Kepsek', 'Guru', 'AsistenLab'];
        if (!auth()->user() || !in_array(strtolower(trim(auth()->user()->role)), array_map('strtolower', $allowedRoles))) {
            abort(403);
        }
        
        // Inisialisasi Filter dari Request
        $jurusanFilter = $request->input('jurusan_filter', 'all');
        $periode = $request->input('periode', 'mingguan'); 
        
        // FIX: HANYA HITUNG 'Hadir' untuk total kehadiran
        $statusesToCount = ['Hadir']; 
        $dataLaporan = []; 
        $now = Carbon::now();
        $startDate = null; $endDate = null;
        
        $jadwals = KelolaJadwal::orderBy('hari', 'asc')->orderBy('waktu_mulai', 'asc')->get(); 
        
        // 1. Tentukan Rentang Tanggal berdasarkan Periode
        switch ($periode) {
            case 'mingguan':
                $startDate = $now->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
                $endDate = $now->copy()->endOfWeek(Carbon::SUNDAY)->toDateString();
                break;
            case 'bulanan':
                $startDate = $now->copy()->startOfMonth()->toDateString();
                $endDate = $now->copy()->endOfMonth()->toDateString();
                break;
            case 'keseluruhan':
            default:
                $startDate = null;
                $endDate = null;
                break;
        }

        // 2. Query Siswa (Filtered by Jurusan)
        $siswaQuery = Siswa::query();
        if ($jurusanFilter !== 'all') {
            $siswaQuery->where('jurusan', $jurusanFilter);
        }
        $semuaSiswa = $siswaQuery->orderBy('kelas')->orderBy('nama')->get();
        $siswaIds = $semuaSiswa->pluck('id')->toArray();

        // 3. Hitung Total Sesi Hadir (HANYA STATUS 'Hadir')
        $presensiAggregatQuery = Presensi::whereIn('siswa_id', $siswaIds)
            ->where('status', 'Hadir'); 
            
        // Terapkan Filter Tanggal
        if ($startDate && $endDate) {
            $presensiAggregatQuery->whereBetween('tanggal', [$startDate, $endDate]);
        }
        
        $presensiAggregat = $presensiAggregatQuery->get()
            ->groupBy('siswa_id')
            ->map(function ($presensis) {
                return $presensis->count();
            });

        // 4. Hitung Total Jadwal (Pembagi untuk Persentase)
        $totalJadwalQuery = KelolaJadwal::query();
        if ($jurusanFilter !== 'all') {
            $totalJadwalQuery->where('jurusan', $jurusanFilter);
        }
        $totalJadwal = $totalJadwalQuery->count(); 
        
        // 5. Hitung dan Gabungkan Data
        $totalSesiSemester = 16;
        
        foreach ($semuaSiswa as $siswa) {
            $totalSesiHadir = $presensiAggregat->get($siswa->id) ?? 0;
            
            $persentase = ($totalJadwal > 0) ? round(($totalSesiHadir / $totalJadwal) * 100, 2) : 0;
            
            $dataLaporan[] = [
                'nis' => $siswa->nis,
                'nama' => $siswa->nama,
                'kelas' => $siswa->kelas,
                'jurusan' => $siswa->jurusan,
                'total_sesi_hadir' => $totalSesiHadir,
                'persentase_hadir' => $persentase,
                'total_kehadiran_format' => "{$totalSesiHadir}/{$totalSesiSemester}", 
            ];
        }

        $dataLaporan = collect($dataLaporan); 

        return view('admin.laporan.index', compact('dataLaporan', 'totalJadwal', 'periode', 'jurusanFilter', 'jadwals'));
    }

    /**
     * Export Excel Rapih menggunakan Laravel Excel (Maatwebsite)
     */
    public function export(Request $request)
    {
        $periode = $request->input('periode', 'keseluruhan');
        $jurusanFilter = $request->input('jurusan_filter', 'all');
        
        // Nama file
        $fileName = 'Rekap_Presensi_' . $jurusanFilter . '_' . date('d-m-Y') . '.xlsx';


        return Excel::download(new LaporanPresensiExport($periode, $jurusanFilter), $fileName);
    }
}

