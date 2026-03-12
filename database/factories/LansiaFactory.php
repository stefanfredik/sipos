<?php

namespace Database\Factories;

use App\Models\Lansia;
use Illuminate\Database\Eloquent\Factories\Factory;

class LansiaFactory extends Factory
{
    protected $model = Lansia::class;

    public function definition(): array
    {
        $jenisKelamin = $this->faker->randomElement(['L', 'P']);
        $namaDepan = $jenisKelamin === 'L'
            ? $this->faker->randomElement(['I Wayan', 'I Made', 'I Ketut', 'I Komang', 'I Nyoman'])
            : $this->faker->randomElement(['Ni Wayan', 'Ni Made', 'Ni Ketut', 'Ni Komang', 'Ni Nyoman']);

        return [
            'nik' => '5104' . $this->faker->unique()->numerify('############'),
            'nama' => $namaDepan . ' ' . $this->faker->lastName(),
            'tgl_lahir' => $this->faker->dateTimeBetween('-85 years', '-60 years')->format('Y-m-d'),
            'jenis_kelamin' => $jenisKelamin,
            'no_telp' => '08' . $this->faker->numerify('##########'),
            'alamat' => 'Br. Dinas ' . $this->faker->randomElement(['Belumbang Kaja', 'Belumbang Kelod', 'Belumbang Kawan', 'Belumbang Kangin', 'Pangkung Karung', 'Tegal Saat', 'Munduk Tumpeng', 'Yeh Buah']) . ', Desa Belumbang',
            'is_active' => true,
        ];
    }
}
