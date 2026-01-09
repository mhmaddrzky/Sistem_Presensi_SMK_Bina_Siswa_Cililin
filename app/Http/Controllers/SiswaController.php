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
    $query = Siswa::with('user')
        ->orderBy('nama', 'asc');

    // 🔍 Search NIS / Nama / Kelas
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('nis', 'like', "%{$search}%")
              ->orWhere('kelas', 'like', "%{$search}%");
        });
    }

    // 🎓 Filter jurusan
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
            'nama' => [
                'required','string','min:3','max:100',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'nis' => [
                'required','string','min:3','max:20',
                Rule::unique('siswas','nis')->ignore($siswa->id),
                'regex:/^[0-9]+$/'
            ],
            'kelas' => ['required','string','min:1','max:10'],
            'jurusan' => ['required','in:TKJ,TBSM'],
        ]);

        $siswa->update([
            'nis' => $request->nis,
            'nama' => ucwords(strtolower(trim($request->nama))),
            'kelas' => strtoupper(trim($request->kelas)),
            'jurusan' => $request->jurusan,
        ]);

        return redirect()->route('admin.siswa.index')
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