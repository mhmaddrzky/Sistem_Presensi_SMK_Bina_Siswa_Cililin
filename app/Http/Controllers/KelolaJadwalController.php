<?php

namespace App\Http\Controllers;

use App\Models\KelolaJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon; 

class KelolaJadwalController extends Controller
{
    // Method untuk mengecek izin Admin utama (Superuser)
    protected function authorizeCreation()
{
    // Cek ini harus mencakup SEMUA role operasional
  $allowedRoles = ['Admin', 'Guru', 'AsistenLab'];
if (!in_array(auth()->user()->role, $allowedRoles)) {
    abort(403, 'Akses ditolak.');
}
}
    
    /** Menampilkan daftar jadwal (Akses oleh Admin, Guru, Aslab) */
    public function index()
    {
        $jadwals = KelolaJadwal::with('admin')
                        ->orderBy('hari', 'asc')
                        ->orderBy('waktu_mulai', 'asc')
                        ->get();
        return view('admin.jadwal.index', compact('jadwals'));
    }

    /** Menampilkan form tambah jadwal (Hanya Admin/Guru/Aslab) */
    public function create()
    {
        $this->authorizeCreation();
        return view('admin.jadwal.create');
    }

    /** Menyimpan jadwal baru ke database (SINGLE CREATE LOGIC) */
public function store(Request $request)
{
   
   $this->authorizeCreation();


    // 1. Validasi Data
    $validatedData = $request->validate([
        'mata_pelajaran' => 'required|string|max:100',
        'nama_guru' => 'required|string|max:100',
        'ruang_lab' => 'required|string|max:50',
        'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
        'waktu_mulai' => 'required|date_format:H:i',
        'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
        'kapasitas' => 'required|integer|min:1|max:50', // Kapasitas maks 50
        'jurusan' => 'required|in:TKJ,TBSM', // Wajib ada untuk Multi-Jurusan
    ]);

    try {

        $adminId = auth()->user()->admin->id; 

        // 2. Simpan Data ke Database
        KelolaJadwal::create([
            'hari' => $validatedData['hari'],
            'mata_pelajaran' => $validatedData['mata_pelajaran'],
            'nama_guru' => $validatedData['nama_guru'],
            'ruang_lab' => $validatedData['ruang_lab'],
            'waktu_mulai' => $validatedData['waktu_mulai'],
            'waktu_selesai' => $validatedData['waktu_selesai'],
            'kapasitas' => $validatedData['kapasitas'],
            'jurusan' => $validatedData['jurusan'], 
            'admin_id' => $adminId, 
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal baru berhasil ditambahkan.');

    } catch (\Exception $e) {

        return back()->withInput()->with('error', 'Gagal menyimpan jadwal: ' . $e->getMessage());
    }
}

    /** Menampilkan form edit (Hanya Admin/Guru/Aslab) */
    public function edit(KelolaJadwal $jadwal)
    {
        $this->authorizeCreation();
        return view('admin.jadwal.edit', compact('jadwal'));
    }
    
    /** Memperbarui jadwal (Hanya Admin/Guru/Aslab) */
   public function update(Request $request, KelolaJadwal $jadwal)
{
    $this->authorizeCreation();
    
    $request->validate([
        'mata_pelajaran' => 'required|string|max:100',
        'nama_guru' => 'required|string|max:100',
        'ruang_lab' => 'required|string|max:50',
        'kapasitas' => 'required|integer|min:1|max:20',
        'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
        'waktu_mulai' => 'required|date_format:H:i',
        'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
        'sesi' => 'nullable|string|max:50', 
        'jurusan' => 'required|in:TKJ,TBSM',
    ]);

    try {
        $jadwal->update($request->only([
             'hari', 'sesi', 'ruang_lab', 'mata_pelajaran', 'nama_guru',
             'kapasitas', 'waktu_mulai', 'waktu_selesai', 
             'jurusan' 
         ]));
         return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    } catch (\Exception $e) {
         return back()->with('error', 'Gagal memperbarui jadwal: ' . $e->getMessage());
    }
}

    /** Menghapus jadwal (Hanya Admin/Guru/Aslab) */
    public function destroy(KelolaJadwal $jadwal)
    {
        $this->authorizeCreation();
        try {
            $jadwal->delete();
            return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }
}