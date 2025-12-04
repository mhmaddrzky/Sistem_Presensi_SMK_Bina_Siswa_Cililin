<?php

// database/migrations/xxxx_add_account_fields_to_registrasis_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrasis', function (Blueprint $table) {
            // Tambahkan kolom untuk menyimpan permintaan akun Siswa
            $table->string('username_request', 50)->nullable();
            $table->string('password_request')->nullable(); // Password yang sudah di-HASH
        });
    }

    public function down(): void
    {
        Schema::table('registrasis', function (Blueprint $table) {
            $table->dropColumn('username_request');
            $table->dropColumn('password_request');
        });
    }
};
