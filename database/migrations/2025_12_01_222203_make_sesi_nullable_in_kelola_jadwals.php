<?php

// database/migrations/xxxx_make_sesi_nullable_in_kelola_jadwals.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelola_jadwals', function (Blueprint $table) {
            // 🛑 PERBAIKAN: Jadikan kolom 'sesi' nullable
            $table->string('sesi', 50)->nullable()->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('kelola_jadwals', function (Blueprint $table) {
            // Rollback: Kembalikan ke NOT NULL (jika diperlukan)
            $table->string('sesi', 50)->nullable(false)->change();
        });
    }
};