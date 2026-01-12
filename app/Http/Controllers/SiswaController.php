<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    /**
     * =================================
     * INDEX – LIST + SEARCH + FILTER
     * =================================
     */
public function index(Request $request)
{
    // Tambahkan ->has('user') agar hanya siswa yang punya akun yang muncul
    $query = Siswa::with('user')
        ->has('user') 
        ->orderBy('nama', 'asc');

    // ... (sisa kodingan search dan filter tetap sama)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('nis', 'like', "%{$search}%")
              ->orWhere('kelas', 'like', "%{$search}%");
        });
    }

    if ($request->filled('jurusan')) {
        $query->where('jurusan', $request->jurusan);
    }

    $siswas = $query->paginate(15)->withQueryString();

    return view('admin.siswa.index', compact('siswas'));
}
    /**
     * =================================
     * EDIT FORM
     * =================================
     */
    public function edit($id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);
        return view('admin.siswa.edit', compact('siswa'));
    }

    /**
     * =================================
     * UPDATE DATA SISWA
     * =================================
     */
 public function update(Request $request, $id)
{
    $siswa = Siswa::with('user')->findOrFail($id);

    $request->validate([
        'nama'    => 'required|min:3|max:100',
        'kelas'   => 'required|max:10',
        'jurusan' => 'required',
        // Validasi: nis harus unik di tabel siswas, kecuali untuk ID siswa ini sendiri
        // Kita gunakan 'password_baru' sebagai input NIS agar sesuai dengan tampilan form
        'password_baru' => 'nullable|min:3|max:20|unique:siswas,nis,' . $siswa->id,
    ], [
        'nama.required'         => 'Nama lengkap wajib diisi.',
        'password_baru.unique'  => 'NIS sudah terdaftar/digunakan oleh siswa lain.',
        'password_baru.min'     => 'NIS/Password minimal 3 karakter.',
    ]);

    try {
        DB::beginTransaction();

        // 1. Update Data Profil
        $siswa->nama = trim($request->nama);
        $siswa->kelas = strtoupper(trim($request->kelas));
        $siswa->jurusan = $request->jurusan;

        // 2. Jika input Password Baru (NIS) diisi
        if ($request->filled('password_baru')) {
            $nilaibaru = $request->password_baru;
            
            // Update NIS di tabel Siswa
            $siswa->nis = $nilaibaru;

            // Update Password di tabel User (Konsep NIS = Password)
            if ($siswa->user) {
                $siswa->user->update([
                    'password' => Hash::make($nilaibaru)
                ]);
            }
        }

        $siswa->save();

        DB::commit();
        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
    /**
     * =================================
     * DELETE SISWA
     * =================================
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $siswa = Siswa::with('user')->findOrFail($id);

            if ($siswa->user) {
                $siswa->user->delete();
            }

            $siswa->delete();

            DB::commit();

            return back()->with('success', 'Data siswa berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus siswa.');
        }
    }

    /**
     * =================================
     * ✅ TOGGLE STATUS AKUN (BARU!)
     * =================================
     */
    public function toggleStatus($id)
    {
        try {
            $siswa = Siswa::with('user')->findOrFail($id);
            
            if (!$siswa->user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak memiliki akun login.'
                ], 404);
            }

            $newStatus = $siswa->user->toggleStatus();

            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => $newStatus === 'Aktif' 
                    ? 'Akun berhasil diaktifkan.' 
                    : 'Akun berhasil dinonaktifkan.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}