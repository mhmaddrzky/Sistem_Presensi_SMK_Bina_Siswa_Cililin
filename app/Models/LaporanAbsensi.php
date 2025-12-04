<?php

// app/Models/LaporanAbsensi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanAbsensi extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'admin_id',
        'periode',
        // Kolom lain bisa ditambahkan di migration, misalnya 'file_path' jika laporan disimpan sebagai file
    ];

    // Relasi BelongsTo: Laporan dibuat oleh satu Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
