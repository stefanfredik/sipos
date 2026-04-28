<?php

namespace Database\Factories;

use App\Models\Posyandu;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosyanduFactory extends Factory
{
    protected $model = Posyandu::class;

    private static array $posyanduData = [
        ['nama_posyandu' => 'Posyandu Melati I', 'lokasi' => 'Br. Dinas Belumbang Kaja'],
        ['nama_posyandu' => 'Posyandu Melati II', 'lokasi' => 'Br. Dinas Belumbang Kelod'],
        ['nama_posyandu' => 'Posyandu Mawar I', 'lokasi' => 'Br. Dinas Belumbang Kawan'],
        ['nama_posyandu' => 'Posyandu Mawar II', 'lokasi' => 'Br. Dinas Belumbang Kangin'],
        ['nama_posyandu' => 'Posyandu Dahlia', 'lokasi' => 'Br. Dinas Pangkung Karung'],
        ['nama_posyandu' => 'Posyandu Anggrek', 'lokasi' => 'Br. Dinas Tegal Saat'],
        ['nama_posyandu' => 'Posyandu Kenanga', 'lokasi' => 'Br. Dinas Munduk Tumpeng'],
        ['nama_posyandu' => 'Posyandu Cempaka', 'lokasi' => 'Br. Dinas Yeh Buah'],
    ];

    private static int $index = 0;

    public function definition(): array
    {
        $names = ['Melati', 'Mawar', 'Anggrek', 'Kenanga', 'Cempaka', 'Dahlia', 'Tulip', 'Sakura'];
        $name = $names[array_rand($names)] . ' ' . rand(1, 100);
        
        return [
            'nama_posyandu' => 'Posyandu ' . $name,
            'lokasi' => 'Lokasi ' . $name,
            'deskripsi' => 'Posyandu Desa Belumbang, Kec. Kerambitan, Kab. Tabanan',
            'is_active' => true,
        ];
    }

    /**
     * State for specific Belumbang posyandu (use with sequence).
     */
    public function belumbang(int $index): static
    {
        $data = self::$posyanduData[$index] ?? self::$posyanduData[0];

        return $this->state(fn () => [
            'nama_posyandu' => $data['nama_posyandu'],
            'lokasi' => $data['lokasi'],
            'deskripsi' => 'Posyandu Desa Belumbang, Kec. Kerambitan, Kab. Tabanan, Bali',
            'is_active' => true,
        ]);
    }

    /**
     * Get posyandu data for seeder.
     */
    public static function getPosyanduData(): array
    {
        return self::$posyanduData;
    }
}
