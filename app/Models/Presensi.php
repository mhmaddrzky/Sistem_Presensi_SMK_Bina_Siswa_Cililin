<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_presensi';

    protected $fillable = [
        'siswa_id',
        'jadwal_id',
        'tanggal',
        'waktu',
        'status', // Hadir, TidakHadir
    ];

    protected $casts = [
        'tanggal' => 'date', // Memberi tahu Laravel bahwa 'tanggal' harus diperlakukan sebagai DATE murni
        // Tambahkan casting lain jika diperlukan
    ];
    
    // Relasi BelongsTo: Presensi milik satu Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // Relasi BelongsTo: Presensi terkait dengan satu Jadwal
    public function jadwal()
    {
        return $this->belongsTo(KelolaJadwal::class, 'jadwal_id');
    }
}
