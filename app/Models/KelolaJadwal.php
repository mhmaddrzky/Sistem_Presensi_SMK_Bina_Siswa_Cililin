<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelolaJadwal extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; 
    
    // 🛑 PERBAIKAN: Hapus $fillable karena $guarded = [] sudah menonaktifkan proteksi
    // Menjamin semua kolom (hari, mata_pelajaran, admin_id, dll.) dapat diisi (Mass Assignment)
    protected $guarded = []; 

    // Relasi BelongsTo: Jadwal dibuat oleh satu Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    // Relasi One-to-Many: Satu Jadwal dapat memiliki banyak data Presensi
    public function presensis()
    {
        // Asumsi Foreign Key di tabel presensis adalah 'jadwal_id' dan Primary Key di sini adalah 'id'
        return $this->hasMany(Presensi::class, 'jadwal_id', 'id');
    }
}