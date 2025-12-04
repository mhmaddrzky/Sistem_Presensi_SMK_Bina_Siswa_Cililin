<?php
// database/factories/SiswaFactory.php

namespace Database\Factories;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; // Tambahkan ini

class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    public function definition(): array
    {
        return [
            // Generate NIS unik 8 digit
            'nis' => $this->faker->unique()->numerify('########'), 
            'nama' => $this->faker->name(),
            'kelas' => $this->faker->randomElement(['X-A', 'X-B', 'XI-A', 'XII-C']),
            'user_id' => null, // Wajib null karena belum di-approve
        ];
    }
}