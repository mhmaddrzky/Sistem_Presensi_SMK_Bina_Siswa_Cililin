<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ... (Bagian 'use' di atas)
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id('id_presensi');
            
            // Relasi ke Siswa dan Jadwal
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('kelola_jadwals')->onDelete('cascade');
            
            $table->date('tanggal');
            $table->time('waktu');
            
            $table->enum('status', ['Hadir', 'TidakHadir']);
            
            // Unique key agar siswa tidak bisa presensi dua kali untuk jadwal dan tanggal yang sama
            $table->unique(['siswa_id', 'jadwal_id', 'tanggal']); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};