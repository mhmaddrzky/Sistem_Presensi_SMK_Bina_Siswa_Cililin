<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'role',
        'status', // ✅ TAMBAHKAN INI
    ];

    // Relasi One-to-One: User bisa menjadi Siswa (sesuai peran)
    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    // Relasi One-to-One: User bisa menjadi Admin/Guru/Aslab (sesuai peran)
    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    /**
     * ✅ METHOD UNTUK CEK STATUS AKTIF
     */
    public function isActive()
    {
        return $this->status === 'Aktif';
    }

    /**
     * ✅ METHOD UNTUK TOGGLE STATUS
     */
    public function toggleStatus()
    {
        $this->status = $this->status === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $this->save();
        return $this->status;
    }
}