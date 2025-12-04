<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiSiswa extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'sesi_siswas';

    // Primary Key (jika Anda menggunakan $table->id())
    protected $primaryKey = 'id'; 

    // Kolom yang dapat diisi secara massal (Mass Assignable)
    protected $fillable = [
        'siswa_id',
        'jadwal_id',
    ];

    // Relasi: SesiSiswa adalah milik satu Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // Relasi: SesiSiswa adalah milik satu Jadwal
    public function jadwal()
    {
        return $this->belongsTo(KelolaJadwal::class, 'jadwal_id');
    }
}