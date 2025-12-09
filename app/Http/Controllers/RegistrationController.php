<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Registrasi;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    // Tampilkan form registrasi untuk siswa
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Proses pendaftaran siswa (oleh user)
    public function register(Request $request)
    {
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
            'jurusan' => 'required|in:TKJ,TBSM',
        ]);

        try {
            DB::beginTransaction();

            $defaultPassword = $request->nis;

            $siswa = Siswa::create([
                'nis' => $request->nis,
                'nama' => $request->nama,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan,
                'user_id' => null,
            ]);

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

    // Daftar registrasi pending (admin)
    public function index()
    {
        $registrations = Registrasi::with('siswa')
            ->where('status', 'Pending')
            ->orderByDesc('id_reg')
            ->get();

        return view('admin.registrations.index', compact('registrations'));
    }

    // Approve satu-satu
    public function approve(Request $request, $id)
    {
        $registration = Registrasi::with('siswa')->find($id);

        if (! $registration) {
            return back()->with('error', 'Permintaan registrasi tidak ditemukan.');
        }
        if ($registration->status !== 'Pending') {
            return back()->with('error', 'Registrasi sudah diproses sebelumnya.');
        }

        if (! auth()->check() || ! auth()->user()->admin) {
            return back()->with('error', 'Gagal: Akun Admin tidak valid atau sesi berakhir.');
        }

        try {
            DB::beginTransaction();

            // cek username unik
            if (User::where('username', $registration->username_request)->exists()) {
                DB::rollBack();
                return back()->with('error', 'Username ' . $registration->username_request . ' sudah ada.');
            }

            $user = User::create([
                'username' => $registration->username_request,
                'password' => $registration->password_request,
                'role' => 'Siswa',
            ]);

            $siswa = $registration->siswa;
            $siswa->user_id = $user->id;
            $siswa->save();

            $registration->update([
                'status' => 'Approved',
                'approved_by_admin_id' => auth()->user()->admin->id,
            ]);

            DB::commit();

            return back()->with('success', 'Registrasi Siswa berhasil disetujui. Akun login telah dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Persetujuan gagal karena error database: ' . $e->getMessage());
        }
    }

    // Reject (hapus)
    public function reject($id)
    {
        $registration = Registrasi::findOrFail($id);

        if ($registration->status !== 'Pending') {
            return back()->with('error', 'Registrasi sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($registration) {
            $registration->siswa->delete();
            $registration->delete();
        });

        return back()->with('success', 'Registrasi Siswa berhasil ditolak dan data dihapus.');
    }

    /**
     * Approve semua registrasi 'Pending' (approveAll)
     */
    public function approveAll(Request $request)
    {
        if (! auth()->check() || ! auth()->user()->admin) {
            return back()->with('error', 'Gagal: Akun Admin tidak valid atau sesi berakhir.');
        }

        $pendings = Registrasi::with('siswa')
            ->where('status', 'Pending')
            ->orderBy('id_reg')
            ->get();

        if ($pendings->isEmpty()) {
            return back()->with('error', 'Tidak ada registrasi yang bisa disetujui.');
        }

        $approvedCount = 0;
        $failures = [];

        foreach ($pendings as $registration) {
            try {
                DB::beginTransaction();

                // jika username sudah ada -> skip
                $exists = User::where('username', $registration->username_request)->exists();
                if ($exists) {
                    $failures[] = "Username '" . $registration->username_request . "' sudah ada (ID Reg: " . $registration->id_reg . ").";
                    DB::rollBack();
                    continue;
                }

                // buat user
                $user = User::create([
                    'username' => $registration->username_request,
                    'password' => $registration->password_request,
                    'role'     => 'Siswa',
                ]);

                // cek siswa terkait
                $siswa = $registration->siswa;
                if (! $siswa) {
                    $failures[] = "Data Siswa tidak ditemukan untuk ID Reg: " . $registration->id_reg . ".";
                    DB::rollBack();
                    continue;
                }

                $siswa->user_id = $user->id;
                $siswa->save();

                $registration->update([
                    'status' => 'Approved',
                    'approved_by_admin_id' => auth()->user()->admin->id,
                ]);

                DB::commit();
                $approvedCount++;
            } catch (\Exception $e) {
                DB::rollBack();
                $failures[] = "Gagal memproses ID Reg " . $registration->id_reg . ": " . $e->getMessage();
                continue;
            }
        }

        $message = $approvedCount . " registrasi berhasil disetujui.";
        if (count($failures) > 0) {
            $sample = array_slice($failures, 0, 5);
            $message .= " Namun ada " . count($failures) . " gagal: " . implode(' | ', $sample);
            if (count($failures) > 5) {
                $message .= " (dan " . (count($failures) - 5) . " lainnya...)";
            }
            return back()->with('error', $message);
        }

        return back()->with('success', $message);
    }
}
