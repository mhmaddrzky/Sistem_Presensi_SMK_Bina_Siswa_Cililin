<?php

// app/Models/Admin.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    
    // Perhatikan: $primaryKey diset jika id di migration menggunakan nama kolom selain 'id'
    // protected $primaryKey = 'id'; 

    protected $fillable = [
        'user_id',
        'id_admin',
        'nama',
    ];

    // Relasi One-to-One: Admin memiliki satu User untuk otentikasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi One-to-Many: Admin bisa melakukan banyak persetujuan Registrasi
    public function registrasis()
    {
        // 'approved_by_admin_id' adalah nama Foreign Key di tabel 'registrasis'
        return $this->hasMany(Registrasi::class, 'approved_by_admin_id'); 
    }

    // Relasi One-to-Many: Admin bisa membuat banyak Jadwal
    public function kelolaJadwals()
    {
        return $this->hasMany(KelolaJadwal::class, 'admin_id');
    }

    // Relasi One-to-Many: Admin bisa membuat banyak Laporan
    public function laporanAbsensis()
    {
        return $this->hasMany(LaporanAbsensi::class, 'admin_id');
    }
}