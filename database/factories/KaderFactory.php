<?php

namespace Database\Factories;

use App\Models\Kader;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class KaderFactory extends Factory
{
    protected $model = Kader::class;

    private static array $dusunBelumbang = [
        'Br. Dinas Belumbang Kaja',
        'Br. Dinas Belumbang Kelod',
        'Br. Dinas Belumbang Kawan',
        'Br. Dinas Belumbang Kangin',
        'Br. Dinas Pangkung Karung',
        'Br. Dinas Tegal Saat',
        'Br. Dinas Munduk Tumpeng',
        'Br. Dinas Yeh Buah',
    ];

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->kader(),
            'posyandu_id' => \App\Models\Posyandu::inRandomOrder()->first()?->id,
            'nama_kader' => $this->faker->randomElement(['Ni Made', 'Ni Wayan', 'Ni Ketut', 'Ni Komang', 'Ni Luh']) . ' ' . $this->faker->lastName(),
            'alamat' => $this->faker->randomElement(self::$dusunBelumbang) . ', Desa Belumbang, Kec. Kerambitan, Kab. Tabanan',
            'no_telp' => '08' . $this->faker->numerify('##########'),
            'jenis_kelamin' => 'P',
        ];
    }
}
