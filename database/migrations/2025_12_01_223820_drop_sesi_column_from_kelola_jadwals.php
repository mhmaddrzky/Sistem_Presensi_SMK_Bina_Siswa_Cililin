<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelola_jadwals', function (Blueprint $table) {
            // Hapus kolom 'sesi'
            $table->dropColumn('sesi'); 
        });
    }

    public function down(): void
    {
        Schema::table('kelola_jadwals', function (Blueprint $table) {
            // Rollback: Tambahkan kembali kolom 'sesi' sebagai string (jika diperlukan)
            $table->string('sesi', 50)->nullable();
        });
    }
};
