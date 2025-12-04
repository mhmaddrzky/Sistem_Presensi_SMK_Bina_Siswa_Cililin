<?php

// app/Models/Siswa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

        
    protected $fillable = [
        'nis',
        'nama',
        'kelas',
        'jurusan', // <<-- BARIS INI WAJIB ADA!
        'user_id',
    ];

    protected $guarded = [];

    

    // Relasi One-to-One: Siswa memiliki satu User untuk otentikasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi One-to-One: Siswa memiliki satu catatan Registrasi (saat ini)
    public function registrasi()
    {
        // Siswa melakukan registrasi (siswa_id adalah Foreign Key di tabel registrasis)
        return $this->hasOne(Registrasi::class, 'siswa_id');
    }

    // Relasi One-to-Many: Siswa dapat memiliki banyak data Presensi
    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'siswa_id');
    }
}
