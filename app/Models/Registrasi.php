<?php

// app/Models/Registrasi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Registrasi extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_reg';

    protected $fillable = [
        'siswa_id',
        'tanggal_reg',
        'status',
        'approved_by_admin_id',
        // TAMBAHKAN DUA BARIS INI:
        'username_request', 
        'password_request',
    ];
    
    // Default cast untuk kolom status
    protected $casts = [
        'status' => 'string',
    ];

    // Relasi One-to-One: Registrasi dimiliki oleh satu Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // Relasi BelongsTo: Registrasi disetujui oleh satu Admin (bisa null)
    public function approver()
    {
        return $this->belongsTo(Admin::class, 'approved_by_admin_id');
    }
}
