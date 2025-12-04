<?php

// database/migrations/xxxx_create_kelola_jadwals_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelola_jadwals', function (Blueprint $table) {
            $table->id(); // Ini akan menjadi kolom 'id'
            
            // KOLOM-KOLOM YANG HILANG:
            $table->date('tanggal');
            $table->string('sesi', 50);
            $table->string('ruang_lab', 50);
            
            // Foreign Key ke Admin (siapa yang membuat jadwal)
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelola_jadwals');
    }
};