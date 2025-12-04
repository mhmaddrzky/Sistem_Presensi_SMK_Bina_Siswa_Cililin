<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            // 1. Tambah Kolom Jurusan ke Tabel Siswas
            $table->enum('jurusan', ['TKJ', 'TBSM'])->after('kelas');
        });

        Schema::table('kelola_jadwals', function (Blueprint $table) {
            // 2. Tambah Kolom Jurusan ke Tabel Kelola Jadwals
            $table->enum('jurusan', ['TKJ', 'TBSM'])->after('nama_guru');
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('jurusan');
        });

        Schema::table('kelola_jadwals', function (Blueprint $table) {
            $table->dropColumn('jurusan');
        });
    }
};
