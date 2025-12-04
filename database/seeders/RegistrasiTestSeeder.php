<?php

// database/seeders/RegistrasiTestSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Registrasi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrasiTestSeeder extends Seeder
{
    public function run(): void
    {
        // 🛑 Tentukan jumlah akun yang ingin dibuat
        $jumlahSiswa = 15; 

        Siswa::factory($jumlahSiswa)->create()->each(function ($siswa) {
            
            // Generate username dan password dari data Siswa yang sudah dibuat
            $username = 'test_' . $siswa->nis;
            $passwordRaw = $siswa->nis; // NIS sebagai password default

            // Buat permintaan registrasi
            Registrasi::create([
                'siswa_id' => $siswa->id,
                'tanggal_reg' => now()->subDays(rand(1, 5))->toDateString(), // Tanggal acak
                'status' => 'Pending', // Wajib Pending
                'approved_by_admin_id' => null,
                'username_request' => $username,
                'password_request' => Hash::make($passwordRaw),
            ]);
        });

        $this->command->info($jumlahSiswa . ' Siswa (Pending) berhasil dibuat untuk pengujian!');
    }
}