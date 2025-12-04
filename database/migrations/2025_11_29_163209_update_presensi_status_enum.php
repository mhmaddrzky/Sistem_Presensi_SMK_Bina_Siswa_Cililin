<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            // 🛑 PERBAIKAN: Ubah tipe kolom enum agar mencakup semua status
            // Nilai yang diizinkan: Hadir, Sakit, Izin, Alfa
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alfa'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            // Rollback ke status awal (Hadir, TidakHadir)
            $table->enum('status', ['Hadir', 'TidakHadir'])->change();
        });
    }
};