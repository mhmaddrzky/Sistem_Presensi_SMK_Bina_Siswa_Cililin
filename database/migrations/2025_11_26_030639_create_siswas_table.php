<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id(); // PK Siswa
            
            // 🛑 PERBAIKAN KRITIS: Tambahkan nullable()
            // Agar Siswa bisa mendaftar (user_id = null) sebelum di-ACC Admin
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            
            $table->string('nis', 20)->unique();
            $table->string('nama', 100);
            $table->string('kelas', 10);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};