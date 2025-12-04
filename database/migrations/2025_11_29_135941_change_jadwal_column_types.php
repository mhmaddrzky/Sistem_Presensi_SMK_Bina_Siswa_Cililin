<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_change_jadwal_column_types.php
// database/migrations/2025_11_29_134313_restructure_jadwal_for_recurring_schedule.php

// ...
public function up(): void
{
    Schema::table('kelola_jadwals', function (Blueprint $table) {
        // 🛑 Pastikan di sini kolom yang diubah adalah 'hari' (bukan tanggal)
        $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])->change();
    });
}

public function down(): void
{
    Schema::table('kelola_jadwals', function (Blueprint $table) {
        // 🛑 PERBAIKAN: Jika Anda ingin roll back, ubah kolom 'hari' kembali ke string.
        // Jika Anda mencoba mengubah 'tanggal' di sini, itu akan gagal.
        $table->string('hari')->change(); 
    });
}
};
