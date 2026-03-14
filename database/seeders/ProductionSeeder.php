<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Bidan;
use App\Models\IbuHamil;
use App\Models\Kader;
use App\Models\Lansia;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::factory()->create([
            'nama_user' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@sipos.belumbang.id',
            'role' => UserRole::Admin,
            'is_active' => true,
        ]);

        // Create Bidan Users (2 bidan desa)
        $bidan1 = User::factory()->create([
            'nama_user' => 'Ni Wayan Yudiasih',
            'username' => 'bidan.yudi',
            'email' => 'bidan1@sipos.belumbang.id',
            'role' => UserRole::Bidan,
            'is_active' => true,
        ]);

        Bidan::factory()->create([
            'user_id' => $bidan1->id,
            'nama_bidan' => 'Ni Wayan Yudiasih',
            'no_telp' => '081234567890',
            'alamat' => 'Br. Belumbang Kaja, Desa Belumbang',
            'jenis_kelamin' => 'P',
        ]);

        $bidan2 = User::factory()->create([
            'nama_user' => 'Kadek Suartini',
            'username' => 'bidan.kadek',
            'email' => 'bidan2@sipos.belumbang.id',
            'role' => UserRole::Bidan,
            'is_active' => true,
        ]);

        Bidan::factory()->create([
            'user_id' => $bidan2->id,
            'nama_bidan' => 'Kadek Suartini',
            'no_telp' => '081234567891',
            'alamat' => 'Br. Belumbang Kelod, Desa Belumbang',
            'jenis_kelamin' => 'P',
        ]);

        // Create 8 Kader (1 per posyandu)
        $posyanduData = Posyandu::getPosyanduData();
        
        foreach ($posyanduData as $index => $data) {
            $kaderUser = User::factory()->create([
                'nama_user' => "Kader {$data['nama_posyandu']}",
                'username' => "kader_" . ($index + 1),
                'email' => "kader" . ($index + 1) . "@sipos.belumbang.id",
                'role' => UserRole::Kader,
                'is_active' => true,
            ]);

            Kader::factory()->create([
                'user_id' => $kaderUser->id,
                'nama_kader' => "Kader {$data['nama_posyandu']}",
                'no_telp' => '08123456789' . $index,
                'alamat' => $data['lokasi'],
                'jenis_kelamin' => 'P',
            ]);
        }

        // Create 8 Posyandu with specific data
        foreach ($posyanduData as $index => $data) {
            Posyandu::factory()->belumbang($index)->create();
        }

        // Create sample Ibu Hamil (7 data)
        $ibuHamilNames = [
            ['nama' => 'Ni Made Sri Astuti', 'nik' => '5103051234567890'],
            ['nama' => 'Kadek Ayu Lestari', 'nik' => '5103051234567891'],
            ['nama' => 'Wayan Suciati', 'nik' => '5103051234567892'],
            ['nama' => 'Made Ratnasari', 'nik' => '5103051234567893'],
            ['nama' => 'Kadek Dewi Sartika', 'nik' => '5103051234567894'],
            ['nama' => 'Wayan Indah Purnama', 'nik' => '5103051234567895'],
            ['nama' => 'Ni Putu Eka Putri', 'nik' => '5103051234567896'],
        ];

        foreach ($ibuHamilNames as $data) {
            $user = User::factory()->create([
                'nama_user' => $data['nama'],
                'username' => $data['nik'],
                'role' => UserRole::Peserta,
                'is_active' => true,
            ]);

            IbuHamil::factory()->create([
                'user_id' => $user->id,
                'nik' => $data['nik'],
                'nama' => $data['nama'],
                'no_telp' => '081234567XXX',
                'alamat' => 'Br. Belumbang, Desa Belumbang',
                'is_active' => true,
            ]);
        }

        // Create sample Balita (20 data)
        for ($i = 0; $i < 20; $i++) {
            Balita::factory()->create([
                'nama' => "Balita Sample {$i}",
                'nama_orang_tua' => "Orang Tua {$i}",
                'nik' => '510305' . str_pad($i + 1000, 10, '0', STR_PAD_LEFT),
                'no_telp' => '081234567XXX',
                'alamat' => 'Br. Belumbang, Desa Belumbang',
                'is_active' => true,
            ]);
        }

        // Create sample Lansia (20 data)
        for ($i = 0; $i < 20; $i++) {
            Lansia::factory()->create([
                'nama' => "Lansia Sample {$i}",
                'nik' => '510305' . str_pad($i + 2000, 10, '0', STR_PAD_LEFT),
                'no_telp' => '081234567XXX',
                'alamat' => 'Br. Belumbang, Desa Belumbang',
                'is_active' => true,
            ]);
        }

        $this->command->info('Production data seeded successfully!');
        $this->command->info('');
        $this->command->info('Default credentials:');
        $this->command->info('Admin: admin / password');
        $this->command->info('Bidan 1: bidan.yudi / password');
        $this->command->info('Bidan 2: bidan.kadek / password');
        $this->command->info('Kader: kader_1 - kader_8 / password');
        $this->command->info('Peserta: Use NIK / password');
    }
}
