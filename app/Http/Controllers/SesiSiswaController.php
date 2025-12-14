<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\KelolaJadwal;
use App\Models\SesiSiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;

class SesiSiswaController extends Controller
{
    /**
     * Menampilkan form pembagian sesi
     */
    public function index(Request $request)
    {

        $jurusanFilter = $request->input('jurusan_filter', 'all');
        
        // Whitelist untuk keamanan - hanya terima value yang valid
        $allowedFilters = ['all', 'TKJ', 'TBSM'];
        if (!in_array($jurusanFilter, $allowedFilters)) {
            $jurusanFilter = 'all';
        }

        // Query Siswa (Siswa yang sudah di-approve)
        $siswasQuery = Siswa::whereNotNull('user_id')
            ->orderBy('kelas')
            ->orderBy('nama');

        // Terapkan Filter Jurusan
        if ($jurusanFilter && $jurusanFilter !== 'all') {
            $siswasQuery->where('jurusan', $jurusanFilter);
        }
        
        $siswas = $siswasQuery->get();
        
        // Ambil SEMUA Jadwal yang tersedia
        $jadwals = KelolaJadwal::orderBy('hari')
            ->orderBy('waktu_mulai')
            ->get();
        
        // Ambil data mapping yang sudah ada
        $mappingSesi = SesiSiswa::get()->groupBy('jadwal_id');

        return view('admin.sesi.index', compact('siswas', 'jadwals', 'mappingSesi', 'jurusanFilter'));
    }

    /**
     * Menyimpan pembagian sesi siswa dengan validasi ketat
     */
    public function store(Request $request)
    {
        // 1. VALIDASI INPUT KETAT
        $validated = $request->validate([
            'jadwal_id' => [
                'required',
                'integer',
                'exists:kelola_jadwals,id',
            ],
            'siswa_ids' => [
                'required',
                'array',
                'min:1',
                'max:100', 
            ],
            'siswa_ids.*' => [
                'required',
                'integer',
                'exists:siswas,id',
            ],
        ], [
            'jadwal_id.required' => 'Jadwal harus dipilih.',
            'jadwal_id.exists' => 'Jadwal yang dipilih tidak valid.',
            'siswa_ids.required' => 'Minimal pilih 1 siswa.',
            'siswa_ids.min' => 'Minimal pilih 1 siswa.',
            'siswa_ids.max' => 'Maksimal 100 siswa dalam satu transaksi.',
            'siswa_ids.*.exists' => 'Data siswa tidak valid.',
        ]);
        
        try {
            // 2. AMBIL DATA JADWAL
            $jadwal = KelolaJadwal::findOrFail($validated['jadwal_id']);
            $siswaDipilihCount = count($validated['siswa_ids']);

            // 3. CEK KUOTA KAPASITAS
            if ($siswaDipilihCount > $jadwal->kapasitas) {
                Log::warning('Percobaan melebihi kuota', [
                    'jadwal_id' => $jadwal->id,
                    'kapasitas' => $jadwal->kapasitas,
                    'siswa_dipilih' => $siswaDipilihCount,
                    'ip' => $request->ip(),
                ]);
                
                return back()->with('error', 
                    'Gagal: Jumlah siswa yang dipilih (' . $siswaDipilihCount . 
                    ') melebihi kuota maksimal (' . $jadwal->kapasitas . 
                    ') untuk sesi ini.'
                )->withInput();
            }

            // 4. AMBIL DATA SISWA YANG DIPILIH (dengan validasi exists)
            $siswaDipilih = Siswa::whereIn('id', $validated['siswa_ids'])
                ->whereNotNull('user_id')
                ->get();

            // 5. VALIDASI: Cek apakah jumlah siswa yang ditemukan sesuai
            if ($siswaDipilih->count() !== $siswaDipilihCount) {
                Log::warning('Percobaan dengan data siswa tidak valid', [
                    'expected' => $siswaDipilihCount,
                    'found' => $siswaDipilih->count(),
                    'ip' => $request->ip(),
                ]);
                
                return back()->with('error', 
                    'Data tidak valid. Beberapa siswa yang dipilih tidak ditemukan atau belum di-approve.'
                )->withInput();
            }

            // 6. KONTROL SILANG: Verifikasi Jurusan
            $siswaInvalidJurusan = [];
            foreach ($siswaDipilih as $siswa) {
                if ($siswa->jurusan !== $jadwal->jurusan) {
                    $siswaInvalidJurusan[] = $siswa->nama . ' (Jurusan: ' . $siswa->jurusan . ')';
                }
            }

            if (!empty($siswaInvalidJurusan)) {
                Log::warning('Percobaan dengan jurusan tidak sesuai', [
                    'jadwal_id' => $jadwal->id,
                    'jadwal_jurusan' => $jadwal->jurusan,
                    'siswa_invalid' => $siswaInvalidJurusan,
                    'ip' => $request->ip(),
                ]);
                
                return back()->with('error', 
                    'Gagal menyimpan. Siswa berikut tidak sesuai dengan Jurusan Jadwal (' . 
                    $jadwal->jurusan . '): ' . implode(', ', $siswaInvalidJurusan)
                )->withInput();
            }

            // 7. PROSES PENYIMPANAN DALAM TRANSAKSI
            DB::beginTransaction();

            // Hapus mapping lama untuk jadwal ini
            SesiSiswa::where('jadwal_id', $jadwal->id)->delete();
            
            // Masukkan mapping baru
            $dataToInsert = [];
            foreach ($validated['siswa_ids'] as $siswaId) {
                $dataToInsert[] = [
                    'siswa_id' => $siswaId,
                    'jadwal_id' => $jadwal->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            SesiSiswa::insert($dataToInsert);

            // Log aktivitas berhasil
            Log::info('Pembagian sesi berhasil', [
                'jadwal_id' => $jadwal->id,
                'jadwal_info' => $jadwal->jurusan . ' - ' . $jadwal->mata_pelajaran,
                'jumlah_siswa' => $siswaDipilihCount,
                'admin_id' => auth()->id(),
                'ip' => $request->ip(),
            ]);

            DB::commit();
            
            return back()->with('success', 
                ' Berhasil! Pembagian ' . $siswaDipilihCount . 
                ' siswa ke sesi ' . $jadwal->jurusan . ' - ' . 
                $jadwal->mata_pelajaran . ' berhasil disimpan.'
            );

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            
            Log::error('Jadwal tidak ditemukan', [
                'jadwal_id' => $request->jadwal_id,
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);
            
            return back()->with('error', 
                'Jadwal tidak ditemukan. Silakan pilih jadwal yang valid.'
            )->withInput();
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Gagal menyimpan sesi', [
                'jadwal_id' => $request->jadwal_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
            ]);
            
            return back()->with('error', 
                'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi administrator.'
            )->withInput();
        }
    }
}