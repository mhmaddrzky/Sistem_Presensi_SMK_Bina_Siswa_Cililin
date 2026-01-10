<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

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
    $siswa = Siswa::findOrFail($id);

    $request->validate([
        'nama'    => 'required|min:3|max:100',
        'nis'     => 'required|min:3|max:20|unique:siswas,nis,' . $siswa->id,
        'kelas'   => 'required|max:10',
        'jurusan' => 'required',
    ], [
        'nama.required'    => 'Nama lengkap wajib diisi.',
        'nama.min'         => 'Nama minimal 3 karakter.',
        'nis.required'     => 'NIS wajib diisi.',
        'nis.unique'       => 'NIS sudah terdaftar.',
        'kelas.required'   => 'Kelas wajib diisi.',
        'jurusan.required' => 'Jurusan wajib dipilih.',
    ]);

    $siswa->update([
        'nama'    => trim($request->nama),
        'nis'     => $request->nis,
        'kelas'   => strtoupper(trim($request->kelas)),
        'jurusan' => $request->jurusan,
    ]);

    return redirect()
        ->route('admin.siswa.index')
        ->with('success', 'Data siswa berhasil diperbarui.');
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