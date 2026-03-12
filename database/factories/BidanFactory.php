<?php

namespace Database\Factories;

use App\Models\Bidan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BidanFactory extends Factory
{
    protected $model = Bidan::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->bidan(),
            'nama_bidan' => $this->faker->randomElement(['Ni Wayan', 'Ni Made', 'Ni Ketut', 'Ni Luh']) . ' ' . $this->faker->lastName(),
            'alamat' => 'Desa Belumbang, Kec. Kerambitan, Kab. Tabanan, Bali',
            'no_telp' => '08' . $this->faker->numerify('##########'),
            'jenis_kelamin' => 'P',
        ];
    }
}
