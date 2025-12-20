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
     * Menampilkan form pembagian sesi dengan Pagination & Search
     */
    public function index(Request $request)
    {
        $jurusanFilter = $request->input('jurusan_filter', 'all');
        $search = $request->input('search');

        // Whitelist Filter
        $allowedFilters = ['all', 'TKJ', 'TBSM'];
        if (!in_array($jurusanFilter, $allowedFilters)) {
            $jurusanFilter = 'all';
        }

        // Query Dasar
        $siswasQuery = Siswa::whereNotNull('user_id')
            ->orderBy('kelas')
            ->orderBy('nama');

        // Filter Jurusan
        if ($jurusanFilter && $jurusanFilter !== 'all') {
            $siswasQuery->where('jurusan', $jurusanFilter);
        }

        // Filter Pencarian (LIVE SEARCH - HANYA NAMA & KELAS)
        if ($search) {
            $siswasQuery->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('kelas', 'LIKE', "%{$search}%");
            });
        }
        
        // Gunakan Paginate agar tabel rapi per 10 baris
        $siswas = $siswasQuery->paginate(20)->withQueryString();
        
        $jadwals = KelolaJadwal::orderBy('hari')->orderBy('waktu_mulai')->get();
        $mappingSesi = SesiSiswa::get()->groupBy('jadwal_id');

        return view('admin.sesi.index', compact('siswas', 'jadwals', 'mappingSesi', 'jurusanFilter'));
    }

    /**
     * Menyimpan pembagian sesi siswa dengan validasi KETAT (RESTORED)
     */
    public function store(Request $request)
    {
        // 1. VALIDASI INPUT DASAR
        $validated = $request->validate([
            'jadwal_id' => ['required', 'integer', 'exists:kelola_jadwals,id'],
            'siswa_ids' => ['required', 'array', 'min:1', 'max:100'], 
            'siswa_ids.*' => ['required', 'integer', 'exists:siswas,id'],
        ], [
            'jadwal_id.required' => 'Jadwal harus dipilih.',
            'siswa_ids.required' => 'Minimal pilih 1 siswa.',
        ]);
        
        try {
            // 2. AMBIL DATA JADWAL
            $jadwal = KelolaJadwal::findOrFail($validated['jadwal_id']);
            $siswaDipilihCount = count($validated['siswa_ids']);

            // 3. CEK KUOTA KAPASITAS
            if ($siswaDipilihCount > $jadwal->kapasitas) {
                return back()->with('error', 
                    'Gagal: Jumlah siswa yang dipilih (' . $siswaDipilihCount . 
                    ') melebihi kuota maksimal (' . $jadwal->kapasitas . ').'
                )->withInput();
            }

            // 4. AMBIL DATA SISWA UNTUK VALIDASI JURUSAN
            $siswaDipilih = Siswa::whereIn('id', $validated['siswa_ids'])->get();

            // 5. VALIDASI JUMLAH DATA
            if ($siswaDipilih->count() !== $siswaDipilihCount) {
                 return back()->with('error', 'Data siswa tidak valid.')->withInput();
            }

            // logika jurusan
            $siswaInvalidJurusan = [];
            foreach ($siswaDipilih as $siswa) {

                if ($siswa->jurusan !== $jadwal->jurusan) {
                    $siswaInvalidJurusan[] = $siswa->nama . ' (' . $siswa->jurusan . ')';
                }
            }

            if (!empty($siswaInvalidJurusan)) {
                return back()->with('error', 
                    'Gagal menyimpan! Jadwal ini khusus jurusan ' . $jadwal->jurusan . 
                    '. Siswa berikut berbeda jurusan: ' . implode(', ', $siswaInvalidJurusan)
                )->withInput();
            }
            // ============================================================

            // 7. PROSES PENYIMPANAN
            DB::beginTransaction();

            $dataToInsert = [];
            foreach ($validated['siswa_ids'] as $siswaId) {
                // Cek agar tidak double entry di jadwal yang sama
                $exists = SesiSiswa::where('jadwal_id', $jadwal->id)
                    ->where('siswa_id', $siswaId)
                    ->exists();
                
                if (!$exists) {
                    $dataToInsert[] = [
                        'siswa_id' => $siswaId,
                        'jadwal_id' => $jadwal->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            if (count($dataToInsert) > 0) {
                SesiSiswa::insert($dataToInsert);
            }

            DB::commit();
            
            return back()->with('success', 
                'Berhasil! ' . count($dataToInsert) . ' siswa ditambahkan ke sesi ' . 
                $jadwal->jurusan . ' - ' . $jadwal->mata_pelajaran . '.'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Simpan Sesi: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}