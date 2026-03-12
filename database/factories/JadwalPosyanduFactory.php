<?php

namespace Database\Factories;

use App\Models\Bidan;
use App\Models\JadwalPosyandu;
use App\Models\Kader;
use App\Models\Posyandu;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalPosyanduFactory extends Factory
{
    protected $model = JadwalPosyandu::class;

    public function definition(): array
    {
        return [
            'posyandu_id' => Posyandu::factory(),
            'kader_id' => Kader::factory(),
            'bidan_id' => Bidan::factory(),
            'tanggal' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'waktu_mulai' => '08:00:00',
            'waktu_selesai' => '12:00:00',
            'status' => 'draft',
        ];
    }
}
