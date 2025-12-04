<?php

// app/Models/User.php

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
        'role', // Admin, Guru, AsistenLab, Siswa
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
}