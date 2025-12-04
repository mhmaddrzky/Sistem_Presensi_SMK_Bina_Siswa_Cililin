<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin; // Model Admin digunakan untuk Guru/Kepsek/Aslab
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    /** Menampilkan daftar semua pengguna staf */
    public function index()
    {
        // Ambil semua user kecuali Siswa
        $users = User::whereIn('role', ['Admin', 'Guru', 'AsistenLab', 'Kepsek'])
                      ->with('admin')
                      ->get();
        return view('admin.users.index', compact('users'));
    }

    /** Menampilkan form pembuatan akun staf */
    public function create()
    {
        // Daftar role yang diizinkan untuk dibuat
        $roles = ['Guru', 'AsistenLab', 'Kepsek'];
        return view('admin.users.create', compact('roles'));
    }

    /** Menyimpan akun staf baru */
 public function store(Request $request)
{
    // 1. Validasi Input
    $request->validate([
        'nama' => 'required|string|max:100',
        'username' => 'required|string|unique:users|max:50',
        'password' => 'required|string|min:6',
        'role' => ['required', \Illuminate\Validation\Rule::in(['Guru', 'AsistenLab', 'Kepsek'])], 
        'id_pengelola' => 'required|string|unique:admins,id_admin', 
    ]);

    // 2. Cek Admin
    if (!\Illuminate\Support\Facades\Auth::check() || !\Illuminate\Support\Facades\Auth::user()->admin) {
        return back()->with('error', 'Akun pengelola tidak terverifikasi.');
    }

    try {
        DB::beginTransaction();

        // 3. Buat record di tabel users
        $user = \App\Models\User::create([
            'username' => $request->username,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'name' => $request->nama, // Jika model User memiliki kolom 'name'
        ]);

        // 4. Buat record di tabel admins
        \App\Models\Admin::create([
            'user_id' => $user->id,
            'id_admin' => $request->id_pengelola, 
            'nama' => $request->nama,
        ]);

        DB::commit();

        // 🛑 FIX UTAMA: Redirect ke halaman daftar (index) setelah berhasil.
        return redirect()->route('admin.users.index')->with('success', 'Akun ' . $request->role . ' baru berhasil dibuat.');

    } catch (\Exception $e) {
        DB::rollBack();
        // Redirect kembali dengan error jika ada kegagalan SQL
        return back()->withInput()->with('error', 'Gagal membuat akun: ' . $e->getMessage());
    }
}
}