<?php

namespace Database\Factories;

use App\Models\Bidan;
use App\Models\JadwalPosyandu;
use App\Models\Kader;
use App\Models\Pemeriksaan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PemeriksaanFactory extends Factory
{
    protected $model = Pemeriksaan::class;

    public function definition(): array
    {
        return [
            'jadwal_posyandu_id' => JadwalPosyandu::factory(),
            'kader_id' => Kader::factory(),
            'bidan_id' => Bidan::factory(),
            'peserta_type' => $this->faker->randomElement(['ibu_hamil', 'balita', 'lansia']),
            'peserta_id' => (string) Str::ulid(),
            'tgl_pemeriksaan' => $this->faker->date(),
            'berat_badan' => $this->faker->randomFloat(2, 5, 100),
            'tinggi_badan' => $this->faker->randomFloat(2, 50, 180),
            'hadir' => true,
        ];
    }
}
