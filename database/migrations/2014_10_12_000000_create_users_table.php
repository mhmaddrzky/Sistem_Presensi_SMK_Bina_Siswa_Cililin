<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // id_user INT
            $table->string('username', 50)->unique();
            $table->string('password'); // Password string
            
            // role enum (Admin, Guru, AsistenLab, Siswa)
            $table->enum('role', ['Admin', 'Guru', 'AsistenLab', 'Siswa']); 
            
            $table->rememberToken();
            $table->timestamps();
            // $table->boolean('login')->default(false); // Opsional, Laravel menangani status login secara otomatis
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};