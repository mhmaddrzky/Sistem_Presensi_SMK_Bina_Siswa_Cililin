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
    /**
     * Tampilkan form registrasi untuk siswa
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Proses pendaftaran siswa (oleh user)
     */
    public function register(Request $request)
    {
        // Validasi dengan pesan bahasa Indonesia dan aturan ketat
        $request->validate([
            'nama' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/', // Hanya huruf dan spasi
            ],
            'username' => [
                'required',
                'string',
                'min:4',
                'max:50',
                'unique:users,username',
                'regex:/^[a-zA-Z0-9_]+$/', // Hanya huruf, angka, underscore
            ],
            'nis' => [
                'required',
                'string',
                'min:3', // Minimal 3 digit
                'max:20', // Maksimal 20 digit
                'unique:siswas,nis',
                'regex:/^[0-9]+$/', // Hanya angka
            ],
            'nis_confirmation' => [
                'required',
                'same:nis', // Harus sama dengan field nis
            ],
            'kelas' => [
                'required',
                'string',
                'min:1',
                'max:10',
            ],
            'jurusan' => [
                'required',
                'in:TKJ,TBSM',
            ],
        ], [
            // Nama
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.min' => 'Nama minimal 3 karakter.',
            'nama.max' => 'Nama maksimal 100 karakter.',
            'nama.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            
            // Username
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 4 karakter.',
            'username.max' => 'Username maksimal 50 karakter.',
            'username.unique' => 'Username sudah digunakan, silakan gunakan username lain.',
            'username.regex' => 'Username hanya boleh berisi huruf, angka, dan underscore (_).',
            
            // NIS
            'nis.required' => 'NIS wajib diisi.',
            'nis.min' => 'NIS minimal 3 digit.',
            'nis.max' => 'NIS maksimal 20 digit.',
            'nis.unique' => 'NIS sudah terdaftar dalam sistem.',
            'nis.regex' => 'NIS hanya boleh berisi angka.',
            
            // NIS Confirmation
            'nis_confirmation.required' => 'Konfirmasi NIS wajib diisi.',
            'nis_confirmation.same' => 'Konfirmasi NIS tidak cocok dengan NIS yang dimasukkan.',
            
            // Kelas
            'kelas.required' => 'Kelas wajib diisi.',
            'kelas.min' => 'Kelas minimal 1 karakter.',
            'kelas.max' => 'Kelas maksimal 10 karakter.',
            
            // Jurusan
            'jurusan.required' => 'Jurusan wajib dipilih.',
            'jurusan.in' => 'Jurusan harus TKJ atau TBSM.',
        ]);

        try {
            DB::beginTransaction();

            // Password default = NIS
            $defaultPassword = $request->nis;

            // Buat data siswa
            $siswa = Siswa::create([
                'nis' => $request->nis,
                'nama' => ucwords(strtolower(trim($request->nama))), 
                'kelas' => strtoupper(trim($request->kelas)), 
                'jurusan' => $request->jurusan,
                'user_id' => null,
            ]);

            // Buat permintaan registrasi
            Registrasi::create([
                'siswa_id' => $siswa->id,
                'tanggal_reg' => now()->toDateString(),
                'status' => 'Pending',
                'username_request' => strtolower(trim($request->username)), 
                'password_request' => Hash::make($defaultPassword),
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan tunggu persetujuan dari admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Pendaftaran gagal. Silakan coba lagi.');
        }
    }

    /**
     * Daftar registrasi pending (admin)
     */
    public function index()
    {
        $registrations = Registrasi::with('siswa')
            ->where('status', 'Pending')
            ->orderByDesc('id_reg')
            ->get();

        return view('admin.registrations.index', compact('registrations'));
    }

    /**
     * Approve satu-satu
     */
    public function approve(Request $request, $id)
    {
        $registration = Registrasi::with('siswa')->find($id);

        if (!$registration) {
            return back()->with('error', 'Permintaan registrasi tidak ditemukan.');
        }

        if ($registration->status !== 'Pending') {
            return back()->with('error', 'Registrasi sudah diproses sebelumnya.');
        }

        if (!auth()->check() || !auth()->user()->admin) {
            return back()->with('error', 'Akses ditolak. Akun admin tidak valid atau sesi berakhir.');
        }

        try {
            DB::beginTransaction();

            // Cek username unik
            if (User::where('username', $registration->username_request)->exists()) {
                DB::rollBack();
                return back()->with('error', 'Username "' . $registration->username_request . '" sudah digunakan.');
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

            return back()->with('success', 'Registrasi siswa berhasil disetujui. Akun login telah dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Persetujuan gagal. Error: ' . $e->getMessage());
        }
    }

    /**
     * Reject (hapus)
     */
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

        return back()->with('success', 'Registrasi siswa berhasil ditolak dan data telah dihapus.');
    }

    /**
     * Approve semua registrasi 'Pending' (approveAll)
     */
    public function approveAll(Request $request)
    {
        if (!auth()->check() || !auth()->user()->admin) {
            return back()->with('error', 'Akses ditolak. Akun admin tidak valid atau sesi berakhir.');
        }

        $pendings = Registrasi::with('siswa')
            ->where('status', 'Pending')
            ->orderBy('id_reg')
            ->get();

        if ($pendings->isEmpty()) {
            return back()->with('error', 'Tidak ada registrasi yang perlu disetujui.');
        }

        $approvedCount = 0;
        $failures = [];

        foreach ($pendings as $registration) {
            try {
                DB::beginTransaction();

                // Jika username sudah ada -> skip
                $exists = User::where('username', $registration->username_request)->exists();
                if ($exists) {
                    $failures[] = "Username '" . $registration->username_request . "' sudah digunakan (ID: " . $registration->id_reg . ")";
                    DB::rollBack();
                    continue;
                }

                // Buat user
                $user = User::create([
                    'username' => $registration->username_request,
                    'password' => $registration->password_request,
                    'role'     => 'Siswa',
                ]);

                // Cek siswa terkait
                $siswa = $registration->siswa;
                if (!$siswa) {
                    $failures[] = "Data siswa tidak ditemukan untuk ID registrasi: " . $registration->id_reg;
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
                $failures[] = "Gagal memproses ID " . $registration->id_reg . ": " . $e->getMessage();
                continue;
            }
        }

        // Buat pesan hasil
        $message = $approvedCount . " registrasi berhasil disetujui.";
        
        if (count($failures) > 0) {
            $sample = array_slice($failures, 0, 5);
            $message .= " Terdapat " . count($failures) . " registrasi gagal: " . implode(' | ', $sample);
            
            if (count($failures) > 5) {
                $message .= " (dan " . (count($failures) - 5) . " lainnya...)";
            }
            
            return back()->with('error', $message);
        }

        return back()->with('success', $message);
    }
}