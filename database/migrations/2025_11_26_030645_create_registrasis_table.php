<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrasis', function (Blueprint $table) {
            $table->id('id_reg'); // PK Registrasi
            
            // Relasi One-to-One: siswa melakukan registrasi
            // Jika siswa sudah terverifikasi, kita akan hapus record registrasi ini.
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade'); 
            
            $table->date('tanggal_reg');
            
            // Status: Pending (belum di acc), Approved (sudah di acc), Rejected (ditolak)
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            
            // Siapa yang memverifikasi? FK ke tabel Admin. Nullable karena awalnya Pending.
            $table->foreignId('approved_by_admin_id')->nullable()->constrained('admins')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrasis');
    }
};