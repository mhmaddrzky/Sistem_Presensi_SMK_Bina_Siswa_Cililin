<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Registrasi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RegistrationController extends Controller
{
    // -------------------------------------------------    --------------------
    // FUNGSI SISWA: PROSES PENDAFTARAN
    // ---------------------------------------------------------------------

    public function showRegistrationForm()
    {
        // View (Tahap 3)
        return view('auth.register');
    }

   // app/Http/Controllers/RegistrationController.php

// ...
 // Pastikan ini di-import!
// ...
public function register(Request $request)
{
    // 1. Validasi Data
    $request->validate([
        'username' => 'required|string|max:50|unique:users',
        'nis' => [
            'required',
            'string',
            'max:20',
            'unique:siswas',
            'same:nis_confirmation'
        ],
        'nis_confirmation' => 'required',
        'nama' => 'required|string|max:100',
        'kelas' => 'required|string|max:10',
        'jurusan' => 'required|in:TKJ,TBSM', // Wajib ada dari form
    ]);

    try {
        DB::beginTransaction();

        $defaultPassword = $request->nis;

        // 2. Buat entitas Siswa (Logika Bersih)
        $siswa = Siswa::create([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan, // 🛑 Ini sekarang akan disimpan!
            'user_id' => null,
        ]);

        // 3. Buat permintaan Registrasi
        Registrasi::create([
            'siswa_id' => $siswa->id,
            'tanggal_reg' => now()->toDateString(),
            'status' => 'Pending',
            'username_request' => $request->username,
            'password_request' => Hash::make($defaultPassword),
        ]);

        DB::commit();

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil. Silakan tunggu persetujuan dari Admin.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Pendaftaran gagal: ' . $e->getMessage());
    }
}
    // ---------------------------------------------------------------------
    // FUNGSI ADMIN: PERSETUJUAN REGISTRASI
    // ---------------------------------------------------------------------

    public function index()
{
    $registrations = Registrasi::with('siswa')
        ->where('status', 'Pending')
        ->orderByDesc('id_reg')   // data paling baru muncul di atas
        ->get();

    return view('admin.registrations.index', compact('registrations'));
}

// app/Http/Controllers/RegistrationController.php

public function approve(Request $request, $id)
{
    // Ambil permintaan registrasi beserta data siswanya (eager load)
    $registration = Registrasi::with('siswa')->find($id);

    // Pengecekan dasar
    if (!$registration) {
        return back()->with('error', 'Permintaan registrasi tidak ditemukan.');
    }
    if ($registration->status !== 'Pending') {
        return back()->with('error', 'Registrasi sudah diproses sebelumnya.');
    }

    // Pengecekan Wajib: Pastikan Admin yang menyetujui sudah login dan punya entitas Admin.
    if (!auth()->check() || !auth()->user()->admin) {
        // Logika ini penting jika sesi Admin tiba-tiba putus atau akunnya tidak lengkap
        return back()->with('error', 'Gagal: Akun Admin tidak valid atau sesi berakhir.');
    }

    try {
        DB::beginTransaction();

        // 1. Buat User (akun login) menggunakan data yang disimpan di registrasi
        $user = User::create([
            'username' => $registration->username_request, // ERROR ADA DI SINI!
            'password' => $registration->password_request,
            'role' => 'Siswa',
        ]);

        // 2. Update Siswa dengan user_id yang baru
        $siswa = $registration->siswa;
        $siswa->user_id = $user->id;
        $siswa->save();

        // 3. Update Status Registrasi
        $registration->update([
            'status' => 'Approved',
            // Ambil ID dari relasi Admin yang terotentikasi
            'approved_by_admin_id' => auth()->user()->admin->id,
        ]);

        DB::commit();

        // Redirect sukses (kembali ke halaman yang sama)
        return back()->with('success', 'Registrasi Siswa berhasil disetujui. Akun login telah dibuat.');

    } catch (\Exception $e) {
        DB::rollBack();
        // TAMPILKAN ERROR DETAIL UNTUK DEBUGGING!
        // Error ini mungkin terjadi jika username sudah ada (unique constraint)
        return back()->with('error', 'Persetujuan gagal karena error database: ' . $e->getMessage());
    }
}

    public function reject($id)
    {
        $registration = Registrasi::findOrFail($id);

        if ($registration->status !== 'Pending') {
            return back()->with('error', 'Registrasi sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($registration) {
            // 1. Hapus catatan Siswa (karena tidak disetujui)
            $registration->siswa->delete();

            // 2. Hapus permintaan Registrasi
            $registration->delete();
        });

        return back()->with('success', 'Registrasi Siswa berhasil ditolak dan data dihapus.');
    }
}
