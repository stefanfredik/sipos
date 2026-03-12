<?php

namespace Database\Factories;

use App\Models\IbuHamil;
use Illuminate\Database\Eloquent\Factories\Factory;

class IbuHamilFactory extends Factory
{
    protected $model = IbuHamil::class;

    public function definition(): array
    {
        $namaDepan = $this->faker->randomElement(['Ni Wayan', 'Ni Made', 'Ni Ketut', 'Ni Komang', 'Ni Luh', 'Ni Putu', 'Ni Kadek']);

        return [
            'nik' => '5104' . $this->faker->unique()->numerify('############'),
            'nama' => $namaDepan . ' ' . $this->faker->lastName(),
            'tgl_lahir' => $this->faker->dateTimeBetween('-35 years', '-20 years')->format('Y-m-d'),
            'kehamilan_keberapa' => $this->faker->numberBetween(1, 4),
            'jarak_anak' => $this->faker->optional(0.7)->numberBetween(1, 8),
            'usia_kehamilan' => $this->faker->numberBetween(4, 40),
            'no_telp' => '08' . $this->faker->numerify('##########'),
            'alamat' => 'Br. Dinas ' . $this->faker->randomElement(['Belumbang Kaja', 'Belumbang Kelod', 'Belumbang Kawan', 'Belumbang Kangin', 'Pangkung Karung', 'Tegal Saat', 'Munduk Tumpeng', 'Yeh Buah']) . ', Desa Belumbang',
            'is_active' => true,
        ];
    }
}
