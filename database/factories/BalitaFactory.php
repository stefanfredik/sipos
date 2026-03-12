<?php

namespace Database\Factories;

use App\Models\Balita;
use Illuminate\Database\Eloquent\Factories\Factory;

class BalitaFactory extends Factory
{
    protected $model = Balita::class;

    public function definition(): array
    {
        $jenisKelamin = $this->faker->randomElement(['L', 'P']);
        $namaDepan = $jenisKelamin === 'L'
            ? $this->faker->randomElement(['I Wayan', 'I Made', 'I Ketut', 'I Komang', 'I Putu', 'I Kadek'])
            : $this->faker->randomElement(['Ni Wayan', 'Ni Made', 'Ni Ketut', 'Ni Komang', 'Ni Putu', 'Ni Kadek']);

        return [
            'nik' => '5104' . $this->faker->unique()->numerify('############'),
            'nama' => $namaDepan . ' ' . $this->faker->lastName(),
            'nama_orang_tua' => $this->faker->randomElement(['Ni Wayan', 'Ni Made', 'Ni Ketut', 'Ni Komang']) . ' ' . $this->faker->lastName(),
            'tgl_lahir' => $this->faker->dateTimeBetween('-5 years', '-1 month')->format('Y-m-d'),
            'jenis_kelamin' => $jenisKelamin,
            'no_telp' => '08' . $this->faker->numerify('##########'),
            'alamat' => 'Br. Dinas ' . $this->faker->randomElement(['Belumbang Kaja', 'Belumbang Kelod', 'Belumbang Kawan', 'Belumbang Kangin', 'Pangkung Karung', 'Tegal Saat', 'Munduk Tumpeng', 'Yeh Buah']) . ', Desa Belumbang',
            'is_active' => true,
        ];
    }
}
