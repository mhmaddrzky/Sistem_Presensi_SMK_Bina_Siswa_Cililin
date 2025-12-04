<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id(); // PK Admin
            
            // Foreign Key ke tabel users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('id_admin', 20)->unique();
            $table->string('nama', 100);
            
            // Kolom dari Class Diagram: accRegistrasi(), bukaPresensi(), kelolaJadwal(), rekapLaporan() adalah methods, tidak perlu di tabel
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};