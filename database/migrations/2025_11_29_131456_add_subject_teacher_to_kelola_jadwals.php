<?php

// database/migrations/xxxx_add_subject_teacher_to_kelola_jadwals.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelola_jadwals', function (Blueprint $table) {
            // Tambahkan kolom baru
            $table->string('mata_pelajaran', 100)->after('sesi'); 
            $table->string('nama_guru', 100)->after('mata_pelajaran');
        });
    }

    public function down(): void
    {
        Schema::table('kelola_jadwals', function (Blueprint $table) {
            $table->dropColumn('mata_pelajaran');
            $table->dropColumn('nama_guru');
        });
    }
};
