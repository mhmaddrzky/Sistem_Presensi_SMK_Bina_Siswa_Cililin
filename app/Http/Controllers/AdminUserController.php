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
            'role' => ['required', Rule::in(['Guru', 'AsistenLab', 'Kepsek'])], 
            'id_pengelola' => 'required|string|unique:admins,id_admin', 
        ]);

        // 2. Cek Admin
        if (!Auth::check() || !Auth::user()->admin) {
            return back()->with('error', 'Akun pengelola tidak terverifikasi.');
        }

        try {
            DB::beginTransaction();

            // 3. Buat record di tabel users
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'name' => $request->nama, 
            ]);

            // 4. Buat record di tabel admins
            Admin::create([
                'user_id' => $user->id,
                'id_admin' => $request->id_pengelola, 
                'nama' => $request->nama,
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'Akun ' . $request->role . ' baru berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat akun: ' . $e->getMessage());
        }
    }

   /** Form Edit Akun */
   public function edit($id)
   {
       $user = User::with('admin')->findOrFail($id);

       // Role yang bisa dipilih
       $roles = ['Guru', 'AsistenLab', 'Kepsek'];

       return view('admin.users.edit', compact('user', 'roles'));
   }

   /** Update Akun */
   public function update(Request $request, $id)
   {
       $user = User::with('admin')->findOrFail($id);

       $request->validate([
           'nama' => 'required|string|max:100',
           'username' => ['required', 'max:50', Rule::unique('users')->ignore($user->id)],
           'role' => ['required', Rule::in(['Guru', 'AsistenLab', 'Kepsek'])],
           'id_pengelola' => [
               'required',
               Rule::unique('admins', 'id_admin')->ignore($user->admin->id_admin, 'id_admin'),
           ],
           'password' => 'nullable|min:6'
       ]);

       DB::beginTransaction();

       try {

           // Update tabel users
           $user->update([
               'username' => $request->username,
               'name'     => $request->nama, // FIX: name harus ikut diperbarui
               'role'     => $request->role,
               'password' => $request->filled('password')
                               ? Hash::make($request->password)
                               : $user->password,
           ]);

           // Update tabel admin
           $user->admin->update([
               'nama'      => $request->nama,
               'id_admin'  => $request->id_pengelola,
           ]);

           DB::commit();

           return redirect()->route('admin.users.index')
                            ->with('success', 'Akun berhasil diperbarui.');

       } catch (\Exception $e) {
           DB::rollBack();
           return back()->with('error', 'Gagal update: ' . $e->getMessage());
       }
   }

   /** Hapus Akun */
   public function destroy($id)
   {
       $user = User::with('admin')->findOrFail($id);

       DB::beginTransaction();

       try {
           // Hapus admin
           if ($user->admin) {
               $user->admin->delete();
           }

           // Hapus user
           $user->delete();

           DB::commit();

           return redirect()->route('admin.users.index')
                            ->with('success', 'Akun berhasil dihapus.');
       } catch (\Exception $e) {
           DB::rollBack();
           return back()->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
       }
   }
}