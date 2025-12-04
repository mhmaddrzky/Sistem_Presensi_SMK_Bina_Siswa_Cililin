<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ... (Bagian 'use' di atas)
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_absensis', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->string('periode', 50); // Contoh: "Semester Ganjil 2025" atau "Mei 2025"
            
            // Tambahan opsional sesuai Class Diagram
            $table->string('file_export')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_absensis');
    }
};