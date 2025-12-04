<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User sebagai Admin
        $userAdmin = User::create([
            'username' => 'adminlab', // Username untuk login
            'password' => Hash::make('password123'), // Password default: password123
            'role' => 'Admin', // Peran utama
        ]);

        // 2. Buat data Admin yang terhubung ke User di atas
        Admin::create([
            'user_id' => $userAdmin->id,
            'id_admin' => 'A001', // ID Admin unik
            'nama' => 'Kepala Laboratorium',
        ]);
        
        $this->command->info('Akun Admin telah berhasil dibuat (Username: adminlab, Password: password123).');
    }
}