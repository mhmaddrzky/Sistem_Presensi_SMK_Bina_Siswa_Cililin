<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
   public function run()
    {
        // 1. BUAT AKUN LOGIN (USERS)
        $adminUser = User::create([
            'username' => 'Superadmin', // Username Paten
            // Password di-hash untuk keamanan
            'password' => Hash::make('20279922'), // Password Paten
            'role' => 'Admin', 
        ]);

        // 2. BUAT DETAIL ADMIN (ADMINS)
        Admin::create([
            'user_id' => $adminUser->id,
            'nama' => 'SMKS Bina Siswa 2', // Nama Paten
            'id_admin' => '22997202', // ID Admin Paten
        ]);
        
        $this->command->info('Akun Super Admin Paten berhasil diinisialisasi.');
    }
}