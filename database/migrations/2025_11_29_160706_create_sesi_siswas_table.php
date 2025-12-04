<?php

// database/migrations/xxxx_create_sesi_siswas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel ini menghubungkan Siswa yang sudah di-approve dengan Jadwal tertentu
        Schema::create('sesi_siswas', function (Blueprint $table) {
            $table->id();

            // FK ke Siswa (Peserta Sesi)
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            
            // FK ke Jadwal (Sesi yang Diikuti)
            $table->foreignId('jadwal_id')->constrained('kelola_jadwals')->onDelete('cascade');
            
            // Menjamin satu Siswa hanya terdaftar satu kali per Jadwal
            $table->unique(['siswa_id', 'jadwal_id']); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_siswas');
    }
};
