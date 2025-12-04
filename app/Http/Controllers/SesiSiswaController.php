<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\KelolaJadwal;
use App\Models\SesiSiswa; // Model baru
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class SesiSiswaController extends Controller
{
    /** Menampilkan form pembagian sesi */
   public function index(Request $request)
{
    // 1. Ambil Filter Jurusan dari request (default: all)
  $jurusanFilter = $request->input('jurusan_filter', 'all'); 

    // 1. Query Siswa (Siswa yang sudah di-approve)
    $siswasQuery = Siswa::whereNotNull('user_id')->orderBy('kelas')->orderBy('nama');

    // 🛑 FIX UTAMA: Terapkan Filter Jurusan
    if ($jurusanFilter && $jurusanFilter !== 'all') {
        // Gunakan where untuk memfilter, ini yang harusnya menampilkan data
        $siswasQuery->where('jurusan', $jurusanFilter); 
    }
    
    $siswas = $siswasQuery->get();
    
    // Ambil SEMUA Jadwal yang tersedia
    $jadwals = KelolaJadwal::orderBy('hari')->orderBy('waktu_mulai')->get();
    
    // Ambil data mapping yang sudah ada
    $mappingSesi = SesiSiswa::get()->groupBy('jadwal_id');

    // Kirim filter Jurusan kembali ke View
    return view('admin.sesi.index', compact('siswas', 'jadwals', 'mappingSesi', 'jurusanFilter'));
}

    /** Menyimpan pembagian sesi siswa */
   public function store(Request $request)
{
    // Validasi dasar
    $request->validate([
        'jadwal_id' => 'required|exists:kelola_jadwals,id',
        'siswa_ids' => 'required|array', 
    ]);
    
    $jadwal = KelolaJadwal::find($request->jadwal_id);
    $siswaDipilihCount = count($request->siswa_ids);

    // 1. Cek Kuota (Sama seperti sebelumnya)
    if ($siswaDipilihCount > $jadwal->kapasitas) {
        return back()->with('error', 'Gagal: Jumlah siswa yang dipilih (' . $siswaDipilihCount . ') melebihi kuota maksimal (' . $jadwal->kapasitas . ') untuk sesi ini.');
    }

    // 2. 🛑 KONTROL SILANG: Verifikasi Jurusan 🛑
    $siswaDipilih = Siswa::whereIn('id', $request->siswa_ids)->get();

    foreach ($siswaDipilih as $siswa) {
        if ($siswa->jurusan !== $jadwal->jurusan) {
            // Jika ada satu saja siswa yang jurusannya beda, tolak seluruh transaksi
            return back()->with('error', 'Gagal menyimpan. Siswa ' . $siswa->nama . ' (Jurusan: ' . $siswa->jurusan . ') tidak sesuai dengan Jurusan Jadwal (' . $jadwal->jurusan . ').');
        }
    }
    // 🛑 END KONTROL SILANG 🛑

    try {
        DB::beginTransaction();

        // 3. Hapus semua mapping lama untuk jadwal ini (untuk update/overwrite)
        SesiSiswa::where('jadwal_id', $jadwal->id)->delete();
        
        // 4. Masukkan mapping baru (Jika Kuota dan Kontrol Silang Lolos)
        $dataToInsert = [];
        foreach ($request->siswa_ids as $siswaId) {
            $dataToInsert[] = [
                'siswa_id' => $siswaId,
                'jadwal_id' => $jadwal->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        SesiSiswa::insert($dataToInsert);

        DB::commit();
        return back()->with('success', 'Pembagian ' . $siswaDipilihCount . ' siswa ke sesi ' . $jadwal->jurusan . ' berhasil disimpan.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal menyimpan sesi: ' . $e->getMessage());
    }
}
}