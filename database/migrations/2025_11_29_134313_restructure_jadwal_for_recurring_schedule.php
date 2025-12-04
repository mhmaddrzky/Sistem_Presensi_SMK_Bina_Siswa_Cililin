<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // WAJIB DI-IMPORT

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelola_jadwals', function (Blueprint $table) {
            
            // 🛑 GANTI SEMUA LOGIKA RENAME/CHANGE DI SINI (HANYA UNTUK TAMBAH KOLOM)
            
            // Tambah kolom Kapasitas dan Waktu
            $table->integer('kapasitas')->default(20)->after('ruang_lab');
            $table->time('waktu_mulai')->after('kapasitas');
            $table->time('waktu_selesai')->after('waktu_mulai');
        });
        
        // 🛑 JALANKAN RAW SQL UNTUK RENAME (HARUS DI LUAR Schema::table)
        // Kita ubah nama kolom 'tanggal' menjadi 'hari' dan tipenya dari DATE ke ENUM
        DB::statement('ALTER TABLE kelola_jadwals CHANGE COLUMN tanggal hari ENUM("Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu") NOT NULL');
    }

    public function down(): void
    {
        Schema::table('kelola_jadwals', function (Blueprint $table) {
            $table->dropColumn(['kapasitas', 'waktu_mulai', 'waktu_selesai']);
        });
        
        // 🛑 RAW SQL ROLLBACK: Ubah kembali 'hari' menjadi 'tanggal' dan tipenya ke DATE
        DB::statement('ALTER TABLE kelola_jadwals CHANGE COLUMN hari tanggal DATE NOT NULL');
    }
};