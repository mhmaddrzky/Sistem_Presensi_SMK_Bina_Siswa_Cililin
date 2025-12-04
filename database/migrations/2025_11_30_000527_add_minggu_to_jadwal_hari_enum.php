<?php

// database/migrations/xxxx_add_minggu_to_jadwal_hari_enum.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // 🛑 WAJIB DITAMBAH

return new class extends Migration
{
    public function up(): void
    {
        // 🛑 Perbaikan: Tambahkan 'Minggu' ke definisi ENUM
        DB::statement("ALTER TABLE kelola_jadwals MODIFY COLUMN hari ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu') NOT NULL");
    }

    public function down(): void
    {
        // Rollback: Hapus 'Minggu' (opsional, tapi baik untuk kebersihan)
        DB::statement("ALTER TABLE kelola_jadwals MODIFY COLUMN hari ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu') NOT NULL");
    }
};