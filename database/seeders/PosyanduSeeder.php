<?php

namespace Database\Seeders;

use App\Models\Posyandu;
use Database\Factories\PosyanduFactory;
use Illuminate\Database\Seeder;

class PosyanduSeeder extends Seeder
{
    public function run(): void
    {
        $posyanduData = PosyanduFactory::getPosyanduData();

        foreach ($posyanduData as $data) {
            Posyandu::factory()->create([
                'nama_posyandu' => $data['nama_posyandu'],
                'lokasi' => $data['lokasi'],
                'deskripsi' => 'Posyandu Desa Belumbang, Kec. Kerambitan, Kab. Tabanan, Bali',
                'is_active' => true,
            ]);
        }
    }
}
