<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\KelolaJadwal;
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Response; 

class LaporanController extends Controller
{
   // app/Http/Controllers/LaporanController.php

// app/Http/Controllers/LaporanController.php

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
    
    $statusesToCount = ['Hadir', 'Sakit', 'Izin']; 
    $dataLaporan = []; 
    $now = Carbon::now();
    $startDate = null; $endDate = null;
    
    // 🛑 FIX: Definisikan $jadwals di awal Controller 🛑
    $jadwals = \App\Models\KelolaJadwal::orderBy('hari', 'asc')->orderBy('waktu_mulai', 'asc')->get(); 
    
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
            break;
    }

    // 2. Query Siswa (Filtered by Jurusan)
    $siswaQuery = Siswa::query();
    if ($jurusanFilter !== 'all') {
        $siswaQuery->where('jurusan', $jurusanFilter);
    }
    $semuaSiswa = $siswaQuery->get();
    $siswaIds = $semuaSiswa->pluck('id')->toArray();

    // 3. Hitung Total Sesi Hadir (Agregasi Collection)
    $presensiAggregatQuery = Presensi::whereIn('siswa_id', $siswaIds)
        ->whereIn('status', $statusesToCount); 
        
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

    // Sekarang $jadwals sudah didefinisikan di awal fungsi
    return view('admin.laporan.index', compact('dataLaporan', 'totalJadwal', 'periode', 'jurusanFilter', 'jadwals'));
}

    /** Menghasilkan file CSV untuk diunduh */
  public function export(Request $request)
    {
        $periode = $request->input('periode', 'keseluruhan');
        $jurusanFilter = $request->input('jurusan_filter', 'all');
        $statusesToCount = ['Hadir', 'Sakit', 'Izin'];
        $now = Carbon::now();
        $startDate = null; $endDate = null;
        
        // Logika penentuan $startDate dan $endDate sama seperti index()
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
                break;
        }

        // 1. Ambil Data Agregasi
        $siswaQuery = Siswa::query();
        if ($jurusanFilter !== 'all') {
            $siswaQuery->where('jurusan', $jurusanFilter);
        }
        $semuaSiswa = $siswaQuery->with('user')->orderBy('kelas')->orderBy('nama')->get();
        $siswaIds = $semuaSiswa->pluck('id')->toArray();
        
        $presensiAggregatQuery = Presensi::whereIn('siswa_id', $siswaIds)
            ->whereIn('status', $statusesToCount);
            
        if ($startDate && $endDate) {
            $presensiAggregatQuery->whereBetween('tanggal', [$startDate, $endDate]);
        }
        
        $presensiAggregat = $presensiAggregatQuery->get()
            ->groupBy('siswa_id')
            ->map(function ($presensis) {
                return $presensis->count();
            });

        // 2. Siapkan List Data CSV
       $totalSesiSemester = 16; // Konstan

$list = $semuaSiswa->map(function ($siswa) use ($presensiAggregat, $totalSesiSemester) {
    $totalHadir = $presensiAggregat->get($siswa->id) ?? 0;
    
    // 🛑 FIX UTAMA: Gabungkan tanda petik tunggal (') di awal
    $formattedAttendance = $totalHadir . '/' . $totalSesiSemester;
    
    return [
        'NIM' => $siswa->nis,
        'Nama_Siswa' => $siswa->user->username ?? $siswa->nama,
        'Kelas' => $siswa->kelas,
        // Kirim data dengan tanda kutip di depannya agar Excel menganggapnya teks murni
        'Total_Sesi_Hadir' => "'" . $formattedAttendance, 
    ];
        })->toArray();

        // Tambahkan Header Kolom
        if (!empty($list)) {
            array_unshift($list, array_keys($list[0]));
        } else {
            $list = [['NIS', 'Nama Siswa', 'Kelas', 'Total Sesi Hadir'], ['Belum ada data presensi untuk periode ini.']];
        }

        // 3. HTTP Stream Logic
        $fileName = 'laporan_presensi_' . $periode . '_' . Carbon::now()->format('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($list) {
            // Kontrol Buffer: Hapus output yang rusak
            if (ob_get_level() > 0) { // 🛑 PERBAIKAN TYPO
                ob_end_clean(); 
            }
            $file = fopen('php://output', 'w');
            fputs($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

            foreach ($list as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        // 4. Final Return
        return Response::stream($callback, 200, $headers);
    }
}