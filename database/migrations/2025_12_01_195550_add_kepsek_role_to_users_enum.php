<?php

// database/migrations/xxxx_add_kepsek_role_to_users_enum.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 🛑 RAW SQL: Tambahkan 'Kepsek' ke daftar ENUM yang sudah ada
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('Admin', 'Guru', 'AsistenLab', 'Siswa', 'Kepsek') NOT NULL");
    }

    public function down(): void
    {
        // Rollback: Hapus 'Kepsek' (atau kembalikan ke status sebelumnya)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('Admin', 'Guru', 'AsistenLab', 'Siswa') NOT NULL");
    }
};