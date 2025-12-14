<?php

namespace App\Exports;

use App\Models\Siswa;
use App\Models\Presensi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths; 
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LaporanPresensiExport implements FromView, WithStyles, WithColumnWidths
{
    protected $periode;
    protected $jurusanFilter;

    public function __construct($periode, $jurusanFilter)
    {
        $this->periode = $periode;
        $this->jurusanFilter = $jurusanFilter;
    }

    // 1. ATUR LEBAR KOLOM DI SINI (Supaya tidak mepet)
    public function columnWidths(): array
    {
        return [
            'A' => 15,  // Kolom NIS 
            'B' => 45,  // Kolom Nama 
            'C' => 15,  // Kolom Kelas
            'D' => 15,  // Kolom Jurusan
            'E' => 20,  // Kolom Kehadiran
            'F' => 20,  // Kolom Persentase
        ];
    }

   // BAGIAN FUNCTION STYLE
   public function styles(Worksheet $sheet)
   {
       return [
           // 1. Atur Default Kolom B (Data Nama) 
           'B' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
           
           // 2. Atur Kolom Lain jadi Rata TENGAH
           'A' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']], // NIS
           'C' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']], // Kelas
           'D' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']], // Jurusan
           'E' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']], // Kehadiran
           'F' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']], // Persentase

           // 3. Judul Laporan (Baris 1 & 2)
           1 => ['font' => ['bold' => true, 'size' => 14]], 
           2 => ['font' => ['bold' => true, 'italic' => true]],
           
           // 4. HEADER TABEL (Baris 3) -> Rata TENGAH
           3 => [ 
               'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
               'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0B57D0']],
               'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
           ],

          // 5. Nama
           'B3' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
       ];
   }

    public function view(): View
    {
        // ... (LOGIKA DATA) ...
        $now = Carbon::now();
        $startDate = null; $endDate = null;
        $ketPeriode = "";
        
        switch ($this->periode) {
            case 'mingguan':
                $startDate = $now->copy()->startOfWeek()->toDateString();
                $endDate = $now->copy()->endOfWeek()->toDateString();
                $ketPeriode = "Minggu Ini (" . $now->startOfWeek()->format('d M') . " - " . $now->endOfWeek()->format('d M Y') . ")";
                break;
            case 'bulanan':
                $startDate = $now->copy()->startOfMonth()->toDateString();
                $endDate = $now->copy()->endOfMonth()->toDateString();
                $ketPeriode = "Bulan " . $now->format('F Y');
                break;
            default:
                $ketPeriode = "Keseluruhan Data";
                break;
        }

        $siswaQuery = Siswa::query();
        if ($this->jurusanFilter !== 'all') {
            $siswaQuery->where('jurusan', $this->jurusanFilter);
        }
        $semuaSiswa = $siswaQuery->orderBy('kelas')->orderBy('nama')->get();
        $siswaIds = $semuaSiswa->pluck('id')->toArray();

        $presensiQuery = Presensi::whereIn('siswa_id', $siswaIds)->where('status', 'Hadir');
        if ($startDate) {
            $presensiQuery->whereBetween('tanggal', [$startDate, $endDate]);
        }
        $presensiAggregat = $presensiQuery->get()->groupBy('siswa_id')->map->count();

        $totalSesiSemester = 16;
        $dataLaporan = [];

        foreach ($semuaSiswa as $siswa) {
            $totalSesiHadir = $presensiAggregat->get($siswa->id) ?? 0;
            $persentase = ($totalSesiSemester > 0) ? round(($totalSesiHadir / $totalSesiSemester) * 100) : 0;
            
            $dataLaporan[] = [
                'nis' => $siswa->nis,
                'nama' => $siswa->nama,
                'kelas' => $siswa->kelas,
                'jurusan' => $siswa->jurusan,
                'kehadiran' => "{$totalSesiHadir} / {$totalSesiSemester}",
                'persentase' => $persentase . '%'
            ];
        }

        return view('admin.laporan.export_excel', [
            'dataLaporan' => collect($dataLaporan),
            'ketPeriode' => $ketPeriode,
            'jurusan' => $this->jurusanFilter === 'all' ? 'Semua Jurusan' : $this->jurusanFilter
        ]);
    }
}