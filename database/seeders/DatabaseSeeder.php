<?php

namespace Database\Seeders;

use App\Models\Bidan;
use App\Models\Kader;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::factory()->admin()->create([
            'nama_user' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@sipos.id',
            'password' => Hash::make('password'),
        ]);

        // 2 Bidan users
        $bidan1 = User::factory()->bidan()->create([
            'nama_user' => 'Ni Wayan Yudiasih',
            'username' => 'bidan1',
            'email' => 'bidan1@sipos.id',
            'password' => Hash::make('password'),
        ]);
        $bidan2 = User::factory()->bidan()->create([
            'nama_user' => 'Ni Made Suartini',
            'username' => 'bidan2',
            'email' => 'bidan2@sipos.id',
            'password' => Hash::make('password'),
        ]);

        // 8 Kader users
        $kaders = [];
        for ($i = 1; $i <= 8; $i++) {
            $kaders[] = User::factory()->kader()->create([
                'nama_user' => "Kader {$i}",
                'username' => "kader{$i}",
                'email' => "kader{$i}@sipos.id",
                'password' => Hash::make('password'),
            ]);
        }

        // 1 Peserta demo
        User::factory()->peserta()->create([
            'nama_user' => 'Peserta Demo',
            'username' => 'peserta1',
            'email' => 'peserta1@sipos.id',
            'password' => Hash::make('password'),
        ]);

        // Posyandu
        $this->call(PosyanduSeeder::class);

        // Get all posyandu
        $posyandus = Posyandu::all();

        // Create Bidan records
        Bidan::create([
            'user_id' => $bidan1->id,
            'nama_bidan' => $bidan1->nama_user,
            'alamat' => 'Desa Belumbang, Kec. Kerambitan, Kab. Tabanan, Bali',
            'no_telp' => '081234567890',
            'jenis_kelamin' => 'P',
        ]);
        Bidan::create([
            'user_id' => $bidan2->id,
            'nama_bidan' => $bidan2->nama_user,
            'alamat' => 'Desa Belumbang, Kec. Kerambitan, Kab. Tabanan, Bali',
            'no_telp' => '081234567891',
            'jenis_kelamin' => 'P',
        ]);

        // Create Kader records (1 per posyandu)
        $dusunNames = [
            'Br. Dinas Belumbang Kaja',
            'Br. Dinas Belumbang Kelod',
            'Br. Dinas Belumberland Kawan',
            'Br. Dinas BelUMMARY Kangin',
            'Br. Dinas Pangkung Karung',
            'Br. Dinas Tegalsaat',
            'Br. Dinas Munduk Tumpeng',
            'Br. Dinas Yeh Buah',
        ];

        foreach ($kaders as $index => $kader) {
            $posyandu = $posyandus->get($index);
            Kader::create([
                'user_id' => $kader->id,
                'posyandu_id' => $posyandu?->id,
                'nama_kader' => $kader->nama_user,
                'alamat' => $dusunNames[$index] . ', Desa Belumbang, Kec. Kerambitan, Kab. Tabanan',
                'no_telp' => '08' . rand(1000000000, 9999999999),
                'jenis_kelamin' => 'P',
            ]);
        }

        // Demo data
        $this->call(DemoDataSeeder::class);
    }
}
